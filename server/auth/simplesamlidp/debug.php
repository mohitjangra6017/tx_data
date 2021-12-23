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
 * Dump the internal SSP config for review
 *
 * @package    auth_simplesamlidp
 * @author     Ben Lobo <ben.lobo@kineo.com>
 * @copyright  Brendan Heywood <brendan@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once('setup.php');
require_login();
require_capability('moodle/site:config', context_system::instance());

$config = \SimpleSAML\Configuration::getInstance();

$PAGE->set_url("$CFG->httpswwwroot/auth/simplesamlidp/debug.php");
$PAGE->set_course($SITE);
echo $OUTPUT->header();
echo \auth_simplesamlidp\Utils\Certificates::prettyPrint($config);
echo $OUTPUT->footer();

