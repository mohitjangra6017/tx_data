<?php

namespace block_carousel\carouseltype;

use block_carousel\helper;

class recommended extends base {
    function generate($data) {
        global $PAGE, $USER;
        $renderer = $PAGE->get_renderer('block_carousel');

        $enrolledcourses = helper::get_enrolled_courses($USER->id, $this->config);
        $recommendedcourses = helper::get_recommended_courses($USER->id);
        // TODO: Need to update the recommended course query to
        // not process this in a loop
        foreach ($recommendedcourses as $key => $course) {
            foreach ($enrolledcourses as $enrolledcourse) {
                if ($enrolledcourse->courseid == $course->courseid) {
                    unset($recommendedcourses[$key]);
                }
            }
        }

        if (empty($recommendedcourses)) {
            return '';
        }

        $data = $this->build_course_slide_context($recommendedcourses, get_string('type:'.\block_carousel\constants::BKC_RECOMMENDED, 'block_carousel'), $data);
        return $renderer->render_recommended($data);
    }
}