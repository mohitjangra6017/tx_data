<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package mod_assessment
 * @subpackage assquestion_text
 */

namespace mod_assessment\question\text\form;

use mod_assessment\question\edit_form;

class edit extends edit_form
{

    public static function get_type(): string
    {
        return 'text';
    }

    protected function add_js()
    {
        // JS not required.
    }

    protected function add_question_fields()
    {
        // No custom question fields.
    }
}
