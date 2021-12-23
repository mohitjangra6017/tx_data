<?php
/**
 * @copyright   City & Guilds Kineo 2017
 * @author      Steven Hughes <steven.hughes@kineo.com>
 */

use mod_assessment\helper;

class rb_assessment_dashboardcompleted_reviewed_embedded extends rb_base_embedded
{

    public $url, $source, $fullname, $filters, $columns;
    public $contentmode, $contentsettings, $embeddedparams;
    public $hidden, $accessmode, $accesssettings, $shortname;

    private $plugin = 'rb_source_assessment';

    public function __construct($data)
    {

        $this->url = '/mod/assessment/dashboard.php?type=completed_reviewed';
        $this->source = 'assessment_dashboard';
        $this->shortname = 'assessment_dashboardcompleted_reviewed';
        $this->fullname = get_string('dashboardcompleted_reviewed', $this->plugin);
        $this->columns = [
            ['type' => 'learner', 'value' => 'fullname', 'heading' => get_string('learnerfullname', $this->plugin)],
            ['type' => 'course', 'value' => 'fullname', 'heading' => get_string('coursename', $this->plugin)],
            ['type' => 'assessment', 'value' => 'name', 'heading' => get_string('activityname', $this->plugin)],
            ['type' => 'assessmentcompl', 'value' => 'status', 'heading' => get_string('activitystatus', 'rb_source_assessment')],
            ['type' => 'assessment', 'value' => 'viewsummary', 'heading' => get_string('viewsummary', $this->plugin)],
        ];

        $this->embeddedparams = [];
        $this->embeddedparams['needsreview'] = 0;
        $this->embeddedparams['attemptfinished'] = 1; // Shows attempts that are either completed (passed) or failed

        // View capability allows view all, given to admins.
        // ... If we don't have that then we are an evaluator
        // ... and must restrict based on evaluator id.
        if (!has_capability('mod/assessment:viewcompletedrevieweddashboard', context_system::instance())) {
            $this->embeddedparams['isevaluatororreviewer'] = 1;
        }

        $this->filters = [];

        $this->contentmode = REPORT_BUILDER_CONTENT_MODE_ALL;
        $this->contentsettings = [
            'user_visibility' => [
                'enable' => 1,
            ]
        ];

        parent::__construct();
    }

    /**
     * Check if the user is capable of accessing this report.
     * We use $reportfor instead of $USER->id and $report->get_param_value() instead of getting params
     * some other way so that the embedded report will be compatible with the scheduler (in the future).
     *
     * @param int $reportfor userid of the user that this report is being generated for
     * @param reportbuilder $report the report object - can use get_param_value to get params
     * @return boolean true if the user can access this report
     */
    public function is_capable(int $reportfor, reportbuilder $report): bool
    {
        return has_capability('mod/assessment:viewcompletedrevieweddashboard', context_system::instance())
            || helper\role::is_user_evaluator()
            || helper\role::is_user_reviewer();

    }

    /**
     * @return bool
     */
    public function embedded_global_restrictions_supported(): bool
    {
        return true;
    }
}