<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

/**
 * @group kineo
 * @group tke
 * @group local_default_filters
 */
class default_filters_test extends advanced_testcase
{
    use \totara_webapi\phpunit\webapi_phpunit_helper;

    public function test_default_filters_apply()
    {
        global $DB, $SESSION;

        $data['user-fullname'] = [
            'operator' => 0,
            'value' => 'fullname',
        ];
        $data['user-lastname'] = [
            'operator' => 0,
            'value' => 'lastname',
        ];

        $record = new stdClass();
        $record->reportid = 1;
        $record->data = serialize($data);
        $record->timemodified = time();

        $DB->insert_record('local_default_filters', $record);

        $user = $this->getDataGenerator()->create_user();
        complete_user_login($user);
        $this->assertObjectHasAttribute('reportbuilder', $SESSION);

        $defaults = $SESSION->reportbuilder[1];

        $this->assertArrayHasKey('user-fullname', $defaults);
        $this->assertArrayHasKey('user-lastname', $defaults);
        $this->assertEquals('fullname', $defaults['user-fullname']['value']);
        $this->assertEquals('lastname', $defaults['user-lastname']['value']);
        $this->assertEquals(0, $defaults['user-lastname']['operator']);
        $this->assertEquals(0, $defaults['user-fullname']['operator']);
    }

    public function test_default_filters_do_not_apply()
    {
        global $SESSION;

        $user = $this->getDataGenerator()->create_user();
        complete_user_login($user);
        $this->assertObjectHasAttribute('reportbuilder', $SESSION);
        $this->assertEmpty($SESSION->reportbuilder);
    }
}


