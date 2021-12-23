<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\question\editor\form;

use mod_assessment\question\edit_form;

class edit extends edit_form
{

    public static function get_type(): string
    {
        return 'editor';
    }

    protected function add_js()
    {
        // JS not required.
    }

    protected function add_question_fields()
    {
        // No special fields.
    }
}
