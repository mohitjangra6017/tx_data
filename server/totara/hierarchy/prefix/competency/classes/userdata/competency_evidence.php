<?php
/**
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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Andrew McGhie <andrew.mcghie@totaralearning.com>
 * @package totara_hierarchy
 */

namespace hierarchy_competency\userdata;

use totara_userdata\userdata\export;
use totara_userdata\userdata\item;
use totara_userdata\userdata\target_user;


/**
 * Class competency_evidence
 * handles the data for the progress the user has made on a competency
 * @deprecated since Totara 13
 */
class competency_evidence extends item {

    // To allow the userdata unit tests to succeed, we can't output the deprecation message at the top if as all userdata
    // class files are imported during the tests.
    private static function is_deprecated() {
        debugging('hierarchy_competency\userdata\competency_evidence has been deprecated, please use totara_competency\userdata\achievement instead.', DEBUG_DEVELOPER);
    }

    /**
     * Get main Frankenstyle component name (core subsystem or plugin).
     * This is used for UI purposes to group items into components.
     */
    public static function get_main_component() {
        return 'totara_competency';
    }

    /**
     * Returns sort order.
     *
     * @return int
     */
    public static function get_sortorder() {
        return 1; // 2nd item of 6 in the 'Competencies' list.
    }

    /**
     * Can user data of this item data be purged from system?
     *
     * @param int $userstatus target_user::STATUS_ACTIVE, target_user::STATUS_DELETED or target_user::STATUS_SUSPENDED
     * @return bool
     */
    public static function is_purgeable(int $userstatus) {
        // This is now replaced by totara_competency\userdata\achievement
        return false;
    }

    /**
     * Purge user data for this item.
     *
     * NOTE: Remember that context record does not exist for deleted users any more,
     *       it is also possible that we do not know the original user context id.
     *
     * @param target_user $user
     * @param \context $context restriction for purging e.g., system context for everything, course context for purging one course
     * @return int result self::RESULT_STATUS_SUCCESS, self::RESULT_STATUS_ERROR or self::RESULT_STATUS_SKIPPED
     */
    protected static function purge(target_user $user, \context $context) {
        global $DB;

        static::is_deprecated();
        $DB->delete_records('comp_record_history', ['userid' => $user->id]);
        $DB->delete_records('comp_record', ['userid' => $user->id]);
        $DB->delete_records('block_totara_stats', [
            'userid' => $user->id,
            'eventtype' => STATS_EVENT_COMP_ACHIEVED
        ]);
        return self::RESULT_STATUS_SUCCESS;
    }

    /**
     * Can user data of this item data be exported from the system?
     *
     * @return bool
     */
    public static function is_exportable() {

        // This is now replaced by totara_competency\userdata\achievement
        return false;
    }

    /**
     * Export user data from this item.
     *
     * @param target_user $user
     * @param \context $context restriction for exporting i.e., system context for everything and course context for course export
     * @return \totara_userdata\userdata\export|int result object or integer error code self::RESULT_STATUS_ERROR or self::RESULT_STATUS_SKIPPED
     */
    protected static function export(target_user $user, \context $context) {
        global $DB;

        static::is_deprecated();

        $export = new export();
        $export->data = $DB->get_records('comp_record', ['userid' => $user->id]);
        foreach ($export->data as &$record) {
            $record->history = $DB->get_records('comp_record_history', [
                'userid' => $user->id,
                'competencyid' => $record->competencyid
            ]);
        }
        return $export;
    }


    /**
     * Can user data of this item be somehow counted?
     * How much data is there?
     *
     * @return bool
     */
    public static function is_countable() {
        return false;
    }

    /**
     * Count user data for this item.
     *
     * @param target_user $user
     * @param \context $context restriction for counting i.e., system context for everything and course context for course data
     * @return int amount of data or negative integer status code (self::RESULT_STATUS_ERROR or self::RESULT_STATUS_SKIPPED)
     */
    protected static function count(target_user $user, \context $context) {
        global $DB;

        static::is_deprecated();
        return $DB->count_records('comp_record', ['userid' => $user->id]);
    }
}
