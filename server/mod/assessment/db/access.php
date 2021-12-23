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

defined('MOODLE_INTERNAL') || die();

$capabilities = [];
$capabilities['mod/assessment:addinstance'] = [
    'captype' => 'write',
    'clonepermissionsfrom' => 'moodle/course:manageactivities',
    'contextlevel' => CONTEXT_COURSE,
    'riskbitmask' => RISK_XSS,
];
$capabilities['mod/assessment:editinstance'] = [
    'captype' => 'write',
    'clonepermissionsfrom' => 'moodle/course:manageactivities',
    'contextlevel' => CONTEXT_MODULE,
    'riskbitmask' => RISK_CONFIG
];
$capabilities['mod/assessment:view'] = [
    'archetypes' => [
        'guest' => CAP_ALLOW,
        'student' => CAP_ALLOW,
        'teacher' => CAP_ALLOW,
        'editingteacher' => CAP_ALLOW,
        'manager' => CAP_ALLOW
    ],
    'captype' => 'read',
    'contextlevel' => CONTEXT_MODULE,
];

$capabilities['mod/assessment:viewdashboard'] = [
    'captype' => 'write',
    'contextlevel' => CONTEXT_SYSTEM,
    'riskbitmask' => RISK_CONFIG,
];
$capabilities['mod/assessment:viewfaileddashboard'] = [
    'captype' => 'write',
    'contextlevel' => CONTEXT_SYSTEM,
    'riskbitmask' => RISK_CONFIG,
];
$capabilities['mod/assessment:viewcompleteddashboard'] = [
    'captype' => 'write',
    'contextlevel' => CONTEXT_SYSTEM,
    'riskbitmask' => RISK_CONFIG,
];
$capabilities['mod/assessment:viewcompletedrevieweddashboard'] = [
    'captype' => 'write',
    'contextlevel' => CONTEXT_SYSTEM,
    'riskbitmask' => RISK_CONFIG,
];
$capabilities['mod/assessment:viewarchiveddashboard'] = [
    'captype' => 'write',
    'contextlevel' => CONTEXT_SYSTEM,
    'riskbitmask' => RISK_CONFIG,
];
$capabilities['mod/assessment:viewasanotherrole'] = [
    'captype' => 'write',
    'clonepermissionsfrom' => 'moodle/course:manageactivities',
    'contextlevel' => CONTEXT_SYSTEM,
    'riskbitmask' => RISK_CONFIG,
];