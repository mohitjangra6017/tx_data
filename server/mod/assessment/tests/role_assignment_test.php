<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

use mod_assessment\entity\assessment_version_assignment;
use mod_assessment\model\attempt;
use mod_assessment\model\role;
use mod_assessment\processor\role_user_processor;

/**
 * @group kineo
 * @group tke
 * @group mod_assessment
 */
class role_assignment_test extends advanced_testcase {

    /** @var mod_assessment_generator $generator */
    protected $generator;

    protected $assessment;
    protected $evaluator;
    protected $learner;
    protected $version;

    public function setUp(): void {
        $this->resetAfterTest();

        $this->generator = $this->getDataGenerator()->get_plugin_generator('mod_assessment');
        $course = $this->getDataGenerator()->create_course();
        $this->assessment = $this->getDataGenerator()->create_module('assessment', ['course' => $course]);
        $this->evaluator = $this->getDataGenerator()->create_user();
        $this->learner = $this->getDataGenerator()->create_user();
        $this->version = $this->generator->create_version($this->assessment->id);
    }

    public function tearDown(): void {
        $this->assessment = null;
        $this->generator = null;
        $this->evaluator = null;
        $this->learner = null;
        $this->version = null;
    }

    public function test_direct_assignment() {
        $assignment = new assessment_version_assignment($this->evaluator->id, new role(role::EVALUATOR), $this->learner->id, $this->version->get_id());
        $assignment->save();

        $processor = new role_user_processor($this->version, new role(role::EVALUATOR));
        $users = $processor->get_valid_role_users($this->createMock(attempt::class));

        $this->assertCount(1, $users);
        $this->assertEquals($this->evaluator->id, reset($users)->id);
    }

    public function test_direct_assignment_ignore_other_role() {
        $assignment = new assessment_version_assignment($this->evaluator->id, new role(role::REVIEWER), $this->learner->id, $this->version->get_id());
        $assignment->save();

        $processor = new role_user_processor($this->version, new role(role::EVALUATOR));
        $users = $processor->get_valid_role_users($this->createMock(attempt::class));

        $this->assertCount(0, $users);
    }

    public function test_direct_assignment_ignore_other_version() {
        $newversion = $this->generator->create_version($this->assessment->id, ['version' => 2]);

        $assignment = new assessment_version_assignment($this->evaluator->id, new role(role::EVALUATOR), $this->learner->id, $newversion->get_id());
        $assignment->save();

        $processor = new role_user_processor($this->version, new role(role::EVALUATOR));
        $users = $processor->get_valid_role_users($this->createMock(attempt::class));

        $this->assertCount(0, $users);
    }
}
