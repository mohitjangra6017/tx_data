<?php

class block_rate_course_edit_form extends block_edit_form {
    protected function specific_definition($mform) {

        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('advcheckbox', 'config_auto_create_self_enrol', get_string('auto_create_self_enrol', 'block_rate_course'));
        $mform->addHelpButton('config_auto_create_self_enrol', 'auto_create_self_enrol', 'block_rate_course');
        $mform->setDefault('config_auto_create_self_enrol', false);
        $mform->setType('config_auto_create_self_enrol', PARAM_BOOL);

        $mform->addElement('advcheckbox', 'config_showbuttons', get_string('showbuttons', 'block_rate_course'));
        $mform->addHelpButton('config_showbuttons', 'showbuttons', 'block_rate_course');
        $mform->setDefault('config_showbuttons', true);
        $mform->setType('config_showbuttons', PARAM_BOOL);

    }
}