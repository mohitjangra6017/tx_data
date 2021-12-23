<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 *
 * DEVIOTIS2
 * - refactored to assign roles generically (i.e. evaluators AND reviewers not just the former).
 */

use mod_assessment\helper\role;
use mod_assessment\model\ruleset;

require_once(__DIR__ . '/../../../../../config.php');

global $OUTPUT;
$assessmentid = required_param('id', PARAM_INT);
$assessment = mod_assessment\model\assessment::instance(['id' => $assessmentid], MUST_EXIST);

$versionid = required_param('versionid', PARAM_INT);
$version = mod_assessment\model\version::instance(['id' => $versionid, 'assessmentid' => $assessment->id], MUST_EXIST);

$role = required_param('role', PARAM_ALPHANUMEXT);

list($course, $cm) = get_course_and_cm_from_instance($assessment->id, 'assessment');
$context = context_module::instance($cm->id);

require_login($course, null, $cm);
require_capability('mod/assessment:editinstance', $context);

/* @var $PAGE moodle_page */
$PAGE->set_url(new moodle_url('/mod/assessment/admin/assignments/version/rules.php', ['role' => $role, 'id' => $assessmentid, 'versionid' => $version->id]));
$PAGE->requires->strings_for_js(['addrule', 'deleteruleconfirm', 'deleteruleitemconfirm'], 'assessment');
local_js([TOTARA_JS_DIALOG, TOTARA_JS_TREEVIEW]);

$jsmodule = array(
    'name' => 'assessment_rulemodals',
    'fullpath' => '/mod/assessment/js/rulemodals.js',
    'requires' => array('json', 'totara_core')
);

$args = ['args' => json_encode(['versionid' => $version->id, 'role' => $role])];
$PAGE->requires->js_init_call('M.mod_assessment_rulemodals.init', $args, false, $jsmodule);
$PAGE->requires->js_call_amd('mod_assessment/updaterule', 'init');

$ruledata = [
    'id' => $assessment->id,
    'operator' => $version->operator,
    'versionid' => $version->id
];
$rulesets = ruleset::instances_from_version($version, $role);
foreach ($rulesets as $ruleset) {
    $ruledata['rulesetoperator[' . $ruleset->id . ']'] = $ruleset->operator;
}

$ruledata['singleevaluator'] = $version->singleevaluator;
$ruledata['singlereviewer'] = $version->singlereviewer;

$renderer = $PAGE->get_renderer('assessment');
$mform = new mod_assessment\form\rules(null, ['version' => $version, 'role' => $role]);
$mform->set_data($ruledata);

if ($data = $mform->get_data()) {

    if (isset($data->rulesetoperator)) {
        foreach ($data->rulesetoperator as $rulesetid => $operator) {
            $ruleset = ruleset::instance(['id' => $rulesetid], MUST_EXIST);
            if (isset($data->rulesetoperator)) {
                $ruleset->set_operator($operator);
            }
            if (isset($data->role)) {
                $ruleset->set_role($data->role);
            }
            $ruleset->save();
        }
    }

    // Update global rulesset operator.
    $version->set_operator($data->operator);

    if (isset($data->singleevaluator)) {
        // Update single evaluator per attempt value.
        $version->set_singleevaluator($data->singleevaluator);
    }
    if (isset($data->singlereviewer)) {
        // Update single reviewer per attempt value.
        $version->set_singlereviewer($data->singlereviewer);
    }

    $version->save();

    // Reload page.
    redirect($PAGE->url);
}

$roleassignmenturl = new moodle_url('/mod/assessment/admin/assignments/version/directview.php', ['id' => $assessment->id, 'versionid' => $versionid, 'role' => $role]);

echo $OUTPUT->header();
echo $OUTPUT->heading($assessment->name);
echo $renderer->content_info($version);

$roletypes = [
    role::EVALUATOR => 'evaluator',
    role::REVIEWER => 'reviewer',
];
$prefix = (isset($roletypes[$role]) ? $roletypes[$role] : '');
echo $renderer->navtabs($prefix . 'rules', $assessment->id, $version->id);
echo html_writer::link($roleassignmenturl, get_string('versionroleassignment', 'assessment'), ['class' => 'btn btn-default']);
echo $mform->render();
echo $OUTPUT->footer();
