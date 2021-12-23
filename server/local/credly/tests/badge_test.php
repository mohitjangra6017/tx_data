<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

use local_credly\entity\BadgeIssue;

/**
 * @group kineo
 * @group tke
 * @group local_credly
 */
class badge_test extends advanced_testcase
{
    use \totara_webapi\phpunit\webapi_phpunit_helper;

    public function setUp(): void
    {
        global $CFG;
        $this->set_wwwroot_from_main_config();

        $CFG->forced_plugin_settings['local_credly'] = [
            'enabled' => 1,
            'endpoint_url' => $CFG->wwwroot . '/local/credly/tests/fixtures/graphql_badges_query_endpoint.php?path=',
            'auth_token' => 'abcd',
            'organisation_id' => 'abcd',
        ];

    }

    public function test_badge_graphql_query()
    {
        $this->setAdminUser();

        /** @var \local_credly\testing\generator $badgeGenerator */
        $badgeGenerator = $this->getDataGenerator()->get_plugin_generator('local_credly');

        $apiBadgeResponse = new stdClass();
        $apiBadgeResponse->data = [];
        $apiBadgeCount = 3;
        for ($i = 1; $i <= $apiBadgeCount; $i++) {
            $badgeGenerator->createBadge(
                [
                    'credlyid' => 'badge' . $i,
                    'name' => 'Test Badge' . $i,
                ]
            );
            $badge = new stdClass();
            $badge->id = 'badge' . $i;
            $badge->name = 'Test Badge ' . $i;
            $apiBadgeResponse->data[] = $badge;
        }
        $apiBadgeResponse->metadata = new stdClass();
        $apiBadgeResponse->metadata->total_count = $apiBadgeCount;

        $return = $this->resolve_graphql_query('local_credly_badges');

        $this->assertEquals($apiBadgeCount, $return['metadata']['total']);
        $this->assertNull($return['metadata']['next']);
        $this->assertEquals('badge1', $return['items'][0]['credlyId']);
        $this->assertNull($return['items'][0]['programId']);
        $this->assertNull($return['items'][0]['courseId']);
        $this->assertCount($apiBadgeCount, $return['items']);
    }

    public function test_link_badge_with_program()
    {
        $this->setAdminUser();

        /** @var \totara_program\testing\generator $programGenerator */
        $programGenerator = $this->getDataGenerator()->get_plugin_generator('totara_program');

        $program1 = $programGenerator->create_program(['fullname' => 'Test Program 1']);

        // First test linking a badge that does not exist in the DB.
        $badge = new \local_credly\entity\Badge();
        $badge->credlyid = 'abcd';
        $badge->name = 'Test Badge 1';
        $badge->save();

        $return = $this->resolve_graphql_mutation(
            'local_credly_link_badge',
            [
                'link' => [
                    'credlyId' => $badge->credlyid,
                    'learningId' => $program1->id,
                    'learningType' => 'program',
                ],
            ]
        );
        $expectedResponse = [
            'credlyId' => $badge->credlyid,
            'learningType' => 'program',
            'learningId' => $program1->id,
            'learningName' => $program1->fullname,
        ];

        $badge = \local_credly\entity\Badge::repository()->findByCredlyId($badge->credlyid);
        $this->assertNotNull($badge);
        $this->assertEquals($program1->id, $badge->programid);
        $this->assertEquals($program1->fullname, $badge->linkedlearningname);
        $this->assertEquals($expectedResponse, $return);

        // Now test linking this badge to another program.
        $program2 = $programGenerator->create_program(['fullname' => 'Test Program 2']);
        $return = $this->resolve_graphql_mutation(
            'local_credly_link_badge',
            [
                'link' => [
                    'credlyId' => $badge->credlyid,
                    'learningId' => $program2->id,
                    'learningType' => 'program',
                ],
            ]
        );
        $expectedResponse = [
            'credlyId' => $badge->credlyid,
            'learningType' => 'program',
            'learningId' => $program2->id,
            'learningName' => $program2->fullname,
        ];

        $badge->refresh();
        $this->assertNotNull($badge);
        $this->assertEquals($program2->id, $badge->programid);
        $this->assertEquals($program2->fullname, $badge->linkedlearningname);
        $this->assertEquals($expectedResponse, $return);
    }

