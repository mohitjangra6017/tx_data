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
 * @author Petr Skoda <petr.skoda@totaralearning.com>
 * @package totara_userdata
 */

namespace totara_userdata\rb\display;

use \totara_reportbuilder\rb\display\base;

/**
 * Export name with link to details.
 */
final class export_type_fullnamelink extends base {
    /**
     * Display data.
     *
     * @param string $value
     * @param string $format
     * @param \stdClass $row
     * @param \rb_column $column
     * @param \reportbuilder $report
     * @return string
     */
    public static function display($value, $format, \stdClass $row, \rb_column $column, \reportbuilder $report) {
        if ($value === null) {
            return '';
        }

        $fullname = \totara_reportbuilder\rb\display\format_string::display($value, $format, $row, $column, $report);

        if ($format !== 'html') {
            return $fullname;
        }

        if (has_capability('totara/userdata:viewexports', \context_system::instance())) {
            $extra = self::get_extrafields_row($row, $column);
            $url = new \moodle_url('/totara/userdata/export_type.php', array('id' => $extra->id));
            $fullname = \html_writer::link($url, $fullname);
        }

        return $fullname;
    }
}
