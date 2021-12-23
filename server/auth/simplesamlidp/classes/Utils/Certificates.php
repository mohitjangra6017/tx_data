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
 * @package    auth_simplesamlidp
 * @author     Ben Lobo <ben.lobo@kineo.com>
 * @author     Adam Lynam <adam.lynam@catalyst.net.nz>
 * @copyright  Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace auth_simplesamlidp\Utils;

use auth_plugin_simplesamlidp;
use coding_exception;
use html_writer;
use lang_string;

defined('MOODLE_INTERNAL') || die();

abstract class Certificates
{
    /**
     * Ensure that valid certificates exist.
     * @copyright  Brendan Heywood <brendan@catalyst-au.net>
     * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
     *
     * @param auth_plugin_simplesamlidp $simpleSamlIdpAuth
     * @param false $dn
     * @param int $numberOfDays
     * @return lang_string|string
     * @throws coding_exception
     */
    public static function createCertificates(
        auth_plugin_simplesamlidp $simpleSamlIdpAuth,
        $dn = false,
        $numberOfDays = 3650
    ) {
        global $SITE;

        $signatureAlgorithm = SSLAlgorithms::getDefaultSamlSignatureAlgorithm();
        $opensslArgs = [
            'digest_alg' => SSLAlgorithms::convertSignatureAlgorithmToDigestAlgFormat($signatureAlgorithm),
        ];
        if (array_key_exists('OPENSSL_CONF', $_SERVER)) {
            $opensslArgs['config'] = $_SERVER['OPENSSL_CONF'];
        }

        if ($dn == false) {
            // These are somewhat arbitrary and aren't really seen except inside
            // the auto created certificate used to sign saml requests.
            $dn = [
                'commonName' => 'moodle',
                'countryName' => 'AU',
                'localityName' => 'moodleville',
                'emailAddress' => self::getDnEmail(),
                'organizationName' => $SITE->shortname ? $SITE->shortname : 'moodle',
                'stateOrProvinceName' => 'moodle',
                'organizationalUnitName' => 'moodle',
            ];
        }

        self::certificateOpensslErrorStrings(); // Ensure existing messages are dropped.
        $privKeyPass = get_site_identifier();
        $privKey = openssl_pkey_new($opensslArgs);
        $csr = openssl_csr_new($dn, $privKey, $opensslArgs);
        $ssCert = openssl_csr_sign($csr, null, $privKey, $numberOfDays, $opensslArgs);
        openssl_x509_export($ssCert, $publicKey);
        openssl_pkey_export($privKey, $privateKey, $privKeyPass, $opensslArgs);
        openssl_pkey_export($privKey, $privateKey, $privKeyPass);
        $errors = self::certificateOpensslErrorStrings();

        // Write Private Key and Certificate files to disk.
        // If there was a generation error with either explode.
        if (empty($privateKey)) {
            return get_string('nullprivatecert', 'auth_simplesamlidp') . $errors;
        }
        if (empty($publicKey)) {
            return get_string('nullpubliccert', 'auth_simplesamlidp') . $errors;
        }

        if (!file_put_contents($simpleSamlIdpAuth->certpem, $privateKey)) {
            return get_string('nullprivatecert', 'auth_simplesamlidp');
        }
        if (!file_put_contents($simpleSamlIdpAuth->certcrt, $publicKey)) {
            return get_string('nullpubliccert', 'auth_simplesamlidp');
        }

    }

    /**
     * Collect and render a list of OpenSSL error messages.
     *
     * @return string
     */
    public static function certificateOpensslErrorStrings()
    {
        $errors = array();
        while ($error = openssl_error_string()) {
            $errors[] = $error;
        }

        return html_writer::alist($errors);
    }

    /**
     * Return email for create_certificates function.
     *
     * @return string
     */
    public static function getDnEmail()
    {
        global $CFG;

        $supportUser = \core_user::get_support_user();

        if ($supportUser && !empty($supportUser->email)) {
            $email = $supportUser->email;
        } else if (isset($CFG->noreplyaddress) && !empty($CFG->noreplyaddress)) {
            $email = $CFG->noreplyaddress;
        } else {
            // Make sure that we get at least something to prevent failing of openssl_csr_new.
            $email = 'moodle@example.com';
        }

        return $email;
    }

    /**
     * A nicer version of print_r
     *
     * @param mixed $arr A variable to display
     * @return string html table
     */
    public static function prettyPrint($arr)
    {
        if (is_object($arr)) {
            $arr = (array) $arr;
        }
        $retStr = '<table class="generaltable">';
        $retStr .= '<tr><th class="header">'.get_string('key', 'auth_simplesamlidp').'</th><th class="header">'.get_string('value', 'auth_simplesamlidp').'</th></tr>';
        if (is_array($arr)) {
            foreach ($arr as $key => $val) {
                if (is_object($val)) {
                    $val = (array) $val;
                }
                if (is_array($val)) {
                    $retStr .= '<tr><td>' . $key . '</td><td>' . self::prettyPrint($val) . '</td></tr>';
                } else {
                    if (strpos($key, 'valid') !== false && ($val * 1) === $val) {
                        $val = userdate($val) . " ($val)";
                    }
                    $retStr .= '<tr><td>' . $key . '</td><td>' . ($val == '' ? '""' : $val) . '</td></tr>';
                }
            }
        }
        $retStr .= '</table>';
        return $retStr;
    }

}
