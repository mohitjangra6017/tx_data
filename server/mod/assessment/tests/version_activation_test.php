<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

use local_coursegroup\model\operator;
use mod_assessment\model\role;
use mod_assessment\model\rule;
use mod_assessment\model\ruleset;
use mod_assessment\model\stage;
use mod_assessment\model\version;
use mod_assessment\model\version_stage;
use mod_assessment\rule\manager;

/**
 * @group kineo
 * @group tke
 * @group mod_assessment
 */
class version_activation_test extends advanced_testcase {

    /** @var mod_assessment_generator */
    protected $generator;

    /** @var stage */
    protected $stage;

    /** @var version */
    protected $version;

    /** @var version_stage */
    protected $versionstage;

    public function setUp(): void {
        $this->resetAfterTest();

        $course = $this->getDataGenerator()->create_course();
        $assessment = $this->getDataGenerator()->create_module('assessment', ['course' => $course->id]);
        $this->generator = $this->getDataGenerator()->get_plugin_generator('mod_assessment');
        $this->stage = $this->generator->create_stage();
        $this->version = $this->generator->create_version($assessment->id);
        $this->versionstage = $this->generator->assign_stage_to_version($this->stage, $this->version);
    }

    public function tearDown(): void {
        $this->generator = null;
        $this->stage = null;
        $this->version = null;
        $this->versionstage = null;
    }

    // Failed activation from having no evaluators.
    public function test_missing_evaluator() {
        $this->expectExceptionMessage(get_string('error_noevaluatorrules', 'assessment'));
        $this->version->activate();
    }

    // Successful activation while having no evaluator rules.
    public function test_missing_evaluator_rule() {
        $this->generator->create_version_assignment($this->version->get_id());
        $this->version->activate();
    }

    // Successful activation while having no evaluator assignments.
    public function test_missing_evaluator_assignment() {
        $ruleset = ruleset::make([
            'role' => role::EVALUATOR,
            'versionid' => $this->version->get_id(),
            'operator' => rule::OP_AND
        ]);
        $ruleset->save();

        $rule = new manager();
        $rule->set_rulesetid($ruleset->id)
            ->set_operator(rule::OP_IN_EQUAL)
            ->encode_value(manager::MANAGER_DIRECT)
            ->save();

        $this->version->activate();
    }

    // Failed activation from having no reviewers.
    public function test_missing_reviewer() {
        $this->expectExceptionMessage(get_string('error_noreviewerrules', 'assessment'));
        $this->generator->create_version_assignment($this->version->get_id());
        $question = $this->generator->create_question(['reviewerperms' => 1]);
        $this->generator->assign_question_to_version_stage($question, $this->versionstage);

        $this->version->activate();
    }

    // Successful activation while having no reviewer rules.
    public function test_missing_reviewer_rule() {
        $this->generator->create_version_assignment($this->version->get_id());
        $this->generator->create_version_assignment($this->version->get_id(), ['role' => role::REVIEWER]);
        $question = $this->generator->create_question(['reviewerperms' => 1]);
        $this->generator->assign_question_to_version_stage($question, $this->versionstage);

        $this->version->activate();
    }

    // Successful activation while having no reviewer assignments.
    public function test_missing_reviewer_assignments() {
        $this->generator->create_version_assignment($this->version->get_id());
        $question = $this->generator->create_question(['reviewerperms' => 1]);
        $this->generator->assign_question_to_version_stage($question, $this->versionstage);

        $ruleset = ruleset::make([
            'role' => role::REVIEWER,
            'versionid' => $this->version->get_id(),
            'operator' => rule::OP_AND
        ]);
        $ruleset->save();

        $rule = new manager();
        $rule->set_rulesetid($ruleset->id)
            ->set_operator(rule::OP_IN_EQUAL)
            ->encode_value(manager::MANAGER_DIRECT)
            ->save();

        $this->version->activate();
    }
}
