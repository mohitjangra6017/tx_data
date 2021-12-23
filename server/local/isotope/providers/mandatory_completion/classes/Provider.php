<?php
/**
 * @package isotopeprovider_mandatory_completion
 * @author Ben Lobo <ben.lobo@kineo.com>
 * @copyright City & Guilds Kineo 2019
 * @license http://www.kineo.com
 */

namespace isotopeprovider_mandatory_completion;

use local_isotope\IsotopeProvider;
use local_isotope\Data\DecoratorSource;
use isotopeprovider_mandatory_completion\DataSources\MandatoryCompletion as MandatoryCompletionDataSource;
use isotopeprovider_mandatory_completion\DataDecorators\MandatoryCompletion as MandatoryCompletionDataDecorator;

defined('MOODLE_INTERNAL') || die;

global $CFG;

require_once $CFG->dirroot . '/totara/hierarchy/prefix/organisation/lib.php';

class Provider extends IsotopeProvider
{
    const COMPONENT = 'isotopeprovider_mandatory_completion';

    public function __construct()
    {
        $this->setConfig([]);
    }

    /**
     * Return the human-friendly name of the provider.
     * @return string
     */
    public function getDisplayName()
    {
        return get_string('title', self::COMPONENT);
    }

    /**
     * Return the short name of the plugin, used in config settings, and as a unique key.
     * @return string
     */
    public function getShortName()
    {
        return 'mandatory_completion';
    }

    public function setConfig(array $config)
    {
        parent::setConfig($config);
    }

    /**
     * @return array
     */
    public function load()
    {
        global $USER, $SESSION;

        $data = [
            'plugin' => self::COMPONENT,
            'config' => $this->config,
            'error' => false
        ];

        $reportersJobAssignment = \totara_job\job_assignment::get_first($USER->id, false);
        if (!$reportersJobAssignment) {
            return $data;
        }

        $reportersOrganisation = new \organisation();
        if (!$reportersJobAssignment->organisationid) {
            return $data;
        }

        $reportersOrganisationChildren = $reportersOrganisation->get_item_descendants($reportersJobAssignment->organisationid);

        $orgId = optional_param('orgid', 0, PARAM_INT);
        $userId = optional_param('userid', 0, PARAM_INT);

        $topLevel = false;
        if ($orgId == 0) {
            $orgId = $reportersJobAssignment->organisationid;
            $topLevel = true;
        }
        $data['toplevel'] = $topLevel;

        if (!$orgId) {
            $data['error'] = true;
            $data['errormsg'] = get_string('error:nojobassignment', self::COMPONENT);
            return $data;
        }

        if ($orgId != $reportersJobAssignment->organisationid && !isset($reportersOrganisationChildren[$orgId])) {
            $data['error'] = true;
            $data['errormsg'] = get_string('error:invalidorgid', self::COMPONENT);
            return $data;
        }

        $data['breadcrumb'] = $this->getBreadCrumb($orgId, $reportersJobAssignment->organisationid);

        $targetOrganisation = new \organisation();
        $targetOrganisationChildren = $targetOrganisation->get_item_descendants($orgId);
        unset($targetOrganisationChildren[$orgId]);

        $dataSource = new MandatoryCompletionDataSource($orgId, $targetOrganisationChildren);
        $decorator = new MandatoryCompletionDataDecorator();
        $dataDecoratorSource = new DecoratorSource($dataSource, $decorator);

        // Cache the data to prevent unnecessary regeneration of the same data during the session.
        if (
            !isset($SESSION->compliance_data) ||
            (isset($SESSION->compliance_data_cache_time) && $SESSION->compliance_data_cache_time + 600 < time())
        ) {
            $SESSION->compliance_data = $dataSource->getCompletionData($reportersJobAssignment->organisationid);
            $SESSION->compliance_data_cache_time = time();
        }

        if (!$userId && $targetOrganisationChildren) {

            $organisation = $targetOrganisation->get_item($orgId);

            if ($organisation->parentid != 0) {
                $data['name'] = $organisation->fullname;
            } else {
                $data['name'] = get_string('all_data', self::COMPONENT);
            }

            $data['items'] = $dataDecoratorSource->getData();
            list($data['completed'], $data['notcompleted'], $data['overdue']) = $dataSource->getSingleOrgPercentData($organisation->id);
            $data['blocks'] = true;

        } elseif ($userId) {

            $url = $dataSource->getUserLevelUrl($userId);
            redirect($url);

        } else { // This is when we're at the bottom level of the org hierarchy (i.e. no more child orgs).

            $data['name'] = $targetOrganisation->get_item($orgId)->fullname;
            $data['items'] = $dataSource->getOrgUserPercentData($orgId);
            list($data['completed'], $data['notcompleted'], $data['overdue']) = $dataSource->getSingleOrgPercentData($orgId);
            $data['blocks'] = true;

        }

        return $data;

    }

    /**
     * Generates the breadcrumb items based on the top and bottom levels of the
     * org hierarchy that we want to display.
     *
     * @global object $DB
     * @global object $FULLME
     * @param int $bottomLevelOrgId
     * @param int $topLevelOrgId
     * @return array
     */
    public function getBreadCrumb($bottomLevelOrgId, $topLevelOrgId)
    {
        global $DB, $FULLME;

        $breadCrumb = [];
        $org = $DB->get_record('org', ['id' => $bottomLevelOrgId]);
        $pathItems = explode('/', $org->path);
        $topLevelOrgReached = false;
        foreach($pathItems as $pathItem) {
            if (empty($pathItem)) {
                continue;
            }
            if ($pathItem == $topLevelOrgId) {
                $topLevelOrgReached = true;
            }
            if (!$topLevelOrgReached) {
                continue;
            }
            $pathItemOrg = $DB->get_record('org', ['id' => $pathItem]);
            if ($pathItem == $bottomLevelOrgId) {
                $breadCrumb[] = [
                    'url' => '',
                    'name' => $pathItemOrg->fullname
                ];
            } else {
                $breadCrumb[] = [
                    'url' => (new \moodle_url($FULLME, ['orgid' => $pathItem]))->out(false),
                    'name' => $pathItemOrg->fullname
                ];
            }
        }
        return $breadCrumb;
    }

    /**
     * Returns the path to the main template to be loaded.
     * @return string
     */
    public function getTemplateFilename()
    {
        return 'mandatory_completion.twig';
    }

    /**
     * @return Option[]
     */
    public function getSettings()
    {
        $options = [];

        return $options;
    }

    /**
     * Include and init any required JavaScript.
     */
    public function initJavaScript()
    {
        global $PAGE, $CFG;
        $selector = !empty($this->blockInstanceId) ? '#inst' . $this->blockInstanceId : '';
        $PAGE->requires->jquery();
        $PAGE->requires->js_init_call(
            'M.isotope_provider_mandatory_completion.init',
            ['selector' => trim("{$selector} .mandatorycompletion")],
            false,
            [
                'name' => 'isotope_provider_mandatory_completion',
                'fullpath' => new \moodle_url(substr(dirname(__DIR__), strlen($CFG->dirroot)) . '/js/base.js'),
            ]
        );
    }

}