    public function test_link_badge_with_certification()
    {
        $this->setAdminUser();

        /** @var \totara_program\testing\generator $programGenerator */
        $programGenerator = $this->getDataGenerator()->get_plugin_generator('totara_program');

        $certification1 = $programGenerator->create_certification(['fullname' => 'Test Certification 1']);

        // First test linking a badge that does not exist in the DB.
        $badge = new \local_credly\entity\Badge();
        $badge->credlyid = 'abcd';
        $badge->name = 'Certif Badge 1';
        $badge->certificationid = $certification1->certifid;
        $badge->save();

        $return = $this->resolve_graphql_mutation(
            'local_credly_link_badge',
            [
                'link' => [
                    'credlyId' => $badge->credlyid,
                    'learningId' => $certification1->certifid,
                    'learningType' => 'certification',
                ],
            ]
        );
        $expectedResponse = [
            'credlyId' => $badge->credlyid,
            'learningType' => 'certification',
            'learningId' => $certification1->certifid,
            'learningName' => $certification1->fullname,
        ];

        $badge = \local_credly\entity\Badge::repository()->findByCredlyId($badge->credlyid);
        $this->assertNotNull($badge);
        $this->assertEquals($certification1->certifid, $badge->certificationid);
        $this->assertEquals($certification1->fullname, $badge->linkedlearningname);
        $this->assertEquals($expectedResponse, $return);

        // Now test linking this badge to another certification.
        $certification2 = $programGenerator->create_certification(['fullname' => 'Test Certification 2']);
        $return = $this->resolve_graphql_mutation(
            'local_credly_link_badge',
            [
                'link' => [
                    'credlyId' => $badge->credlyid,
                    'learningId' => $certification2->certifid,
                    'learningType' => 'certification',
                ],
            ]
        );
        $expectedResponse = [
            'credlyId' => $badge->credlyid,
            'learningType' => 'certification',
            'learningId' => $certification2->certifid,
            'learningName' => $certification2->fullname,
        ];

        $badge->refresh();
        $this->assertNotNull($badge);
        $this->assertEquals($certification2->certifid, $badge->certificationid);
        $this->assertEquals($certification2->fullname, $badge->linkedlearningname);
        $this->assertEquals($expectedResponse, $return);
    }

    public function test_link_badge_with_course()
    {
        $this->setAdminUser();

        $course1 = $this->getDataGenerator()->create_course();

        // First test linking a badge that does not exist in the DB.
        $badge = new \local_credly\entity\Badge();
        $badge->credlyid = 'efgh';
        $badge->name = 'Test Badge 1';
        $badge->save();

        $return = $this->resolve_graphql_mutation(
            'local_credly_link_badge',
            [
                'link' => [
                    'credlyId' => $badge->credlyid,
                    'learningId' => $course1->id,
                    'learningType' => 'course',
                ],
            ]
        );
        $expectedResponse = [
            'credlyId' => $badge->credlyid,
            'learningType' => 'course',
            'learningId' => $course1->id,
            'learningName' => $course1->fullname,
        ];

        $badge = \local_credly\entity\Badge::repository()->findByCredlyId($badge->credlyid);
        $this->assertNotNull($badge);
        $this->assertEquals($course1->id, $badge->courseid);
        $this->assertEquals($course1->fullname, $badge->linkedlearningname);
        $this->assertEquals($expectedResponse, $return);

        // Now test linking this badge to another course.
        $course2 = $this->getDataGenerator()->create_course();
        $return = $this->resolve_graphql_mutation(
            'local_credly_link_badge',
            [
                'link' => [
                    'credlyId' => $badge->credlyid,
                    'learningId' => $course2->id,
                    'learningType' => 'course',
                ],
            ]
        );
        $expectedResponse = [
            'credlyId' => $badge->credlyid,
            'learningType' => 'course',
            'learningId' => $course2->id,
            'learningName' => $course2->fullname,
        ];

        $badge->refresh();
        $this->assertNotNull($badge);
        $this->assertEquals($course2->id, $badge->courseid);
        $this->assertEquals($course2->fullname, $badge->linkedlearningname);
        $this->assertEquals($expectedResponse, $return);
    }

