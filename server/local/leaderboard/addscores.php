<?php

use core\notification;

global $CFG, $USER, $OUTPUT, $SESSION, $PAGE;

require_once(dirname(dirname(__DIR__)) . '/config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/user/filters/lib.php');
require_once($CFG->dirroot . '/admin/user/user_bulk_forms.php');
require_once(__DIR__ . '/lib.php');

// Security checks.
admin_externalpage_setup('local_leaderboard_adhoc');

if (!isset($SESSION->bulk_user_scores)) {
    $SESSION->bulk_user_scores = [];
}

// Page definition.
$url = new moodle_url('/local/leaderboard/addscores.php');
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());

// Create the user filter form
$ufiltering = new user_filtering();
$userBulkForm = new user_bulk_form(null, getScoresSelectionData($ufiltering));

if ($userBulkData = $userBulkForm->get_data()) {
    if (!empty($userBulkData->addall)) {
        addScoresSelectionAll($ufiltering);

    } else if (!empty($userBulkData->addsel)) {
        if (!empty($userBulkData->ausers)) {
            if (in_array(0, $userBulkData->ausers)) {
                addScoresSelectionAll($ufiltering);
            } else {
                foreach ($userBulkData->ausers as $userid) {
                    if ($userid == -1) {
                        continue;
                    }
                    if (!isset($SESSION->bulk_user_scores[$userid])) {
                        $SESSION->bulk_user_scores[$userid] = $userid;
                    }
                }
            }
        }

    } else if (!empty($userBulkData->removeall)) {
        $SESSION->bulk_user_scores = [];
    } else if (!empty($userBulkData->removesel)) {
        if (!empty($userBulkData->susers)) {
            if (in_array(0, $userBulkData->susers)) {
                $SESSION->bulk_user_scores = [];
            } else {
                foreach ($userBulkData->susers as $userid) {
                    if ($userid == -1) {
                        continue;
                    }
                    unset($SESSION->bulk_user_scores[$userid]);
                }
            }
        }
    }

    // Reset the form selections
    unset($_POST);
    $userBulkForm = new user_bulk_form(null, getScoresSelectionData($ufiltering));
}

$scoreForm = new \local_leaderboard\Form\UserBulkScoreForm();
if ($scoredata = $scoreForm->get_data()) {
    if (empty($SESSION->bulk_user_scores)) {
        notification::warning(get_string('form:warning:nousers', 'local_leaderboard'));
    } else {
        $score = $scoredata->score;
        $users = $SESSION->bulk_user_scores;

        // Trigger the ad hoc score event for each selected user
        try {
            if (!empty($score) && !empty($users)) {
                foreach ($users as $user) {
                    // Trigger the custom ad hoc score event.
                    \local_leaderboard\event\adhoc_leaderboard::create(
                        [
                            'userid' => $USER->id,
                            'relateduserid' => $user,
                            'other' => [
                                'score' => $score,
                            ],
                        ]
                    )->trigger();
                }
                notification::success(get_string('form:notification:scoreadded', 'local_leaderboard', $score));
            }
        } catch (\Exception $exception) {
            notification::warning(get_string('form:warning:scorenotadded', 'local_leaderboard', $score));
        }

    }
    // Reset the form
    unset($_POST);
    $scoreForm = new \local_leaderboard\Form\UserBulkScoreForm();
}

// Page display.
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('settings:title:adhoc', 'local_leaderboard'));
echo $OUTPUT->box(get_string('settings:description:adhoc', 'local_leaderboard'));
$ufiltering->display_add();
$ufiltering->display_active();

$userBulkForm->display();
$scoreForm->display();
echo $OUTPUT->footer();