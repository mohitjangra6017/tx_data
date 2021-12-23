<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

use local_credly\Exception\InvalidCredlyEventTypeException;


/**
 * @group kineo
 * @group tke
 * @group local_credly
 */
class webhook_test extends advanced_testcase
{
    public function test_mm_unavailable()
    {
        global $CFG;

        $client = new \totara_core\http\clients\matching_mock_client();
        $endpoint = new \local_credly\Endpoint('http://abcd.example.com', 'authA', 'orgA', $client);
        $webhook = new \local_credly\WebhookEndpoint($endpoint);

        $CFG->maintenance_enabled = true;
        $this->expectException(\local_credly\Exception\CoreUnavailableException::class);
        $webhook->execute(null, null);
        $CFG->maintenance_enabled = false;
    }

    public function test_upgrade_unavailable()
    {
        global $CFG;

        $client = new \totara_core\http\clients\matching_mock_client();
        $endpoint = new \local_credly\Endpoint('http://abcd.example.com', 'authA', 'orgA', $client);
        $webhook = new \local_credly\WebhookEndpoint($endpoint);

        $CFG->upgraderunning = true;
        $this->expectException(\local_credly\Exception\CoreUnavailableException::class);
        $webhook->execute(null, null);
        $CFG->upgraderunning = false;
    }

    public function test_invalid_token()
    {
        set_config('webhooktoken', 'ABCDE', 'local_credly');
        $client = new \totara_core\http\clients\matching_mock_client();
        $endpoint = new \local_credly\Endpoint('http://abcd.example.com', 'authA', 'orgA', $client);
        $webhook = new \local_credly\WebhookEndpoint($endpoint);
        $this->expectException(InvalidArgumentException::class);
        $webhook->execute('FGHIJ', null);
    }

    public function test_invalid_organisation_id()
    {
        set_config('webhooktoken', 'ABCDE', 'local_credly');
        set_config('organisation_id', 'orgA', 'local_credly');
        $client = new \totara_core\http\clients\matching_mock_client();
        $endpoint = new \local_credly\Endpoint('http://abcd.example.com', 'authA', 'orgA', $client);
        $webhook = new \local_credly\WebhookEndpoint($endpoint);
        $this->expectException(InvalidArgumentException::class);
        $webhook->execute('ABCDE', ['organization_id' => 'orgb']);
    }

    public function test_invalid_organisation_data()
    {
        set_config('webhooktoken', 'ABCDE', 'local_credly');
        set_config('organisation_id', 'orgA', 'local_credly');
        $client = new \totara_core\http\clients\matching_mock_client();
        $endpoint = new \local_credly\Endpoint('http://abcd.example.com', 'authA', 'orgA', $client);
        $webhook = new \local_credly\WebhookEndpoint($endpoint);
        $this->expectException(InvalidArgumentException::class);
        $webhook->execute('ABCDE', null);
    }

    public function test_invalid_webhook_event_type()
    {
        set_config('webhooktoken', 'ABCDE', 'local_credly');
        set_config('organisation_id', 'orgA', 'local_credly');
        $client = new \totara_core\http\clients\matching_mock_client();
        $endpoint = new \local_credly\Endpoint('http://abcd.example.com', 'authA', 'orgA', $client);
        $webhook = new \local_credly\WebhookEndpoint($endpoint);
        $this->expectException(InvalidCredlyEventTypeException::class);
        $webhook->execute('ABCDE', [
            'organization_id' => 'orgA',
            'event_type' => 'badge.created'
        ]);
    }

    public function test_invalid_event_id()
    {
        set_config('webhooktoken', 'ABCDE', 'local_credly');
        set_config('organisation_id', 'orgA', 'local_credly');
        $client = new \totara_core\http\clients\matching_mock_client();
        $endpoint = new \local_credly\Endpoint('http://abcd.example.com', 'authA', 'orgA', $client);

        $eventData = [
            "message" => 'resource not found'
        ];

        $expectedResponse = new \totara_core\http\response(
            json_encode($eventData),
            404,
            [],
            'application/json'
        );

        $client->add_response('http://abcd.example.com/organizations/orgA/events/id2', $expectedResponse);
        $this->expectException(InvalidArgumentException::class);
        $webhook = new \local_credly\WebhookEndpoint($endpoint);
        $webhook->execute('ABCDE', [
            'organization_id' => 'orgA',
            'event_type' => 'badge_template.created',
            'id' => 'id1'
        ]);
    }

