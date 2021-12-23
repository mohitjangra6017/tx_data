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
 * Strings for component 'auth_simplesamlidp', language 'en'.
 *
 * @package   auth_simplesamlidp
 * @author    Ben Lobo <ben.lobo@kineo.com>
 * @copyright 2019 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

defined('MOODLE_INTERNAL') || die();

$string['auth_simplesamlidpdescription'] = 'An authentication plugin that enables Totara to act as an Identity Provider for SSO';
$string['authsource'] = 'Authentication source';
$string['authsource_help'] = 'The name of the configured SimpleSAMLphp authentication source to use in config/authsources.php';
$string['certificatedetails'] = 'Certificate details';
$string['certificatedetailshelp'] = '<h1>SAML2 auto generated public certificate contents</h1><p>The path for the cert is here:</p>';
$string['certificatepath'] = 'Path: {$a}';
$string['commonname'] = 'Common Name';
$string['countryname'] = 'Country';
$string['debug'] = 'Debugging';
$string['debug_help'] = '<p>This adds extra debugging to the normal moodle log | <a href=\'{$a}\'>View SSP config</a></p>';
$string['exception'] = 'SimpleSAMLIdP exception: {$a}';
$string['expirydays'] = 'Expiry in Days';
$string['idpmetadata'] = 'IdP Metadata';
$string['idpmetadata_help'] = '<a href=\'{$a}\'>View Identity Provider metadata</a> | <a href=\'{$a}?download=1\'>Download IdP metadata</a>
<p>You may need to give this to the Service Provider admin to whitelist you.</p>';
$string['key'] = 'Key';
$string['localityname'] = 'Locality';
$string['logdir'] = 'Log Directory';
$string['logdir_help'] = 'The log directory SSP will write to, the file will be named simplesamlphp.log';
$string['logdirdefault'] = '/tmp/';
$string['logtofile'] = 'Enable logging to file';
$string['logtofile_help'] = 'Turning this on will redirect SSP log output to a file in the logdir';
$string['nullprivatecert'] = 'Creation of Private Certificate failed.';
$string['nullpubliccert'] = 'Creation of Public Certificate failed.';
$string['organisationname'] = 'Organisation';
$string['organisationalunitname'] = 'Organisational Unit';
$string['pluginname'] = 'SimpleSAML Identity Provider';
$string['regeneratecertkeypair'] = 'Regenerate Private Key and Certificate';
$string['regeneratecertificate'] = 'Regenerate certificate';
$string['regeneratecertificate_help'] = 'Regenerate the Private Key and Certificate used by this IdP | <a href=\'{$a}\'>View IdP certificate</a>';
$string['regeneratecertificate_warning'] = 'Warning: Generating a new certificate will overwrite the current one and you may need to update your SP';
$string['regeneratesubmit'] = 'Regenerate';
$string['required'] = 'This field is required';
$string['requireint'] = 'This field is required and needs to be a positive integer';
$string['spmetadata'] = 'Service Provider metadata';
$string['spmetadata_help'] = 'This must be the service provider metadata in SimpleSAMLphp flat file format and not the SAML 2.0 Metadata XML format version of the metadata';
$string['stateorprovincename'] = 'State or Province';
$string['value'] = 'Value';
$string['notags'] = 'Tags are not permitted in this field';

/*
 * Signing Algorithm
 */
$string['sha1'] = 'Legacy SHA1 (Dangerous)';
$string['sha256'] = 'SHA256';
$string['sha384'] = 'SHA384';
$string['sha512'] = 'SHA512';
$string['signaturealgorithm'] = 'Signing Algorithm';
$string['signaturealgorithm_help'] = 'This is the algorithm that will be used to sign SAML requests. Warning: The SHA1 Algorithm is only provided for backwards compatibility, unless you absolutely must use it it is recommended to avoid it and use at least SHA256 instead.';
