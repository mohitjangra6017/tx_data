<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    $settings->add(
        new admin_setting_configcheckbox(
            'block_leaderboard/displaytotal',
            get_string('displaytotal', 'block_leaderboard'),
            get_string('displaytotal_desc', 'block_leaderboard'),
            false
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'block_leaderboard/leaderboardurl',
            get_string('leaderboardurl', 'block_leaderboard'),
            get_string('leaderboardurl_desc', 'block_leaderboard'),
            '',
            PARAM_URL
        )
    );
}