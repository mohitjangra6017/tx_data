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

use mod_facetoface\seminar_event;

defined('MOODLE_INTERNAL') || die();

/**
 * Event triggered when the attendance is updated.
 *
 * @property-read array $other {
 * Extra information about the event.
 *
 * - sessionid Session's ID.
 *
 * }
 *
 * @author Alastair Munro <alastair.munro@totaralms.com>
 * @package mod_facetoface
 */
class attendees_updated extends \core\event\base {

    /** @var bool Flag for prevention of direct create() call. */
    protected static $preventcreatecall = true;

    /**
     * Create from session.
     *
     * @param \stdClass $session
     * @param \context_module $context
     * @return attendees_updated
     */
    public static function create_from_session(\stdClass $session, \context_module $context) {
        $data = array(
            'context' => $context,
            'other' => array('sessionid' => $session->id),
        );

        self::$preventcreatecall = false;
        /** @var attendees_updated $event */
        $event = self::create($data);
        self::$preventcreatecall = true;

        return $event;
    }

    /**
     * Create from session.
     *
     * @param seminar_event $session
     * @param \context_module $context
     * @return attendees_updated
     */
    public static function create_from_seminar_event(seminar_event $seminarevent, \context_module $context) {
        $data = array(
            'context' => $context,
            'other' => array('sessionid' => $seminarevent->get_id()),
        );

        self::$preventcreatecall = false;
        /** @var attendees_updated $event */
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
        return get_string('eventattendeesedited', 'mod_facetoface');
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        return "The attendees for Session id {$this->other['sessionid']} has been edited by User with id {$this->userid}.";
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
        return array($this->courseid, 'facetoface', 'Add/remove attendees',
            "attendees/view.php?s={$this->other['sessionid']}", $this->other['sessionid'], $this->contextinstanceid);
    }

    /**
     * Custom validation.
     *
     * @return void
     */
    protected function validate_data() {
        if (self::$preventcreatecall) {
           throw new \coding_exception('cannot call create() directly, use create_from_session() instead.');
        }

        if (!isset($this->other['sessionid'])) {
            throw new \coding_exception('sessionid must be set in $other.');
        }

        parent::validate_data();
    }
}
