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

$ruleid = required_param('ruleid', PARAM_INT);
$rule = mod_assessment\model\rule::instance(['id' => $ruleid], MUST_EXIST);
$ruleset = ruleset::instance(['id' => $rule->rulesetid], MUST_EXIST);
$version = mod_assessment\model\version::instance(['id' => $ruleset->versionid], MUST_EXIST);

list ($course, $cm) = get_course_and_cm_from_instance($version->assessmentid, 'assessment');
$context = context_module::instance($cm->id);

require_login($course, false, $cm);
require_capability('mod/assessment:editinstance', $context);
require_sesskey();

$result = $rule->delete();
echo json_encode($result);
