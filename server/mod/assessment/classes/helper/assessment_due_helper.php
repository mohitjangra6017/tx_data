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
 * Helper for assessment due dates
 *
 * @package     mod_assessment
 * @author      Russell England <Russell.England@kineo.com>
 * @copyright   2020 Kineo Group Inc. <http://www.kineo.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

namespace mod_assessment\helper;

use coding_exception;
use moodle_database;

defined('MOODLE_INTERNAL') || die();

class assessment_due_helper
{

    public const DUE_TYPE_NONE = 0;
    public const DUE_TYPE_FIXED = 1;
    public const DUE_TYPE_FIRST_LOGIN = 2;
    public const DUE_TYPE_ENROLLED = 3;
    public const DUE_TYPE_PROFILE_FIELD = 4;

    public const PERIOD_TYPE_DAYS = 'D';
    public const PERIOD_TYPE_WEEKS = 'W';
    public const PERIOD_TYPE_MONTHS = 'M';
    public const PERIOD_TYPE_YEARS = 'Y';

    public const PERIOD_QTY_DAYS = 1;
    public const PERIOD_QTY_WEEKS = 7;
    public const PERIOD_QTY_MONTHS = 28;
    public const PERIOD_QTY_YEARS = 365;

    /**
     * Returns an array of due types.
     *
     * @return array of due types
     * @throws coding_exception
     */
    public static function get_duetypes(): array
    {
        return array(
            self::DUE_TYPE_NONE => get_string('duetype:none', 'mod_assessment'),
            self::DUE_TYPE_FIXED => get_string('duetype:fixed', 'mod_assessment'),
            self::DUE_TYPE_FIRST_LOGIN => get_string('duetype:firstlogin', 'mod_assessment'),
            self::DUE_TYPE_ENROLLED => get_string('duetype:enrolled', 'mod_assessment'),
            self::DUE_TYPE_PROFILE_FIELD => get_string('duetype:profilefield', 'mod_assessment'),
        );
    }

    /**
     * Returns an array of custom user date fields as a menu.
     *
     * @return array of custom user date fields
     * @global moodle_database $DB
     */
    public static function get_date_profile_fields(): array
    {
        global $DB;

        $sql = "SELECT f.id, " . $DB->sql_concat('f.name', "' ('", 'f.shortname', "')'") . " AS fieldname
                FROM {user_info_field} f
                WHERE f.datatype IN (:datatype1, :datatype2)
                ORDER BY f.name";

        $params = array('datatype1' => 'date', 'datatype2' => 'datetime');

        return $DB->get_records_sql_menu($sql, $params);
    }

    /**
     * Convert due qty and period into days
     *
     * @param int $dueqty
     * @param string $dueperiod - one of D, W, M, Y
     * @return int
     */
    public static function get_days_from_period(int $dueqty, string $dueperiod)
    {
        $dueperiod = strtoupper($dueperiod);

        switch (strtoupper($dueperiod)) {
            case self::PERIOD_TYPE_DAYS :
                $duedays = $dueqty;
                break;
            case self::PERIOD_TYPE_WEEKS :
                $duedays = $dueqty * self::PERIOD_QTY_WEEKS;
                break;
            case self::PERIOD_TYPE_MONTHS :
                $duedays = $dueqty * self::PERIOD_QTY_MONTHS;
                break;
            case self::PERIOD_TYPE_YEARS :
                $duedays = $dueqty * self::PERIOD_QTY_YEARS;
                break;
            default :
                debugging('Unknown due date period type', DEBUG_DEVELOPER);
                $duedays = $dueqty;
                break;
        }

        return $duedays;

    }

    /**
     * Converts days into a period and qty.
     *
     * @param int|null $duedays
     * @return array period qty and period type
     */
    public static function get_period_from_days(?int $duedays): array
    {

        // Default to days.
        $period = array(
            'dueqty' => (int)$duedays,
            'dueperiod' => self::PERIOD_TYPE_DAYS,
        );

        if (!empty($duedays)) {
            // Attempt to convert to years, months or weeks.
            $periods = array(
                self::PERIOD_TYPE_YEARS => self::PERIOD_QTY_YEARS,
                self::PERIOD_TYPE_MONTHS => self::PERIOD_QTY_MONTHS,
                self::PERIOD_TYPE_WEEKS => self::PERIOD_QTY_WEEKS,
            );

            foreach ($periods as $type => $qty) {
                if (($duedays % $qty) == 0) {
                    $period = array(
                        'dueqty' => $duedays / $qty,
                        'dueperiod' => $type,
                    );
                    break;
                }
            }
        }

        return $period;
    }

    /**
     * Returns an array of period types
     *
     * @return array
     */
    public static function get_period_types(): array
    {
        return array(
            self::PERIOD_TYPE_DAYS => get_string('period:days', 'assessment'),
            self::PERIOD_TYPE_WEEKS => get_string('period:weeks', 'assessment'),
            self::PERIOD_TYPE_MONTHS => get_string('period:months', 'assessment'),
            self::PERIOD_TYPE_YEARS => get_string('period:years', 'assessment'),
        );
    }
}