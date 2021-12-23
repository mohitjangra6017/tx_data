<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

use mod_assessment\entity\assessment_version_assignment;
use mod_assessment\entity\assessment_version_assignment_log;
use mod_assessment\factory\assessment_version_assignment_factory;
use mod_assessment\factory\assessment_version_assignment_log_factory;
use mod_assessment\model\import_error;
use mod_assessment\model\role;
use mod_assessment\model\user_identifier;
use mod_assessment\model\version;
use mod_assessment\processor\version_assignments_import_processor;

/**
 * @group kineo
 * @group tke
 * @group mod_assessment
 */
class mod_assessment_version_assignment_processor_test extends advanced_testcase {

    /** @var stdClass */
    protected $assessment;

    /** @var stdClass */
    protected $course;

    /** @var array|stdClass */
    protected $learner;

    /** @var assessment_version_assignment_log */
    protected $log;

    /** @var array|stdClass */
    protected $user;

    /** @var version */
    protected $version;

    public function setUp(): void { // KINEO US
        $this->resetAfterTest();

        /** @var mod_assessment_generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_assessment');
        $this->course = $this->getDataGenerator()->create_course();
        $this->learner = $this->getDataGenerator()->create_user();
        $this->user = $this->getDataGenerator()->create_user();
        $this->assessment = $this->getDataGenerator()->create_module('assessment', ['course' => $this->course]);
        $this->version = $generator->create_version($this->assessment->id);

        $this->log = new assessment_version_assignment_log(
            assessment_version_assignment_log::get_next_importid(),
            1,
            $this->learner->id,
            $this->user->username,
            true,
            time(),
            new role(role::EVALUATOR),
            $this->version->get_id()
        );
        $this->log->save();
    }

    public function tearDown(): void { // KINEO US
        $this->assessment = null;
        $this->course = null;
        $this->learner = null;
        $this->log = null;
        $this->user = null;
        $this->version = null;
    }

    public function test_learner_not_found() {
        // Test skipped due to SQl error where fix is unknown
        $this->markTestSkipped();
        $processor = new version_assignments_import_processor(
            $this->log->get_importid(),
            new user_identifier(user_identifier::USERNAME),
            new user_identifier(user_identifier::ID),
            false,
            false
        );
        $processor->preprocess();

        /** @var assessment_version_assignment_log $log */
        $log = assessment_version_assignment_log_factory::fetch(['id' => $this->log->get_id()]);
        $this->assertTrue($log->is_skipped());
        $this->assertSame(import_error::LEARNER_NOT_FOUND, $log->get_errorcode()->value());
    }

    public function test_user_not_found() {
        // Test skipped due to SQl error where fix is unknown
        $this->markTestSkipped();
        $processor = new version_assignments_import_processor(
            $this->log->get_importid(),
            new user_identifier(user_identifier::ID),
            new user_identifier(user_identifier::ID),
            false,
            false
        );
        $processor->preprocess();

        /** @var assessment_version_assignment_log $log */
        $log = assessment_version_assignment_log_factory::fetch(['id' => $this->log->get_id()]);
        $this->assertTrue($log->is_skipped());
        $this->assertSame(import_error::USER_NOT_FOUND, $log->get_errorcode()->value());
    }

    public function test_user_not_enrolled() {
        $processor = new version_assignments_import_processor(
            $this->log->get_importid(),
            new user_identifier(user_identifier::ID),
            new user_identifier(user_identifier::USERNAME),
            false,
            false
        );
        $processor->preprocess();

        /** @var assessment_version_assignment_log $log */
        $log = assessment_version_assignment_log_factory::fetch(['id' => $this->log->get_id()]);
        $this->assertTrue($log->is_skipped());
        $this->assertSame(import_error::LEARNER_NOT_ENROLLED, $log->get_errorcode()->value());
    }

    public function test_assignment_exists_error() {
        $this->getDataGenerator()->enrol_user($this->learner->id, $this->course->id);
        $existing = new assessment_version_assignment($this->user->id, $this->log->get_role(), $this->learner->id, $this->version->get_id());
        $existing->save();

        $processor = new version_assignments_import_processor(
            $this->log->get_importid(),
            new user_identifier(user_identifier::ID),
            new user_identifier(user_identifier::USERNAME),
            false,
            false
        );
        $processor->preprocess();

        /** @var assessment_version_assignment_log $log */
        $log = assessment_version_assignment_log_factory::fetch(['id' => $this->log->get_id()]);
        $this->assertTrue($log->is_skipped());
        $this->assertSame(import_error::ASSIGNMENT_EXISTS, $log->get_errorcode()->value());
    }

