<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2019 Kineo
 */

use mod_assessment\model\attempt;
use mod_assessment\rule\manager;
use totara_job\job_assignment;

/**
 * @group kineo
 * @group tke
 * @group mod_assessment
 */
class mod_assessment_rule_manager_test extends advanced_testcase {

    public function test_directmanager_sql() {
        // Setup users.
        $indirect = $this->getDataGenerator()->create_user();
        $indirectja = job_assignment::create_default($indirect->id);

        $manager = $this->getDataGenerator()->create_user();
        $managerja = job_assignment::create_default($manager->id, ['managerjaid' => $indirectja->id]);

        $learner = $this->getDataGenerator()->create_user();
        job_assignment::create_default($learner->id, ['managerjaid' => $managerja->id]);

        // Create attempt mock.
        $attempt = $this->createMock(attempt::class);
        $attempt->method('get_userid')->willReturn((int) $learner->id);

        // Create rule.
        $rule = new manager();
        $rule->encode_value($rule::MANAGER_DIRECT);
        $rule->operator = manager::OP_IN_EQUAL;

        // Test the rule!
        list($sql, $params) = $rule->get_sql($attempt);

        global $DB;
        $directmanagers = $DB->get_records_sql("SELECT id FROM {user} AS auser WHERE {$sql}", $params);

        $this->assertCount(1, $directmanagers);
        $this->assertEquals($manager->id, current($directmanagers)->id);
    }

    public function test_indirectmanager_sql() {
        // Setup users.
        $indirect = $this->getDataGenerator()->create_user();
        $indirectja = job_assignment::create_default($indirect->id);

        $manager = $this->getDataGenerator()->create_user();
        $managerja = job_assignment::create_default($manager->id, ['managerjaid' => $indirectja->id]);

        $learner = $this->getDataGenerator()->create_user();
        job_assignment::create_default($learner->id, ['managerjaid' => $managerja->id]);

        // Create attempt mock.
        $attempt = $this->createMock(attempt::class);
        $attempt->method('get_userid')->willReturn((int) $learner->id);

        // Create rule.
        $rule = new manager();
        $rule->encode_value($rule::MANAGER_INDIRECT);
        $rule->operator = manager::OP_IN_EQUAL;

        // Test the rule!
        list($sql, $params) = $rule->get_sql($attempt);

        global $DB;
        $indirectmanagers = $DB->get_records_sql("SELECT id FROM {user} AS auser WHERE {$sql}", $params);

        $this->assertCount(1, $indirectmanagers);
        $this->assertEquals($indirect->id, current($indirectmanagers)->id);
    }

}
