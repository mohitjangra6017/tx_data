<?php
global $CFG, $PAGE, $OUTPUT, $DB;

require_once(dirname(dirname(__DIR__)) . '/config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once(__DIR__ . '/lib.php');

use core\notification;
use local_leaderboard\Output\LeaderboardRenderer;
use local_leaderboard\Repository\LeaderboardRepository;

// Security checks.
admin_externalpage_setup('local_leaderboard_config');

// Page definition.
$url = new moodle_url('/local/leaderboard/index.php');
$PAGE->set_url($url);
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_title(get_string('settings:title', 'local_leaderboard'));

$jsurl = new moodle_url('/local/leaderboard/js/form.js');
$PAGE->requires->js($jsurl);

$renderer = new LeaderboardRenderer($PAGE, null);

// Form definition.
$form = new \local_leaderboard\Form\SettingsForm($url, getFormCustomData());

$defaults = [];
if ($courseMultiplierFieldId = get_config('local_leaderboard', 'coursemultiplierfieldid')) {
    $defaults['coursemultiplierfieldid'] = $courseMultiplierFieldId;
}

if ($progMultiplierFieldId = get_config('local_leaderboard', 'progmultiplierfieldid')) {
    $defaults['progmultiplierfieldid'] = $progMultiplierFieldId;
}

if ($excludeUsersFieldId = get_config('local_leaderboard', 'excludeusersfieldid')) {
    $defaults['excludeusersfieldid'] = $excludeUsersFieldId;
}

$activeOnly = get_config('local_leaderboard', 'activeonly');
$defaults['activeonly'] = $activeOnly ?? 0;

$usersWithScores = get_config('local_leaderboard', 'rankuserswithscores');
$defaults['rankuserswithscores'] = $usersWithScores ?? 0;

$form->set_data($defaults);

$repo = new LeaderboardRepository($DB);
$scores = $repo->fetchAll();
ksort($scores);

$table = new \html_table();
$table->head = ['Event', 'Award Threshold', 'Score', 'Grade', 'Manage'];
$data = [];

foreach ($scores as $key => $item) {
    $event = new html_table_cell($item->eventname);

    [$count, $frequency] = formatFrequency((int)$item->frequency);
    $time = formatReadableTimeInterval($count, $frequency);
    $threshold = new html_table_cell($time);

    $score = new html_table_cell($item->score);
    $usegrade = new html_table_cell($item->usegrade ? get_string('yes') : get_string('no'));
    $edit = new html_table_cell($renderer->printScoreTableActions($item->id, $context));
    $row = new html_table_row([$event, $threshold, $score, $usegrade, $edit]);
    $table->data[] = $row;
}

// Form processing - may need this if add a submit button. Leave for now.
if ($data = $form->get_data()) {
    notification::success(get_string('changessaved'));
}

// Page display.
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('settings:title:config', 'local_leaderboard'));
echo $OUTPUT->box(get_string('settings:description:config', 'local_leaderboard'));
$form->display();
echo $OUTPUT->heading(get_string('settings:header:config', 'local_leaderboard'), 3);
echo $OUTPUT->render($table);
echo $OUTPUT->single_button(new moodle_url('edit.php'), get_string('button:newevent', 'local_leaderboard'));
echo $OUTPUT->footer();