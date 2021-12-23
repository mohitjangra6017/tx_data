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

use mod_assessment\factory\assessment_question_factory;
use mod_assessment\helper\role;
use mod_assessment\model\attempt;
use mod_assessment\model\question;
use mod_assessment\model\question_perms;

defined('MOODLE_INTERNAL') || die;

global $CFG;
require_once($CFG->dirroot . '/mod/assessment/rb_sources/rb_source_assessment.php');

class rb_source_assessment_detail extends rb_source_assessment
{

    /**
     * @var mixed|null
     */
    private $assessmentid;

    public function __construct($groupid, rb_global_restriction_set $globalrestrictionset = null)
    {
        if ($groupid instanceof rb_global_restriction_set) {
            throw new coding_exception('Wrong parameter orders detected during report source instantiation.');
        }

        $this->sourcesummary = get_string('sourcesummary', 'rb_source_assessment_detail');
        $this->sourcelabel = get_string('sourcelabel', 'rb_source_assessment_detail');

        parent::__construct($groupid, $globalrestrictionset);
    }

    protected function define_columnoptions(): array
    {
        $columnoptions = parent::define_columnoptions();

        $columnoptions[] = new rb_column_option(
            'attempt',
            'attempt',
            get_string('attempt', 'rb_source_assessment_detail'),
            'attempt.attempt',
            [
                'displayfunc' => 'integer',
                'joins' => ['attempt']
            ]
        );

        $columnoptions[] = new rb_column_option(
            'attempt',
            'datecompleted',
            get_string('attemptdatecompleted', 'rb_source_assessment_detail'),
            'attempt.timecompleted',    // From attempt and NOT course_modules completion - timecompleted deprecated?
            [
                'displayfunc' => 'nice_date',
                'joins' => ['attempt'],
            ]
        );

        $columnoptions[] = new rb_column_option(
            'attempt',
            'datearchived',
            get_string('attemptdatearchived', 'rb_source_assessment_detail'),
            'attempt.timearchived',
            [
                'displayfunc' => 'nice_date',
                'joins' => ['attempt'],
            ]
        );


        $columnoptions[] = new rb_column_option(
            'attempt',
            'grade',
            get_string('grade', 'rb_source_assessment_detail'),
            'attempt.grade',
            [
                'displayfunc' => 'round2',
                'joins' => ['attempt'],
            ]
        );

        $columnoptions[] = new rb_column_option(
            'attempt',
            'status',
            get_string('attemptstatus', 'rb_source_assessment_detail'),
            'CASE WHEN attempt.status IS NULL
                  THEN ' . attempt::STATUS_NOTSTARTED . '
                  ELSE attempt.status
              END',
            [
                'displayfunc' => 'attempt_status',
                'joins' => ['attempt'],
            ]
        );

        $columnoptions[] = new rb_column_option(
            'attempt',
            'version',
            get_string('version', 'rb_source_assessment_detail'),
            'version.version',
            [
                'displayfunc' => 'integer',
                'joins' => ['attempt']
            ]
        );

        self::add_core_user_columns($columnoptions, 'euser', 'submittingevaluator');

        return $columnoptions;
    }

    protected function define_filteroptions(): array
    {
        $filteroptions = parent::define_filteroptions();

        $filteroptions[] = new rb_filter_option(
            'attempt',
            'attempt',
            get_string('attempt', 'rb_source_assessment_detail'),
            'number'
        );

        $filteroptions[] = new rb_filter_option(
            'attempt',
            'datecompleted',
            get_string('attemptdatecompleted', 'rb_source_assessment_detail'),
            'date'
        );

        $filteroptions[] = new rb_filter_option(
            'attempt',
            'datearchived',
            get_string('attemptdatearchived', 'rb_source_assessment_detail'),
            'date'
        );

        $filteroptions[] = new rb_filter_option(
            'attempt',
            'grade',
            get_string('grade', 'rb_source_assessment_detail'),
            'number'
        );

        $filteroptions[] = new rb_filter_option(
            'attempt',
            'status',
            get_string('attemptstatus', 'rb_source_assessment_detail'),
            'select',
            [
                'simplemode' => true,
                'selectchoices' => $this->get_attempt_status_menu(),
            ]
        );

        $filteroptions[] = new rb_filter_option(
            'attempt',
            'version',
            get_string('version', 'rb_source_assessment_detail'),
            'number'
        );

        self::add_core_user_filters($filteroptions, 'submittingevaluator');

        return $filteroptions;
    }

