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
 * Mnet access control updated event.
 *
 * @package    core
 * @since      Moodle 2.7
 * @copyright  2013 Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace core\event;

defined('MOODLE_INTERNAL') || die();

/**
 * Mnet access control updated event class.
 *
 * @property-read array $other {
 *      Extra information about event.
 *
 *      - string username: the username of the user.
 *      - string hostname: the name of the host the user came from.
 *      - string accessctrl: the access control value.
 * }
 *
 * @package    core
 * @since      Moodle 2.7
 * @copyright  2013 Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mnet_access_control_updated extends base {

    /**
     * Init method.
     */
    protected function init() {
        $this->data['crud'] = 'u';
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }

    /**
     * Returns localised general event name.
     *
     * @return string
     */
    public static function get_name() {
        return 'mnet_access_control_updated';
    }

    /**
     * Returns relevant URL.
     *
     * @return \moodle_url
     */
    public function get_url() {
        return null;
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        return "The user with id '$this->userid' updated access control for the user with username '{$this->other['username']}' " .
            "belonging to mnet host '{$this->other['hostname']}'.";
    }

    public static function is_deprecated() {
        return true;
    }
}