    public function test_unlink_badge_from_program()
    {
        $this->setAdminUser();

        /** @var \totara_program\testing\generator $programGenerator */
        $programGenerator = $this->getDataGenerator()->get_plugin_generator('totara_program');

        /** @var \local_credly\testing\generator $badgeGenerator */
        $badgeGenerator = $this->getDataGenerator()->get_plugin_generator('local_credly');

        $program = $programGenerator->create_program(['fullname' => 'Test Program 1']);
        $badge = $badgeGenerator->createBadge(['programid' => $program->id]);

        $return = $this->resolve_graphql_mutation(
            'local_credly_link_badge',
            [
                'link' => [
                    'credlyId' => $badge->credlyid,
                    'learningId' => null,
                    'learningType' => 'unlinked',
                ],
            ]
        );
        $expectedResponse = [
            'credlyId' => $badge->credlyid,
            'learningType' => 'unlinked',
            'learningId' => null,
            'learningName' => null,
        ];

        $badge->refresh();
        $this->assertNotNull($badge);
        $this->assertNull($badge->programid);
        $this->assertNull($badge->linkedlearningname);
        $this->assertEquals($expectedResponse, $return);
    }

    public function test_unlink_badge_from_certification()
    {
        $this->setAdminUser();

        /** @var \totara_program\testing\generator $programGenerator */
        $programGenerator = $this->getDataGenerator()->get_plugin_generator('totara_program');

        /** @var \local_credly\testing\generator $badgeGenerator */
        $badgeGenerator = $this->getDataGenerator()->get_plugin_generator('local_credly');

        $certification = $programGenerator->create_certification(['fullname' => 'Test Certification 1']);
        $badge = $badgeGenerator->createBadge(['certificationid' => $certification->id]);

        $return = $this->resolve_graphql_mutation(
            'local_credly_link_badge',
            [
                'link' => [
                    'credlyId' => $badge->credlyid,
                    'learningId' => null,
                    'learningType' => 'unlinked',
                ],
            ]
        );
        $expectedResponse = [
            'credlyId' => $badge->credlyid,
            'learningType' => 'unlinked',
            'learningId' => null,
            'learningName' => null,
        ];

        $badge->refresh();
        $this->assertNotNull($badge);
        $this->assertNull($badge->certificationid);
        $this->assertNull($badge->linkedlearningname);
        $this->assertEquals($expectedResponse, $return);
    }

    public function test_unlink_badge_from_course()
    {
        $this->setAdminUser();

        $course = $this->getDataGenerator()->create_course();

        /** @var \local_credly\testing\generator $badgeGenerator */
        $badgeGenerator = $this->getDataGenerator()->get_plugin_generator('local_credly');

        $badge = $badgeGenerator->createBadge(['courseid' => $course->id]);

        $return = $this->resolve_graphql_mutation(
            'local_credly_link_badge',
            [
                'link' => [
                    'credlyId' => $badge->credlyid,
                    'learningId' => null,
                    'learningType' => 'unlinked',
                ],
            ]
        );
        $expectedResponse = [
            'credlyId' => $badge->credlyid,
            'learningType' => 'unlinked',
            'learningId' => null,
            'learningName' => null,
        ];

        $badge->refresh();
        $this->assertNotNull($badge);
        $this->assertNull($badge->courseid);
        $this->assertNull($badge->linkedlearningname);
        $this->assertEquals($expectedResponse, $return);
    }

