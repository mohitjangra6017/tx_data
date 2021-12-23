<?php

namespace block_carousel\carouseltype;

use block_carousel\constants;
use block_carousel\helper;

class whatsnew extends base
{
    function generate($data)
    {
        global $PAGE;
        $renderer = $PAGE->get_renderer('block_carousel');

        $new_courses = helper::get_new_courses($this->config);

        $data =
            $this->build_course_slide_context(
                $new_courses,
                get_string('type:' . constants::BKC_WHATS_NEW, 'block_carousel'),
                $data
            );

        return $renderer->render_whatsnew($data);
    }

    public static function extend_form(\MoodleQuickForm $form, \stdClass $block_instance)
    {
        $form->addElement(
            'text',
            'config_whatsnewlimit',
            get_string('whatsnewlimit', 'block_carousel'),
            ['oninput' => "this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"]
        ); // only allow numeric values
        $form->addHelpButton('config_whatsnewlimit', 'whatshotlimit', 'block_carousel');
        $form->setType('config_whatsnewlimit', PARAM_INT);
        $form->setDefault('config_whatsnewlimit', constants::BKC_DEFAULT_RECORD_LIMIT);
        $form->hideIf('config_whatsnewlimit', 'config_carouseltype', 'neq', constants::BKC_WHATS_NEW);
    }

}