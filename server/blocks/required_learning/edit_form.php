<?php

defined('MOODLE_INTERNAL') || die;

class block_required_learning_edit_form extends block_edit_form
{
    /**
     * Enable general settings
     *
     * @return bool
     */
    protected function has_general_settings()
    {
        return true;
    }

    /**
     * @param MoodleQuickForm $mform
     * @throws coding_exception
     */
    protected function specific_definition($mform)
    {
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('editor', 'config_body', get_string('config_body', 'block_required_learning'));
        $mform->setDefault('config_body', get_string('config_body:default', 'block_required_learning'));

        $mform->addElement('editor', 'config_nocontent', get_string('config_nocontent', 'block_required_learning'));
        $mform->setDefault('config_nocontent', get_string('config_nocontent:default', 'block_required_learning'));

        $mform->addElement('text', 'config_count', get_string('config_count', 'block_required_learning'));
        $mform->setDefault('config_count', get_string('config_count:default', 'block_required_learning'));
        $mform->setType('config_count', PARAM_INT);

        $options = ['prog' => get_string('program', 'totara_program'), 'course' => get_string('course')];
        $mform->addElement('select', 'config_show', get_string('config_show', 'block_required_learning'), $options);
        $mform->setDefault('config_show', 'prog');

        $mform->addElement('advcheckbox', 'config_show_header', get_string('config_show_header', 'block_required_learning'));
        $mform->setDefault('config_show_header', 0);

        $mform->addElement('advcheckbox', 'config_show_rl_button', get_string('config_show_rl_button', 'block_required_learning'));
        $mform->setDefault('config_show_rl_button', 1);

        $mform->addElement('advcheckbox', 'config_show_program', get_string('config_show_program', 'block_required_learning'));
        $mform->setDefault('config_show_program', 0);

        $mform->addElement('advcheckbox', 'config_show_date', get_string('config_show_date', 'block_required_learning'));
        $mform->addHelpButton('config_show_date', 'show_date', 'block_required_learning');
        $mform->setDefault('config_show_date', 1);

        $curtime = time();
        $types = [
            'strftimedatetime' => strftime(get_string('strftimedatetime', 'langconfig'), $curtime),
            'strfdateattime' => strftime(get_string('strfdateattime', 'langconfig'), $curtime),
            'strftimedate' => strftime(get_string('strftimedate', 'langconfig'), $curtime),
            'strftimedatefullshort' => strftime(get_string('strftimedatefullshort', 'langconfig'), $curtime),
            'strfdateshortmonth' => strftime(get_string('strfdateshortmonth', 'langconfig'), $curtime),
        ];
        $mform->addElement('select', 'config_date_format', get_string('config_date_format', 'block_required_learning'), $types);

        $types = [
            'default' => get_string('config_progresstype:default', 'block_required_learning'),
            'radial' => get_string('config_progresstype:radial', 'block_required_learning'),
        ];
        $mform->addElement('select', 'config_progresstype', get_string('config_progresstype', 'block_required_learning'), $types);
        $mform->setDefault('config_progresstype', 'default');
        $mform->setType('config_progresstype', PARAM_ALPHANUM);

        $mform->disabledIf('config_progresstype', 'config_show', 'eq', 'course');
        $mform->disabledIf('config_show_program', 'config_show', 'eq', 'prog');
    }
}