    public function test_existing_issue_marked_as_replace_on_certification_completion()
    {
        /** @var \totara_program\testing\generator $programGenerator */
        $programGenerator = $this->getDataGenerator()->get_plugin_generator('totara_program');

        /** @var \local_credly\testing\generator $badgeGenerator */
        $badgeGenerator = $this->getDataGenerator()->get_plugin_generator('local_credly');

        /** @var \core_completion\testing\generator $completionGenerator */
        $completionGenerator = $this->getDataGenerator()->get_plugin_generator('core_completion');

        $certification = $programGenerator->create_certification(['fullname' => 'Test Certif 1']);

        $course = $this->getDataGenerator()->create_course();
        $programGenerator->add_courses_and_courseset_to_program($certification, [[$course]]);
        $user = $this->getDataGenerator()->create_user();

        $badge = $badgeGenerator->createBadge(['certificationid' => $certification->certifid]);
        $badgeIssue = new BadgeIssue();
        $badgeIssue->certificationid = $certification->certifid;
        $badgeIssue->badgeid = $badge->id;
        $badgeIssue->userid = $user->id;
        $badgeIssue->timeexpires = time() + 31556926;
        $badgeIssue->issuetime = time();
        $badgeIssue->status = BadgeIssue::STATUS_SUCCESS;
        $badgeIssue->issueid = 'abcde';
        $badgeIssue->save();

        $programGenerator->assign_program($certification->id, [$user->id]);
        $completionGenerator->complete_course($course, $user);

        $badgeIssue->refresh();
        $this->assertEquals(BadgeIssue::STATUS_REPLACE, $badgeIssue->status);
    }

    public function test_sync_badges()
    {
        $sync = new \local_credly\Sync();

        $reflect = new ReflectionClass(get_class($sync));
        $prop = $reflect->getProperty('endpoint');
        $prop->setAccessible(true);

        $client = new \totara_core\http\clients\matching_mock_client();
        $endpoint = new \local_credly\Endpoint('http://abcd.example.com', 'authA', 'orgA', $client);
        $prop->setValue($sync, $endpoint);

        /** @var \local_credly\testing\generator $badgeGenerator */
        $badgeGenerator = $this->getDataGenerator()->get_plugin_generator('local_credly');
        $existingBadge = $badgeGenerator->createBadge(['name' => 'Existing badge 1']);
        $this->assertEquals('active', $existingBadge->state);

        // Test that an existing gets updated via the api
        $responseData = new stdClass();
        $responseData->data =
            [
                [
                    'id' => $existingBadge->credlyid,
                    'name' => 'Existing badge name has changed',
                    'state' => 'archived',
                ],
                [
                    'id' => uniqid('badge'),
                    'name' => 'New Credly Badge 2',
                    'state' => 'active',
                ],
                [
                    'id' => uniqid('badge'),
                    'name' => 'New Credly Badge 3',
                    'state' => 'draft',

                ],
            ];
        $responseData->metadata = [
            "current_page" => 1,
            "total_pages" => 1
        ];

        $expectedResponse = new \totara_core\http\response(
            json_encode($responseData),
            200,
            [],
            'application/json'
        );
        // Add 2 identical responses here as the first one used by the sync pulls the metadata out
        // and the 2nd gets the actual badges
        $client->add_response('http://abcd.example.com/organizations/orgA/badge_templates?sort=name&page=1&filter', $expectedResponse);
        $client->add_response('http://abcd.example.com/organizations/orgA/badge_templates?sort=name&page=1&filter', $expectedResponse);
        $sync->synchroniseWithCredly();
        $existingBadge->refresh();

        $this->assertEquals('archived', $existingBadge->state);
        $this->assertEquals('Existing badge name has changed', $existingBadge->name);
        $this->assertEquals(3, \local_credly\entity\Badge::repository()->getCountOfBadges());
    }

