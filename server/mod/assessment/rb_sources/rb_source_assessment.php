<?php
/**
 * @copyright 2017-2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

use mod_assessment\helper\role;
use mod_assessment\helper;

use totara_job\rb\source\report_trait;

defined('MOODLE_INTERNAL') || die;

global $CFG;
require_once($CFG->libdir . '/completionlib.php');

class rb_source_assessment extends rb_base_source
{

    use \core_course\rb\source\report_trait;
    use \core_user\rb\source\report_trait;
    use report_trait;

    public function __construct($groupid, rb_global_restriction_set $globalrestrictionset = null)
    {
        if ($groupid instanceof rb_global_restriction_set) {
            throw new coding_exception('Wrong parameter orders detected during report source instantiation.');
        }
        // Remember the active global restriction set.
        $this->globalrestrictionset = $globalrestrictionset;

        $this->base = $this->define_base();
        $this->joinlist = $this->define_joinlist();
        $this->columnoptions = $this->define_columnoptions();
        $this->filteroptions = $this->define_filteroptions();
        $this->contentoptions = $this->define_contentoptions();
        $this->paramoptions = $this->define_paramoptions();
        $this->sourcetitle = $this->define_sourcetitle();
        $this->sourcesummary = get_string('sourcesummary', 'rb_source_assessment');
        $this->sourcelabel = get_string('sourcelabel', 'rb_source_assessment');

        $this->requiredcolumns = $this->define_requiredcolumns();
        $this->add_global_report_restriction_join('learnerids', 'userid', 'learnerids');
        $this->usedcomponents[] = 'mod_assessment';
        $this->usedcomponents[] = 'totara_cohort';

        parent::__construct();
    }

    protected function define_base(): string
    {
        return '{assessment}';
    }

    protected function define_columnoptions(): array
    {
        $columnoptions = parent::define_columnoptions();

        $columnoptions[] = new rb_column_option(
            'assessment',
            'name',
            get_string('activityname', 'rb_source_assessment'),
            'base.name',
            [
                'displayfunc' => 'format_string',
                'dbdatatype' => 'char',
                'outputformat' => 'text',
            ]
        );

        $columnoptions[] = new rb_column_option(
            'assessment',
            'userlink',
            get_string('assessmentuserlink', 'rb_source_assessment'),
            'base.name',
            [
                'displayfunc' => 'assessment_link',
                'extrafields' => ['cmid' => 'coursemodule.id', 'userid' => 'auser.id'],
                'defaultheading' => get_string('activityname', 'rb_source_assessment'),
                'joins' => ['auser', 'coursemodule'],
            ]
        );

        $columnoptions[] = new rb_column_option(
            'assessment',
            'viewsummary',
            get_string('viewsummary', 'rb_source_assessment'),
            'base.id',
            [
                'displayfunc' => 'assessment_view_summary',
                'extrafields' => ['userid' => 'auser.id'],
                'joins' => ['auser', 'coursemodule'],
                'noexport' => true,
                'nosort' => true
            ]
        );

        $columnoptions[] = new rb_column_option(
            'assessmentcompl',
            'status',
            get_string('activitystatus', 'rb_source_assessment'),
            "CASE WHEN coursemodulecompl.completionstate IS NULL
                  THEN 0
                  ELSE CASE WHEN coursemodulecompl.completionstate IN (0, 3)
                       THEN 0
                       ELSE 1
                       END
                  END",
            [
                'displayfunc' => 'cm_status',
                'joins' => ['coursemodulecompl'],
            ]
        );

        $truncatelen = 100;

        $columnoptions[] = new rb_column_option(
            'assessmentcompl',
            'evaluators',
            get_string('evaluators', 'rb_source_assessment'),
            'evaluatorssummary.evaluatorfullnames',
            [
                'dbdatatype' => 'text',
                'displayfunc' => 'truncated_text',
                'extrafields' => ['maxchars' => $truncatelen],
                'joins' => ['evaluatorssummary'],
                'nosort' => true,
                'outputformat' => 'text',
            ]
        );

        $columnoptions[] = new rb_column_option(
            'assessmentcompl',
            'reviewers',
            get_string('reviewers', 'rb_source_assessment'),
            'reviewerssummary.reviewerfullnames',
            [
                'dbdatatype' => 'text',
                'displayfunc' => 'truncated_text',
                'extrafields' => ['maxchars' => $truncatelen],
                'joins' => ['reviewerssummary'],
                'nosort' => true,
                'outputformat' => 'text',
            ]
        );

        $columnoptions[] = new rb_column_option(
            'assessmentcompl',
            'datecompleted',
            get_string('activitydatecompleted', 'rb_source_assessment'),
            'summary.timecompleted',
            [
                'displayfunc' => 'nice_date',
                'joins' => ['summary'],
            ]
        );

        $columnoptions[] = new rb_column_option(
            'assessmentcompl',
            'attemptcount',
            get_string('attemptcount', 'rb_source_assessment'),
            'summary.attempts',
            [
                'displayfunc' => 'integer',
                'joins' => ['summary']
            ]
        );

        $columnoptions[] = new rb_column_option(
            'assessment',
            'duetype',
            get_string('duetype', 'rb_source_assessment'),
            'base.duetype',
            array(
                'dbdatatype' => 'integer',
                'displayfunc' => 'assessment_duetype',
            )
        );

        $columnoptions[] = new rb_column_option(
            'assessmentdue',
            'timedue',
            get_string('timedue', 'rb_source_assessment'),
            'assessmentdue.timedue',
            array(
                'joins' => array('assessmentdue'),
                'dbdatatype' => 'timestamp',
                'displayfunc' => 'nice_date',
            )
        );

        $now = time();
        $columnoptions[] = new rb_column_option(
            'assessmentdue',
            'daysdue',
            get_string('daysdue', 'rb_source_assessment'),
            "(CASE WHEN assessmentdue.timedue > 0 THEN (assessmentdue.timedue - {$now})/24/60/60 ELSE NULL END)",
            array(
                'joins' => array('assessmentdue'),
                'dbdatatype' => 'integer',
                'displayfunc' => 'integer'
            )
        );

        self::add_core_course_columns($columnoptions);
        self::add_core_user_columns($columnoptions, 'auser', 'learner');
        self::add_totara_job_columns($columnoptions);

        return $columnoptions;
    }

    protected function define_contentoptions(): array
    {
        $contentoptions = parent::define_contentoptions();

        $this->add_basic_user_content_options($contentoptions);

        $contentoptions[] = new rb_content_option(
            'date',
            get_string('activitydatecompleted', 'rb_source_assessment'),
            'summary.timecompleted',
            ['summary']
        );

        return $contentoptions;
    }

    protected function define_filteroptions(): array
    {
        $filteroptions = parent::define_filteroptions();

        $filteroptions[] = new rb_filter_option(
            'assessment',
            'name',
            get_string('activityname', 'rb_source_assessment'),
            'text'
        );

        $filteroptions[] = new rb_filter_option(
            'assessmentcompl',
            'status',
            get_string('activitystatus', 'rb_source_assessment'),
            'select',
            [
                'simplemode' => true,
                'selectchoices' => $this->get_cm_status_menu(),
            ]
        );

        $filteroptions[] = new rb_filter_option(
            'assessmentcompl',
            'evaluators',
            get_string('evaluators', 'rb_source_assessment'),
            'text'
        );

        $filteroptions[] = new rb_filter_option(
            'assessmentcompl',
            'reviewers',
            get_string('reviewers', 'rb_source_assessment'),
            'text'
        );

        $filteroptions[] = new rb_filter_option(
            'assessmentcompl',
            'datecompleted',
            get_string('activitydatecompleted', 'rb_source_assessment'),
            'date'
        );

        $filteroptions[] = new rb_filter_option(
            'assessment',
            'duetype',
            get_string('duetype', 'rb_source_assessment'),
            'select',
            array(
                'simplemode' => true,
                'selectchoices' => helper\assessment_due_helper::get_duetypes(),
            )
        );

        $filteroptions[] = new rb_filter_option(
            'assessmentdue',
            'timedue',
            get_string('timedue', 'rb_source_assessment'),
            'date'
        );

        $filteroptions[] = new rb_filter_option(
            'assessmentdue',
            'daysdue',
            get_string('daysdue', 'rb_source_assessment'),
            'number'
        );

        self::add_core_course_filters($filteroptions);
        self::add_core_user_filters($filteroptions, 'learner');
        self::add_totara_job_filters($filteroptions);

        return $filteroptions;
    }

    protected function define_joinlist(): array
    {
        global $DB;

        $joinlist = [];

        $attempt_status = mod_assessment\model\attempt::STATUS_INPROGRESS; // was: WHERE attempt.status = " . mod_assessment\model\attempt::STATUS_COMPLETE . "
        $max_chars = 500; // let's truncate the names list so we don't pull so much data, as there may be many evaluators assigned

        $this->uniquedelimiter = ', '; // Comma delimited names
        $evaluator_role = role::EVALUATOR;
        $reviewer_role = role::REVIEWER;
        $fullnamesql = $DB->sql_group_concat_unique(
            $DB->sql_concat_join("' '", totara_get_all_user_name_fields_join('u', null, true)),
            $this->uniquedelimiter,
            $DB->sql_concat_join("' '", totara_get_all_user_name_fields_join('u', null, true))
        );

        $archived = optional_param('archived', 0, PARAM_BOOL); // This is a param option of rb_source_assessment_dashboard.
        $archived_clause = ($archived ? "AND attempt.timearchived > 0" : "AND attempt.timearchived = 0");

        // I think we want to count any in-progress and failed attempts as well...?
        $joinlist[] = new rb_join(
            'summary',
            'LEFT',
            "(SELECT attempt.userid, version.assessmentid,
                     MIN(timecompleted) AS timecompleted,
                     COUNT(attempt.id) AS attempts
                FROM {assessment_attempt} attempt
                JOIN {assessment_version} version ON version.id = attempt.versionid
               WHERE attempt.status >= {$attempt_status} {$archived_clause}
            GROUP BY attempt.userid, version.assessmentid)",
            'summary.assessmentid = base.id AND summary.userid = auser.id',
            REPORT_BUILDER_RELATION_MANY_TO_MANY,
            ['auser']
        );

        $joinlist[] = new rb_join(
            'evaluatorssummary',
            'LEFT',
            "(SELECT attempt.userid, version.assessmentid, substring($fullnamesql, 1, {$max_chars}) AS evaluatorfullnames
                      FROM {assessment_attempt_assignments} assign
                      JOIN {assessment_attempt} attempt ON attempt.id = assign.attemptid
                      JOIN {assessment_version} version ON version.id = attempt.versionid
                      JOIN {user} u ON u.id = assign.userid
                     WHERE attempt.status >= {$attempt_status} AND assign.role = {$evaluator_role} {$archived_clause}
                  GROUP BY attempt.userid, version.assessmentid)",
            'evaluatorssummary.assessmentid = base.id AND evaluatorssummary.userid = auser.id',
            REPORT_BUILDER_RELATION_MANY_TO_MANY,
            ['auser']
        );

        $joinlist[] = new rb_join(
            'reviewerssummary',
            'LEFT',
            "(SELECT attempt.userid, version.assessmentid, substring($fullnamesql, 1, {$max_chars}) AS reviewerfullnames
                      FROM {assessment_attempt_assignments} assign
                      JOIN {assessment_attempt} attempt ON attempt.id = assign.attemptid
                      JOIN {assessment_version} version ON version.id = attempt.versionid
                      JOIN {user} u ON u.id = assign.userid
                     WHERE attempt.status >= {$attempt_status} AND assign.role = {$reviewer_role} {$archived_clause}
                  GROUP BY attempt.userid, version.assessmentid)",
            'reviewerssummary.assessmentid = base.id AND reviewerssummary.userid = auser.id',
            REPORT_BUILDER_RELATION_MANY_TO_MANY,
            ['auser']
        );

        $moduleId = $DB->get_field('modules', 'id', ['name' => 'assessment']);
        $joinlist[] = new rb_join(
            'coursemodule',
            'INNER',
            "{course_modules}",
            "coursemodule.module = {$moduleId} AND 
            coursemodule.instance = base.id AND 
            coursemodule.course = base.course",
            REPORT_BUILDER_RELATION_MANY_TO_MANY
        );

        $joinlist[] = new rb_join(
            'coursemodulecompl',
            'LEFT',
            '{course_modules_completion}',
            'coursemodulecompl.coursemoduleid = coursemodule.id AND coursemodulecompl.userid = auser.id',
            REPORT_BUILDER_RELATION_ONE_TO_MANY,
            ['coursemodule', 'auser']
        );

        // Just grab user once through attempt without duplicating records with attempts.
        $joinlist[] = new rb_join(
            'learnerids',
            'LEFT',
            "(SELECT DISTINCT attempt.userid, version.assessmentid
                FROM {assessment_attempt} attempt
                JOIN {assessment_version} version ON version.id = attempt.versionid {$archived_clause}
             )",
            "learnerids.assessmentid = base.id",
            REPORT_BUILDER_RELATION_MANY_TO_MANY,
            []
        );

        $joinlist[] = new rb_join(
            'assessmentdue',
            'LEFT',
            '{assessment_due}',
            "(assessmentdue.assessmentid = base.id AND assessmentdue.userid = learnerids.userid)",
            REPORT_BUILDER_RELATION_ONE_TO_ONE,
            array(
                'joins' => 'learnerids',
            )
        );

        self::add_core_user_tables($joinlist, 'learnerids', 'userid');
        self::add_core_course_tables($joinlist, 'base', 'course');
        self::add_totara_job_tables($joinlist, 'coursemodulecompl', 'userid');

        return $joinlist;
    }

    protected function define_sourcetitle(): string
    {
        return get_string('sourcetitle', 'rb_source_assessment');
    }

    public function get_cm_status_menu(): array
    {
        return [
            COMPLETION_COMPLETE => get_string('complete', 'rb_source_assessment'),
            0 => get_string('incomplete', 'rb_source_assessment')
        ];
    }

    public function global_restrictions_supported(): bool
    {
        return true;
    }

    public function phpunit_column_test_expected_count($columnoption): int
    {
        return 0;
    }

}
