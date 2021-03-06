<?php
/*
 * This file is part of Totara Learn
 *
 * Copyright (C) 2018 onwards Totara Learning Solutions LTD
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
 * @author Valerii Kuznetsov <valerii.kuznetsov@totaralearning.com>
 * @package totara_reportbuilder
 */

use totara_reportbuilder\userdata\saved_search_private;
use totara_userdata\userdata\target_user;

defined('MOODLE_INTERNAL') || die();

global $CFG;

/**
 * This class tests purging and exporting private saved search userdata item.
 *
 * @group totara_reportbuilder
 * @group totara_userdata
 */
class totara_reportbuilder_userdata_saved_search_private_test extends advanced_testcase {

    /**
     * Generate data for purge, export, and count test
     * It generates: 2 users, 1 report, 2 private searches (1 per user), 2 public searches (1 per user), 2 default searches (1 per user)
     * 4 scheduled reports (1 per search), each scheduled report has 1 audience, 1 sys user, 1 external
     * @return array generated data
     */
    public function seed() {
        global $DB;

        $gen = $this->getDataGenerator();
        /** @var \totara_reportbuilder\testing\generator $rbgen */
        $rbgen = $gen->get_plugin_generator('totara_reportbuilder');

        // 2 users
        $user = $gen->create_user();
        $otheruser = $gen->create_user();

        // Audience
        $cohort = $gen->create_cohort();

        // Report.
        $report = (object)['fullname' => 'Users', 'shortname' => 'user',
            'source' => 'user', 'hidden'=>1, 'embedded' => 1];
        $report->id = $DB->insert_record('report_builder', $report);

        // Saved searches (2 private + 2 public) and set one as default for each user.
        $userpubsearch = $rbgen->create_saved_search($report, $user, ['name'=>'Saved 1.1', 'ispublic' => 1]);
        $userprivsearch = $rbgen->create_saved_search($report, $user, ['name'=>'Saved 1.2', 'ispublic' => 0]);
        $userdefaultsearch = $rbgen->create_saved_search_user_default($report, $user, $userprivsearch);

        $otheruserpubsearch = $rbgen->create_saved_search($report, $otheruser, ['name'=>'Saved 2.1', 'ispublic' => 1]);
        $otheruserprivsearch = $rbgen->create_saved_search($report, $otheruser, ['name'=>'Saved 2.2', 'ispublic' => 0]);
        $otheruserdefaultsearch = $rbgen->create_saved_search_user_default($report, $otheruser, $otheruserprivsearch);

        // Scheduled reports.
        $userpubsched = $rbgen->create_scheduled_report($report, $user, ['savedsearch' => $userpubsearch]);
        $userprivsched = $rbgen->create_scheduled_report($report, $user, ['savedsearch' => $userprivsearch]);
        $otheruserpubsched = $rbgen->create_scheduled_report($report, $otheruser, ['savedsearch' => $otheruserpubsearch]);
        $otheruserprivsched = $rbgen->create_scheduled_report($report, $otheruser, ['savedsearch' => $otheruserprivsearch]);

        foreach([$userpubsched, $userprivsched,$otheruserpubsched, $otheruserprivsched] as $schedule) {
            $rbgen->add_scheduled_audience($schedule, $cohort);
            $rbgen->add_scheduled_email($schedule);
            $rbgen->add_scheduled_user($schedule, $otheruser); // Saved search doesn't clean scheduled recipients.
        }

        return [
            'user' => $user,
            'otheruser' => $otheruser,
            'report' => $report,
            'userpublicsearch' => $userpubsearch,
            'userprivatesearch' => $userprivsearch,
            'userdefaultsearch' => $userdefaultsearch,
            'otheruserpublicsearch' => $otheruserpubsearch,
            'otheruserprivatesearch' => $otheruserprivsearch,
            'otheruserdefaultsearch' => $otheruserdefaultsearch,
            'userpublicsched' => $userpubsched,
            'userprivatesched' => $userprivsched,
            'otheruserpublicsched' => $otheruserpubsched,
            'otheruserprivatesched' => $otheruserprivsched,
        ];
    }

