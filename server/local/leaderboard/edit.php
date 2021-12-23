<?php

require_once(dirname(dirname(__DIR__)) . '/config.php');
require_once(__DIR__ . '/lib.php');
require_once(dirname(dirname(__DIR__)) . '/lib/datalib.php');

use local_leaderboard\Form\EditLeaderboardForm;
use local_leaderboard\Repository\LeaderboardRepository;
use local_leaderboard\Utils;

global $PAGE, $DB, $OUTPUT;

require_login();
$context = context_system::instance();
require_capability('local/leaderboard:config', $context);

$scoreId = optional_param('id', 0, PARAM_INT);
$repo = new LeaderboardRepository($DB, $scoreId);
$returnUrl = new \moodle_url('/local/leaderboard/index.php');

$PAGE->set_context($context);
$PAGE->set_url(new \moodle_url('/local/leaderboard/edit.php'));
$PAGE->set_title(get_string('settings:title', 'local_leaderboard'));

$eventlist = tool_monitor\eventlist::get_all_eventlist(true);
$pluginlist = tool_monitor\eventlist::get_plugin_list();

// Have to filter on grades back to front as forms disabled if 'not in' not supported
$nonGradeEvents = $eventlist;
foreach (Utils::GRADE_EVENTS as $event) {
    unset($nonGradeEvents[$event]);
}

$configuredEvents = Utils::getConfiguredEvents();

$eventlist = array_filter(
    $eventlist,
    function ($classname) use ($configuredEvents, $scoreId) {
        return !$classname::is_deprecated() && (!isset($configuredEvents[$classname]) || $configuredEvents[$classname] == $scoreId);
    },
    ARRAY_FILTER_USE_KEY
);

$eventlist = array_merge(['' => get_string('choosedots')], $eventlist);
$pluginlist = array_merge(['' => get_string('choosedots')], $pluginlist);

$form = new EditLeaderboardForm(
    null, [
    'eventlist' => $eventlist,
    'pluginlist' => $pluginlist,
    'scoreid' => $scoreId,
    'frequencyoptions' => Utils::FREQUENCY_OPTIONS,
]
);

if (!empty($scoreId)) {
    $data = $repo->get();
    [$data->count, $data->frequency] = formatFrequency((int)$data->frequency);
    $form->set_data($data);
}

$PAGE->requires->yui_module(
    'moodle-local_leaderboard-dropdown',
    'Y.M.local_leaderboard.DropDown.init',
    [['eventlist' => $eventlist, 'pluginlist' => $pluginlist]]
);

if ($form->is_cancelled()) {
    redirect($returnUrl);
} else if ($data = $form->get_data()) {

    $data->frequency = calculateFrequency((int)$data->count, (int)$data->frequency);
    $data->timemodified = time();
    unset($data->submitbutton);
    unset($data->count);

    $currentRecord = $scoreId ? $DB->get_record('local_leaderboard', ['id' => $scoreId]) : false;

    if (!empty($scoreId)) {
        $repo->update($data);
    } else {
        unset($data->id);
        $repo->create($data);
    }

    if ($currentRecord) {
        foreach ($data as $column => $value) {
            if ($value != $currentRecord->$column) {
                add_to_config_log('score_updated', $currentRecord->$column, $value, 'local_leaderboard');
            }
        }
    }

    redirect($returnUrl);
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('settings:addnewscore', 'local_leaderboard'));
$form->display();
echo $OUTPUT->footer();