    protected function define_joinlist(): array
    {
        $joinlist = parent::define_joinlist();

        $joinlist[] = new rb_join(
            'version',
            'LEFT',
            '{assessment_version}',
            'version.assessmentid = base.id',
            REPORT_BUILDER_RELATION_ONE_TO_MANY,
            []
        );

        $joinlist[] = new rb_join(
            'attempt',
            'INNER',
            '{assessment_attempt}',
            'attempt.versionid = version.id AND attempt.userid = auser.id',
            REPORT_BUILDER_RELATION_ONE_TO_MANY,
            ['version', 'auser']
        );

        $joinlist[] = new rb_join(
            'evaluatorids',
            'LEFT',
            "(SELECT DISTINCT answer.userid, answer.attemptid
                      FROM {assessment_answer} answer
                     WHERE answer.role = " . role::EVALUATOR . ")",
            "evaluatorids.attemptid = attempt.id",
            REPORT_BUILDER_RELATION_ONE_TO_MANY,
            ['attempt']
        );

        self::add_core_user_tables($joinlist, 'evaluatorids', 'userid', 'euser');

        return $joinlist;
    }

    protected function define_paramoptions(): array
    {
        $paramoptions = parent::define_paramoptions();
        $paramoptions[] = new rb_param_option('assessmentid', 'base.id', [], PARAM_INT);

        return $paramoptions;
    }

    protected function define_sourcetitle(): string
    {
        return get_string('sourcetitle', 'rb_source_assessment_detail');
    }

    public function get_attempt_status_menu(): array
    {
        return [
            mod_assessment\model\attempt::STATUS_COMPLETE => get_string('completed', 'assessment'),
            mod_assessment\model\attempt::STATUS_INPROGRESS => get_string('inprogress', 'assessment'),
            mod_assessment\model\attempt::STATUS_NOTSTARTED => get_string('notstarted', 'assessment'),
            mod_assessment\model\attempt::STATUS_FAILED => get_string('failed', 'assessment'),
            mod_assessment\model\attempt::STATUS_TERMINATED => get_string('terminated', 'assessment'),
        ];
    }

    /**
     * @param reportbuilder $report
     * @global moodle_database $DB
     */
    public function post_params(reportbuilder $report)
    {
        global $DB;

        $this->assessmentid = $report->get_param_value('assessmentid');
        if (!$this->assessmentid) {
            $redirecturl = new moodle_url('/mod/assessment/rb_sources/chooseassessment.php', ['id' => $report->_id]);
            $this->set_redirect($redirecturl);
            $this->needs_redirect();
        }

        // You have questions?
        $sql = "SELECT DISTINCT question.id,
                       question.question,
                       question.type,
                       vq.parentid,
                       MIN(version.version),
                       MIN(vs.sortorder),
                       MIN(vq.sortorder),
                       MIN(vq.parentid)
                  FROM {assessment_question} question
                  JOIN {assessment_version_question} vq ON vq.questionid = question.id
                  JOIN {assessment_version} version ON version.id = vq.versionid
                  JOIN {assessment_version_stage} vs ON vs.stageid = vq.stageid
                 WHERE version.assessmentid = :assessmentid
              GROUP BY question.id, question.question, question.type, vq.parentid
              ORDER BY MIN(version.version), MIN(vs.sortorder), MIN(vq.parentid), MIN(vq.sortorder)";
        $questions = $DB->get_records_sql($sql, ['assessmentid' => $this->assessmentid]);

        // Oh yeah, baby.  Let's build all them answers.
        foreach ($questions as $question) {
            $qid = "q_{$question->id}";

            $qm = question::instance(['id' => $question->id]);
            if (!$qm->is_question()) { // Don't create joins/column for question if it's not answerable.
                continue;
            }

            // Add for each role.
            foreach (role::get_roles() as $rolevalue => $rolestring) {
                $checkquestion = $qm;
                if ($question->parentid) {
                    $checkquestion = assessment_question_factory::fetch(['id' => $question->parentid]);
                }
                if (!$checkquestion->check_permission(new \mod_assessment\model\role($rolevalue), question_perms::CAN_ANSWER)) {
                    continue;
                }

                $uid = "{$qid}_{$rolevalue}";
                $this->joinlist[] = new rb_join(
                    $uid,
                    "LEFT",
                    "{assessment_answer}",
                    "{$uid}.attemptid = attempt.id AND $uid.questionid = {$question->id} AND $uid.role = {$rolevalue}",
                    REPORT_BUILDER_RELATION_ONE_TO_MANY,
                    ['attempt']
                );

                $this->requiredcolumns[] = new rb_column(
                    'question',
                    "answer_$uid",
                    "({$rolestring}) $question->question",
                    "$uid.value",
                    [
                        'displayfunc' => 'assessment_answer',
                        'extrafields' => ['questionid' => $qm->get_id()],
                        'joins' => [$uid],
                        'nosort' => true,
                    ]
                );

            }
        }
    }

}