    public function test_badge_template_updated_webhook()
    {
        /** @var \local_credly\testing\generator $badgeGenerator */
        $badgeGenerator = $this->getDataGenerator()->get_plugin_generator('local_credly');
        $badge = $badgeGenerator->createBadge(
            [
                'credlyid' => 'credlyid_1',
                'name' => 'Badge Template 1'
            ]
        );

        set_config('webhooktoken', 'ABCDE', 'local_credly');
        set_config('organisation_id', 'orgA', 'local_credly');
        $client = new \totara_core\http\clients\matching_mock_client();
        $eventData = [
            "data" => [
                "badge_template" => [
                    "id" => "credlyid_1",
                    "name" => "Badge Template 1 changed",
                    "state" => "active",
                ],
                "id" => "id1",
                "event_type" => "badge_template.changed",
                "occurred_at" => "2014-04-01T14:41:00.000Z",
            ],
        ];

        $expectedResponse = new \totara_core\http\response(
            json_encode($eventData),
            200,
            [],
            'application/json'
        );

        $client->add_response('http://abcd.example.com/organizations/orgA/events/id1', $expectedResponse);
        $endpoint = new \local_credly\Endpoint('http://abcd.example.com', 'authA', 'orgA', $client);

        $webhook = new \local_credly\WebhookEndpoint($endpoint);
        $webhook->execute('ABCDE', [
            'organization_id' => 'orgA',
            'event_type' => 'badge_template.changed',
            'id' => 'id1'
        ]);

        $badge->refresh();
        $this->assertEquals('Badge Template 1 changed', $badge->name);
        $webHookLog = \local_credly\entity\WebhookLog::repository()->where('eventid', '=', 'id1')->get()->to_array();
        $webHookLog = reset($webHookLog);
        $this->assertEquals($badge->id, $webHookLog['badgeid']);
        $this->assertEquals('id1', $webHookLog['eventid']);
        $this->assertEquals('badge_template.changed', $webHookLog['eventtype']);
    }


    public function test_badge_template_created_webhook()
    {
        set_config('webhooktoken', 'ABCDE', 'local_credly');
        set_config('organisation_id', 'orgA', 'local_credly');
        $client = new \totara_core\http\clients\matching_mock_client();
        $eventData = [
            "data" => [
                "badge_template" => [
                    "id" => "credlyid_2",
                    "name" => "New Badge Template 1",
                    "state" => "active",
                ],
                "id" => "id2",
                "event_type" => "badge_template.created",
                "occurred_at" => "2014-04-01T14:41:00.000Z",
            ],
        ];

        $expectedResponse = new \totara_core\http\response(
            json_encode($eventData),
            200,
            [],
            'application/json'
        );

        $client->add_response('http://abcd.example.com/organizations/orgA/events/id2', $expectedResponse);
        $endpoint = new \local_credly\Endpoint('http://abcd.example.com', 'authA', 'orgA', $client);

        $webhook = new \local_credly\WebhookEndpoint($endpoint);
        $webhook->execute('ABCDE', [
            'organization_id' => 'orgA',
            'event_type' => 'badge_template.created',
            'id' => 'id2'
        ]);

        $badge = \local_credly\entity\Badge::repository()->findByCredlyId('credlyid_2');

        $this->assertEquals('New Badge Template 1', $badge->name);
        $webHookLog = \local_credly\entity\WebhookLog::repository()->where('eventid', '=', 'id2')->get()->to_array();
        $webHookLog = reset($webHookLog);
        $this->assertEquals($badge->id, $webHookLog['badgeid']);
        $this->assertEquals('id2', $webHookLog['eventid']);
        $this->assertEquals('badge_template.created', $webHookLog['eventtype']);
        $this->assertEquals('active', $badge->state);
    }


    public function test_badge_template_deleted_webhook()
    {
        /** @var \local_credly\testing\generator $badgeGenerator */
        $badgeGenerator = $this->getDataGenerator()->get_plugin_generator('local_credly');
        $badge = $badgeGenerator->createBadge(
            [
                'credlyid' => 'credlyid_3',
                'name' => 'Badge Template 3'
            ]
        );

        set_config('webhooktoken', 'ABCDE', 'local_credly');
        set_config('organisation_id', 'orgA', 'local_credly');
        $client = new \totara_core\http\clients\matching_mock_client();
        $eventData = [
            "data" => [
                "badge_template" => [
                    "id" => "credlyid_3",
                    "name" => "Badge Template 1 deleted",
                    "state" => "active",
                ],
                "id" => "id3",
                "event_type" => "badge_template.deleted",
                "occurred_at" => "2014-04-01T14:41:00.000Z",
            ],
        ];

        $expectedResponse = new \totara_core\http\response(
            json_encode($eventData),
            200,
            [],
            'application/json'
        );

        $client->add_response('http://abcd.example.com/organizations/orgA/events/id3', $expectedResponse);
        $endpoint = new \local_credly\Endpoint('http://abcd.example.com', 'authA', 'orgA', $client);

        $webhook = new \local_credly\WebhookEndpoint($endpoint);
        $webhook->execute('ABCDE', [
            'organization_id' => 'orgA',
            'event_type' => 'badge_template.deleted',
            'id' => 'id3'
        ]);

        $badge->refresh();
        $this->assertEquals('archived', $badge->state);
        $webHookLog = \local_credly\entity\WebhookLog::repository()->where('eventid', '=', 'id3')->get()->to_array();
        $webHookLog = reset($webHookLog);
        $this->assertEquals($badge->id, $webHookLog['badgeid']);
        $this->assertEquals('id3', $webHookLog['eventid']);
        $this->assertEquals('badge_template.deleted', $webHookLog['eventtype']);
    }
}
