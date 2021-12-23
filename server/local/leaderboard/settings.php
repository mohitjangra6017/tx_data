<?php

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {

    $ADMIN->add(
        'localplugins',
        new admin_category('local_leaderboard_folder', get_string('settings:title', 'local_leaderboard'))
    );

    $ADMIN->add(
        'local_leaderboard_folder',
        new admin_externalpage(
            'local_leaderboard_config',
            get_string('settings:config', 'local_leaderboard'),
            new moodle_url('/local/leaderboard/index.php'),
            ['local/leaderboard:config']
        )
    );

    $ADMIN->add(
        'local_leaderboard_folder',
        new admin_externalpage(
            'local_leaderboard_adhoc',
            get_string('settings:adhoc', 'local_leaderboard'),
            new moodle_url('/local/leaderboard/addscores.php'),
            ['local/leaderboard:allocate']
        )
    );
}
