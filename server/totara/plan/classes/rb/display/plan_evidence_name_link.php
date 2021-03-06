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
 * @package totara_plan
 * @deprecated since Totara 13
 */

namespace totara_plan\rb\display;
use totara_reportbuilder\rb\display\base;

/**
 * Display class intended for evidence name with link to the details page
 *
 * @author Simon Player <simon.player@totaralearning.com>
 * @package totara_plan
 * @deprecated since Totara 13
 */
class plan_evidence_name_link extends base {

    /**
     * Handles the display
     *
     * @deprecated since Totara 13
     *
     * @param string $value
     * @param string $format
     * @param \stdClass $row
     * @param \rb_column $column
     * @param \reportbuilder $report
     * @return string
     */
    public static function display($value, $format, \stdClass $row, \rb_column $column, \reportbuilder $report) {
        debugging('\totara_plan\rb\display\plan_evidence_name_link has been deprecated and is no longer used, please use totara_evidence\rb\display\evidence_item_name instead.', DEBUG_DEVELOPER);
        $isexport = ($format !== 'html');
        $extrafields = self::get_extrafields_row($row, $column);

        $value = \totara_reportbuilder\rb\display\format_string::display($value, $format, $row, $column, $report);

        if ($isexport) {
            return $value;
        } else {
            $url = new \moodle_url('/totara/plan/record/evidence/view.php', array('id' => $extrafields->evidence_id));
            $evidencename = empty($value) ? '(' . get_string('viewevidence', 'rb_source_dp_evidence') . ')' : $value;
            return \html_writer::link($url, $evidencename);
        }
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
