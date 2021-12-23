<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

defined('MOODLE_INTERNAL') || die();

/* List of handlers */

$tasks = [
    [
        'classname' => 'mod_assessment\task\update_role_assignments',
        'blocking' => 0,
        'minute' => '*/10', // every 10 mins
        'hour' => '*',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*'
    ],
    [
        'classname' => 'mod_assessment\task\send_assignment_notifications',
        'blocking' => 0,
        'minute' => '*/10', // every 10 mins
        'hour' => '*',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*'
    ],
];