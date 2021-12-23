<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright Kineo 2019
 */

namespace block_related_courses\Model;

use coding_exception;
use context_system;
use dml_exception;
use Exception;
use moodle_url;

class CourseImageModel
{
    /** @var string  */
    public const SOURCE_CATALOG = 'catalog';

    /** @var string  */
    public const SOURCE_SUMMARY = 'summary';

    /** @var object */
    protected $course;

    /** @var string */
    protected $source;

    /**
     * CourseImageModel constructor.
     * @param $source
     * @param $course
     * @throws coding_exception
     * @throws Exception
     */
    public function __construct(string $source, object $course)
    {
        if (!array_key_exists($source, static::getSources())) {
            throw new Exception("Invalid image source ({$source})");
        }

        $this->course = $course;
        $this->source = $source;
    }

    /**
     * @return array
     * @throws coding_exception
     */
    public static function getSources(): array
    {
        return [
            self::SOURCE_CATALOG => get_string('catalog', 'block_related_courses'),
            self::SOURCE_SUMMARY => get_string('summary', 'block_related_courses'),
        ];
    }

    /**
     * @return string
     * @throws dml_exception
     */
    public static function getDefaultImage(): string
    {
        $defaultimage = get_config('block_related_courses', 'defaultimage');
        if (!$defaultimage) {
            return '';
        }

        return moodle_url::make_pluginfile_url(
            context_system::instance()->id,
            'block_related_courses',
            'defaults',
            0,
            '/',
            trim($defaultimage, '/')
        )
                         ->out(false);
    }

    /**
     * @return moodle_url|string
     * @throws dml_exception|coding_exception
     */
    public function getImagePath()
    {
        $imagePath = false;

        switch ($this->source) {
            case static::SOURCE_CATALOG:
                $imagePath = $this->getCatalogImage();
                break;
            case static::SOURCE_SUMMARY:
                $imagePath = $this->getSummaryImage();
                break;
        }

        if (!$imagePath) {
            $imagePath = static::getDefaultImage();
        }

        return $imagePath;
    }

    /**
     * @return moodle_url
     */
    protected function getCatalogImage(): moodle_url
    {
        return course_get_image($this->course);
    }

    /**
     * @return string
     * @throws coding_exception
     */
    protected function getSummaryImage(): ?string
    {
        $description = new DescriptionModel($this->course->summary, \context_course::instance($this->course->id));
        return $description->getBanner();
    }
}