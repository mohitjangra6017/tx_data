<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\form;

use mod_assessment\model\role;
use mod_assessment\model\user_identifier;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/csvlib.class.php');

class version_roles_verify_form extends version_assignments_import_form
{

    /** @var role */
    protected role $role;

    protected function definition()
    {
        $this->role = $this->_customdata['role'];

        $this->_form->addElement('hidden', 'id');
        $this->_form->setType('id', PARAM_INT);

        $this->_form->addElement('hidden', 'versionid');
        $this->_form->setType('versionid', PARAM_INT);

        $this->_form->addElement('hidden', 'role');
        $this->_form->setType('role', PARAM_INT);

        $this->_form->addElement('hidden', 'importid');
        $this->_form->setType('importid', PARAM_INT);

        $this->add_import_options();

        $this->add_action_buttons(true, get_string('continue'));
    }

    public function add_action_buttons($cancel = true, $submitlabel = null)
    {
        $mform =& $this->_form;
        $buttonarray = array();
        $buttonarray[] = &$mform->createElement('submit', 'showpreview', get_string('preview', 'assessment'));
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', $submitlabel);
        $buttonarray[] = &$mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('buttonar');
    }
}
