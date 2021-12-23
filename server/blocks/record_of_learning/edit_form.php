<?php

require_once(__DIR__ . '/block_record_of_learning.php');

defined('MOODLE_INTERNAL') || die;

class block_record_of_learning_edit_form extends block_edit_form
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
     * Provide specific definition for this block.
     *
     * @param MoodleQuickForm $mform
     * @throws coding_exception
     */
    protected function specific_definition($mform)
    {
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // Block title was added as common block setting in Totara 12.
        $mform->addElement('text', 'config_title', get_string('config_title', 'block_record_of_learning'));
        $mform->setDefault('config_title', get_string('config_title:default', 'block_record_of_learning'));
        $mform->setType('config_title', PARAM_TEXT);

        $mform->addElement('editor', 'config_body', get_string('config_body', 'block_record_of_learning'));
        $mform->setDefault('config_body', get_string('config_body:default', 'block_record_of_learning'));

        $mform->addElement('editor', 'config_nocontent', get_string('config_nocontent', 'block_record_of_learning'));
        $mform->setDefault('config_nocontent', get_string('config_nocontent:default', 'block_record_of_learning'));

        $mform->addElement('text', 'config_count', get_string('config_count', 'block_record_of_learning'));
        $mform->setDefault('config_count', get_string('config_count:default', 'block_record_of_learning'));
        $mform->setType('config_count', PARAM_INT);

        $mform->addElement('advcheckbox', 'config_include_complete', get_string('config_include_complete', 'block_record_of_learning'));

        $columns = array();
        foreach (block_record_of_learning::block_record_of_learning_get_columns() as $column => $string) {
            $columns[] = &$mform->createElement('advcheckbox', $column, '', $string);
        }
        $mform->addGroup($columns, 'config_show_columns', get_string('showcolumns', 'block_record_of_learning'));

        $sort = array();
        $sort[] = &$mform->createElement('select', 'config_sort_column', get_string('sortcolumn', 'block_record_of_learning'),
            array(0 => get_string('default')) + block_record_of_learning::block_record_of_learning_get_columns());
        $sort[] = &$mform->createElement('radio', 'config_sort_direction', '', get_string('sortasc', 'block_record_of_learning'), 1);
        $sort[] = &$mform->createElement('radio', 'config_sort_direction', '', get_string('sortdesc', 'block_record_of_learning'), 0);
        $mform->addGroup($sort, '', get_string('sortby'), ' ', false);

        $mform->addElement(
            'select',
            'config_linklocation',
            get_string('link:target', 'block_record_of_learning'),
            array(
                'rol' => get_string('link:target:rol', 'block_record_of_learning'),
                'rol.all' => get_string('link:target:rol.all','block_record_of_learning'),
                'my' => get_string('link:target:my', 'block_record_of_learning')
            )
        );
        $mform->setDefault('config_linklocation', 'rol');
    }
}
