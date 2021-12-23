<?php
/**
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010-2020 Kineo Group Inc.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Form for assessment due dates
 *
 * @package     mod_assessment
 * @author      Russell England <Russell.England@kineo.com>
 * @copyright   2020 Kineo Group Inc. <http://www.kineo.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

namespace mod_assessment\form;

use mod_assessment\helper\assessment_due_helper;
use moodleform;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class assessment_due_form extends moodleform
{

    protected function definition()
    {
        $mform = $this->_form;
        $version = $this->_customdata['version'];

        // Assessment id.
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'versionid');
        $mform->setType('versionid', PARAM_INT);

        $duetypes = assessment_due_helper::get_duetypes();
        $mform->addElement('select', 'duetype', get_string('label:duetype', 'assessment'), $duetypes);
        $mform->setType('duetype', PARAM_INT);
        $mform->addHelpButton('duetype', 'label:duetype', 'assessment');

        $mform->addElement('date_time_selector', 'timedue', get_string('label:timedue', 'assessment'));
        $mform->addHelpButton('timedue', 'label:timedue', 'assessment');
        $mform->disabledIf('timedue', 'duetype', 'neq', assessment_due_helper::DUE_TYPE_FIXED);

        $periodfields = array();

        $periodfields[] = &$mform->createElement('text', 'dueqty', '');

        $periodtypes = assessment_due_helper::get_period_types();

        $periodfields[] = &$mform->createElement('select', 'dueperiod', '', $periodtypes);

        $mform->addGroup($periodfields, 'periodgroup', get_string('label:periodgroup', 'assessment'), array(' '), false);
        $mform->addHelpButton('periodgroup', 'label:periodgroup', 'assessment');

        // Check qty is numeric.
        $mform->addGroupRule('periodgroup',
            array('dueqty' => array(
                array(null, 'numeric', null, 'client'))
            )
        );

        $mform->setType('dueqty', PARAM_INT);
        $mform->disabledIf('dueqty', 'duetype', 'eq', assessment_due_helper::DUE_TYPE_NONE);
        $mform->disabledIf('dueqty', 'duetype', 'eq', assessment_due_helper::DUE_TYPE_FIXED);

        $mform->setType('dueperiod', PARAM_ALPHA);
        $mform->disabledIf('dueperiod', 'duetype', 'eq', assessment_due_helper::DUE_TYPE_NONE);
        $mform->disabledIf('dueperiod', 'duetype', 'eq', assessment_due_helper::DUE_TYPE_FIXED);

        $datefields = ['' => get_string('choose')] + assessment_due_helper::get_date_profile_fields();
        $mform->addElement('select', 'duefieldid', get_string('label:duefieldid', 'assessment'), $datefields);
        $mform->setType('duefieldid', PARAM_INT);
        $mform->addHelpButton('duefieldid', 'label:duefieldid', 'assessment');
        $mform->disabledIf('duefieldid', 'duetype', 'neq', assessment_due_helper::DUE_TYPE_PROFILE_FIELD);

        $this->add_action_buttons();

        // Copied from the content form.
        if ($version->is_draft()) {
            // Disable everything!
            $mform->freeze();
        }
    }

}
