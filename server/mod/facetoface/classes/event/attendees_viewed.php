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
 * Event triggered when the attendance list is viewed.
 *
 * @property-read array $other {
 * Extra information about the event.
 *
 * - sessionid Session's ID.
 * - section Refers to the section(tab) viewed in the attendees page.
 *
 * }
 *
 * @author Alastair Munro <alastair.munro@totaralms.com>
 * @package mod_facetoface
 */
class attendees_viewed extends \core\event\base {

    /** @var bool Flag for prevention of direct create() call. */
    protected static $preventcreatecall = true;

    /**
     * Create from session.
     *
     * @param \stdClass $session
     * @param \context_module $context
     * @param string $section The section viewed
     * @return attendees_viewed
     */
    public static function create_from_session(\stdClass $session, \context_module $context, $section) {
        $data = array(
            'context' => $context,
            'other' => array(
                'sessionid' => $session->id,
                'section' => $section
            )
        );

        self::$preventcreatecall = false;
        /** @var attendees_viewed $event */
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
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }

    /**
     * Return localised event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('eventattendeesviewed', 'mod_facetoface');
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        $description = "The user with id {$this->userid} viewed attendance for session with id {$this->other['sessionid']}. ";
        if (!empty($this->other['section'])) {
            $description .= ' Section viewed: ' . $this->other['section'];
        }
        return $description;
    }

    /**
     * Returns relevant URL.
     *
     * @return \moodle_url
     */
    public function get_url() {
        $params = array('s' => $this->other['sessionid']);
        if (!empty($this->other['section'])) {
            $params['action'] = $this->other['section'];
        }
        return new \moodle_url('/mod/facetoface/attendees/view.php', $params);
    }

    /**
     * Return the legacy event log data.
     *
     * @return array
     */
    public function get_legacy_logdata() {
        $s = $this->other['sessionid'];
        return [
            $this->courseid,
            'facetoface',
            'view attendance',
            "attendees/view.php?s=$s",
            $this->other['sessionid'],
            $this->contextinstanceid
        ];
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
