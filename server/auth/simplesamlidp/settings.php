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
 * Admin config settings page
 *
 * @package   auth_simplesamlidp
 * @author    Ben Lobo <ben.lobo@kineo.com>
 * @copyright 2019 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

use auth_simplesamlidp\Settings\TextOnly;
use auth_simplesamlidp\Settings\Button;
use auth_simplesamlidp\Settings\SpMetadata;

defined('MOODLE_INTERNAL') || die;

global $CFG;

if ($ADMIN->fulltree) {

    $yesno = array(
            new lang_string('no'),
            new lang_string('yes'),
    );

    // Introductory explanation.
    $settings->add(new admin_setting_heading('auth_simplesamlidp/pluginname', '',
        new lang_string('auth_simplesamlidpdescription', 'auth_simplesamlidp')));

    // SimpleSAMLphp Service Provider metadata.
    $spMetadata = new SpMetadata();
    $spMetadata->set_updatedcallback('auth_simplesamlidp_update_sp_metadata');
    $settings->add($spMetadata);

    // Debugging.
    $settings->add(new admin_setting_configselect(
            'auth_simplesamlidp/debug',
            get_string('debug', 'auth_simplesamlidp'),
            get_string('debug_help', 'auth_simplesamlidp', $CFG->wwwroot . '/auth/simplesamlidp/debug.php'),
            0, $yesno));

    // Logging.
    $settings->add(new admin_setting_configselect(
            'auth_simplesamlidp/logtofile',
            get_string('logtofile', 'auth_simplesamlidp'),
            get_string('logtofile_help', 'auth_simplesamlidp'),
            0, $yesno));
    $settings->add(new admin_setting_configtext(
            'auth_simplesamlidp/logdir',
            get_string('logdir', 'auth_simplesamlidp'),
            get_string('logdir_help', 'auth_simplesamlidp'),
            get_string('logdirdefault', 'auth_simplesamlidp'),
            PARAM_TEXT));

    // Button to regenerate certificate.
    $settings->add(new Button(
            'auth_simplesamlidp/regeneratecertificate',
            get_string('regeneratecertificate', 'auth_simplesamlidp'),
            get_string('regeneratecertificate_help', 'auth_simplesamlidp', $CFG->wwwroot . '/auth/simplesamlidp/cert.php'),
            get_string('regeneratecertificate', 'auth_simplesamlidp'),
            $CFG->wwwroot . '/auth/simplesamlidp/regenerate.php'
            ));

    // Text links to view or download IdP Metadata.
    $settings->add(new TextOnly(
           'auth_simplesamlidp/idpmetadata',
           get_string('idpmetadata', 'auth_simplesamlidp'),
           get_string('idpmetadata_help', 'auth_simplesamlidp', $CFG->wwwroot . '/auth/simplesamlidp/idp/metadata.php')
           ));
}
