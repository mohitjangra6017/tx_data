<?php
/*
 * This file is part of Totara Learn
 *
 * Copyright (C) 2018 onwards Totara Learning Solutions LTD
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
 *
 * @author Simon Player <simon.player@totaralearning.com>
 * @package totara_reportbuilder
 */

namespace totara_reportbuilder\rb\display;

/**
 * Display class intended for showing a user's profile picture, name and links to their profile.
 * When exporting, only the user's full name is displayed (without link)
 *
 * @author Simon Player <simon.player@totaralearning.com>
 * @package totara_reportbuilder
 */
class user_icon_link extends base {

    /**
     * Handles the display
     *
     * @param string $value
     * @param string $format
     * @param \stdClass $row
     * @param \rb_column $column
     * @param \reportbuilder $report
     * @return string
     */
    public static function display($value, $format, \stdClass $row, \rb_column $column, \reportbuilder $report) {
        global $OUTPUT, $PAGE, $DB;

        // Extra fields expected are fields from totara_get_all_user_name_fields_join() and totara_get_all_user_name_fields_join()
        $extrafields = self::get_extrafields_row($row, $column);
        $isexport = ($format !== 'html');

        if (isset($extrafields->user_id)) {
            debugging('Invalid extra fields detected in report source for user_icon_link display method .', DEBUG_DEVELOPER);
            // Some ancient legacy stuff.
            return clean_string($value);
        }

        $fullname = fullname($extrafields);
        if (empty($fullname)) {
            return '';
        }

        $userid = $extrafields->id;
        if ($isexport || $userid == 0) {
            return \core_text::entities_to_utf8($fullname);
        }

        if (CLI_SCRIPT && !PHPUNIT_TEST) {
            $course = null;
            $courseid = SITEID;
        } else {
            $course = $PAGE->course;
            $courseid = $course->id;
        }

        $link = false;
        $url = user_get_profile_url($userid, $course);
        if ($url) {
            $fullname = \html_writer::link($url, $fullname);
            $link = true;
        }

        return $OUTPUT->user_picture($extrafields, ['courseid' => $courseid, 'link' => $link]) . "&nbsp;" . $fullname;
    }

    /**
     * Is this column graphable?
     *
     * @param \rb_column $column
     * @param \rb_column_option $option
     * @param \reportbuilder $report
     * @return bool
     */
    public static function is_graphable(\rb_column $column, \rb_column_option $option, \reportbuilder $report) {
        return false;
    }
}
