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

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/mod/assessment/lib.php');

/**
 * Upgrade steps for the assessment module
 *
 * @param int $oldversion
 * @return bool
 * @global moodle_database $DB
 */
function xmldb_assessment_upgrade($oldversion)
{
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2017080600) {

        // Define field evaluatorid to be added to assessment_attempt.
        $table = new xmldb_table('assessment_attempt');
        $field = new xmldb_field('evaluatorid', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'id');

        // Conditionally launch add field evaluatorid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2017080600, 'assessment');
    }

    if ($oldversion < 2017080601) {

        // Define key fk_evaluatorid (foreign) to be added to assessment_attempt.
        $table = new xmldb_table('assessment_attempt');
        $key = new xmldb_key('fk_evaluatorid', XMLDB_KEY_FOREIGN, array('evaluatorid'), 'user', array('id'));

        // Launch add key fk_evaluatorid.
        $dbman->add_key($table, $key);

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2017080601, 'assessment');
    }

    if ($oldversion < 2017082300) {

        // Define field status to be added to assessment_attempt.
        $table = new xmldb_table('assessment_attempt');
        $field = new xmldb_field('status', XMLDB_TYPE_INTEGER, '4', null, null, null, null, 'versionid');

        // Conditionally launch add field status.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2017082300, 'assessment');
    }

    if ($oldversion < 2017090200) {

        // Define field grade to be added to assessment_attempt.
        $table = new xmldb_table('assessment_attempt');
        $field = new xmldb_field('grade', XMLDB_TYPE_NUMBER, '10, 5', null, null, null, null, 'versionid');

        // Conditionally launch add field grade.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2017090200, 'assessment');
    }

    if ($oldversion < 2017090400) {

        // Rename field grade on table assessment to gradepass.
        $table = new xmldb_table('assessment');
        $field = new xmldb_field('grade', XMLDB_TYPE_NUMBER, '10, 5', null, null, null, null, 'grademethod');

        // Launch rename field gradepass.
        $dbman->rename_field($table, $field, 'gradepass');

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2017090400, 'assessment');
    }

    if ($oldversion < 2017090500) {

        // Define field attempt to be added to assessment_attempt.
        $table = new xmldb_table('assessment_attempt');
        $field = new xmldb_field('attempt', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'versionid');

        // Conditionally launch add field attempt.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2017090500, 'assessment');
    }

    if ($oldversion < 2017090600) {

        // Define field reviewerid to be added to assessment_attempt.
        $table = new xmldb_table('assessment_attempt');
        $field = new xmldb_field('reviewerid', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'evaluatorid');

        // Conditionally launch add field reviewerid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2017090600, 'assessment');
    }

    if ($oldversion < 2017090601) {

        // Define field timereviewed to be added to assessment_attempt.
        $table = new xmldb_table('assessment_attempt');
        $field = new xmldb_field('timereviewed', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'timecompleted');

        // Conditionally launch add field timereviewed.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2017090601, 'assessment');
    }

    if ($oldversion < 2017090602) {

        // Define key fk_reviewerid (foreign) to be added to assessment_attempt.
        $table = new xmldb_table('assessment_attempt');
        $key = new xmldb_key('fk_reviewerid', XMLDB_KEY_FOREIGN, array('reviewerid'), 'user', array('id'));

        // Launch add key fk_reviewerid.
        $dbman->add_key($table, $key);

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2017090602, 'assessment');
    }

    if ($oldversion < 2017091300) {

        // Changing nullability of field timestarted on table assessment_attempt to null.
        $table = new xmldb_table('assessment_attempt');
        $field = new xmldb_field('timestarted', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'status');

        // Launch change of nullability for field timestarted.
        $dbman->change_field_notnull($table, $field);

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2017091300, 'assessment');
    }

    if ($oldversion < 2017091500) {

        // Define table assessment_override to be created.
        $table = new xmldb_table('assessment_override');

        // Adding fields to table assessment_override.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('assessmentid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('attempts', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table assessment_override.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('fk_assessmentid', XMLDB_KEY_FOREIGN, array('assessmentid'), 'assessment', array('id'));
        $table->add_key('userid', XMLDB_KEY_FOREIGN, array('userid'), 'user', array('id'));

        // Adding indexes to table assessment_override.
        $table->add_index('ui_assessmentuser', XMLDB_INDEX_UNIQUE, array('assessmentid', 'userid'));

        // Conditionally launch create table for assessment_override.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2017091500, 'assessment');
    }

    if ($oldversion < 2017101610) {

        // Define field singleevaluator to be added to assessment_version.
        $table = new xmldb_table('assessment_version');
        $field = new xmldb_field('singleevaluator', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, 0, 'operator');

        // Conditionally launch add field singleevaluator.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field singlereviewer to be added to assessment_version.
        $field = new xmldb_field('singlereviewer', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, 0, 'singleevaluator');

        // Conditionally launch add field singleevaluator.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field hidegrade to be added to assessment.
        $table = new xmldb_table('assessment');
        $field = new xmldb_field('hidegrade', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, 0, 'timecreated');

        // Conditionally launch add field hidegrade.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field needsrolesrefresh to be added to assessment.
        $field = new xmldb_field('needsrolesrefresh', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, 0, 'hidegrade');

        // Conditionally launch add field needsrolesrefresh.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define index idx_needsrolesrefresh (not unique) to be added to assessment.
        $index = new xmldb_index('idx_needsrolesrefresh', XMLDB_INDEX_NOTUNIQUE, array('needsrolesrefresh'));

        // Conditionally launch add index idx_needsrolesrefresh.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Define table assessment_attempt_assignments to be created.
        $table = new xmldb_table('assessment_attempt_assignments');

        // Adding fields to table assessment_attempt_assignments.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('attemptid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('role', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timenotified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table assessment_attempt_assignments.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('fk_attemptid', XMLDB_KEY_FOREIGN, array('attemptid'), 'assessment_attempt', array('id'));
        $table->add_key('fk_userid', XMLDB_KEY_FOREIGN, array('userid'), 'user', array('id'));

        // Adding indexes to table assessment_attempt_assignments.
        $table->add_index('idx_role', XMLDB_INDEX_NOTUNIQUE, array('role'));
        $table->add_index('idx_timecreated', XMLDB_INDEX_NOTUNIQUE, array('timecreated'));
        $table->add_index('idx_timemodified', XMLDB_INDEX_NOTUNIQUE, array('timemodified'));

        // Conditionally launch create table for assessment_attempt_assignments.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define key fk_reviewerid (foreign) to be dropped form assessment_attempt.
        $table = new xmldb_table('assessment_attempt');
        $key = new xmldb_key('fk_reviewerid', XMLDB_KEY_FOREIGN, array('reviewerid'), 'user', array('id'));

        // Launch drop key fk_reviewerid.
        $dbman->drop_key($table, $key);

        // Rename field reviewerid on table assessment_attempt to reviewedbyid.
        $field = new xmldb_field('reviewerid', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'evaluatorid');

        // Launch rename field reviewerid.
        $dbman->rename_field($table, $field, 'reviewedbyid');

        // Transfer existing assignments to the new table before dropping the fields.
        assessment_upgrade_transfer_assignments();

        // Define key fk_evaluatorid to be dropped from assessment_attempt.
        $table = new xmldb_table('assessment_attempt');
        $key = new xmldb_key('fk_evaluatorid', XMLDB_KEY_FOREIGN, array('evaluatorid'), 'user', array('id'));

        // Launch drop key evaluatorid_fk.
        $dbman->drop_key($table, $key);

        // Define field evaluatorid to be dropped from assessment_attempt.
        $field = new xmldb_field('evaluatorid');

        // Conditionally launch drop field evaluatorid.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // Define key fk_reviewedbyid (foreign) to be added to assessment_attempt.
        $key = new xmldb_key('fk_reviewedbyid', XMLDB_KEY_FOREIGN, array('reviewedbyid'), 'user', array('id'));

        // Launch add key fk_reviewedbyid.
        $dbman->add_key($table, $key);

        // Define field role to be added to assessment_ruleset.
        $defaultrole = 80; // \mod_assessment\helper\role::EVALUATOR
        $table = new xmldb_table('assessment_ruleset');
        $field = new xmldb_field('role', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, $defaultrole, 'versionid');

        // Conditionally launch add field role.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define index idx_role (not unique) to be added to assessment_ruleset.
        $index = new xmldb_index('idx_role', XMLDB_INDEX_NOTUNIQUE, array('role'));

        // Conditionally launch add index idx_role.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Define field reviewerperms to be added to assessment_question.
        $table = new xmldb_table('assessment_question');
        $field = new xmldb_field('reviewerperms', XMLDB_TYPE_INTEGER, '3', null, XMLDB_NOTNULL, null, 0, 'evaluatorperms');

        // Conditionally launch add field reviewerperms.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define key uk_attempt_stage_user (unique) to be dropped form assessment_stage_completion.
        $table = new xmldb_table('assessment_stage_completion');
        $key = new xmldb_key('uk_attempt_stage_user', XMLDB_KEY_UNIQUE, array('attemptid', 'stageid', 'userid'));

        // Launch drop key uk_attempt_stage_user.
        $dbman->drop_key($table, $key);

        // Define key uk_attempt_stage_user (unique) to be added to assessment_stage_completion.
        $key = new xmldb_key('uk_attempt_stage_user', XMLDB_KEY_UNIQUE, array('attemptid', 'stageid', 'role', 'userid'));

        // Launch add key uk_attempt_stage_user.
        $dbman->add_key($table, $key);

        // Ensure existing permissions in the database are converted to the new constant values.
        assessment_convert_old_question_permissions();

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2017101610, 'assessment');
    }

    if ($oldversion < 2020091000) {

        // Define table assessment_version_assignment to be created.
        $table = new xmldb_table('assessment_version_assignment');

        // Adding fields to table assessment_version_assignment.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('role', XMLDB_TYPE_INTEGER, '3', null, XMLDB_NOTNULL, null, null);
        $table->add_field('learnerid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('versionid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table assessment_version_assignment.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('fk_userid', XMLDB_KEY_FOREIGN, array('userid'), 'user', array('id'));
        $table->add_key('fk_learnerid', XMLDB_KEY_FOREIGN, array('learnerid'), 'user', array('id'));
        $table->add_key('fk_versionid', XMLDB_KEY_FOREIGN, array('versionid'), 'assessment_version', array('id'));

        // Conditionally launch create table for assessment_version_assignment.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2020091000, 'assessment');
    }

    if ($oldversion < 2020091100) {

        // Define table assessment_version_assignment_log to be created.
        $table = new xmldb_table('assessment_version_assignment_log');

        // Adding fields to table assessment_version_assignment_log.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('role', XMLDB_TYPE_INTEGER, '3', null, XMLDB_NOTNULL, null, null);
        $table->add_field('learnerid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('versionid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('importid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('row', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, null);
        $table->add_field('learneridraw', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('useridraw', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('skipped', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timeconfirmed', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('errorcode', XMLDB_TYPE_INTEGER, '3', null, null, null, null);

        // Adding keys to table assessment_version_assignment_log.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('fk_userid', XMLDB_KEY_FOREIGN, array('userid'), 'user', array('id'));
        $table->add_key('fk_learnerid', XMLDB_KEY_FOREIGN, array('learnerid'), 'user', array('id'));
        $table->add_key('fk_versionid', XMLDB_KEY_FOREIGN, array('versionid'), 'assessment_version', array('id'));

        // Conditionally launch create table for assessment_version_assignment_log.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2020091100, 'assessment');
    }

    if ($oldversion < 2020091402) {

        // Define field parentid to be added to assessment_version_question.
        $table = new xmldb_table('assessment_version_question');
        $field = new xmldb_field('parentid', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'timecreated');

        // Conditionally launch add field parentid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2020091402, 'assessment');
    }

    if ($oldversion < 2020092100) {

        // Define field locked to be added to assessment_version_stage.
        $table = new xmldb_table('assessment_version_stage');
        $field = new xmldb_field('locked', XMLDB_TYPE_INTEGER, '3', null, XMLDB_NOTNULL, null, '0', 'versionid');

        // Conditionally launch add field locked.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2020092100, 'assessment');
    }

    if ($oldversion < 2020092200) {

        // Define table assessment_attempt_completion to be created.
        $table = new xmldb_table('assessment_attempt_completion');

        // Adding fields to table assessment_attempt_completion.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('attemptid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('role', XMLDB_TYPE_INTEGER, '3', null, XMLDB_NOTNULL, null, null);
        $table->add_field('status', XMLDB_TYPE_INTEGER, '3', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table assessment_attempt_completion.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('fk_attemptid', XMLDB_KEY_FOREIGN, array('attemptid'), 'assessment_attempt', array('id'));
        $table->add_key('uk_attempt_role', XMLDB_KEY_UNIQUE, array('attemptid', 'role'));

        // Conditionally launch create table for assessment_attempt_completion.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2020092200, 'assessment');
    }

    if ($oldversion < 2020092201) {

        // Conditionally launch add field timedue.
        // Define field duedays to be added to assessment.
        $table = new xmldb_table('assessment');
        $field = new xmldb_field('duedays', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'timedue');

        // Conditionally launch add field duedays.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field duetype to be added to assessment.
        $table = new xmldb_table('assessment');
        $field = new xmldb_field('duetype', XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'duedays');

        // Conditionally launch add field duetype.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field duefieldid to be added to assessment.
        $table = new xmldb_table('assessment');
        $field = new xmldb_field('duefieldid', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'duetype');

        // Conditionally launch add field duefieldid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define table assessment_due to be created.
        $table = new xmldb_table('assessment_due');

        // Adding fields to table assessment_due.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('assessmentid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timedue', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table assessment_due.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('fk_assessmentid', XMLDB_KEY_FOREIGN, array('assessmentid'), 'assessment', array('id'));
        $table->add_key('fk_userid', XMLDB_KEY_FOREIGN, array('userid'), 'user', array('id'));

        // Adding indexes to table assessment_due.
        $table->add_index('ui_assessmentuser', XMLDB_INDEX_UNIQUE, array('assessmentid', 'userid'));

        // Conditionally launch create table for assessment_due.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2020092201, 'assessment');
    }

    if ($oldversion < 2020093000) {

        // Rename field row on table assessment_version_assignment_log to csvrow.
        $table = new xmldb_table('assessment_version_assignment_log');
        $field = new xmldb_field('row', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, null, 'importid');

        // Launch rename field csvrow.
        if ($dbman->field_exists('assessment_version_assignment_log', $field)) {
            $dbman->rename_field($table, $field, 'csvrow');
        }

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2020093000, 'assessment');
    }

    if ($oldversion < 2020093001) {

        // Changing precision of field csvrow on table assessment_version_assignment_log to (10).
        $table = new xmldb_table('assessment_version_assignment_log');
        $field = new xmldb_field('csvrow', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'importid');

        // Launch change of precision for field csvrow.
        $dbman->change_field_precision($table, $field);

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2020093001, 'assessment');
    }

    if ($oldversion < 2020102801) {

        // Define field timearchived to be added to assessment_attempt.
        $table = new xmldb_table('assessment_attempt');
        $field = new xmldb_field('timearchived', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'timereviewed');

        // Conditionally launch add field timearchived.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define index ix_attempt (not unique) to be added to assessment_attempt.
        $index = new xmldb_index('ix_attempt', XMLDB_INDEX_NOTUNIQUE, array('attempt'));

        // Conditionally launch add index ix_attempt.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Define index ix_grade (not unique) to be added to assessment_attempt.
        $index = new xmldb_index('ix_grade', XMLDB_INDEX_NOTUNIQUE, array('grade'));

        // Conditionally launch add index ix_grade.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Define index ix_timestarted (not unique) to be added to assessment_attempt.
        $index = new xmldb_index('ix_timestarted', XMLDB_INDEX_NOTUNIQUE, array('timestarted'));

        // Conditionally launch add index ix_timestarted.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Define index ix_timecompleted (not unique) to be added to assessment_attempt.
        $index = new xmldb_index('ix_timecompleted', XMLDB_INDEX_NOTUNIQUE, array('timecompleted'));

        // Conditionally launch add index ix_timecompleted.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Define index ix_timereviewed (not unique) to be added to assessment_attempt.
        $index = new xmldb_index('ix_timereviewed', XMLDB_INDEX_NOTUNIQUE, array('timereviewed'));

        // Conditionally launch add index ix_timereviewed.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Define index ix_timearchived (not unique) to be added to assessment_attempt.
        $index = new xmldb_index('ix_timearchived', XMLDB_INDEX_NOTUNIQUE, array('timearchived'));

        // Conditionally launch add index ix_timearchived.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2020102801, 'assessment');
    }

    if ($oldversion < 2020102802) {
        $fixFilesSql = <<<SQL
UPDATE {files}
SET 
  itemid = aa.id,
  userid = aa.userid
FROM {assessment_answer} aa
LEFT JOIN {files} f
  ON f.itemid::text = replace(aa.value, '"', '')
    AND f.filearea = 'answer'
    AND f.component = 'mod_assessment' 
WHERE aa.questionid IN (SELECT id FROM {assessment_question} WHERE type = 'file') 
  AND aa.value <> 'null'
  AND f.userid IS NULL
  AND {files}.id = f.id
SQL;

        $DB->execute($fixFilesSql);

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2020102802, 'assessment');
    }

    if ($oldversion < 2020102803) {

        // Define field timedue to be added to assessment.
        $table = new xmldb_table('assessment');
        $field = new xmldb_field('timedue', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'timemodified');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Assessment savepoint reached.
        upgrade_mod_savepoint(true, 2020102803, 'assessment');
    }

    // Rebuild embedded reports with MT content option set
    if ($oldversion < 2020102804) {
        $embedded_sources = [
            'assessment_dashboard',
            'assessment_dashboardarchived',
            'assessment_dashboardcompleted',
            'assessment_dashboardcompleted_reviewed',
            'assessment_dashboardfailed',
            'assessment_version_assignment_direct'
        ];
        [$inSql, $inParams] = $DB->sql_in($embedded_sources);

        $reports = $DB->get_records_sql('SELECT * FROM {report_builder} WHERE embedded = 1 AND shortname ' . $inSql, $inParams);

        foreach ($reports as $report) {
            // The following is a near duplicate of the core function _reportbuilder_delete_report_,
            // except we wrap the _report_builder_saved_user_default_ delete in a try/catch,
            // and then just carry on if it fails. This table was added in T13, so this stops <T13 upgrades from failing.

            // Delete report source cache.
            reportbuilder_purge_cache($report->id, true);

            // Delete graph related data.
            $DB->delete_records('report_builder_graph', ['reportid' => $report->id]);
            // Delete scheduling related data.
            $select = "scheduleid IN (SELECT s.id FROM {report_builder_schedule} s WHERE s.reportid = ?)";
            $DB->delete_records_select('report_builder_schedule_email_audience', $select, [$report->id]);
            $DB->delete_records_select('report_builder_schedule_email_systemuser', $select, [$report->id]);
            $DB->delete_records_select('report_builder_schedule_email_external', $select, [$report->id]);
            $DB->delete_records('report_builder_schedule', ['reportid' => $report->id]);
            // Delete search related data.
            $DB->delete_records('report_builder_search_cols', ['reportid' => $report->id]);
            // Delete any columns.
            $DB->delete_records('report_builder_columns', ['reportid' => $report->id]);

            // Delete any filters.
            $DB->delete_records('report_builder_filters', ['reportid' => $report->id]);
            // Delete any content and access settings.
            $DB->delete_records('report_builder_settings', ['reportid' => $report->id]);
            // Delete any saved searches.
            $DB->delete_records('report_builder_saved', ['reportid' => $report->id]);

            // Note: this table only exists in T13+, so make sure we ignore any exceptions here.
            try {
                $DB->delete_records('report_builder_saved_user_default', ['reportid' => $report->id]);
            } catch (dml_exception $e) {
                // Ignore.
            }

            // Delete the report.
            $DB->delete_records('report_builder', ['id' => $report->id]);
        }

        upgrade_mod_savepoint(true, 2020102804, 'assessment');
    }


    return true;
}
