<?php
/**
 * @copyright   City & Guilds Kineo 2017
 * @author      Steven Hughes <steven.hughes@kineo.com>
 */

namespace mod_assessment\controller;

use context;
use context_system;
use core_renderer;
use mod_assessment\helper;
use moodle_database;
use moodle_exception;
use moodle_page;
use rb_config;
use rb_global_restriction_set;
use reportbuilder;
use totara_reportbuilder\event\report_viewed;

abstract class dashboard_controller
{

    const TYPES = ['standard', 'failed', 'completed', 'completed_reviewed', 'archived'];

    /**
     * @var moodle_database
     */
    protected moodle_database $db;

    /**
     * @var reportbuilder
     */
    protected $report;

    /**
     * @var context
     */
    protected $context;

    /**
     * @var core_renderer
     */
    protected $output;

    /**
     * @var moodle_page
     */
    protected moodle_page $page;

    /**
     * @var string
     */
    protected string $plugin = 'mod_assessment';

    protected string $reportshortname = '';
    protected string $title = '';

    public function __construct(moodle_page $page, moodle_database $db)
    {
        $this->db = $db;

        $this->page = $page;
        $this->context = context_system::instance();
        $this->page->set_context($this->context);
        $this->page->set_pagelayout('noblocks');
        $this->page->set_title($this->title);
        $this->page->set_heading($this->title);
        $this->output = $this->page->get_renderer('mod_assessment');

        $this->check_permissions();
        $this->setup();
    }

    /**
     * @param string $type
     * @return dashboard_controller
     * @throws moodle_exception
     */
    public static function get_active_controller(string $type = '')
    {
        global $SESSION;

        $controller = null;

        // Check requested first.
        if ($type) {
            $controller = static::get_controller($type);
        }

        // Then check the previous dashboard.
        if (!$controller && !empty($SESSION->assessment['lastdash'])) {
            $controller = static::get_controller($SESSION->assessment['lastdash']);
        }

        if ($controller) {
            return $controller;
        }

        // Gee, do we have access to ANY controller?
        foreach (static::TYPES as $type) {
            $controller = static::get_controller($type);
            if ($controller) {
                return $controller;
            }
        }

        // No?  Throw an error.
        print_error('error:nodashboardaccess', 'mod_assessments');

    }

    protected static function get_controller(string $type): ?dashboard_controller
    {
        global $DB, $PAGE;
        $controller = "mod_assessment\\controller\\{$type}dashboard_controller";
        if (class_exists($controller) && $controller::has_permissions()) {
            return new $controller($PAGE, $DB);
        }
        return null;
    }

    public static function has_permissions(): bool
    {
        return has_capability(static::get_permission(), context_system::instance())
            || helper\role::is_user_evaluator()
            || helper\role::is_user_reviewer();
    }

    protected function check_permissions()
    {
        // Allow reviewers to view dashboards as well
        if (!static::has_permissions()) {
            print_error('error:nodashboardaccess', 'mod_assessment');
        }
    }

    protected function setup()
    {
        global $DB;

        $reportrecord = $DB->get_record('report_builder', ['shortname' => $this->reportshortname]);
        $globalrestrictionset = rb_global_restriction_set::create_from_page_parameters($reportrecord);
        $config = (new rb_config())->set_global_restriction_set($globalrestrictionset);
        $this->report = reportbuilder::create_embedded($this->reportshortname, $config);

        if (!$this->report) {
            print_error('error:couldnotgenerateembeddedreport', 'totara_reportbuilder');
        }

        if ($debug = optional_param('debug', false, PARAM_INT)) { // Allow debug param to be set
            $this->report->debug($debug);
        }

        report_viewed::create_from_report($this->report)->trigger();
        $this->report->include_js();
        $this->page->set_button($this->report->edit_button());
    }

    public function get_page(): moodle_page
    {
        return $this->page;
    }

    abstract public static function get_permission();

    abstract public function render_dashboard();
}