<?php

define('AJAX_SCRIPT', true);

require_once(dirname(dirname(__DIR__)) . '/config.php');
require_once(__DIR__ . '/lib.php');

global $PAGE;

require_login();
require_sesskey();

$field = required_param('field', PARAM_ALPHANUMEXT);
$value = required_param('value', PARAM_INT);

if (!has_capability('local/leaderboard:config', context_system::instance())) {
    print_error('accessdenied', 'admin');
    $result = new \stdClass();
    $result->field = $field;
    $result->message = get_string('accessdenied', 'admin');
    echo json_encode($result);
    die();
}

$validFields = [];

// Instantiate form and iterate through to get valid form values.
$form = new \local_leaderboard\Form\SettingsForm(new moodle_url('/local/leaderboard/index.php'), getFormCustomData());
foreach ($form->_form->_elements as $element) {
    if ($element instanceof MoodleQuickForm_advcheckbox || $element instanceof MoodleQuickForm_select) {
        $validFields[] = $element->getAttribute('name');
    }
}

if (array_search($field, $validFields) === false) {
    $result = new \stdClass();
    $result->field = $field;
    $result->message = get_string("settings:unknown:problem", 'local_leaderboard', $field);
    echo json_encode($result);
    die();
}
$PAGE->set_url('/local/leaderboard/ajax.php');
$PAGE->set_context(context_system::instance());

$result = new \stdClass();
$result->field = $field;
try {
    $updated = set_config($field, $value, 'local_leaderboard');
} catch (\Exception $e) {
    $updated = false;
}

$result->updated = $updated;
if (!empty($updated)) {
    $result->message = get_string("settings:{$field}:success", 'local_leaderboard');
} else {
    $result->message = get_string("settings:{$field}:problem", 'local_leaderboard');
}
echo json_encode($result);

die();