    /**
     * Test that data are purged correctly
     */
    public function test_purge() {
        global $DB;

        $seed = (object)$this->seed();

        // Run purge.
        $targetuser = new target_user($seed->user);
        $otheruser = new target_user($seed->otheruser);

        $precount = saved_search_private::execute_count($targetuser, context_system::instance());
        $precountother = saved_search_private::execute_count($otheruser, context_system::instance());

        saved_search_private::execute_purge($targetuser, context_system::instance());

        $postcount = saved_search_private::execute_count($targetuser, context_system::instance());
        $postcountother = saved_search_private::execute_count($otheruser, context_system::instance());

        $this->assertEquals(1, $precount);
        $this->assertEquals(1, $precountother);
        $this->assertEquals(0, $postcount);
        $this->assertEquals(1, $postcountother);

        // Confirm
        $this->assertCount(2, $DB->get_records('report_builder_saved', ['userid' => $seed->otheruser->id]));
        $this->assertCount(1, $DB->get_records('report_builder_saved_user_default', ['userid' => $seed->otheruser->id]));

        $usersaved = $DB->get_record('report_builder_saved', ['userid' => $seed->user->id], '*', MUST_EXIST);
        $this->assertEquals(1, $usersaved->ispublic);
        $this->assertEquals('Saved 1.1', $usersaved->name);
        $this->assertEquals($seed->userpublicsearch, $usersaved);

        $this->assertCount(0, $DB->get_records('report_builder_saved_user_default', ['userid' => $seed->user->id]));

        $this->assertCount(2, $DB->get_records('report_builder_schedule', ['userid' => $seed->otheruser->id]));

        $userschedule = $DB->get_record('report_builder_schedule', ['userid' => $seed->user->id], '*', MUST_EXIST);
        $this->assertEquals($seed->userpublicsearch->id, $userschedule->savedsearchid);
        $this->assertEquals($seed->userpublicsched, $userschedule);

        $this->assertTrue($DB->record_exists('report_builder_schedule_email_audience', ['scheduleid' => $seed->userpublicsched->id]));
        $this->assertFalse($DB->record_exists('report_builder_schedule_email_audience', ['scheduleid' => $seed->userprivatesched->id]));
        $this->assertTrue($DB->record_exists('report_builder_schedule_email_audience', ['scheduleid' => $seed->otheruserpublicsched->id]));
        $this->assertTrue($DB->record_exists('report_builder_schedule_email_audience', ['scheduleid' => $seed->otheruserprivatesched->id]));

        $this->assertTrue($DB->record_exists('report_builder_schedule_email_systemuser', ['scheduleid' => $seed->userpublicsched->id]));
        $this->assertFalse($DB->record_exists('report_builder_schedule_email_systemuser', ['scheduleid' => $seed->userprivatesched->id]));
        $this->assertTrue($DB->record_exists('report_builder_schedule_email_systemuser', ['scheduleid' => $seed->otheruserpublicsched->id]));
        $this->assertTrue($DB->record_exists('report_builder_schedule_email_systemuser', ['scheduleid' => $seed->otheruserprivatesched->id]));

        $this->assertTrue($DB->record_exists('report_builder_schedule_email_external', ['scheduleid' => $seed->userpublicsched->id]));
        $this->assertFalse($DB->record_exists('report_builder_schedule_email_external', ['scheduleid' => $seed->userprivatesched->id]));
        $this->assertTrue($DB->record_exists('report_builder_schedule_email_external', ['scheduleid' => $seed->otheruserpublicsched->id]));
        $this->assertTrue($DB->record_exists('report_builder_schedule_email_external', ['scheduleid' => $seed->otheruserprivatesched->id]));
    }

    /**
     * Test that data are purged correctly
     */
    public function test_purge_count() {
        $seed = (object)$this->seed();

        // Run export.
        $targetuser = new target_user($seed->user);
        $export = saved_search_private::execute_export($targetuser, context_system::instance());
        $count = saved_search_private::execute_count($targetuser, context_system::instance());
        $this->assertEquals(1, $count);
        $this->assertCount(1, $export->data);
        $data = current($export->data);
        $this->assertEquals('Users', $data['reportname']);
        $this->assertEquals('Saved 1.2', $data['searchname']);
        $this->assertNotEmpty($data['search']);
        $this->assertTrue(is_array($data['search']));
    }
}