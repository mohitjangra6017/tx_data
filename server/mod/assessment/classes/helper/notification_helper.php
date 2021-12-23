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
 * Helper for notifications
 *
 * @package     mod_assessment
 * @author      Russell England <Russell.England@kineo.com>
 * @copyright   2020 Kineo Group Inc. <http://www.kineo.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

namespace mod_assessment\helper;

use moodle_database;

defined('MOODLE_INTERNAL') || die();

class notification_helper
{

    /**
     * Returns an array of message providers.
     *
     * @param int $assessmentid
     * @param bool $unused
     * @return array type => description
     * @global moodle_database $DB
     */
    public static function get_message_types(int $assessmentid, $unused = true): array
    {
        global $DB;

        $sql = "SELECT p.name AS messagetype, p.name AS messagename
                FROM {message_providers} p
                WHERE p.component = :component";

        $params = array('component' => 'mod_assessment');

        if ($unused) {
            $sql .= "\nAND NOT EXISTS (
                        SELECT n.messagetype
                        FROM {assessment_notification} n
                        WHERE n.messagetype = p.name
                        AND n.assessmentid = :assessmentid
                    )";

            $params['assessmentid'] = $assessmentid;
        }

        if ($options = $DB->get_records_sql_menu($sql, $params)) {
            foreach ($options as $messagetype => $ignore) {
                $options[$messagetype] = self::get_message_type_name($messagetype);
            }

            // Sort by description.
            asort($options);
        }

        return $options;
    }

    /**
     * Returns the human name for the message type
     *
     * @param string $messagetype
     * @return string
     */
    public static function get_message_type_name($messagetype)
    {
        // Try a custom name.
        $stringid = 'msg:' . $messagetype;
        if (!get_string_manager()->string_exists($stringid, 'mod_assessment')) {
            // Default to the message provider name - this string must exist.
            $stringid = 'messageprovider:' . $messagetype;
        }

        $messagename = get_string($stringid, 'mod_assessment');

        return $messagename;

    }

}