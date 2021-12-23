<?php
/**
 * @copyright 2010 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

namespace mod_assessment\form;

use mod_assessment\model\version;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class assessmentsettings extends \moodleform
{
    /** @var version */
    protected version $version;

    protected function definition()
    {
        $mform = $this->_form;
        $this->version = $this->_customdata['version'];

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'versionid');
        $mform->setType('versionid', PARAM_INT);

        $freezefields = [];

        $atts = ['onchange' => 'window.onbeforeunload = null; $(this).closest("form").submit();'];
        $mform->addElement('advcheckbox', 'hidegrade', get_string('label:hidegradeinoverview', 'assessment'), '', $atts);
        //$default = get_config('assessment', 'defaulthidegradeinoverview');
        //$mform->setDefault('hidegrade', $default);
        $freezefields[] = 'hidegrade';

        if (!$this->version->is_draft()) {
            // Disable everything!
            $mform->freeze($freezefields);
        }
    }

    public function get_version(): version
    {
        return $this->version;
    }
}