    public function test_single_badge_issue()
    {
        /** @var \totara_program\testing\generator $programGenerator */
        $programGenerator = $this->getDataGenerator()->get_plugin_generator('totara_program');

        /** @var \local_credly\testing\generator $badgeGenerator */
        $badgeGenerator = $this->getDataGenerator()->get_plugin_generator('local_credly');

        /** @var \core_completion\testing\generator $completionGenerator */
        $completionGenerator = $this->getDataGenerator()->get_plugin_generator('core_completion');

        $program = $programGenerator->create_program(['fullname' => 'Test Program 1']);

        $course = $this->getDataGenerator()->create_course();
        $programGenerator->add_courses_and_courseset_to_program($program, [[$course]]);

        $badge = $badgeGenerator->createBadge(['programid' => $program->id]);
        $user = $this->getDataGenerator()->create_user();

        $programGenerator->assign_program($program->id, [$user->id]);
        $completionGenerator->complete_course($course, $user);
        $completion = prog_load_completion($program->id, $user->id, false);

        $this->assertNotFalse($completion);
        $badgeIssue = BadgeIssue::repository()->findBadgesToBeIssuedByLearningType('program', $user->id, $program->id);
        $badgeIssue = reset($badgeIssue);
        $this->assertNotNull($badgeIssue);

        $responseData = new stdClass();
        $responseData->data = ['badge_template' => ['id' => $badge->credlyid], 'user' => ['email' => $user->email], 'id' => 'abcde'];
        $responseData->metadata = [];

        $expectedResponse = new \totara_core\http\response(
            json_encode($responseData),
            200,
            [],
            'application/json'
        );
        $client = new \totara_core\http\clients\matching_mock_client();
        $client->add_response('http://abcd.example.com/organizations/orgA/badges', $expectedResponse);
        $endpoint = new \local_credly\Endpoint('http://abcd.example.com', 'authA', 'orgA', $client);
        $endpoint->issueBadge($badgeIssue);

        // Reload from the DB to ensure it has been saved properly.
        $badgeIssue = BadgeIssue::repository()->findByUserIdAndBadgeId($user->id, $badge->id);
        $log = $badgeIssue->logs->first();

        $this->assertEquals(BadgeIssue::STATUS_SUCCESS, $badgeIssue->status);
        $this->assertEquals($completion->timecompleted, $badgeIssue->issuetime);
        $this->assertEquals('abcde', $badgeIssue->issueid);
        $this->assertCount(1, $badgeIssue->logs);
        $this->assertNull($log->response);


        //Now test a replacement
        $badgeIssue->status = BadgeIssue::STATUS_REPLACE;

        $responseData = new stdClass();
        $responseData->data = ['badge_template' => ['id' => $badge->credlyid], 'user' => ['email' => $user->email], 'id' => 'fghij'];
        $responseData->metadata = [];

        $expectedResponse = new \totara_core\http\response(
            json_encode($responseData),
            200,
            [],
            'application/json'
        );

        $client->reset_responses();
        $client->add_response(
            "http://abcd.example.com/organizations/orgA/badges/{$badgeIssue->issueid}/replace",
            $expectedResponse
        );

        $endpoint = new \local_credly\Endpoint('http://abcd.example.com', 'authA', 'orgA', $client);
        $endpoint->issueBadge($badgeIssue);

        // Reload from the DB to ensure it has been saved properly.
        $badgeIssue->refresh();
        $this->assertEquals(BadgeIssue::STATUS_SUCCESS, $badgeIssue->status);
        $this->assertEquals('fghij', $badgeIssue->issueid);
    }

