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
 *
 * @package    block
 * @subpackage rate_course
 * @copyright  2009 Jenny Gray
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * Code was Rewritten for Moodle 2.X By Atar + Plus LTD for Comverse LTD.
 * @copyright &copy; 2011 Comverse LTD.
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

defined('MOODLE_INTERNAL') || die;

$settings->add(
    new admin_setting_configcheckbox(
        'block_rate_course/showbuttons',
        get_string('showbuttons', 'block_rate_course'),
        get_string('showbuttons_help', 'block_rate_course'),
        true
    )
);
$settings->add(
    new admin_setting_configcheckbox(
        'block_rate_course/auto_create_self_enrol',
        get_string('auto_create_self_enrol', 'block_rate_course'),
        get_string('auto_create_self_enrol_help', 'block_rate_course'),
        false
    )
);
$settings->add(
    new admin_setting_configcheckbox(
        'block_rate_course/enable_course_suggestions',
        get_string('enable_course_suggestions', 'block_rate_course'),
        get_string('enable_course_suggestions_help', 'block_rate_course'),
        true
    )
);
$settings->add(
    new admin_setting_configcheckbox(
        'block_rate_course/exclude_zero_star_rating',
        get_string('exclude_zero_star_rating', 'block_rate_course'),
        get_string('exclude_zero_star_rating_help', 'block_rate_course'),
        false
    )
);
$records = $DB->get_records('user_info_field');
$choices = [0 => get_string('norestrictions', 'block_rate_course')];
foreach ($records as $record) {
    $choices[$record->id] = $record->name;
}
$settings->add(
    new admin_setting_configselect(
        'block_rate_course/limitrecommend',
        get_string('limitrecommend', 'block_rate_course'),
        get_string('limitrecommenddesc', 'block_rate_course'),
        0,
        $choices
    )
);
$settings->add(
    new admin_setting_configcheckbox(
        'block_rate_course/enablecomments',
        get_string('enablecomments', 'block_rate_course'),
        get_string('enablecomments_help', 'block_rate_course'),
        false
    )
);