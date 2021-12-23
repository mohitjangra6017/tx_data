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
 * Unfortunately this file inside SSP couldn't be customised in any clean
 * way so it has been copied here and forked. The main differences are the inclusion
 * of the setup.php file to bootstrap simpleSAMLphp and the change to
 * require the simpleSAML _include.php file.
 *
 * Original file is: extlib/simplesamlphp/www/saml2/idp/SingleLogoutService.php
 *
 * @package   auth_simplesamlidp
 * @author    Ben Lobo <ben.lobo@kineo.com>
 * @copyright 2019 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

require_once(__DIR__ . '/../../../config.php');
require_once('../setup.php');

global $CFG;
require_once($CFG->dirroot.'/auth/simplesamlidp/extlib/simplesamlphp/www/_include.php');

// Tell SSP that we are on 443 if we are terminating SSL elsewhere.
if (!empty($CFG->sslproxy)) {
    $_SERVER['SERVER_PORT'] = '443';
}

\SimpleSAML\Logger::info('SAML2.0 - IdP.SingleLogoutService: Accessing SAML 2.0 IdP endpoint SingleLogoutService');

$metadata = \SimpleSAML\Metadata\MetaDataStorageHandler::getMetadataHandler();
$idpEntityId = $metadata->getMetaDataCurrentEntityID('saml20-idp-hosted');
$idp = \SimpleSAML\IdP::getById('saml2:'.$idpEntityId);

if (isset($_REQUEST['ReturnTo'])) {
    $idp->doLogoutRedirect(\SimpleSAML\Utils\HTTP::checkURLAllowed((string) $_REQUEST['ReturnTo']));
} else {
    try {
        \SimpleSAML\Module\saml\IdP\SAML2::receiveLogoutMessage($idp);
    } catch (\Exception $e) {
        // TODO: look for a specific exception
        /*
         * This is dirty. Instead of checking the message of the exception, \SAML2\Binding::getCurrentBinding() should
         * throw an specific exception when the binding is unknown, and we should capture that here
         */
        if ($e->getMessage() === 'Unable to find the current binding.') {
            throw new \SimpleSAML\Error\Error('SLOSERVICEPARAMS', $e, 400);
        } else {
            throw $e; // do not ignore other exceptions!
        }
    }
}
assert(false);
