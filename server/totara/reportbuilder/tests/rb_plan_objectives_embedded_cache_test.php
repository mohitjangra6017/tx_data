<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010 onwards Totara Learning Solutions LTD
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
 * @author Valerii Kuznetsov <valerii.kuznetsov@totaralms.com>
 * @package totara
 * @subpackage reportbuilder
 *
 * Unit/functional tests to check Record of Learning: Objectives reports caching
 */
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}
global $CFG;
require_once($CFG->dirroot . '/totara/reportbuilder/tests/reportcache_advanced_testcase.php');

/**
 * @group totara_reportbuilder
 */
class totara_reportbuilder_rb_plan_objectives_embedded_cache_testcase extends reportcache_advanced_testcase {
    // testcase data
    protected $report_builder_data = array('id' => 12, 'fullname' => 'Record of Learning: Objectives', 'shortname' => 'plan_objectives',
                                           'source' => 'dp_objective', 'hidden' => 1, 'embedded' => 1);

    protected $report_builder_columns_data = array(
                        array('id' => 54, 'reportid' => 12, 'type' => 'plan', 'value' => 'planlink',
                              'heading' => 'A', 'sortorder' => 1),
                        array('id' => 55, 'reportid' => 12, 'type' => 'plan', 'value' => 'status',
                              'heading' => 'B', 'sortorder' => 2),
                        array('id' => 56, 'reportid' => 12, 'type' => 'objective', 'value' => 'fullnamelink',
                              'heading' => 'C', 'sortorder' => 3),
                        array('id' => 57, 'reportid' => 12, 'type' => 'objective', 'value' => 'description',
                              'heading' => 'D', 'sortorder' => 4),
                        array('id' => 58, 'reportid' => 12, 'type' => 'objective', 'value' => 'priority',
                              'heading' => 'E', 'sortorder' => 5),
                        array('id' => 59, 'reportid' => 12, 'type' => 'objective', 'value' => 'duedate',
                              'heading' => 'F', 'sortorder' => 6),
                        array('id' => 60, 'reportid' => 12, 'type' => 'objective', 'value' => 'proficiencyandapproval',
                              'heading' => 'G', 'sortorder' => 7));

    protected $report_builder_filters_data = array(
                        array('id' => 25, 'reportid' => 12, 'type' => 'objective', 'value' => 'fullname',
                              'sortorder' => 1, 'advanced' => 0),
                        array('id' => 26, 'reportid' => 12, 'type' => 'objective', 'value' => 'priority',
                              'sortorder' => 2, 'advanced' => 1),
                        array('id' => 27, 'reportid' => 12, 'type' => 'objective', 'value' => 'duedate',
                              'sortorder' => 3, 'advanced' => 1),
                        array('id' => 28, 'reportid' => 12, 'type' => 'plan', 'value' => 'name',
                              'sortorder' => 4, 'advanced' => 1));

    // Work data
    public static $ind = 0;
    protected $user1 = null;
    protected $user2 = null;
    protected $user3 = null;
    protected $plan1 = null;
    protected $plan2 = null;
    protected $plan3 = null;
    protected $objectives = array();

    protected function tearDown(): void {
        $this->report_builder_data = null;
        $this->report_builder_columns_data = null;
        $this->report_builder_filters_data = null;
        $this->user1 = null;
        $this->user2 = null;
        $this->user3 = null;
        $this->plan1 = null;
        $this->plan2 = null;
        $this->plan3 = null;
        $this->objectives = null;
        parent::tearDown();
    }

    /**
     * Prepare mock data for testing
     *
     * Common part of all test cases:
     * - Create 3 users
     * - Create plan1 by user1
     * - Create plan2 and plan3 by user2
     * - Add 2 objectives to each plan
     */
    protected function setUp(): void {
        global $DB;

        parent::setup();
        $this->setAdminUser();

        $plan_generator = \totara_plan\testing\generator::instance();

        // Common parts of test cases:
        // Create report record in database
        $this->loadDataSet($this->createArrayDataSet(array('report_builder' => array($this->report_builder_data),
                                                           'report_builder_columns' => $this->report_builder_columns_data,
                                                           'report_builder_filters' => $this->report_builder_filters_data)));
        $this->user1 = $this->getDataGenerator()->create_user();
        $this->user2 = $this->getDataGenerator()->create_user();
        $this->user3 = $this->getDataGenerator()->create_user();
        $this->user4 = $this->getDataGenerator()->create_user();
        $this->plan1 = $plan_generator->legacy_create_plan($this->user1->id, []);
        $this->plan2 = $plan_generator->legacy_create_plan($this->user2->id, []);
        $this->plan3 = $plan_generator->legacy_create_plan($this->user2->id, []);
        $this->objectives[] = $this->create_objective($this->plan1->id);
        $this->objectives[] = $this->create_objective($this->plan1->id);
        $this->objectives[] = $this->create_objective($this->plan2->id);
        $this->objectives[] = $this->create_objective($this->plan2->id);
        $this->objectives[] = $this->create_objective($this->plan3->id);
        $this->objectives[] = $this->create_objective($this->plan3->id);

        $syscontext = context_system::instance();

        // Assign user2 to be user1's manager and remove viewallmessages from manager role.
        $managerja = \totara_job\job_assignment::create_default($this->user2->id);
        \totara_job\job_assignment::create_default($this->user1->id, array('managerjaid' => $managerja->id));
        $rolemanager = $DB->get_record('role', array('shortname'=>'manager'));
        assign_capability('totara/plan:accessanyplan', CAP_PROHIBIT, $rolemanager->id, $syscontext);

        // Assign user3 to course creator role and add viewallmessages to course creator role.
        $rolecoursecreator = $DB->get_record('role', array('shortname'=>'coursecreator'));
        role_assign($rolecoursecreator->id, $this->user3->id, $syscontext);
        assign_capability('totara/plan:accessanyplan', CAP_ALLOW, $rolecoursecreator->id, $syscontext);
    }

