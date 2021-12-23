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
 * SSP config that utilises various settings from Moodle config
 *
 * @package   auth_simplesamlidp
 * @author    Ben Lobo <ben.lobo@kineo.com>
 * @copyright 2019 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

use auth_simplesamlidp\Utils\SSLAlgorithms;

defined('MOODLE_INTERNAL') || die();

global $CFG, $simplesamlidpauth;

// Check for https login.
$wwwroot = $CFG->wwwroot;
if (!empty($CFG->loginhttps)) {
    $wwwroot = str_replace('http:', 'https:', $CFG->wwwroot);
}

$dbType = ($CFG->dbtype == 'mysqli') ? 'mysql' : $CFG->dbtype;

$config = array(
    'baseurlpath'       => $wwwroot . '/auth/simplesamlidp/idp/',
    'certdir'           => $simplesamlidpauth->getSimpleSamlIdpDirectory() . '/',
    'debug'             => $simplesamlidpauth->config->debug ? true : false,
    'logging.level'     => $simplesamlidpauth->config->debug ? SimpleSAML\Logger::DEBUG : SimpleSAML\Logger::ERR,
    'logging.handler'   => $simplesamlidpauth->config->logtofile ? 'file' : 'errorlog',
    'loggingdir'        => $simplesamlidpauth->config->logdir,
    'logging.logfile'   => 'simplesamlphp.log',
    'showerrors'        => $CFG->debugdisplay ? true : false,
    'errorreporting'    => false,
    'debug.validatexml' => false,
    'secretsalt'        => get_site_identifier(),
    'technicalcontact_name'  => $CFG->supportname,
    'technicalcontact_email' => $CFG->supportemail ? $CFG->supportemail : $CFG->noreplyaddress,
    'timezone' => class_exists('core_date') ? core_date::get_server_timezone() : null,

    'session.duration'          => 60 * 60 * 8, // 8 hours. TODO same as moodle.
    'session.datastore.timeout' => 60 * 60 * 4,
    'session.state.timeout'     => 60 * 60,

    'session.authtoken.cookiename'  => 'MDL_SSP_IDP_AuthToken',
    'session.cookie.name'     => 'MDL_SSP_IDP_SessID',
    'session.cookie.path'     => $CFG->sessioncookiepath,
    'session.cookie.domain'   => null,
    'session.cookie.secure'   => !empty($CFG->cookiesecure),
    'session.cookie.lifetime' => 0,

    'session.phpsession.cookiename' => null,
    'session.phpsession.savepath'   => null,
    'session.phpsession.httponly'   => true,

    'enable.saml20-idp' => true,

    'enable.http_post' => false,

    'signature.algorithm' => SSLAlgorithms::getDefaultSamlSignatureAlgorithm(),

    'metadatadir'                   => "$CFG->dataroot/simplesamlidp/",

    'metadata.sign.enable'          => true,
    'metadata.sign.certificate'     => $simplesamlidpauth->certcrt,
    'metadata.sign.privatekey'      => $simplesamlidpauth->certpem,
    'metadata.sign.privatekey_pass' => get_site_identifier(),

    //'store.type'                    => '\\auth_simplesamlidp\\Utils\\Store',
    'store.type'                    => 'sql',
    'store.sql.dsn'                 => "{$dbType}:host={$CFG->dbhost};dbname={$CFG->dbname}",
    'store.sql.username'            => $CFG->dbuser,
    'store.sql.password'            => $CFG->dbpass,

    'proxy' => null,

);

if (!empty($CFG->dboptions['dbport'])) {
    $config['store.sql.dsn'] .= ";port={$CFG->dboptions['dbport']}";
}