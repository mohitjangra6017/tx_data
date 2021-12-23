<?php

 /**
 * Form for exporting configuration
 *
 * @copyright  City & Guilds Kineo 2023-03-23 {@link http://www.kineo.com}
 * @author     tri.le
 * @version    1.0
 */

namespace local_backup\form;

class backup_form extends \moodleform {

    protected function definition() {
        $mform = $this->_form;
        $elements = \local_backup\element\base::get_all();

        foreach ($elements as $name => $element) {
            $mform->addElement('checkbox', "elements[{$name}]", '', $element->get_name(), 1);
        }

        $this->add_action_buttons(false, get_string('backupdownload', 'local_backup'));
    }

    function validation($data, $files) {
        $errors = parent::validation($data, $files);
        if (empty($data['elements'])) {
            $elements = \local_backup\element\base::get_all();
            $firstelementname = array_keys($elements)[0];
            $errors["elements[{$firstelementname}]"] = get_string('needchoosingatleastone', 'local_backup');
        }
        return $errors;
    }
}