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
 * Open ID authentication. This file is a simple login entry point for OAuth identity providers.
 *
 * @package auth_oauth2
 * @copyright 2017 Damyon Wiese
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

require_once('../../config.php');

$issuerid = required_param('id', PARAM_INT);
$wantsurl = optional_param('wantsurl', '/', PARAM_URL);
if ($wantsurl === '') {
    $wantsurl = '/';
}

$PAGE->set_url('/auth/oauth2/login.php', ['id' => $issuerid, 'wantsurl' => $wantsurl]);
$PAGE->set_context(context_system::instance());

require_sesskey();

if (!is_enabled_auth('oauth2')) {
    throw new \moodle_exception('notenabled', 'auth_oauth2');
}

$issuer = new \core\oauth2\issuer($issuerid);

$returnparams = ['wantsurl' => $wantsurl, 'sesskey' => sesskey(), 'id' => $issuerid];
$returnurl = new moodle_url('/auth/oauth2/login.php', $returnparams);

$client = \core\oauth2\api::get_user_oauth_client($issuer, $returnurl);
if (!$client) {
    throw new moodle_exception('loginerror_authenticationfailed', 'auth_oauth2', get_login_url());
}

if (!$client->is_logged_in()) {
    redirect($client->get_login_url());
}

$auth = get_auth_plugin('oauth2');
$auth->complete_login($client, new moodle_url($wantsurl));

