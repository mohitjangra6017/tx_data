<?php
/**
 *Created by PhpStorm.
 * User: Gavin McMaster
 * Date: 09/06/20
 * Time: 16:10
 *
 * Define the complete assessment structure for restore
 * TODO - Add user info data
 * @package   mod_assessment
 *
 */
defined('MOODLE_INTERNAL') || die();

class restore_assessment_activity_structure_step extends restore_activity_structure_step
{
    /**
     * Define the structure of the restore workflow.
     *
     * @return restore_path_element $structure
     */
    protected function define_structure() {

        $paths = array();

        // Define each element separated.
        $paths[] = new restore_path_element('assessment', '/activity/assessment');

        $paths[] = new restore_path_element('assessment_question',
            '/activity/assessment/questions/question');

        $paths[] = new restore_path_element('assessment_stage',
            '/activity/assessment/stages/stage');

        $paths[] = new restore_path_element('assessment_version', '/activity/assessment/versions/version');

        $paths[] = new restore_path_element('assessment_version_stage',
            '/activity/assessment/versions/version/versionstages/versionstage');

        $paths[] = new restore_path_element('assessment_version_question',
            '/activity/assessment/versions/version/versionquestions/versionquestion');

        $paths[] = new restore_path_element('assessment_ruleset',
            '/activity/assessment/versions/version/rulesets/ruleset');

        $paths[] = new restore_path_element('assessment_rule',
            '/activity/assessment/versions/version/rulesets/ruleset/rules/rule');

        return $this->prepare_activity_structure($paths);
    }

    /**
     * @param $data
     * @throws base_step_exception
     * @throws dml_exception
     */
    protected function process_assessment($data)
    {
        global $DB;

        $data = (object)$data;
        $data->course = $this->get_courseid();
        $data->timecreated = $this->apply_date_offset($data->timecreated);
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        $data->timecreated = $this->apply_date_offset($data->timecreated);
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        $newItemId = $DB->insert_record('assessment', $data);
        $this->apply_activity_instance($newItemId);
    }

    /**
     * @param $data
     * @throws dml_exception
     * @throws restore_step_exception
     */
    protected function process_assessment_question($data)
    {
        global $DB;

        $data = (object)$data;
        $oldId = $data->id;
        $data->timecreated = $this->apply_date_offset($data->timecreated);
        if (isset($data->timemodified)) {
            $data->timemodified = $this->apply_date_offset($data->timemodified);
        }

        $newItemId = $DB->insert_record('assessment_question', $data);
        $this->set_mapping('assessment_question', $oldId, $newItemId);
    }

    /**
     * @param $data
     * @throws dml_exception
     * @throws restore_step_exception
     */
    protected function process_assessment_stage($data)
    {
        global $DB;

        $data = (object)$data;
        $oldId = $data->id;
        $data->timecreated = $this->apply_date_offset($data->timecreated);
        if (isset($data->timemodified)) {
            $data->timemodified = $this->apply_date_offset($data->timemodified);
        }

        $newItemId = $DB->insert_record('assessment_stage', $data);
        $this->set_mapping('assessment_stage', $oldId, $newItemId);
    }

    /**
     * @param $data
     * @throws dml_exception
     * @throws restore_step_exception
     */
    protected function process_assessment_version($data)
    {
        global $DB;

        $data = (object)$data;
        $oldId = $data->id;
        $data->assessmentid = $this->get_new_parentid('assessment');
        if (isset($data->timeopened)) {
            $data->timeopened = $this->apply_date_offset($data->timeopened);
        }
        if (isset($data->timeclosed)) {
            $data->timeclosed = $this->apply_date_offset($data->timeclosed);
        }
        $data->timecreated = $this->apply_date_offset($data->timecreated);

        $newItemId = $DB->insert_record('assessment_version', $data);
        $this->set_mapping('assessment_version', $oldId, $newItemId);
    }

    /**
     * @param $data
     * @throws dml_exception
     * @throws restore_step_exception
     */
    protected function process_assessment_version_stage($data)
    {
        global $DB;

        $data = (object)$data;
        $oldId = $data->id;

        // Important that all stages added already - XML processor order
        $data->stageid = $this->get_mappingid('assessment_stage', $data->stageid);
        $data->versionid = $this->get_new_parentid('assessment_version');

        $data->timecreated = $this->apply_date_offset($data->timecreated);
        if (isset($data->timemodified)) {
            $data->timemodified = $this->apply_date_offset($data->timemodified);
        }

        $newItemId = $DB->insert_record('assessment_version_stage', $data);
        $this->set_mapping('assessment_version_stage', $oldId, $newItemId);
    }

    /**
     * @param $data
     * @throws dml_exception
     * @throws restore_step_exception
     */
    protected function process_assessment_version_question($data)
    {
        global $DB;

        $data = (object)$data;
        $oldId = $data->id;

        // Important that all stages added already - XML processor order
        $data->stageid = $this->get_mappingid('assessment_stage', $data->stageid);
        // Important that all questions added already - XML processor order
        $data->questionid = $this->get_mappingid('assessment_question', $data->questionid);
        $data->versionid = $this->get_new_parentid('assessment_version');

        $data->timecreated = $this->apply_date_offset($data->timecreated);
        if (isset($data->timemodified)) {
            $data->timemodified = $this->apply_date_offset($data->timemodified);
        }

        $newItemId = $DB->insert_record('assessment_version_question', $data);
        $this->set_mapping('assessment_version_question', $oldId, $newItemId);
    }

    /**
     * @param $data
     * @throws dml_exception
     * @throws restore_step_exception
     */
    protected function process_assessment_ruleset($data)
    {
        global $DB;
        $data = (object)$data;
        $oldId = $data->id;
        $data->versionid = $this->get_new_parentid('assessment_version');

        $newItemId = $DB->insert_record('assessment_ruleset', $data);
        $this->set_mapping('assessment_ruleset', $oldId, $newItemId);
    }

    /**
     * @param $data
     * @throws dml_exception
     * @throws restore_step_exception
     */
    protected function process_assessment_rule($data)
    {
        global $DB;
        $data = (object)$data;
        $oldId = $data->id;
        $data->rulesetid = $this->get_new_parentid('assessment_ruleset');

        $newItemId = $DB->insert_record('assessment_rule', $data);
        $this->set_mapping('assessment_rule', $oldId, $newItemId);
    }
}
