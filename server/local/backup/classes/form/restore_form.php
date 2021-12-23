<?php

 /**
 * Form for restoring configuration
 *
 * @copyright  City & Guilds Kineo 2023-03-23 {@link http://www.kineo.com}
 * @author     tri.le
 * @version    1.0
 */

namespace local_backup\form;

class restore_form extends \moodleform {

    protected function definition() {
        $mform = $this->_form;
        $mform->addElement('static', '', get_string('warning_label', 'local_backup'), get_string('warning', 'local_backup'));
        $mform->addElement('filepicker', 'importfile', get_string('uploadbackupfile', 'local_backup'), null, array('maxbytes' => 50*1024*1024, 'accepted_types' => '*.zip'));
        $mform->addHelpButton('importfile', 'uploadbackupfile', 'local_backup', get_string('uploadbackupfile_help_title', 'local_backup'));
        $this->add_action_buttons(false, get_string('import'));
    }
}
