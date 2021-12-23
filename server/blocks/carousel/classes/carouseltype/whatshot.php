<?php

namespace block_carousel\carouseltype;

use block_carousel\constants;
use block_carousel\helper;

class whatshot extends base {
    function generate($data) {
        global $PAGE;
        $renderer = $PAGE->get_renderer('block_carousel');

        $hot_courses = helper::get_hot_courses($this->config);

        $data = $this->build_course_slide_context($hot_courses, get_string('type:' . constants::BKC_WHATS_HOT, 'block_carousel'), $data);

        return $renderer->render_whatshot($data);
    }

    public static function extend_form(\MoodleQuickForm $form, \stdClass $block_instance)
    {
        $form->addElement(
            'text',
            'config_whatshotlimit',
            get_string('whatshotlimit', 'block_carousel'),
            ['oninput' => "this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"]
        ); // only allow numeric values
        $form->addHelpButton('config_whatshotlimit', 'whatshotlimit', 'block_carousel');
        $form->setType('config_whatshotlimit', PARAM_INT);
        $form->setDefault('config_whatshotlimit', constants::BKC_DEFAULT_RECORD_LIMIT);
        $form->hideIf('config_whatshotlimit', 'config_carouseltype', 'neq', constants::BKC_WHATS_HOT);
    }

}