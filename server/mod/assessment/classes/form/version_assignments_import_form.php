<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\form;

use mod_assessment\model\role;
use mod_assessment\model\user_identifier;
use moodleform;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/formslib.php');

abstract class version_assignments_import_form extends moodleform
{
    public function add_import_options()
    {
        // Import options.
        $this->_form->addElement('header', 'importoptions', get_string('importoptions', 'assessment'));

        $this->_form->addElement('select', 'learneridfield', get_string('idfield:learner', 'assessment'), user_identifier::get_identifiers());
        $this->_form->addHelpButton('learneridfield', 'idfield:learner', 'assessment');

        if ($this->role->value() == role::EVALUATOR) {
            $this->_form->addElement('select', 'useridfield', get_string('idfield:evaluator', 'assessment'), user_identifier::get_identifiers());
            $this->_form->addHelpButton('useridfield', 'idfield:evaluator', 'assessment');
        } elseif ($this->role->value() == role::REVIEWER) {
            $this->_form->addElement('select', 'useridfield', get_string('idfield:reviewer', 'assessment'), user_identifier::get_identifiers());
            $this->_form->addHelpButton('useridfield', 'idfield:reviewer', 'assessment');
        }

        $this->_form->addElement(
            'select',
            'replaceassignments',
            get_string('import:addorreplace', 'assessment'),
            [
                0 => get_string('import:addassignments', 'assessment'),
                1 => get_string('import:replaceassignments', 'assessment')
            ]
        );
        $this->_form->addHelpButton('replaceassignments', 'import:addorreplace', 'assessment');
        $this->_form->setDefault('replaceassignments', get_config('assessment', 'replaceversionassignments'));

        $this->_form->addElement(
            'select',
            'autoenrol',
            get_string('import:autoenrol', 'assessment'),
            [
                0 => get_string('import:autoenrolskip', 'assessment'),
                1 => get_string('import:autoenroladd', 'assessment'),
            ]
        );
        $this->_form->addHelpButton('autoenrol', 'import:autoenrol', 'assessment');
        $this->_form->setDefault('autoenrol', get_config('assessment', 'autoenrol'));
    }
}