    public function test_valid() {
        $this->getDataGenerator()->enrol_user($this->learner->id, $this->course->id);

        $processor = new version_assignments_import_processor(
            $this->log->get_importid(),
            new user_identifier(user_identifier::ID),
            new user_identifier(user_identifier::USERNAME),
            false,
            false
        );
        $processor->preprocess();

        /** @var assessment_version_assignment_log $log */
        $log = assessment_version_assignment_log_factory::fetch(['id' => $this->log->get_id()]);
        $this->assertFalse($log->is_skipped());
        $this->assertSame(null, $log->get_errorcode()->value());
        $this->assertEquals($this->learner->id, $log->get_learnerid());
        $this->assertEquals($this->user->id, $log->get_userid());

        $processor->execute();
    }

    public function test_enrollment() {
        $pluginman = core_plugin_manager::instance();
        $enrolinfo = $pluginman->get_plugin_info('enrol_assessment');
        if (!$enrolinfo) {
            //$this->markTestSkipped('enrol_assessment plug-in not installed');
        }

        $processor = new version_assignments_import_processor(
            $this->log->get_importid(),
            new user_identifier(user_identifier::ID),
            new user_identifier(user_identifier::USERNAME),
            false,
            true
        );
        $processor->preprocess();

        /** @var assessment_version_assignment_log $log */
        $log = assessment_version_assignment_log_factory::fetch(['id' => $this->log->get_id()]);
        $this->assertFalse(is_enrolled(context_course::instance($this->course->id), $this->learner));
        $this->assertFalse($log->is_skipped());
        $this->assertSame(null, $log->get_errorcode()->value());

        $processor->execute();

        $this->assertTrue(is_enrolled(context_course::instance($this->course->id), $this->learner));
    }

    public function test_process_add() {
        $this->getDataGenerator()->enrol_user($this->learner->id, $this->course->id);
        $existing = new assessment_version_assignment(3, new role(role::EVALUATOR), 4, $this->version->get_id());
        $existing->save();

        $processor = new version_assignments_import_processor(
            $this->log->get_importid(),
            new user_identifier(user_identifier::ID),
            new user_identifier(user_identifier::USERNAME),
            false,
            false
        );
        $processor->preprocess();
        $processor->execute();

        /** @var assessment_version_assignment_log $log */
        $log = assessment_version_assignment_log_factory::fetch(['id' => $this->log->get_id()]);
        $this->assertTrue($log->is_confirmed());

        /** @var assessment_version_assignment[] $assignments */
        $assignments = assessment_version_assignment_factory::fetch_all();
        $this->assertCount(2, $assignments);

        $assignment = reset($assignments);
        $this->assertSame($assignment->get_id(), $existing->get_id());

        $assignment = next($assignments);
        $this->assertSame($log->get_learnerid(), $assignment->get_learnerid());
        $this->assertSame($log->get_userid(), $assignment->get_userid());
        $this->assertEquals($log->get_role(), $assignment->get_role());
    }

    public function test_process_replace() {
        $this->getDataGenerator()->enrol_user($this->learner->id, $this->course->id);

        $existing = new assessment_version_assignment(3, new role(role::EVALUATOR), 4, $this->version->get_id());
        $existing->save();

        $duplicate = new assessment_version_assignment($this->user->id, $this->log->get_role(), $this->learner->id, $this->version->get_id());
        $duplicate->save();

        $processor = new version_assignments_import_processor(
            $this->log->get_importid(),
            new user_identifier(user_identifier::ID),
            new user_identifier(user_identifier::USERNAME),
            true,
            false
        );
        $processor->preprocess();
        $processor->execute();

        /** @var assessment_version_assignment_log $log */
        $log = assessment_version_assignment_log_factory::fetch(['id' => $this->log->get_id()]);
        $this->assertTrue($log->is_confirmed());

        /** @var assessment_version_assignment[] $assignments */
        $assignments = assessment_version_assignment_factory::fetch_all();
        $this->assertCount(1, $assignments);

        $assignment = reset($assignments);
        $this->assertSame($log->get_learnerid(), $assignment->get_learnerid());
        $this->assertSame($log->get_userid(), $assignment->get_userid());
        $this->assertEquals($log->get_role(), $assignment->get_role());
    }

}
