<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\form;

use core_text;
use csv_import_reader;
use mod_assessment\model\role;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/csvlib.class.php');

class version_roles_add_file_form extends version_assignments_import_form
{

    /** @var role */
    protected role $role;

    protected function definition()
    {
        $this->_form->addElement('header', 'assignrolesviafileupload', get_string('assignrolesviafileupload', 'assessment'));
        $this->role = $this->_customdata['role'];

        $this->_form->addElement('hidden', 'id');
        $this->_form->setType('id', PARAM_INT);

        $this->_form->addElement('hidden', 'versionid');
        $this->_form->setType('versionid', PARAM_INT);

        $this->_form->addElement('hidden', 'role');
        $this->_form->setType('role', PARAM_INT);

        $this->_form->addElement('filepicker', 'file', get_string('uploadfile', 'assessment'), null, ['accepted_types' => ['.csv']]);
        $this->_form->addRule('file', null, 'required');

        $this->_form->addElement('select', 'delimiter', get_string('csvdelimiter', 'assessment'), csv_import_reader::get_delimiter_list());
        $this->_form->addElement('select', 'encoding', get_string('encoding', 'assessment'), core_text::get_encodings());

        $this->add_import_options();

        $this->add_action_buttons(true, get_string('preview', 'assessment'));
    }
}
