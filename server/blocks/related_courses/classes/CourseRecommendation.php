<?php
/**
 * Course Recommendation
 *
 * @package   block_related_courses
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2016 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace block_related_courses;

use block_related_courses\Factory\CourseBasedFactory;
use block_related_courses\Model\CourseImageModel;
use block_related_courses\Model\DescriptionModel;
use coding_exception;
use Exception;
use moodle_exception;

defined('MOODLE_INTERNAL') || die;

global $CFG;
require_once($CFG->dirroot . '/tag/lib.php');
require_once($CFG->dirroot . '/completion/completion_completion.php');

/**
 * Class recommendation
 * This is used to get recommendation learning using the tag functionality.
 *
 * @package block_related_courses\model
 */
class CourseRecommendation
{
    /**
     * @var CourseTagging
     */
    protected $tagging;

    /**
     * @var CourseBased
     */
    protected $based;

    /**
     * @var int
     */
    protected $userId;

    /**
     * recommendation constructor.
     *
     * @param CourseTagging $tagging
     * @param CourseBased $based
     * @param int $userId
     */
    public function __construct(CourseTagging $tagging, CourseBased $based, int $userId)
    {
        $this->tagging = $tagging;
        $this->based = $based;
        $this->userId = $userId;
    }

    /**
     * Get courses with the same tags to the based ones that are not completed.
     *
     * @return object[]
     * @throws coding_exception
     * @throws moodle_exception
     * @throws Exception
     */
    public function getRecommendedCourses(): array
    {
        $config = get_config('block_related_courses');

        $relatedCourseIds = $this->based->getCourseIds();
        $completed =
            CourseBasedFactory::create(CourseBasedFactory::BASED_COMPLETED)
                              ->getCourseIds();
        $tags = $this->tagging->getTags($relatedCourseIds);

        $courses = [];

        // Get the courses for the list of tags that are not completed and not in the given course array.
        foreach ($tags as $tag) {
            $taggedCourses = $this->tagging->getCourses($tag);
            foreach ($taggedCourses as $course) {
                if (isset($courses[$course->id])) {
                    continue;
                }
                if (isset($relatedCourseIds[$course->id])) {
                    continue;
                }
                if (isset($completed[$course->id])) {
                    continue;
                }
                if (is_enrolled(\context_course::instance($course->id), $this->userId)) {
                    continue;
                }
                if (!totara_course_is_viewable($course->id)) {
                    continue;
                }

                $description = new DescriptionModel($course->summary, \context_course::instance($course->id));
                $image = new CourseImageModel($config->imagesource, $course);

                $course->url = new \moodle_url('/course/view.php', ['id' => $course->id]);
                $course->imageurl = $image->getImagePath();
                $course->summary = $description->getSummary();
                $normalisedTags = [];
                foreach ($this->tagging->getTags([$course->id]) as $rawTag) {
                    $normalisedTags[] = str_replace(' ', '-', $rawTag);
                }
                $course->tags = $normalisedTags;

                $courses[$course->id] = $course;
            }
        }

        return $courses;
    }
}