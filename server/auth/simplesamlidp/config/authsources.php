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
 * SSP auth sources which inherits from Moodle config
 *
 * @package   auth_simplesamlidp
 * @author    Ben Lobo <ben.lobo@kineo.com>
 * @copyright 2019 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

defined('MOODLE_INTERNAL') || die();

global $simplesamlidpauth, $CFG, $SITE, $SESSION;

// Check for https login.
$wwwroot = $CFG->wwwroot;
if (!empty($CFG->loginhttps)) {
    $wwwroot = str_replace('http:', 'https:', $CFG->wwwroot);
}

$config = [];

$config[$simplesamlidpauth->authsource] = [
    'totara:External',
    'totara_coderoot' => $CFG->dirroot,
    'login_url' => $CFG->wwwroot . '/login/index.php',
    'cookie_name' => auth_plugin_simplesamlidp::AUTH_COOKIE_DEFAULT,
    'privatekey' => $simplesamlidpauth->certpem,
    'privatekey_pass' => get_site_identifier(),
    'certificate' => $simplesamlidpauth->certcrt,
    'sign.logout' => true,
    'redirect.sign' => true,
];
