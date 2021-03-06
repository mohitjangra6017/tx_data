<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2014 onwards Totara Learning Solutions LTD
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
 * @author Alastair Munro <alastair.munro@totaralms.com>
 * @package mod_facetoface
 */

namespace mod_facetoface\event;
defined('MOODLE_INTERNAL') || die();

/**
 * Event triggered when a booking requests have been approved.
 *
 * @property-read array $other {
 * Extra information about the event.
 *
 * - sessionid Session ID where the attendance requests were made.
 * - userids List of users ID's whose requests were approved.
 *
 * }
 *
 * @author Alastair Munro <alastair.munro@totaralms.com>
 * @package mod_facetoface
 */
class booking_requests_approved extends \core\event\base {

    /** @var bool Flag for prevention of direct create() call. */
    protected static $preventcreatecall = true;

    /**
     * Create from data.
     *
     * @param array $requestdata
     * @param \context_module $context
     * @return booking_requests_approved
     */
    public static function create_from_data(array $requestdata, \context_module $context) {
        $data = array(
            'context' => $context,
            'other' => $requestdata, // WARNING: Don't ever copy this pattern! Always provide explicit mapping.
        );

        self::$preventcreatecall = false;
        /** @var booking_requests_approved $event */
        $event = self::create($data);
        self::$preventcreatecall = true;

        return $event;
    }

    /**
     * Init method
     *
     * @return void
     */
    protected function init() {
        $this->data['crud'] = 'u';
        $this->data['edulevel'] = self::LEVEL_TEACHING;
    }

    /**
     * Return localised event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('eventattendancerequestsapproved', 'facetoface');
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        $id = $this->other['sessionid'];
        return "Attendance requests have been approved for session with the id {$id} by the user with id {$this->userid}.";
    }

    /**
     * Returns relevant URL.
     *
     * @return \moodle_url
     */
    public function get_url() {
        return new \moodle_url('/mod/facetoface/attendees/view.php', array('s' => $this->other['sessionid']));
    }

    /**
     * Return the legacy event log data.
     *
     * @return array
     */
    public function get_legacy_logdata() {
        return array($this->courseid, 'facetoface', 'approve requests',"attendance.php?s={$this->other['sessionid']}",
            $this->other['sessionid'], $this->contextinstanceid);
    }

    /**
     * Custom validation.
     *
     * @return void
     */
    protected function validate_data() {
        if (self::$preventcreatecall) {
            throw new \coding_exception('cannot call create() directly, use create_from_data() instead.');
        }

        parent::validate_data();

        if (!isset($this->other['sessionid'])) {
            throw new \coding_exception('sessionid must be set in $other.');
        }

        if (!isset($this->other['userids']) || empty($this->other['userids'])) {
            throw new \coding_exception('userids must be set in $other and cannot be empty.');
        }
    }
}
