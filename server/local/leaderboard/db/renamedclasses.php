<?php
/**
 * @copyright City & Guilds Kineo 2019
 * @author Simon Adams <simon.adams@kineo.com>
 */

defined('MOODLE_INTERNAL') || die();

$renamedclasses = [
    'totara_reportbuilder\\rb\\aggregate\\rank' => 'local_leaderboard\\rb\\aggregate\\rank',
    'totara_reportbuilder\\rb\\content\\leaderboard_date' => 'local_leaderboard\\rb\\content\\leaderboard_date_content',
];
