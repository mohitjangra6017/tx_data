<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This plugin is used to access and download scorm e-learning packages that
 * have been made available in an Adapt Authoring Tool service instance.
 *
 * @since Moodle 3.0
 * @package    repository_kineoadapt
 * @copyright  2016 City & Guilds Kineo
 * @author     Ben Lobo <ben.lobo@kineo.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use repository_kineoadapt\adapt;

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once $CFG->dirroot . '/repository/lib.php';
require_once $CFG->dirroot . '/repository/kineoadapt/classes/adapt.php';

/**
 * Adapt Authoring Tool Plugin
 *
 */
class repository_kineoadapt extends repository {

    /**
     * @var adapt The instance of adapt api client.
     */
    protected $adapt;

    /**
     * API URL for accessing the AAT API.
     * @var string
     */
    private $apiurl;

    /**
     * API key for using the AAT API.
     * @var string
     */
    private $apikey;

    /**
     * Constructor.
     *
     * @param int $repositoryid repository instance id.
     * @param int|stdClass $context a context id or context object.
     * @param array $options repository options.
     * @param int $readonly indicate this repo is readonly or not.
     * @return void
     */
    public function __construct($repositoryid, $context = SYSCONTEXTID, $options = array(), $readonly = 0) {

        parent::__construct($repositoryid, $context, $options, $readonly);

        $this->apiurl = $this->get_option('apiurl');
        $this->apikey = $this->get_option('apikey');

        // Without an API url or API key, don't show this repo to users as its useless without them.
        if (empty($this->apiurl) || empty($this->apikey)) {
            $this->disabled = true;
        }

        $this->adapt = new repository_kineoadapt\adapt($this->apiurl, $this->apikey);
    }

    /**
     * Return a listing containing the available scorms.
     * @param string $path
     * @param string $page
     * @return array
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function get_listing($path = '', $page = '') {

        $list = [
            'dynload' => true,
            'nologin' => true,
            'nosearch' => false,
            'norefresh' => false,
            'list' => [],
            'allowcaching' => false // Indicates that result of get_listing() can be cached in filepicker.js.
        ];

        // Note - we deliberately do not catch the coding exceptions here.
        try {
            $result = $this->adapt->get_listing();
        } catch (\repository_kineoadapt\authentication_exception $e) {
            // The token has expired.
            return $list;
        } catch (\repository_kineoadapt\adapt_exception $e) {
            // There was some other form of non-coding failure.
            // This could be a rate limit, or it could be a server-side error.
            // Just return early instead.
            return $list;
        }

        if (empty($result) or !is_array($result)) {
            return $list;
        }

        $list['list'] = $this->process_entries($result);
        return $list;

    }

    /**
     * Get Adapt files that match a search term.
     *
     * @param string $query The search query
     * @param int $page The page number
     * @return array
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function search($query, $page = 0) {

        $list = [
                'list'      => [],
                'dynload'   => true,
            ];

        // Note - we deliberately do not catch the coding exceptions here.
        try {
            $result = $this->adapt->search($query);
        } catch (\repository_kineoadapt\authentication_exception $e) {
            // The token has expired.
            return $list;
        } catch (\repository_kineoadapt\adapt_exception $e) {
            // There was some other form of non-coding failure.
            // This could be a rate limit, or it could be a server-side error.
            // Just return early instead.
            return $list;
        }

        if (empty($result) or !is_array($result)) {
            return $list;
        }

        $list['list'] = $this->process_entries($result);
        return $list;
    }

    /**
     * Process a standard entries list.
     *
     * @param array $entries The list of entries returned from the API
     * @return array The manipulated entries for display in the file picker
     * @throws coding_exception
     */
    protected function process_entries(array $entries) {
        global $OUTPUT;
        $fileslist  = [];

        foreach ($entries as $entrydata) {
            $icon = file_extension_icon($entrydata->title . '.zip', 90);
            $imageUrl = $OUTPUT->image_url($icon);
            $fileslist[] = [
                'shorttitle' => $entrydata->title,
                'title' => strtolower(str_replace(' ', '', $entrydata->title)) . '.zip',
                'source' => $entrydata->_id,
                'path' => $entrydata->_id,
                'size' => $entrydata->size,
                'date' => strtotime($entrydata->publishedAt),
                'thumbnail' => $imageUrl->out(false),
            ];
        }

        $fileslist = array_filter($fileslist, [$this, 'filter']);

        return $fileslist;
    }

    /**
     * Downloads a file from external repository and saves it in temp dir.
     *
     * @inheritDocs
     * @throws moodle_exception
     */
    public function get_file($reference, $filename = '') {

        $path = $this->prepare_file($filename);

        $result = $this->adapt->get_scorm($reference, $path);

        if ($result !== true) {
            throw new moodle_exception('errorwhiledownload', 'repository', '', $result);
        }

        return ['path' => $path, 'url' => $reference];
    }

