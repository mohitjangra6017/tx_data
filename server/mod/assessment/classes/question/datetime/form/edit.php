<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\question\datetime\form;

use mod_assessment\question\datetime\model\question;
use mod_assessment\question\edit_form;

class edit extends edit_form
{

    public static function get_type(): string
    {
        return 'datetime';
    }

    protected function add_js()
    {
        // JS is not required.
    }

    protected function add_question_fields()
    {
        $this->_form->addElement('header', 'head_options', get_string('options', 'assquestion_datetime'));
        $this->_form->addElement('checkbox', 'config_showtime', get_string('showtime', 'assquestion_datetime'));
    }
}
