<?php

namespace block_carousel\carouseltype;

use block_carousel\helper;

class courselets extends base {
    function generate($data) {
        global $PAGE;
        $renderer = $PAGE->get_renderer('block_carousel');

        $courselets = helper::get_courslets();

        $data = $this->build_course_slide_context($courselets, get_string('type:'.\block_carousel\constants::BKC_COURSELETS, 'block_carousel'), $data);
        // TODO: Make template configurable in the future
        $data['template'] = $data['template'].'_courselets';

        return $renderer->render_courselets($data);
    }
}