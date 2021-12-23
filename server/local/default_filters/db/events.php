<?php
/**
 * @copyright City & Guilds Kineo 2017
 * @author Michael Geering <michael.geering@kineo.com>
 */

defined('MOODLE_INTERNAL') || die;

$observers = [
    [
        'eventname' => '\core\event\user_loggedin',
        'callback' => 'local_default_filters\Observer\DefaultFiltersObserver::set_defaults_on_login',
    ]
];