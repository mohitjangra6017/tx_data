<?php
/**
 * Created by PhpStorm.
 * User: Gavin McMaster
 * Date: 08/06/20
 * Time: 12:22
 *
 * Define all the backup steps that will be used by the backup_scorm_activity_task
 * TODO - Add user info data
 * @package   mod_assessment
 *
 */
defined('MOODLE_INTERNAL') || die();

class backup_assessment_activity_structure_step extends backup_activity_structure_step
{
    /**
     * Define the structure to be processed by this backup step.
     *
     * @return backup_nested_element
     */
    protected function define_structure()
    {
        // Define each element that will form the basis of the restore XML
        $assessment = new backup_nested_element('assessment', array('id'), array(
            'name', 'intro', 'introformat',  'attempts', 'extraattempts', 'grademethod', 'gradepass', 'statusrequired',
            'timecreated', 'timemodified', 'hidegrade', 'needsrolesrefresh', 'timedue', 'duedays', 'duetype', 'duefieldid',
            'timearchived'
        ));

        $questions = new backup_nested_element('questions');
        $question = new backup_nested_element('question', array('id'), array(
            'question', 'type', 'weight', 'learnerperms', 'evaluatorperms', 'reviewerperms', 'config',
            'timecreated', 'timemodified'
        ));

        $stages = new backup_nested_element('stages');
        $stage = new backup_nested_element('stage', array('id'), array(
            'name', 'newpage', 'timecreated', 'timemodified'
        ));

        $versions = new backup_nested_element('versions');
        $version = new backup_nested_element('version', array('id'), array(
            'operator', 'singleevaluator', 'singlereviewer', 'version', 'timeopened', 'timeclosed', 'timecreated'
        ));

        $versionStages = new backup_nested_element('versionstages');
        $versionStage =  new backup_nested_element('versionstage', array('id'), array(
            'stageid', 'locked', 'sortorder', 'versionid', 'timecreated', 'timemodified'
        ));

        $versionQuestions = new backup_nested_element('versionquestions');
        $versionQuestion = new backup_nested_element('versionquestion', array('id'), array(
            'stageid', 'questionid', 'parentid', 'sortorder', 'versionid', 'timecreated', 'timemodified'
        ));

        $rulesets = new backup_nested_element('rulesets');
        $ruleset = new backup_nested_element('ruleset', array('id'), array(
            'versionid', 'role', 'operator'
        ));

        $rules = new backup_nested_element('rules');
        $rule = new backup_nested_element('rule', array('id'), array(
            'rulesetid', 'type', 'operator', 'value'
        ));

        // Build the tree - order is important for restore item mapping ids
        // Questions & stages must be added to XML structure before version stages/questions
        $assessment->add_child($questions);
        $questions->add_child($question);

        $assessment->add_child($stages);
        $stages->add_child($stage);

        $assessment->add_child($versions);
        $versions->add_child($version);
        $version->add_child($versionStages);
        $versionStages->add_child($versionStage);

        $version->add_child($versionQuestions);
        $versionQuestions->add_child($versionQuestion);
        $version->add_child($rulesets);
        $rulesets->add_child($ruleset);
        $ruleset->add_child($rules);
        $rules->add_child($rule);

        // Define sources
        $assessment->set_source_table('assessment', array('id' => backup::VAR_ACTIVITYID));
        $version->set_source_table('assessment_version', array('assessmentid' => backup::VAR_PARENTID));

        $stage->set_source_sql("SELECT DISTINCT(stage.*)
            FROM {assessment_stage} stage
            JOIN {assessment_version_stage} avs ON avs.stageid = stage.id
            JOIN {assessment_version} av ON av.id = avs.versionid
            JOIN {assessment} assess ON assess.id = av.assessmentid
            WHERE av.assessmentid = :assessmentid", array('assessmentid' => backup::VAR_ACTIVITYID));

        $versionStage->set_source_table('assessment_version_stage', array('versionid' => backup::VAR_PARENTID));

        $question->set_source_sql("SELECT DISTINCT(question.*)
            FROM {assessment_question} question
            JOIN {assessment_version_question} avq ON avq.questionid = question.id
            JOIN {assessment_version} av ON av.id = avq.versionid
            JOIN {assessment} assess ON assess.id = av.assessmentid
            WHERE av.assessmentid = :assessmentid", array('assessmentid' => backup::VAR_ACTIVITYID));

        $versionQuestion->set_source_table('assessment_version_question', array('versionid' => backup::VAR_PARENTID));

        $ruleset->set_source_table('assessment_ruleset', array('versionid' => backup::VAR_PARENTID));
        $rule->set_source_table('assessment_rule',array('rulesetid' => backup::VAR_PARENTID));

        // Annotate FK ids
        $versionStage->annotate_ids('assessment_stage', 'stageid');
        $versionQuestion->annotate_ids('assessment_question', 'questionid');
        $versionQuestion->annotate_ids('assessment_stage', 'stageid');

        return $this->prepare_activity_structure($assessment);
    }
}
