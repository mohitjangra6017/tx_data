<?php

namespace isotopeprovider_programs\DataDecorators;

use context_course;
use core_course\theme\file\course_image;
use isotopeprovider_programs\Contracts\DataDecorator;
use moodle_url;

global $CFG;

require_once($CFG->dirroot . '/totara/program/lib.php');

class CourseSet implements DataDecorator
{
    const DATE_FORMAT = '%e %b %Y';
    const COMPONENT = 'isotopeprovider_programs';

    private $config;

    /**
     * Config options valid for the Decorator.
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @param $data
     * @param array $context
     * @return array
     */
    public function decorate($data, $context = [])
    {
        $completions = \completion_info::get_all_courses($context['userid']);
        $dateformat = static::DATE_FORMAT;

        return array_map(
            function ($course) use ($completions, $dateformat, $context) {
                global $PAGE;

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
                        $course->status = 'notstarted';
                        break;
                }

                if (totara_course_is_viewable($course->id)) {
                    $course->url = (new \moodle_url('/course/view.php', ['id' => $course->id]))->out(false);
                } else {
                    $course->url = (new \moodle_url(
                        '/totara/program/required.php',
                        ['id' => $course->programid, 'cid' => $course->id, 'sesskey' => sesskey()]
                    ))->out(false);
                }

                $course->date = ($course->timecompleted != 0)
                    ? get_string('datecomplete', self::COMPONENT, userdate($course->timecompleted, $dateformat))
                    : '';
                $course->type = 'course';
                $course->displaytype = get_string('type:course', self::COMPONENT);

                $course->imgstatus = 'show';

                // Get the course image. Order of preference is course image -> core course default
                $courseContext = context_course::instance($course->id);
                $fs = get_file_storage();
                $imageFiles = $fs->get_area_files($courseContext->id, 'course', 'images', 0, "timemodified DESC", false);
                if ($imageFiles) {
                    $course->image = moodle_url::make_pluginfile_url(
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
                    $course->image = $course_image->get_current_or_default_url();
                }

                return $course;
            },
            $data
        );
    }
}