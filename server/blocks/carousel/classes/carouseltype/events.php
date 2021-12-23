<?php

namespace block_carousel\carouseltype;

class events extends base {
    function generate($data) {
        global $PAGE, $USER, $CFG;
        
        if (!file_exists($CFG->dirroot.'/local/coachingsession/version.php')) {
            return '';
        }
        
        $renderer = $PAGE->get_renderer('block_carousel');

        $sessions = \local_coachingsession\coaching_booking::upcoming_events($USER->id);

        if (empty($sessions)) {
            return '';
        }
        
        foreach ($sessions as $session) {
            $data['slides'][] = $session->get_display_data();
        }
        $data['title'] = empty($data['title']) ? get_string('type:'.\block_carousel\constants::BKC_EVENTS, 'block_carousel') : $data['title'];

        return $renderer->render_events($data);
    }
}