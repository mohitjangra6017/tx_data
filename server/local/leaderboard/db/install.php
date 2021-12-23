<?php

use local_leaderboard\Utils;

defined('MOODLE_INTERNAL') || die();

function xmldb_local_leaderboard_install()
{
    global $DB;

    $DB->insert_records(
        'local_leaderboard',
        [
            [
                'plugin' => 'local_leaderboard',
                'eventname' => Utils::EVENT_NAME_ADHOC,
                'usegrade' => 0,
                'timemodified' => time(),
            ],
        ]
    );
}