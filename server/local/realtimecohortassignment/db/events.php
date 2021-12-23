<?php
/**
 * Event and observer definitions.
 *
 * @package   local_realtimecohortassignment
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2016 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

$observers = array(
    array(
        'eventname'   => '\core\event\user_created',
        'callback'    => '\local_realtimecohortassignment\Observer::updateDynamicCohorts',
    ),
    array(
        'eventname'   => '\core\event\user_updated',
        'callback'    => '\local_realtimecohortassignment\Observer::updateDynamicCohorts',
    ),
    array(
        'eventname'   => '\core\event\user_loggedin',
        'callback'    => '\local_realtimecohortassignment\Observer::updateDynamicCohorts',
    ),
    array(
        'eventname'   => '\core\event\course_completed',
        'callback'    => '\local_realtimecohortassignment\Observer::updateDynamicCohorts',
    )
);