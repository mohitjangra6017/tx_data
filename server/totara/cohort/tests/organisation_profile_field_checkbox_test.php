<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2015 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Sam Hemelryk <sam.hemelryk@totaralms.com>
 * @package totara_cohort
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/totara/cohort/lib.php');
require_once($CFG->dirroot . '/user/profile/lib.php');
require_once($CFG->dirroot . '/user/profile/definelib.php');
require_once($CFG->dirroot . '/user/profile/field/checkbox/define.class.php');

/**
 * Test audience rules.
 *
 * NOTE: the numbers are coming straight from Totara 2.7.2 which is the baseline for us,
 *       any changes in results need to be documented here.
 */
class totara_cohort_organisation_profile_field_checkbox_testcase extends advanced_testcase {
    protected $cohort_generator = null;
    protected $hierarchy_generator = null;
    protected $cohort = null;
    protected $ruleset = 0;

    private $org  = 0;
    private $typeid = 0;
    private $input  = 0;
    private $fieldid = 0;

    const TEST_USER_COUNT_MEMBERS = 22; //53;

    protected function tearDown(): void {
        $this->cohort_generator = null;
        $this->hierarchy_generator = null;
        $this->cohort = null;
        $this->ruleset = null;
        $this->org = null;
        $this->typeid = null;
        $this->input = null;
        $this->fieldid = null;
        parent::tearDown();
    }

    protected function setUp(): void {
        global $DB;

        parent::setup();
        $this->setAdminUser();

        $generator = $this->getDataGenerator();

        // Set totara_cohort generator.
        $this->cohort_generator = \totara_cohort\testing\generator::instance();

        // Set \totara_hierarchy\testing\generator.
        $this->hierarchy_generator = \totara_hierarchy\testing\generator::instance();

        // Create organisation type.
        $this->typeid = $this->hierarchy_generator->create_org_type();
        $this->assertTrue($DB->record_exists('org_type', array('id' => $this->typeid)));
        $typeidnumber = $DB->get_field('org_type', 'idnumber', array('id' => $this->typeid));

        // Create checkbox for organisation type.
        $defaultdata = 1; // Checked.
        $shortname   = 'checkbox'.$this->typeid;
        $this->input = 'customfield_'.$shortname;
        $data = array('hierarchy' => 'organisation', 'typeidnumber' => $typeidnumber, 'value' => $defaultdata);
        $this->hierarchy_generator->create_hierarchy_type_checkbox($data);
        unset($data);
        $this->assertTrue($DB->record_exists('org_type_info_field', array('shortname' => $shortname)));
        $this->fieldid = $DB->get_field('org_type_info_field', 'id', array('shortname' => $shortname));

        // Create organisation framework.
        $framework = $this->hierarchy_generator->create_org_frame(array());

        // Create organisation.
        $data['frameworkid'] = $framework->id;
        $data['typeid'] = $this->typeid;
        $this->org = $this->hierarchy_generator->create_org($data);
        $this->assertEquals($this->typeid, $this->org->typeid);

        // Create users.
        $users = array();
        for ($i = 1; $i <= self::TEST_USER_COUNT_MEMBERS; $i++) {
            $userdata['username'] = 'user' . $i;
            $userdata['idnumber'] = 'USER00' . $i;
            $userdata['firstname'] = 'nz_' . $i . '_testuser';
            $userdata['lastname'] = 'NZ FAMILY NAME';
            $userdata['city'] = 'Wellington';
            $userdata['country'] = 'NZ';
            $userdata['email'] = $userdata['firstname'] . '@example.com';
            $userdata['lang'] = 'en';
            $userdata['department'] = 'system';
            $userdata['institution'] = 'Totara';
            $user = $generator->create_user($userdata);
            $users[$user->id] = $user;
        }
        $this->assertSame(self::TEST_USER_COUNT_MEMBERS + 2, $DB->count_records('user'));

        // Set custom field values for some of them.
        reset($users);
        for ($i = 0; $i < 10; $i++) {
            next($users);
            \totara_job\job_assignment::create_default(key($users), array('organisationid' => $this->org->id));
        }
        $this->assertEquals(10, $DB->count_records('job_assignment', array('organisationid' => $this->org->id)));

        // We need to reset the rules after adding the custom user profile fields.
        cohort_rules_list(true);

        // Creating an empty dynamic cohort.
        $this->cohort = $this->cohort_generator->create_cohort(array('cohorttype' => cohort::TYPE_DYNAMIC));
        $this->assertTrue($DB->record_exists('cohort', array('id' => $this->cohort->id)));
        $this->assertEquals(0, $DB->count_records('cohort_members', array('cohortid' => $this->cohort->id)));

        // Creating a ruleset.
        $this->ruleset = cohort_rule_create_ruleset($this->cohort->draftcollectionid);
        $this->assertTrue($DB->record_exists('cohort_rulesets', array('id' => $this->ruleset)));
    }

    /**
     * Tests the checkbox profile field and multiple values.
     */
    public function test_checkbox_checked() {
        global $DB;

        $this->profilefield_save_data(1);
        $this->assertTrue($DB->record_exists('org_type_info_data', array('organisationid' => $this->org->id)));

        $this->cohort_generator->create_cohort_rule_params(
            $this->ruleset,
            'alljobassign',
            'orgcustomfield'.$this->fieldid,
            array('equal' => COHORT_RULES_OP_IN_EQUAL),
            array(0) // Checked.
        );
        $this->assertTrue($DB->record_exists('cohort_rules', array('rulesetid' => $this->ruleset)));
        $sql = "SELECT count('x')
                  FROM {cohort_rule_params} crp
                  JOIN {cohort_rules} cr ON cr.id = crp.ruleid
                 WHERE cr.rulesetid = :rulesetid";
        $this->assertEquals(2, $DB->count_records_sql($sql, array('rulesetid' => $this->ruleset)));

        cohort_rules_approve_changes($this->cohort);
        $this->assertEquals(10, $DB->count_records('cohort_members', array('cohortid' => $this->cohort->id)));
    }

    /**
     * Tests the checkbox profile field and multiple values.
     */
    public function test_checkbox_unchecked() {
        global $DB;

        $this->profilefield_save_data(1);
        $this->assertTrue($DB->record_exists('org_type_info_data', array('organisationid' => $this->org->id)));

        $this->cohort_generator->create_cohort_rule_params(
            $this->ruleset,
            'alljobassign',
            'orgcustomfield'.$this->fieldid,
            array('equal' => COHORT_RULES_OP_IN_EQUAL),
            array(1) // Unchecked.
        );
        $this->assertTrue($DB->record_exists('cohort_rules', array('rulesetid' => $this->ruleset)));
        $sql = "SELECT count('x')
                  FROM {cohort_rule_params} crp
                  JOIN {cohort_rules} cr ON cr.id = crp.ruleid
                 WHERE cr.rulesetid = :rulesetid";
        $this->assertEquals(2, $DB->count_records_sql($sql, array('rulesetid' => $this->ruleset)));

        cohort_rules_approve_changes($this->cohort);
        $this->assertEquals(0, $DB->count_records('cohort_members', array('cohortid' => $this->cohort->id)));
    }

    /**
     * Save a new value for organisation profile field.
     *
     * @param $newvalue string, value to save for organisation profile field.
     */
    private function profilefield_save_data($newvalue) {
        $item = new \stdClass();
        $item->id = $this->org->id;
        $item->typeid = $this->typeid;
        $item->{$this->input} = $newvalue;
        customfield_save_data($item, 'organisation', 'org_type');
    }
}
