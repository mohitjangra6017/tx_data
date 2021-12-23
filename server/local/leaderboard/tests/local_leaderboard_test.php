<?php
/**
 * local_score_test.php
 *
 * @package   local_score
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2016 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

/**
 * Class local_score_testcase
 * @group kineo
 * @group tke
 * @group local_leaderboard
 */
class local_leaderboard_testcase extends advanced_testcase
{

    /**
     * @var object
     */
    private $user;

    /**
     * @var local_leaderboard_generator
     */
    private $generator;

    public function setUp(): void
    {
        $this->resetAfterTest(true);
        $this->user = $this->getDataGenerator()->create_user();
        $this->setUser($this->user);
        $this->generator = $this->getDataGenerator()->get_plugin_generator('local_leaderboard');

        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->user = null;
        $this->generator = null;

        parent::tearDown();
    }

    public function test_complete_course()
    {
        $course = $this->getDataGenerator()->create_course();
        $score = $this->generator->createScore(
            [
                'eventname' => '\core\event\course_completed',
                'score' => 8,
            ]
        );

        $this->getDataGenerator()->enrol_user($this->user->id, $course->id);

        $completion = new completion_completion(['userid' => $this->user->id, 'course' => $course->id]);
        $completion->mark_complete();

        $this->assertEquals(8, $this->get_total_score($score->id));
    }

    public function test_multiplier()
    {
        global $DB;

        $course1 = $this->getDataGenerator()->create_course();
        $course2 = $this->getDataGenerator()->create_course();
        $score = $this->generator->createScore(
            [
                'eventname' => '\core\event\course_completed',
                'score' => 8,
            ]
        );

        $fieldId = $this->generator->createCourseMultiplier();

        $newField = new stdClass();
        $newField->fieldid = $fieldId;
        $newField->courseid = $course1->id;
        $newField->data = 1;
        $DB->insert_record('course_info_data', $newField);

        $newField->courseid = $course2->id;
        $newField->data = 3;
        $DB->insert_record('course_info_data', $newField);

        $this->getDataGenerator()->enrol_user($this->user->id, $course1->id);
        $this->getDataGenerator()->enrol_user($this->user->id, $course2->id);

        $completion = new completion_completion(['userid' => $this->user->id, 'course' => $course1->id]);
        $completion->mark_complete();

        $this->assertEquals(8, $this->get_most_recent_user_score($score->id));

        $completion = new completion_completion(['userid' => $this->user->id, 'course' => $course2->id]);
        $completion->mark_complete();

        $this->assertEquals(24, $this->get_most_recent_user_score($score->id));
    }

    public function test_frequency()
    {
        $course = $this->getDataGenerator()->create_course();
        $score = $this->generator->createScore(
            [
                'eventname' => '\core\event\course_viewed',
                'frequency' => 2,
                'score' => 5,
            ]
        );

        $this->getDataGenerator()->enrol_user($this->user->id, $course->id);
        $context = context_course::instance($course->id);

        course_view($context);
        $this->assertEquals(5, $this->get_total_score($score->id));

        course_view($context);
        $this->assertEquals(5, $this->get_total_score($score->id));

        sleep(2);

        course_view($context);
        $this->assertEquals(10, $this->get_total_score($score->id));
    }

    /**
     * Get the score amount a user has earned in a particular score event.
     *
     * @param $leaderboardId
     * @return mixed
     */
    protected function get_total_score($leaderboardId)
    {
        global $DB;

        $record = $DB->get_field(
            'local_leaderboard_user',
            'sum(score)',
            ['userid' => $this->user->id, 'leaderboardid' => $leaderboardId]
        );

        $record = (integer) $record;

        return $record;
    }


    /**
     * Get the most recent score for the user
     *
     * @param $leaderboardId
     * @return mixed
     * @throws dml_exception
     */
    protected function get_most_recent_user_score($leaderboardId)
    {
        global $DB;

        $records = $DB->get_records(
            'local_leaderboard_user',
            ['userid' => $this->user->id, 'leaderboardid' => $leaderboardId],
            'id DESC'
        );

        $record = reset($records);

        return $record->score;
    }
}