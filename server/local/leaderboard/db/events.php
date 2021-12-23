<?php
/**
 * Event's observer definition.
 *
 * @package   local_score
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2016 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname' => '*',
        'callback' => '\local_leaderboard\Observer::eventTriggered',
    ],
];