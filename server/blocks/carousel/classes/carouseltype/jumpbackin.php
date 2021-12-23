<?php

namespace block_carousel\carouseltype;

use block_carousel\helper;

class jumpbackin extends base {
    function generate($data) {
        global $PAGE, $USER;
        $renderer = $PAGE->get_renderer('block_carousel');

        $enrolledcourses = helper::get_enrolled_courses($USER->id, $this->config, true);
        if (empty($enrolledcourses)) {
            return '';
        }
        $data = $this->build_course_slide_context($enrolledcourses, get_string('type:'.\block_carousel\constants::BKC_JUMPBACKIN, 'block_carousel'), $data);
        return $renderer->render_jumpbackin($data);
    }
}