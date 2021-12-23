<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

require_once(__DIR__ . '/../../config.php');

global $USER, $PAGE, $DB, $OUTPUT, $CFG;

use core\notification;
use mod_assessment\helper;

$id = required_param('id', PARAM_INT);    // Course Module ID.
$learnerid = optional_param('userid', null, PARAM_INT);
$archived = optional_param('archived', false, PARAM_BOOL);
$viewasrole = optional_param('viewasrole', null, PARAM_INT);
$viewingrole = false;

list($course, $cm) = get_course_and_cm_from_cmid($id, 'assessment');
$context = context_module::instance($cm->id);

if ($context->is_user_access_prevented($USER)) {
    throw new Exception(get_string('error:noaccess', 'mod_assessment'));
}

$assessment = mod_assessment\model\assessment::instance(['id' => $cm->instance], MUST_EXIST);
$version = mod_assessment\model\version::active_instance($assessment);
$role = helper\role::get_assessment_role($assessment, $USER->id, $learnerid, $archived);

switch ($role) {
    // Admins can view as another role, potentially.
    case helper\role::ADMIN:
        if (empty($viewasrole)) {
            redirect(new moodle_url('/mod/assessment/admin/content.php', ['id' => $assessment->id]));
        }
        else {
            $viewingrole = $viewasrole;
            $PAGE->set_cm($cm);
        }
        break;
    case helper\role::EVALUATOR:
    case helper\role::REVIEWER:
        $PAGE->set_cm($cm);
        break;
    case helper\role::LEARNER:
        require_login($course, false, $cm);
        $learnerid = $USER->id;
        break;
    default:
        redirect($CFG->wwwroot, get_string('error:notevaluatororreviewer', 'mod_assessment'), null, notification::ERROR);
}

$learner = $DB->get_record('user', ['id' => $learnerid]);
if ($role == helper\role::EVALUATOR) {
    $attempts = mod_assessment\model\attempt::instances_for_user($learner->id, $assessment->id, $USER->id, null, $archived);
} else {
    $attempts = mod_assessment\model\attempt::instances_for_user($learner->id, $assessment->id, null, null, $archived);
}

$event = \mod_assessment\event\course_module_viewed::create(
    [
        'objectid' => $PAGE->cm->instance,
        'context' => $PAGE->context,
    ]
);
$event->add_record_snapshot('course', $course);
$event->trigger();

if (empty($attempts)) {
    if ($version) {
        $attempt_url = new moodle_url('/mod/assessment/attempt.php', ['assessmentid' => $assessment->id]);
        if ($viewingrole) {
            $attempt_url->param('role', $viewingrole);
            $attempt_url->param('userid', $learnerid);
        }
        redirect($attempt_url);
    } else {
        redirect(new moodle_url('/course/view.php', ['id' => $assessment->course]), get_string('error_inactive', 'assessment'));
    }
}

$attempt = null;
if ($version) {
    try {
        $attempt = mod_assessment\model\attempt::instance_active($version, $learner->id);
    } catch (Exception $ex) {
        // Attempt not found.
        $attempt = null;
    }
}

$otherroles = helper\role::get_other_roles($role);

// Prepare output.
$PAGE->set_title($assessment->name);
$PAGE->set_url(new moodle_url('/mod/assessment/view.php', ['id' => $id, 'userid' => $learner->id]));
$PAGE->requires->js_call_amd('mod_assessment/markreviewed', 'initialize');
/* @var mod_assessment_renderer $renderer */
$renderer = $PAGE->get_renderer('assessment');

$rendercontext = [];

// Attempts info.
$attemptsremaining = helper\attempt::get_remaining_attempts($assessment, $learnerid);
$rendercontext['attemptinfo'] = [
    'strattemptsremaining' => get_string('attemptsremaining', 'assessment'),
    'remaining' => $attemptsremaining == helper\attempt::UNLIMITED ? get_string('unlimited') : $attemptsremaining,
];

