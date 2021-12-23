<?php
/**
 * Related courses block definition.
 *
 * @package   block_related_courses
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2016 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

use block_related_courses\CourseBased;
use block_related_courses\CourseSorting;
use block_related_courses\CourseTagging;
use block_related_courses\Factory\CourseBasedFactory;
use block_related_courses\Factory\CourseSortFactory;
use block_related_courses\Factory\CourseTaggingFactory;
use block_related_courses\CourseRecommendation;

defined('MOODLE_INTERNAL') || die;

/**
 * Class block_related_courses
 *
 * @property stdClass $config Block configuration.
 */
class block_related_courses extends block_base
{
    /**
     * Block view modes
     */
    public const VIEW_FULL = 1;

    public const VIEW_CAROUSEL = 2;

    /**
     * Course Recommendation
     *
     * @var CourseRecommendation
     */
    protected $recommendation;

    /**
     * Course Based
     *
     * @var CourseBased
     */
    protected $based;

    /**
     * Course Tagging
     *
     * @var CourseTagging
     */
    protected $tagging;

    /**
     * Course Sorting
     *
     * @var CourseSorting
     */
    protected $sorting;

    /**
     * Block initialisation.
     * @throws coding_exception
     */
    public function init(): void
    {
        $this->title = get_string('pluginname', __CLASS__);
    }

    /**
     * {@inheritdoc}
     */
    public function specialization(): void
    {
        global $USER;

        $this->based = CourseBasedFactory::create($this->get_config('based'));
        $this->tagging = CourseTaggingFactory::create($this->get_config('tagging'));
        $this->sorting = CourseSortFactory::create($this->get_config('sorting'));
        $this->recommendation = new CourseRecommendation($this->tagging, $this->based, $USER->id);
    }

    /**
     * Indicate if the block have settings.php
     *
     * @return bool
     */
    public function has_config(): bool
    {
        return true;
    }

    /**
     * Return the value of a given config variable.
     *
     * @param string $name
     * @return mixed
     */
    protected function get_config(string $name)
    {
        $global_default = get_config('block_related_courses', $name);
        return isset($this->config->{$name}) ? $this->config->{$name} : $global_default;
    }

    /**
     * Return the block content.
     *
     * @return object
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function get_content(): object
    {
        if ($this->content != null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';

        // Load and sort the courses.
        $courses = $this->recommendation->getRecommendedCourses();
        $courses = $this->sorting->sort($courses);

        if ($limit = $this->get_config('limit')) {
            $courses = array_slice($courses, 0, $limit);
        }

        // Render the view mode.
        $view = $this->get_config('view');
        if (isset($view) && !empty($courses)) {
            if ($view == self::VIEW_FULL) {
                $this->content->text = $this->view_full($courses);
            } else if ($view == self::VIEW_CAROUSEL) {
                $this->content->text = $this->view_carousel($courses);
            }
        }

        return $this->content;
    }

    /**
     * Return the content of the full view of the block.
     *
     * @param object[] $courses
     * @return string
     * @throws coding_exception
     */
    protected function view_full(array $courses): string
    {
        global $OUTPUT;

        $rawTags = $this->tagging->getTags(array_keys($courses));

        $processedTags = [];
        foreach ($rawTags as $tag) {
            $normalisedTag = str_replace(' ', '-', $tag);
            $processedTags[$normalisedTag] = $normalisedTag;
        }
        asort($processedTags);

        $this->page->requires->js_call_amd('block_related_courses/full', 'initialise', [$this->instance->id]);

        return $OUTPUT->render_from_template('block_related_courses/full', [
            'courses' => array_values($courses),
            'tags'    => array_values($processedTags),
            'display' => $this->get_config('display'),
        ]);
    }

    /**
     * Return the content of the carousel view of the block.
     *
     * @param object[] $courses
     * @return string
     * @throws coding_exception
     */
    protected function view_carousel(array $courses): string
    {
        global $OUTPUT;

        $this->page->requires->js_call_amd('block_related_courses/carousel', 'initialise', [$this->instance->id]);

        return $OUTPUT->render_from_template('block_related_courses/carousel', [
            'courses'     => array_values($courses),
            'fullviewurl' => $this->get_config('fullviewurl'),
            'display'     => $this->get_config('display'),
        ]);
    }
}