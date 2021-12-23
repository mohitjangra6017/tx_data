<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_credly\Form;

use totara_form\form;
use totara_form\form\element\checkbox;
use totara_form\form\element\static_html;

class MyPreferencesForm extends form
{
    protected function definition()
    {
        $description = get_config('local_credly', 'opt_out_disclaimer');
        $this->model->add(new static_html('opt_out_desc', '', format_text($description, FORMAT_HTML)));

        $checkbox = new checkbox('opt_out', get_string('user_preferences:opt_out', 'local_credly'));
        $checkbox->add_help_button('user_preferences:opt_out', 'local_credly');
        $this->model->add($checkbox);

        $this->model->add_action_buttons(false);
    }
}