// Role actions.
if ($role == helper\role::EVALUATOR || $role == helper\role::REVIEWER || $role == helper\role::ADMIN) {
    $rendercontext['contenthead'] = get_string('attemptsummarylearner', 'assessment', fullname($learner));

    $dashboardurl = new moodle_url('/mod/assessment/dashboard.php');
    if ($archived) {
        $dashboardurl->param('type', 'archived');
        $dashboardurl->param('archived', 1);
    }
    $rendercontext['buttons'][] = [
        'class' => 'default',
        'label' => get_string('backtodashboard', 'assessment'),
        'url' => $dashboardurl,
    ];

    if ($role == helper\role::EVALUATOR) {
        if ($attemptsremaining == 0 && $assessment->extraattempts) {
            $rendercontext['buttons'][] = [
                'class' => 'primary',
                'label' => get_string('attemptoverride', 'assessment'),
                'url' => new moodle_url('/mod/assessment/override.php', ['assessmentid' => $assessment->id, 'userid' => $learnerid])
            ];
        }
    }

} else {
    $rendercontext['contenthead'] = get_string('attemptsummaryyours', 'assessment');

    $rendercontext['buttons'][] = [
        'class' => 'default',
        'label' => get_string('backtocourse', 'assessment'),
        'url' => new moodle_url('/course/view.php', ['id' => $assessment->course]),
    ];
}

$rendercontext['tablehtml'] = $renderer->attempts_table($assessment, $attempts, $role, $viewingrole);

// Buttons and notices why button ain't there.
if (helper\completion::is_complete($assessment, $learnerid)) {
    $rendercontext += [
        'notice' => get_string('assessmentcomplete', 'assessment'),
        'noticetype' => 'success',
    ];
} elseif ($attempt) {
    if (helper\completion::is_role_complete($attempt, $role)) {
        // We have multiple other roles now, so output pending completion info about each, where appropriate.
        $notices = [];
        foreach ($otherroles as $otherrole) {
            if (!helper\completion::is_role_complete($attempt, $otherrole)) {
                $notices[] = get_string('waitingforrolex', 'assessment', helper\role::get_string($otherrole));
            }
        }

        $rendercontext += [
            'notice' => join('<br/>', $notices),
            'noticetype' => 'warning',
        ];

        $reviewurl = new moodle_url('/mod/assessment/attempt.php', ['attemptid' => $attempt->id]);
        $rendercontext['buttons'][] = [
            'class' => 'primary',
            'label' => get_string('attemptreview', 'assessment'),
            'url' => $reviewurl,
        ];
    } else {
        $rendercontext['buttons'][] = [
            'class' => 'primary',
            'label' => get_string('attemptcontinue', 'assessment'),
            'url' => new moodle_url('/mod/assessment/attempt.php', ['attemptid' => $attempt->id]),
        ];
    }
} elseif (helper\attempt::can_make_new_attempt($assessment, $learnerid)) {
    if ($role == helper\role::LEARNER) {
        $rendercontext['buttons'][] = [
            'class' => 'primary',
            'label' => get_string('attemptstart', 'assessment'),
            'url' => new moodle_url('/mod/assessment/attempt.php', ['assessmentid' => $assessment->id]),
        ];
    } else {
        $rendercontext += [
            'notice' => get_string('error_noactiveattempts', 'assessment'),
            'noticetype' => 'warning',
        ];
    }
} elseif (!$version) {
    $rendercontext += [
        'notice' => get_string('error_inactive', 'assessment'),
        'noticetype' => 'warning',
    ];
} else {
    $rendercontext += [
        'notice' => get_string('error_noavailableattempts', 'assessment'),
        'noticetype' => 'warning',
    ];
}

$pagecontent = $renderer->render_from_template('mod_assessment/attemptsummary', $rendercontext);

echo $OUTPUT->header();
echo $OUTPUT->heading($assessment->name);
echo $pagecontent;
echo $OUTPUT->footer();