    /**
     * Save apiurl and apikey in config table.
     * @param array $options
     * @return boolean
     */
    public function set_option($options = []) {
        if (!empty($options['apiurl'])) {
            set_config('apiurl', trim($options['apiurl']), 'kineoadapt');
            unset($options['apiurl']);
        }
        if (!empty($options['apikey'])) {
            set_config('apikey', trim($options['apikey']), 'kineoadapt');
            unset($options['apikey']);
        }
        return parent::set_option($options);
    }

    /**
     * Get apiurl or apikey from config table.
     *
     * @param string $config
     * @return mixed
     */
    public function get_option($config = '') {
        if ($config === 'apiurl') {
            return trim(get_config('kineoadapt', 'apiurl'));
        } else if ($config === 'apikey') {
            return trim(get_config('kineoadapt', 'apikey'));
        } else {
            $options = parent::get_option();
            $options['apiurl'] = trim(get_config('kineoadapt', 'apiurl'));
            $options['apikey'] = trim(get_config('kineoadapt', 'apikey'));
        }
        return $options;
    }

    /**
     * What kind of files will be in this repository?
     *
     * @return string return '*' means this repository support any files, otherwise
     *               return mimetypes of files, it can be an array
     */
    public function supported_filetypes() {
        return '*';
    }

    /**
     * Tells how the file can be picked from this repository.
     *
     * Maximum value is FILE_INTERNAL | FILE_EXTERNAL | FILE_REFERENCE.
     *
     * @return int
     */
    public function supported_returntypes() {
        return FILE_INTERNAL;
    }

    /**
     * Is this repository accessing private data?
     *
     * @return bool
     */
    public function contains_private_data() {
        return false;
    }

    /**
     * Return names of the general options.
     * By default: no general option name.
     *
     * @return array
     */
    public static function get_type_option_names() {
        return ['apiurl', 'apikey', 'pluginname'];
    }

    /**
     * Edit/Create Admin Settings Moodle form.
     *
     * @param moodleform $mform Moodle form (passed by reference).
     * @param string $classname repository class name.
     * @throws coding_exception
     */
    public static function type_config_form($mform, $classname = 'repository') {

        parent::type_config_form($mform, $classname);

        $apikey = get_config('kineoadapt', 'apikey');
        $apiurl = get_config('kineoadapt', 'apiurl');

        if (empty($apikey)) {
            $apikey = '';
        }

        if (empty($apiurl)) {
            $apiurl = '';
        }

        // API URL.
        $mform->addElement(
            'text',
            'apiurl',
            get_string('apiurl', 'repository_kineoadapt'),
            ['value' => $apiurl, 'size' => '100']
        );
        $mform->setType('apiurl', PARAM_URL);
        $mform->addRule('apiurl', get_string('required'), 'required', null, 'client');

        // API Key.
        $mform->addElement(
            'text',
            'apikey',
            get_string('apikey', 'repository_kineoadapt'),
            ['value' => $apikey, 'size' => '40']
        );
        $mform->setType('apikey', PARAM_RAW_TRIMMED);
        $mform->addRule('apikey', get_string('required'), 'required', null, 'client');

        // Initialise a javascript file to support an Ajax call to testing the API connection.
        $connection_test_link_id = 'adapttestconn';
        self::init_javascript($connection_test_link_id);

        // Add an HTML form element for testing the API connection.
        $str_testconnection = get_string('testconnection', 'repository_kineoadapt');
        $html = html_writer::link('#', $str_testconnection, ['id' => $connection_test_link_id]);
        $html .= html_writer::tag('p', get_string('saveconfigmessage', 'repository_kineoadapt'));
        $html = html_writer::tag('div', $html, ['class' => 'testconnection']);
        $mform->addElement('html', $html);
    }

    /**
     * Include and init any required JavaScript.
     */
    protected static function init_javascript($connection_test_link_id) {
        global $PAGE;
        $PAGE->requires->jquery();
        $PAGE->requires->js_init_call(
            'M.repository_adapt.init',
            ['selector' => '#' . $connection_test_link_id],
            false,
            [
                'name' => 'repository_kineoadapt',
                'fullpath' => '/repository/kineoadapt/js/adapt.js',
            ]
        );

        $PAGE->requires->strings_for_js(array('connectionsuccessful',
            'connectionnotsuccessful', 'connectionnotsuccessful_norepo'), 'repository_kineoadapt');

    }

    /**
     * Tests the Adapt API connection.
     *
     * @return bool True if connection successful, false if not
     * @throws moodle_exception
     */
    public function test_adapt_connection() {
        return $this->adapt->test_connection();
    }
}

