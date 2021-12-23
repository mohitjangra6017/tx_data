<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * DEVIOTIS2
 *
 * This file adds the settings pages to the navigation menu
 *
 * @package   mod_assessement
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    $settings->add(new admin_setting_heading('activitydefaults', get_string('head:activitydefaults', 'mod_assessment'), ''));

    $name = new lang_string('label:singleevaluator', 'mod_assessment');
    $description = new lang_string('labeldesc:singleevaluator', 'mod_assessment');
    $settings->add(new admin_setting_configcheckbox('assessment/defaultsingleevaluator',
        $name,
        $description,
        1));

    $name = new lang_string('label:singlereviewer', 'mod_assessment');
    $description = new lang_string('labeldesc:singlereviewer', 'mod_assessment');
    $settings->add(new admin_setting_configcheckbox('assessment/defaultsinglereviewer',
        $name,
        $description,
        1));

    $name = new lang_string('label:hidegradeinoverview', 'mod_assessment');
    $description = new lang_string('labeldesc:hidegradeinoverview', 'mod_assessment');
    $settings->add(new admin_setting_configcheckbox('assessment/defaulthidegradeinoverview',
        $name,
        $description,
        0));

    $defaultcount = 100;
    $name = new lang_string('label:maximumuserwarningcount', 'mod_assessment');
    $description = new lang_string('labeldesc:maximumuserwarningcount', 'mod_assessment');
    $settings->add(new admin_setting_configtext('assessment/maximumuserwarningcount',
        $name,
        $description,
        $defaultcount));

    $settings->add(new admin_setting_heading('importdefaults', get_string('head:importdefaults', 'mod_assessment'), ''));
    $settings->add(new admin_setting_configselect(
        'assessment/replaceversionassignments',
        get_string('import:addorreplace', 'assessment'),
        get_string('import:addorreplace_help', 'assessment'),
        0,
        [
            0 => get_string('import:addassignments', 'assessment'),
            1 => get_string('import:replaceassignments', 'assessment')
        ]
    ));

    $settings->add(new admin_setting_configselect(
        'assessment/autoenrol',
        get_string('import:autoenrol', 'assessment'),
        get_string('import:autoenrol_help', 'assessment'),
        0,
        [
            0 => get_string('import:autoenrolskip', 'assessment'),
            1 => get_string('import:autoenroladd', 'assessment'),
        ]
    ));

}

