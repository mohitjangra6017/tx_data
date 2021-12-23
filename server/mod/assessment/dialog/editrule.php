<?php
/**
 * @copyright 2017-2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

use mod_assessment\model\rule;

require_once(__DIR__ . '/../../../config.php');

global $PAGE;

$type = required_param('type', PARAM_TEXT);
$versionid = required_param('versionid', PARAM_INT);
$role = required_param('role', PARAM_INT);
$ruleid = optional_param('ruleid', null, PARAM_INT);
$rulesetid = optional_param('rulesetid', null, PARAM_INT);

$version = mod_assessment\model\version::instance(['id' => $versionid], MUST_EXIST);
if ($ruleid) {
    $rule = rule::instance(['id' => $ruleid], MUST_EXIST);
} else {
    $rule = rule::class_from_type($type);
    $rule->set_rulesetid($rulesetid);
}

list ($course, $cm) = get_course_and_cm_from_instance($version->assessmentid, 'assessment');
$context = context_module::instance($cm->id);

require_login();
require_capability('mod/assessment:editinstance', $context);

$PAGE->set_context($context);

$dialogclass = '\\mod_assessment\\dialog\\rule' . $type;
$dialog = new $dialogclass($rule, $versionid, $role);

echo $dialog->generate_markup();
