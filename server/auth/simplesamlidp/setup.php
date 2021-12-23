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
 * Common setup.
 *
 * @package    auth_simplesamlidp
 * @author     Ben Lobo <ben.lobo@kineo.com>
 * @copyright  Brendan Heywood <brendan@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;
require_once(__DIR__ . '/_autoload.php');

global $CFG, $simplesamlidpauth;
require_once("{$CFG->dirroot}/auth/simplesamlidp/auth.php");

// Tell SSP that we are on 443 if we are terminating SSL elsewhere.
if (isset($CFG->sslproxy) && $CFG->sslproxy) {
      $_SERVER['SERVER_PORT'] = '443';
}

$simplesamlidpauth = new auth_plugin_simplesamlidp();

// Auto create unique certificates for this moodle SP.
//
// This is one area which many SSP instances get horridly wrong and leave the
// default certificates which is very insecure. Here we create a customized
// cert/key pair just-in-time. If for some reason you do want to use existing
// files then just copy them over the files in /sitedata/simplesamlidp/.
$simplesamlidpauth->getSimpleSamlIdpDirectory(); // It will create it if needed.
if (!file_exists($simplesamlidpauth->certpem) || !file_exists($simplesamlidpauth->certcrt)) {
    $error = \auth_simplesamlidp\Utils\Certificates::createCertificates($simplesamlidpauth);
    if ($error) {
        // @codingStandardsIgnoreStart
        error_log($error);
        // @codingStandardsIgnoreEnd
    }
}

\SimpleSAML\Configuration::setConfigDir("$CFG->dirroot/auth/simplesamlidp/config");
