<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

use mod_assessment\model\ruleset;

define('AJAX_SCRIPT', true);

require_once(__DIR__ . '/../../../config.php');

global $DB;
$versionid = required_param('versionid', PARAM_INT);
$version = mod_assessment\model\version::instance(['id' => $versionid], MUST_EXIST);

$operator = optional_param('operator', null, PARAM_INT);
$ruleid = optional_param('ruleid', null, PARAM_INT);
$rulesetid = optional_param('rulesetid', null, PARAM_INT);
$type = optional_param('type', null, PARAM_TEXT);
$value = optional_param('value', null, PARAM_TEXT);
$role = optional_param('role', null, PARAM_ALPHANUMEXT);

if ($ruleid) {
    $rule = mod_assessment\model\rule::instance(['id' => $ruleid], MUST_EXIST);
} elseif ($rulesetid) {
    $ruleset = ruleset::instance(['id' => $rulesetid, 'role' => $role], MUST_EXIST);
}

list ($course, $cm) = get_course_and_cm_from_instance($version->assessmentid, 'assessment');
$context = context_module::instance($cm->id);

require_login($course, false, $cm);
require_capability('mod/assessment:editinstance', $context);
require_sesskey();

// Set transaction for all-or-nothing success.
$transaction = $DB->start_delegated_transaction();

if (isset($rule)) {
    if (empty($value)) {
        $rule->delete();
    } else {
        $rule->set_operator($operator ? $operator : $rule->operator);
        $rule->encode_value($value)->save();
    }
} elseif ($type) {
    // Create rule.
    $rule = mod_assessment\model\rule::class_from_type($type);
    if (!isset($ruleset)) {
        // Create ruleset.
        $ruleset = new ruleset();
        $ruleset->set_operator(mod_assessment\model\rule::OP_AND);    // TODO: Get configurable default?
        $ruleset->set_versionid($versionid);
        $ruleset->set_role($role);
        $ruleset->save();
    }
    $rule->set_rulesetid($ruleset->id);
    $rule->set_operator($operator);
    $ruleset->set_role($role);
    $rule->encode_value($value);
    $rule->save();
} elseif (isset($ruleset)) {
    $ruleset->set_operator($operator);
    $ruleset->set_role($role);
    $ruleset->save();
} else {
    $version->set_operator($operator)->save();
}

$transaction->allow_commit();

$result = true;
echo json_encode($result);