    public function test_scheduled_task_batches_badge_issues()
    {
        $issueTask = new \local_credly\Task\Issue();

        $reflect = new ReflectionClass(get_class($issueTask));
        $prop = $reflect->getProperty('endpoint');
        $prop->setAccessible(true);

        $client = new \totara_core\http\clients\matching_mock_client();
        $endpoint = new \local_credly\Endpoint('http://abcd.example.com', 'authA', 'orgA', $client);
        $prop->setValue($issueTask, $endpoint);

        /** @var \totara_program\testing\generator $programGenerator */
        $programGenerator = $this->getDataGenerator()->get_plugin_generator('totara_program');

        /** @var \local_credly\testing\generator $badgeGenerator */
        $badgeGenerator = $this->getDataGenerator()->get_plugin_generator('local_credly');

        $program = $programGenerator->create_program(['fullname' => 'Test Program 1']);
        $certification = $programGenerator->create_certification(['fullname' => 'Test Certif 1']);
        $course = $this->getDataGenerator()->create_course();
        $progBadge = $badgeGenerator->createBadge(['programid' => $program->id]);
        $certifBadge = $badgeGenerator->createBadge(['certificationid' => $certification->certifid]);
        $courseBadge = $badgeGenerator->createBadge(['courseid' => $course->id]);

        $user1 = $this->getDataGenerator()->create_user();
        $user2 = $this->getDataGenerator()->create_user();
        $user3 = $this->getDataGenerator()->create_user();
        $user4 = $this->getDataGenerator()->create_user();
        $user5 = $this->getDataGenerator()->create_user();

        $badgeIssue1 = new BadgeIssue();
        $badgeIssue1->badgeid = $progBadge->id;
        $badgeIssue1->userid = $user1->id;
        $badgeIssue1->programid = $program->id;
        $badgeIssue1->status = BadgeIssue::STATUS_RECOVERABLE_FAILURE;
        $badgeIssue1->issuetime = time();
        $badgeIssue1->save();

        $badgeIssue2 = new BadgeIssue();
        $badgeIssue2->badgeid = $progBadge->id;
        $badgeIssue2->userid = $user2->id;
        $badgeIssue2->programid = $program->id;
        $badgeIssue2->status = BadgeIssue::STATUS_RECOVERABLE_FAILURE;
        $badgeIssue2->issuetime = time();
        $badgeIssue2->save();

        $badgeIssue3 = new BadgeIssue();
        $badgeIssue3->badgeid = $courseBadge->id;
        $badgeIssue3->userid = $user3->id;
        $badgeIssue3->courseid = $course->id;
        $badgeIssue3->status = BadgeIssue::STATUS_RECOVERABLE_FAILURE;
        $badgeIssue3->issuetime = time();
        $badgeIssue3->save();

        $badgeIssue4 = new BadgeIssue();
        $badgeIssue4->badgeid = $certifBadge->id;
        $badgeIssue4->userid = $user4->id;
        $badgeIssue4->certificationid = $certification->certifid;
        $badgeIssue4->status = BadgeIssue::STATUS_RECOVERABLE_FAILURE;
        $badgeIssue4->issuetime = time();
        $badgeIssue4->save();

        $responseData = [
            'data' => [
                ['badge_template' => ['id' => $progBadge->credlyid], 'user' => ['email' => $user1->email], 'id' => 'id0'],
                ['badge_template' => ['id' => $progBadge->credlyid], 'user' => ['email' => $user2->email], 'id' => 'id1'],
                ['badge_template' => ['id' => $courseBadge->credlyid], 'user' => ['email' => $user3->email], 'id' => 'id2'],
                ['badge_template' => ['id' => $certifBadge->credlyid], 'user' => ['email' => $user4->email], 'id' => 'id3'],
            ],
            'metadata' => [],
        ];
        $expectedResponse = new \totara_core\http\response(
            json_encode($responseData),
            200,
            [],
            'application/json'
        );
        $client->add_response('http://abcd.example.com/organizations/orgA/badges/batch', $expectedResponse);
        $issueTask->execute();

        $badgeIssue1->refresh();
        $badgeIssue2->refresh();
        $badgeIssue3->refresh();
        $badgeIssue4->refresh();

        $this->assertEquals(BadgeIssue::STATUS_SUCCESS, $badgeIssue1->status);
        $this->assertEquals('id0', $badgeIssue1->issueid);
        $this->assertEquals(BadgeIssue::STATUS_SUCCESS, $badgeIssue2->status);
        $this->assertEquals('id1', $badgeIssue2->issueid);
        $this->assertEquals(BadgeIssue::STATUS_SUCCESS, $badgeIssue3->status);
        $this->assertEquals('id2', $badgeIssue3->issueid);
        $this->assertEquals(BadgeIssue::STATUS_SUCCESS, $badgeIssue4->status);
        $this->assertEquals('id3', $badgeIssue4->issueid);

        // Now fake a 422 response and check the record shows an unrecoverable failure.
        $badgeIssue5 = new BadgeIssue();
        $badgeIssue5->badgeid = $progBadge->id;
        $badgeIssue5->userid = $user5->id;
        $badgeIssue5->programid = $program->id;
        $badgeIssue5->status = BadgeIssue::STATUS_RECOVERABLE_FAILURE;
        $badgeIssue5->issuetime = time();
        $badgeIssue5->save();

        $responseData = [
            'data' => [
                'message' => 'A failure occurred.',
                'error_index' => 0,
            ],
            'metadata' => [],
        ];
        $expectedResponse = new \totara_core\http\response(
            json_encode($responseData),
            422,
            [],
            'application/json'
        );
        $client->reset_responses();
        $client->add_response('http://abcd.example.com/organizations/orgA/badges/batch', $expectedResponse);

        $issueTask->execute();

        $badgeIssue5->refresh();
        $this->assertEquals(BadgeIssue::STATUS_UNRECOVERABLE_FAILURE, $badgeIssue5->status);
        $this->assertCount(1, $badgeIssue5->logs);
        $this->assertEquals(json_encode($responseData['data']), $badgeIssue5->logs->first()->response);
    }

