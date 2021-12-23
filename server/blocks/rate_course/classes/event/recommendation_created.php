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
 * Event for when a new blog entry is added..
 *
 * @package    core
 * @copyright  2013 Ankit Agarwal
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_rate_course\event;

defined('MOODLE_INTERNAL') || die();

/**
 * Create review event.
 *
 * @package    block
 * @subpackage rate_course
 * @copyright  &copy; 2014 Nine Lanterns Pty Ltd  {@link http://www.ninelanterns.com.au}
 * @author     james.ballard
 * @version    1.0
 */
class recommendation_created extends \core\event\base {

    /** @var $recommendationentry A reference to the active review object. */
    protected $recommendationentry;

    /**
     * Set basic properties for the event.
     */
    protected function init() {
        global $DB;
        $this->recommendationentry = $DB->get_record('rate_course_recommendations', array('id' => $this->objectid));
        $this->data['objecttable'] = 'rate_course_recommendations';
        $this->data['crud'] = 'c';
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }

    /**
     * Returns localised general event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('eventrecommendationadded', 'block_rate_course');
    }

    /**
     * Returns non-localised event description with id's for admin use only.
     *
     * @return string
     */
    public function get_description() {
        return "The user with id '$this->userid' recommended the course with id '$this->courseid' to the user '.
            'with id '".$this->recommendationentry->usertoid."'";
    }

    /**
     * Returns relevant URL.
     * @return \moodle_url
     */
    public function get_url() {
        return new \moodle_url('/block/rate_course/recommend.php', array('courseid' => $this->courseid));
    }

    /**
     * Does this event replace legacy event?
     *
     * @return string legacy event name
     */
    public static function get_legacy_eventname() {
        return 'block_rate_course_recommend';
    }

    /**
     * Legacy event data if get_legacy_eventname() is not empty.
     *
     * @return \blog_entry
     */
    protected function get_legacy_eventdata() {
        return $this->recommendationentry;
    }

    /**
     * Replace add_to_log() statement.
     *
     * @return array of parameters to be passed to legacy add_to_log() function.
     */
    protected function get_legacy_logdata() {
        return array ($this->objectid, 'block_rate_course', 'recommend course', '/block/rate_course/rate.php?courseid=' . $this->courseid,
            $this->objectid);
    }
}
