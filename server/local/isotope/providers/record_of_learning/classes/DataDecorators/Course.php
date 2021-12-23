<?php

namespace isotopeprovider_record_of_learning\DataDecorators;

use coding_exception;
use completion_info;
use context_course;
use core_course\theme\file\course_image;
use local_isotope\Data\DecoratorInterface;
use moodle_url;

class Course implements DecoratorInterface
{
    const DATE_FORMAT = '%e %b %Y';
    const COMPONENT = 'isotopeprovider_record_of_learning';

    /** @var array */
    private $config;

    /**
     * Config options valid for the Decorator.
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * Do whatever needs doing to the collection of data items.
     *
     * @param array $data The data to decorate
     * @param array $context Extra information used to decorate items
     * @return array
     * @throws coding_exception
     */
    public function decorate(array $data, $context = [])
    {
        $completions = completion_info::get_all_courses($context['userid']);
        $dateFormat = static::DATE_FORMAT;

        return array_map(
            function ($course) use ($completions, $dateFormat) {
                global $CFG;
                $status = array_key_exists($course->id, $completions) ? $completions[$course->id]->status : null;

                // Status will be null when a course is hidden but a completion exists, because
                // ... \completion_info::get_all_courses(), will not return a completion for a course that is not visible
                // ... to a user. We already know the user is enrolled as the course dataSource checks this. So all we
                // ... have to do is check for a timecompleted to determine if the course was completed or not.
                // ... This avoids us getting an Unknown status option.
                if (is_null($status) && !empty($course->timecompleted)) {
                    $status = COMPLETION_STATUS_COMPLETE;
                }

                switch ($status) {
                    case COMPLETION_STATUS_COMPLETE:
                    case COMPLETION_STATUS_COMPLETEVIARPL:
                        $course->status = 'completed';
                        break;
                    case COMPLETION_STATUS_INPROGRESS:
                        $course->status = 'started';
                        break;
                    case COMPLETION_STATUS_NOTYETSTARTED:
                        $course->status = "notstarted";
                        break;
                    default:
                        $course->status = 'unknown';
                        break;
                }
                $course->url = $CFG->wwwroot . '/course/view.php?id=' . $course->id;
                $course->date = ($course->timecompleted != 0)
                    ? get_string('datecomplete', self::COMPONENT, userdate($course->timecompleted, $dateFormat))
                    : '';
                $course->type = 'course';
                $course->displaytype = get_string('type:course', self::COMPONENT);

                if ($this->config['display_status_colours_with_image']) {
                    $course->imgstatus = 'show';
                } else {
                    $course->imgstatus = 'hide';
                }

                $course->image = $this->getCourseImageUrl($course);

                return $course;
            },
            $data
        );
    }

    /**
     * Order of preference is:
     * course image ->
     * core course default (if "look for image in summary file" set to no) ->
     * 1st summary file (if "look for image in summary file" set to yes)
     *
     * @param $course
     * @return string|null
     */
    private function getCourseImageUrl($course): ?string
    {
        global $PAGE;

        if (empty($this->config['display_course_image']) || $this->config['display_course_image'] === 'disabled') {
            return null;
        }

        $courseContext = context_course::instance($course->id);
        $fs = get_file_storage();

        $imageFiles = $fs->get_area_files($courseContext->id, 'course', 'images', 0, "timemodified DESC", false);
        if ($imageFiles) {
            $url = moodle_url::make_pluginfile_url(
                $courseContext->id,
                'course',
                'images',
                $course->cacherev,
                '/',
                'image',
                false
            )->out(false);
        } else {
            $course_image = new course_image($PAGE->theme);
            $url = $course_image->get_current_or_default_url();
        }

        // If configured, look first for the course image in the 'course summary' field and then in the 'course summary files' field.
        if (isset($this->config['use_course_summary_image']) && $this->config['use_course_summary_image']) {
            $fs = get_file_storage();

            $overviewFiles = $fs->get_area_files(\context_course::instance($course->id)->id, 'course', 'overviewfiles');
            $summaryFiles = $fs->get_area_files(\context_course::instance($course->id)->id, 'course', 'summary');
            $allFiles = $summaryFiles + $overviewFiles;

            foreach ($allFiles as $file) {
                if ($file->is_directory()) {
                    continue;
                }
                try {
                    if (!$file->is_valid_image()) {
                        continue;
                    }
                } catch (\moodle_exception $e) {
                    // So is_valid_image likes to throw an exception instead of returning false if the file doesn't exist.
                    continue;
                }

                if ($file->get_filearea() === 'images') {
                    // We must include an itemid for this area.
                    $itemId = $file->get_itemid() ?: 0;
                } else if ($file->get_filearea() === 'overviewfiles' || $file->get_filearea() === 'summary') {
                    // We must not include an itemid for this area.
                    $itemId = null;
                }

                // Set the image then drop out. We only want 1 of them.
                $url = \moodle_url::make_pluginfile_url(
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea(),
                    $itemId,
                    $file->get_filepath(),
                    $file->get_filename()
                )->out(false);
                break;
            }
        }

        return $url ?? null;
    }
}