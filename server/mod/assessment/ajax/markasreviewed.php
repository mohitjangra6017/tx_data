<?php
/**
 * @copyright   City & Guilds Kineo 2017
 * @author      Steven Hughes <steven.hughes@kineo.com>
 */
define('AJAX_SCRIPT', true);
require_once(__DIR__ . '/../../../config.php');

use mod_assessment\helper;
use mod_assessment\model;

try {
    $attemptid = required_param('attemptid', PARAM_INT);

    require_login();
    require_sesskey();

    $attempt = model\attempt::instance(['id' => $attemptid]);
    $version = model\version::instance(['id' => $attempt->get_versionid()]);
    $cm = get_coursemodule_from_instance('assessment', $version->get_assessmentid());
    $context = context_module::instance($cm->id);
    if ($context->is_user_access_prevented()) {
        throw new Exception(get_string('error:noaccess', 'mod_assessment'));
    }

    helper\role::is_user_evaluator() || helper\role::is_user_reviewer() || require_capability('mod/assessment:viewfaileddashboard', context_system::instance());

    $attempt = model\attempt::instance(['id' => $attemptid], MUST_EXIST);
    $attempt->mark_reviewed()->save();

    echo json_encode([
        'status' => 'OK',
        'message' => 'Reviewed'
    ]);

} catch (Exception $ex) {
    echo json_encode([
        'status' => 'ERROR',
        'message' => $ex->getMessage()
    ]);
}

