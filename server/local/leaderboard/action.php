<?php

require_once(dirname(dirname(__DIR__)) . '/config.php');

if (!has_capability('local/leaderboard:config', context_system::instance())) {
    print_error('accessdenied', 'admin');
}

use local_leaderboard\Repository\LeaderboardRepository;

$scoreId = required_param('id', PARAM_INT);
$return = required_param('return', PARAM_LOCALURL);
$delete = optional_param('delete', 0, PARAM_INT);

require_login();
require_sesskey();

global $DB;
$repo = new LeaderboardRepository($DB, $scoreId);

if (!empty($delete)) {
    $repo->softDelete();
}

redirect(new \moodle_url($return));
