<?php

namespace block_carousel\carouseltype;

use block_carousel\constants;
use program;
use stdClass;

class programs extends base {
    function generate($data) {
        global $PAGE, $USER;

        $renderer = $PAGE->get_renderer('block_carousel');

        $carousel_type = get_string('type:' . constants::BKC_PROGRAMS, 'block_carousel');
        
        $userid = $USER->id;

        $programs = prog_get_all_programs($userid);

        $card_size = $data['cardsize'];

        $show_progressbar = !empty($this->config->showprogressbar) ? true : false;
        
        foreach ($programs as $program) {
            $strip_title_length = ($card_size == constants::BKC_CARD_LARGE) ? constants::BKC_TITLE_LENGHT_LARGE_CARD : constants::BKC_TITLE_LENGHT_SMALL_CARD;
    
            $title = empty($program->fullname) ? $program->fullname : $program->fullname;

            $programid = $program->id;

            $programclass = new program($programid);

            $slides = [
                'title' => substr(strip_tags($title), 0, $strip_title_length) . (strlen($title) > $strip_title_length ? '...' : ''),
                'fulltitle' => $title,
                'url' => '/totara/program/view.php?id=' . $programid,
                'thumbnail' => new \moodle_url($programclass->get_image(), ['preview' => 'block_carousel_large', 'theme' => $PAGE->theme->name]),
                'programid' => $programid,
                'summary' => empty($program->summary) ? $program->summary : null,
                'cardsize' => !empty($this->config->cardsize) ? $this->config->cardsize : null,
                'carouseltype' => $carousel_type,
                'progressbar' => $show_progressbar ? $this->get_progressbar($programid, $userid) : null
            ];

            $data['slides'][] = $slides;
        }
              
        $data['title'] = empty($data['title']) ? get_string('type:' . constants::BKC_PROGRAMS, 'block_carousel') : $data['title'];
        $data['template'] = $data['template'].'_programs';

        return $renderer->render_programs($data);
    }

    public function get_progressbar($programid, $userid) {
        global $PAGE;

        $renderer = $PAGE->get_renderer('totara_core');

        $percentage = totara_program_get_user_percentage_complete($programid, $userid);
        $progress = new \lang_string('notassigned', 'totara_program');
        if ($percentage !== null) {
            $progress = $renderer->progressbar($percentage, 'medium', false);
        }
        return $progress;
    }

    public static function extend_form(\MoodleQuickForm $form, stdClass $block_instance)
    {
        $form->addElement(
            'text',
            'config_programslimit',
            get_string('programslimit', 'block_carousel'),
            ['oninput' => "this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"]
        ); // only allow numeric values
        $form->addHelpButton('config_programslimit', 'programslimit', 'block_carousel');
        $form->setType('config_programslimit', PARAM_INT);
        $form->setDefault('config_programslimit', constants::BKC_DEFAULT_RECORD_LIMIT);
        $form->hideIf('config_programslimit', 'config_carouseltype', 'neq', constants::BKC_PROGRAMS);


        $form->addElement('advcheckbox', 'config_showprogressbar', get_string('showprogressbar', 'block_carousel'));
        $form->setDefault('config_showprogressbar', 0);
        $form->hideIf('config_showprogressbar', 'config_carouseltype', 'neq', constants::BKC_PROGRAMS);
    }


}