<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This script is called via a javscript ajax call from the Adapt repository
 * plugin global settings page when the 'Test connection' link is clicked.
 * The connection to the configured Adapt instance connection will be tested and
 * the user will be notified of success or failure.
 *
 * @since Moodle 3.0
 * @package    repository_kineoadapt
 * @copyright  2016 City & Guilds Kineo
 * @author     Ben Lobo <ben.lobo@kineo.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('AJAX_SCRIPT', true);

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

global $CFG, $PAGE, $OUTPUT, $DB;

$context = context_system::instance();

if (!has_capability('moodle/site:config', $context)) {
    echo json_encode('{"result":-1}');
    die;
}

require_login();

$PAGE->set_context($context);

echo $OUTPUT->header(); // Send headers.

$sql = "SELECT
            ri.*, r.type
        FROM {repository_instances} ri
        JOIN {repository} r ON r.id = ri.typeid
        WHERE r.type = :repotype
        AND ri.contextid = :systemcontextid";
$params = [
    'repotype' => 'kineoadapt',
    'systemcontextid' => $context->id
];
if ($repository_instance = $DB->get_record_sql($sql, $params)) {
    $repo = new repository_kineoadapt($repository_instance->id, $context->id);
    $result = $repo->test_adapt_connection();
} else {
    $result = 'Error';
}

ajax_check_captured_output();

if ($result === 'Error') {
    echo json_encode('{"result":-1}');
} else if ($result) {
    echo json_encode('{"result":1}');
} else {
    echo json_encode('{"result":0}');
}