    public function test_program_deleted_unlinks_badge()
    {
        /** @var \totara_program\testing\generator $programGenerator */
        $programGenerator = $this->getDataGenerator()->get_plugin_generator('totara_program');

        /** @var \local_credly\testing\generator $badgeGenerator */
        $badgeGenerator = $this->getDataGenerator()->get_plugin_generator('local_credly');

        $program = $programGenerator->create_program(['fullname' => 'Test Program 1']);
        $badge = $badgeGenerator->createBadge(['programid' => $program->id]);
        $this->assertEquals($program->id, $badge->programid);

        $program->delete();
        $badge->refresh();

        $this->assertNull($badge->programid);
    }

    public function test_course_deleted_unlinks_badge()
    {
        $course = $this->getDataGenerator()->create_course();

        /** @var \local_credly\testing\generator $badgeGenerator */
        $badgeGenerator = $this->getDataGenerator()->get_plugin_generator('local_credly');

        $badge = $badgeGenerator->createBadge(['courseid' => $course->id]);
        $this->assertEquals($course->id, $badge->courseid);

        $container = \core_container\factory::from_record($course);
        $container->delete();

        $badge->refresh();

        $this->assertNull($badge->courseid);
    }

    public function test_user_opt_out()
    {
        /** @var \local_credly\testing\generator $badgeGenerator */
        $badgeGenerator = $this->getDataGenerator()->get_plugin_generator('local_credly');

        /** @var \core_completion\testing\generator $completionGenerator */
        $completionGenerator = $this->getDataGenerator()->get_plugin_generator('core_completion');

        $course = $this->getDataGenerator()->create_course();
        $badge = $badgeGenerator->createBadge(['courseid' => $course->id]);

        $user = $this->getDataGenerator()->create_user();
        $completionGenerator->complete_course($course, $user);

        // Ensure global opt-out is disabled.
        set_config('allow_opt_out', 0, 'local_credly');

        $issues = BadgeIssue::repository()->findBadgesToBeIssuedByLearningType('course');
        $this->assertCount(1, $issues);

        // Allow users to opt-out if they want, but our user has not changed this, so is still opted-in.
        // Note our user has never set the preference, so the SQL query needs to handle this.
        set_config('allow_opt_out', 1, 'local_credly');

        $issues = BadgeIssue::repository()->findBadgesToBeIssuedByLearningType('course');
        $this->assertCount(1, $issues);

        // Opt-out as the user, but then switch off global opt-out again.
        set_user_preference('local_credly_opt_out', 1, $user);
        set_config('allow_opt_out', 0, 'local_credly');

        $issues = BadgeIssue::repository()->findBadgesToBeIssuedByLearningType('course');
        $this->assertCount(1, $issues);

        // Allow users to opt-out if they want. Our user must now be opted-out.
        set_config('allow_opt_out', 1, 'local_credly');

        $issues = BadgeIssue::repository()->findBadgesToBeIssuedByLearningType('course');
        $this->assertCount(0, $issues);
    }

    private function set_wwwroot_from_main_config()
    {
        $CFG = new stdClass();
        require __DIR__ . '/../../../../config.php';

        $wwwroot = $CFG->wwwroot;
        unset($CFG);

        global $CFG;
        $CFG->wwwroot = $wwwroot;
    }
}