    /**
     * Test courses report
     * Test case:
     * - Common part (@see: self::setUp() )
     * - Check that user1 has objectives of plan1
     * - Check that user2 has objectives of plan2 and plan3
     * - Check that user3 has no plans
     *
     * @param int $usecache Use cache or not (1/0)
     * @dataProvider provider_use_cache
     */
    public function test_plan_objectives($usecache) {
        if ($usecache) {
            $this->enable_caching($this->report_builder_data['id']);
        }
        $objectiveidalias = reportbuilder_get_extrafield_alias('objective', 'fullnamelink', 'objective_id');
        $result = $this->get_report_result($this->report_builder_data['shortname'],
                            array('userid' => $this->user1->id), $usecache);
        $this->assertCount(2, $result);
        $was = array('');
        foreach($result as $r) {
            $this->assertContains($r->$objectiveidalias, array($this->objectives[0]->id, $this->objectives[1]->id));
            $this->assertNotContainsEquals($r->objective_fullnamelink, $was);
            $was[] = $r->objective_fullnamelink;
        }

        $result = $this->get_report_result($this->report_builder_data['shortname'],
                            array('userid' => $this->user2->id), $usecache);
        $this->assertCount(4, $result);
        $was = array('');
        foreach($result as $r) {
            $this->assertContains($r->$objectiveidalias, array($this->objectives[2]->id,
                $this->objectives[3]->id, $this->objectives[4]->id, $this->objectives[5]->id));
            $this->assertNotContainsEquals($r->objective_fullnamelink, $was);
            $was[] = $r->objective_fullnamelink;
        }

        $result = $this->get_report_result($this->report_builder_data['shortname'],
                            array('userid' => $this->user3->id), $usecache);
        $this->assertCount(0, $result);
    }

    /**
     * Create mock objective for plan
     * @param int $planid
     * @param stdClass|array $record
     */
    public function create_objective($planid, $record = array()) {
        global $DB;
        self::$ind++;
        $plan = new development_plan($planid);
        $component = $plan->get_component('objective');

        $default = array(
            'planid' => $planid,
            'fullname' => 'Learning plan ' . self::$ind,
            'description' => 'Description plan' . self::$ind,
            'priority' => 1,
            'duedate' => time() + 23328000,
            'scalevalueid' => $DB->get_field('dp_objective_scale', 'defaultid',
                    array('id' => $component->get_setting('objectivescale'))),
            'approved' => DP_APPROVAL_APPROVED
        );
        $properties = array_merge($default, $record);
        $newid = $DB->insert_record('dp_plan_objective', (object)$properties);
        dp_plan_item_updated(2, 'objective', $newid);

        $result = $DB->get_record('dp_plan_objective', array('id' => $newid));
        return $result;
    }

    public function test_is_capable() {

        // Set up report and embedded object for is_capable checks.
        $shortname = $this->report_builder_data['shortname'];
        $config = (new rb_config())->set_embeddata(array('userid' => $this->user1->id));
        $report = reportbuilder::create_embedded($shortname, $config);
        $embeddedobject = $report->embedobj;

        // Test admin can access report.
        $this->assertTrue($embeddedobject->is_capable(2, $report),
                'admin cannot access report');

        // Test user1 can access report for self.
        $this->assertTrue($embeddedobject->is_capable($this->user1->id, $report),
                'user cannot access their own report');

        // Test user1's manager can access report (we have removed accessanyplan from manager role).
        $this->assertTrue($embeddedobject->is_capable($this->user2->id, $report),
                'manager cannot access report');

        // Test user3 can access report using accessanyplan (we give 'coursecreator' role access to accessanyplan).
        $this->assertTrue($embeddedobject->is_capable($this->user3->id, $report),
                'user with accessanyplan cannot access report');

        // Test that user4 cannot access the report for another user.
        $this->assertFalse($embeddedobject->is_capable($this->user4->id, $report),
                'user should not be able to access another user\'s report');
    }
}
