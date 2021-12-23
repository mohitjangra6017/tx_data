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
use mod_assessment\model\attempt;

defined('MOODLE_INTERNAL') || die;

global $CFG;
require_once($CFG->dirroot . '/mod/assessment/rb_sources/rb_source_assessment.php');

class rb_source_assessment_dashboard extends rb_source_assessment
{

    public function __construct($groupid, rb_global_restriction_set $globalrestrictionset = null)
    {
        if ($groupid instanceof rb_global_restriction_set) {
            throw new coding_exception('Wrong parameter orders detected during report source instantiation.');
        }
        $this->sourcesummary = get_string('sourcesummary', 'rb_source_assessment_dashboard');
        $this->sourcelabel = get_string('sourcelabel', 'rb_source_assessment_dashboard');

        parent::__construct($groupid, $globalrestrictionset);
    }


    protected function define_base(): string
    {
        return '{assessment}';
    }

    protected function define_joinlist(): array
    {
        $joinlist = parent::define_joinlist();

        // For the dashboard, we only care about active versions.
        $joinlist['version'] = new rb_join(
            'version',
            'INNER',
            '{assessment_version}',
            "version.assessmentid = base.id AND version.timeopened > 0 AND version.timeclosed IS NULL"
        );

        $archived = optional_param('archived', 0, PARAM_BOOL); // This is a param option of rb_source_assessment_dashboard.

        // Get extra data for use in embedded filters and joins.
        $archived_clause = ($archived ? "timearchived > 0" : "timearchived = 0");
        $joinlist['meta'] = new rb_join(
            'meta',
            'INNER',
            "(  SELECT MAX(id) AS id, userid, versionid
                 FROM {assessment_attempt} attempt
                 WHERE {$archived_clause}
                 GROUP BY userid, versionid
             )",
            "meta.userid = auser.id AND meta.versionid = version.id",
            REPORT_BUILDER_RELATION_ONE_TO_MANY,
            ['auser', 'version']
        );

        // Only get latest attempt for dashboard.
        // TODO: Modify based on form params and associate content filter?
        $joinlist['attempt'] = new rb_join(
            'attempt',
            'INNER',
            '{assessment_attempt}',
            "attempt.id = meta.id",
            REPORT_BUILDER_RELATION_MANY_TO_ONE,
            ['meta']
        );

        return $joinlist;
    }

    protected function define_paramoptions(): array
    {
        global $DB, $USER;

        $paramoptions = parent::define_paramoptions();

        $alias = $DB->get_unique_param('assign');
        $wheresql = "$alias.attemptid = attempt.id AND $alias.role = " . role::EVALUATOR;
        $paramfieldsql = "SELECT $alias.userid FROM {assessment_attempt_assignments} $alias WHERE $wheresql";
        $paramoptions[] = new rb_param_option(
            'isevaluator',
            "$USER->id = ANY ($paramfieldsql)",
            ['attempt']
        );

        $alias = $DB->get_unique_param('assign');
        $wheresql = "$alias.attemptid = attempt.id AND $alias.role = " . role::REVIEWER;
        $paramfieldsql = "SELECT $alias.userid FROM {assessment_attempt_assignments} $alias WHERE $wheresql";
        $paramoptions[] = new rb_param_option(
            'isreviewer',
            "$USER->id = ANY ($paramfieldsql)",
            ['attempt']
        );

        $alias = $DB->get_unique_param('assign');
        $wheresql = "$alias.attemptid = attempt.id AND $alias.role IN (" . implode(',', [role::EVALUATOR, role::REVIEWER]) . ")";
        $paramfieldsql = "SELECT $alias.userid FROM {assessment_attempt_assignments} $alias WHERE $wheresql";
        $paramoptions[] = new rb_param_option(
            'isevaluatororreviewer',
            "$USER->id = ANY ($paramfieldsql)",
            ['attempt']
        );

        $notstartedstatus = attempt::STATUS_NOTSTARTED;
        $incompletestatus = attempt::STATUS_INPROGRESS;
        $completedstatus = attempt::STATUS_COMPLETE;
        $failedstatus = attempt::STATUS_FAILED;

        // include failed and passed (complete)
        $statuses = [$failedstatus, $completedstatus];
        $statuses_in = 'IN (' . join(',', $statuses) . ')';
        $paramoptions[] = new rb_param_option(
            'needsreview',
            "CASE WHEN attempt.status {$statuses_in} AND attempt.timereviewed IS NULL THEN 1 ELSE 0 END",
            ['attempt']
        );

        // include not started and in progress
        $statuses = [$notstartedstatus, $incompletestatus];
        $statuses_in = 'IN (' . join(',', $statuses) . ')';
        $paramoptions[] = new rb_param_option(
            'attemptincomplete',
            "(attempt.status {$statuses_in})",
            ['attempt']
        );

        // include only completed
        $paramoptions[] = new rb_param_option(
            'attemptcomplete',
            "(attempt.status = {$completedstatus})",
            ['attempt']
        );

        // include only failed
        $paramoptions[] = new rb_param_option(
            'attemptfailed',
            "(attempt.status = {$failedstatus})",
            ['attempt']
        );

        // include failed and passed (complete)
        $statuses = [$failedstatus, $completedstatus];
        $statuses_in = 'IN (' . join(',', $statuses) . ')';
        $paramoptions[] = new rb_param_option(
            'attemptfinished',
            "(attempt.status {$statuses_in})",
            ['attempt']
        );

        // include only archived
        $paramoptions[] = new rb_param_option(
            'archived',
            "(attempt.timearchived > 0)",
            ['attempt']
        );

        return $paramoptions;
    }

    protected function define_requiredcolumns(): array
    {
        $requiredcolumns = parent::define_defaultcolumns();

        $requiredcolumns[] = new rb_column(
            'attempt',
            'id',
            get_string('attemptid', 'rb_source_assessment_dashboard'),
            'attempt.id',
            [
                'hidden' => true,
                'joins' => ['attempt'],
            ]
        );

        return $requiredcolumns;
    }

    protected function define_sourcetitle(): string
    {
        return get_string('sourcetitle', 'rb_source_assessment_dashboard');
    }

}
