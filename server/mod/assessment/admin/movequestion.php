<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

use mod_assessment\factory\assessment_version_factory;
use mod_assessment\model\version_question;

require_once(__DIR__ . '/../../../config.php');

global $PAGE;

$id = required_param('id', PARAM_INT);
$versionid = required_param('versionid', PARAM_INT);
$version = assessment_version_factory::fetch(['id' => $versionid]);
$questionversion = version_question::instance(['questionid' => $id, 'versionid' => $version->get_id()], MUST_EXIST);
$direction = required_param('direction', PARAM_TEXT);

$assessment = mod_assessment\model\assessment::instance(['id' => $version->assessmentid], MUST_EXIST);
list($course, $cm) = get_course_and_cm_from_instance($assessment->id, 'assessment');
require_sesskey();

$context = context_module::instance($cm->id);
require_login($course, null, $cm);
require_capability('mod/assessment:editinstance', $context);

$PAGE->set_url(new moodle_url(
        '/mod/assessment/admin/movequestion.php',
        ['versionid' => $version->get_id(), 'id' => $questionversion->id])
);
$renderer = $PAGE->get_renderer('assessment');
$returnurl = new moodle_url(
    '/mod/assessment/admin/content.php',
    ['id' => $assessment->get_id(), 'versionid' => $version->get_id(), 'stageid' => $questionversion->stageid]
);
$returnurl = optional_param('returnurl', $returnurl, PARAM_URL);

if ($direction == 'up' && $questionversion->can_moveup()) {
    $questionversion->update_sortorder($questionversion->sortorder - 1);
} elseif ($direction == 'down' && $questionversion->can_movedown()) {
    $questionversion->update_sortorder($questionversion->sortorder + 1);
} else {
    throw new Exception(get_string('error:invalidmove', 'mod_assessment'));
}

redirect($returnurl);
