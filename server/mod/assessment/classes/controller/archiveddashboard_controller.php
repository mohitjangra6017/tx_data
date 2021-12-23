<?php
/**
 * @copyright   City & Guilds Kineo 2017
 * @author      Steven Hughes <steven.hughes@kineo.com>
 * @author    Alex Glover <alex.glover@kineo.com>
 *
 * HITACHI-87
 * - This Dashboard shows only completed, archived assessments
 * - Show navigation tabs
 */

namespace mod_assessment\controller;

use mod_assessment\model\embedded_report;
use moodle_database;
use moodle_page;
use moodle_url;

class archiveddashboard_controller extends dashboard_controller
{

    /**
     * @var string
     */
    private $template = '';

    private $model;

    public static function get_permission(): string
    {
        return 'mod/assessment:viewarchiveddashboard';
    }

    public function __construct(moodle_page $page, moodle_database $db)
    {

        $this->reportshortname = 'assessment_dashboardarchived';
        $this->title = get_string('navigation:archiveddashboard', $this->plugin);

        parent::__construct($page, $db);

        $this->page->set_url(new moodle_url('/mod/assessment/dashboard.php', ['type' => 'archived', 'archived' => 1]));
    }

    public function render_dashboard()
    {
        echo $this->output->header();

        $context = $this->model->to_array();
        $context['navtabs'] = $this->output->dashboard_navtabs($this->page->url->get_param('type'));
        echo $this->output->render_from_template(
            $this->template,
            $context
        );

        echo $this->output->footer();
    }

    protected function setup()
    {
        parent::setup();
        $this->model = new embedded_report($this->title, $this->report);
        $this->template = 'mod_assessment/dashboard';
    }
}