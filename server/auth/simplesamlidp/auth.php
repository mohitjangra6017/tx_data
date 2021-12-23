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
 * Authentication Plugin: SimpleSAMLphp Identity Provider (IdP)
 *
 * Enables Totara to act as an IdP using SimpleSAMLphp.
 *
 * @package   auth_simplesamlidp
 * @author    Ben Lobo <ben.lobo@kineo.com>
 * @copyright 2019 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir.'/authlib.php');

class auth_plugin_simplesamlidp extends auth_plugin_base
{

    const COMPONENT_NAME = 'auth_simplesamlidp';
    const AUTH_COOKIE_DEFAULT = 'TotaraSimpleSAMLIdPSessionID';

    /**
     * @var array $defaults The config defaults
     */
    public $defaults = [
        'spmetadata'         => '',
        'debug'              => 0,
        'logtofile'          => 0,
        'logdir'             => '/tmp/',
    ];

    /**
     * @var string
     */
    public $authsource;

    /**
     * @var string
     */
    public $idpname;

    /**
     * @var string
     */
    public $certpem;

    /**
     * @var string
     */
    public $certcrt;

    /**
     * Constructor.
     */
    public function __construct()
    {
        global $CFG;

        $this->authtype = 'simplesamlidp';
        $this->authsource = 'totara-userpass'; // The name of the SimpleSAMLphp authentication source to use.

        $mdl = new moodle_url($CFG->wwwroot);
        $this->idpname = $mdl->get_host();
        $this->certpem = $this->getFile("{$this->idpname}.pem");
        $this->certcrt = $this->getFile("{$this->idpname}.crt");
        $this->config = (object) array_merge($this->defaults, (array) get_config(self::COMPONENT_NAME));
    }

    public function getSimpleSamlIdpDirectory()
    {
        global $CFG;

        $directory = "{$CFG->dataroot}/simplesamlidp";
        if (!file_exists($directory)) {
            mkdir($directory);
        }
        return $directory;
    }

    public function getFile($file)
    {
        return $this->getSimpleSamlIdpDirectory() . '/' . $file;
    }

    public function getSpMetadataFile()
    {
        return $this->getFile('saml20-sp-remote.php');
    }

    public function getIdPMetadataFile()
    {
        return $this->getFile('saml20-idp-hosted.php');
    }

    /**
     * Saves $_GET{'ReturnTo'}
     *
     * @throws coding_exception
     */
    public function loginpage_hook()
    {
        // If the plugin has not been configured then do NOT try to use simplesamlidp.
        if ($this->is_configured() === false) {
            return;
        }

        if (isset($_GET['ReturnTo']) && $_GET['ReturnTo']) {
            if (isloggedin() and !isguestuser()) {
                // This is a consequent return to Moodle via SAML
                // it needs to be handled a usual SAML way - create a cookie and redirect to the return address
                global $USER;
                $this->setCookie($USER);
                header('Location: '. $_GET['ReturnTo']);
                exit();
            } else {
                global $SESSION;
                $SESSION->samlurl = $_GET['ReturnTo'];  # record $_GET['ReturnTo'] as it will not be available later
            }
        }
    }

    /**
     * Sets a module-specific cookie to send a user ID to SimpleSAMLphp
     * called from loginpage_hook() and user_authenticated_hook()
     *
     * @param $user
     * @throws Exception
     */
    private function setCookie($user)
    {
        require_once('setup.php');
        $sspConfig = \SimpleSAML\Configuration::getInstance();
        $sspAuthSources = \SimpleSAML\Configuration::getConfig('authsources.php');
        $authSource = $sspAuthSources->getValue($this->authsource);
        $uid = $user->id;
        if ($authSource && isset($authSource['cookie_name']) && $authSource['cookie_name']) {
            $salt = $sspConfig->getValue('secretsalt');
            setcookie($authSource['cookie_name'], sha1($salt . $uid).':'.$uid, 0, $sspConfig->getValue('session.cookie.path'));
        } else {
            $this->reportMisconfiguredAuthsources();
        }
    }

    /**
     * If configured properly, sets an authentication cookie, encrypts a user ID into the cookie value
     *
     * @param object $user user object, later used for $USER
     * @param string $username (with system magic quotes)
     * @param string $password plain text password (with system magic quotes)
     * @throws Exception
     */
    public function user_authenticated_hook(&$user, $username, $password)
    {
        global $SESSION, $USER;

        $this->setCookie($user);

        if (isset($SESSION->samlurl) && $SESSION->samlurl) {
            $samlUrl = $SESSION->samlurl;
            unset($SESSION->samlurl);

            // Make sure all user data is fetched.
            $user = get_complete_user_data('username', $user->username);
            complete_user_login($user);     # need to run it here otherwise the moodle user is not really logged in
            $USER->loggedin = true;
            $USER->site = $CFG->wwwroot;
            set_moodle_cookie($USER->username);

            header('Location: '. $samlUrl);
            exit();                         # need to exit as otherwise moodle takes control and redirects to own /
        }
    }

    /**
     * If configured properly, destroys own auth cookie, optionally redirects to the ReturnTo URL
     *
     * @param stdClass $user clone of USER object before the user session was terminated
     * @throws Exception
     */
    public function postlogout_hook($user)
    {
        require_once('setup.php');

        $sspConfig = \SimpleSAML\Configuration::getInstance();
        $sspAuthsources = \SimpleSAML\Configuration::getConfig('authsources.php');
        $authSource = $sspAuthsources->getValue($this->authsource);

        // If there's a live SAML session make sure we log out so that the user
        // will be logged out of federated applications when their sessions expire.
        $samlSession = \SimpleSAML\Session::getSessionFromRequest();
        $samlSession->doLogout($this->authsource);

        if ($authSource && isset($authSource['cookie_name']) && $authSource['cookie_name']) {

            setcookie($authSource['cookie_name'], '',  time() - 3600, $sspConfig->getValue('session.cookie.path'));

            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params['path'], $params['domain'],
                    $params['secure'], $params['httponly']
                );
            }
            if (isset($_GET['ReturnTo']) && $_GET['ReturnTo']) {
                header('Location: '.$_GET['ReturnTo']);
                exit();
            }
        } else {
            $this->reportMisconfiguredAuthsources();
        }

    }

    /**
     * Reports a configuration error into http server error.log
     *
     * @return void
     */
    private function reportMisconfiguredAuthsources()
    {
        $msg = sprintf("Misconfigured SimpleSAMLphp IdP (missing configuration block for '%s' in authsources.php, or 'cookie_name' entry in the block) 
            or incorrect SAML IdP Moodle module configuration (wrong authsource)", $this->authsource);
        trigger_error($msg, E_USER_WARNING);
    }

    /**
     * Returns true if the username and password work and false if they are
     * wrong or don't exist.
     *
     * @param string $username The username
     * @param string $password The password
     * @return bool Authentication success or failure.
     */
    public function user_login($username, $password)
    {
        return false;
    }

    /**
     * Called when the user record is updated.
     * Modifies user in external database. It takes olduser (before changes) and newuser (after changes)
     * compares information saved modified information to external db.
     *
     * @param stdClass $olduser     Userobject before modifications
     * @param stdClass $newuser     Userobject new modified userobject
     * @return boolean result
     *
     */
    public function user_update($olduser, $newuser)
    {
        return false;
    }

    /**
     * Returns true if this authentication plugin is "internal".
     *
     * Internal plugins use password hashes from Moodle user table for authentication.
     *
     * @return bool
     */
    public function is_internal()
    {
        return false;
    }

    /**
     * Returns false if this plugin is enabled but not configured.
     *
     * @return bool
     */
    public function is_configured()
    {
        if (!empty($this->config->spmetadata)) {
            return true;
        }
        return false;
    }

    /**
     * Indicates if moodle should automatically update internal user
     * records with data from external sources using the information
     * from auth_plugin_base::get_userinfo().
     *
     * @return bool true means automatically copy data from ext to user table
     */
    public function is_synchronised_with_external()
    {
        return false;
    }

    /**
     * Returns true if this authentication plugin can change the user's
     * password.
     *
     * @return bool
     */
    public function can_change_password()
    {
        return false;
    }

    /**
     * Returns the URL for changing the user's pw, or empty if the default can
     * be used.
     *
     * @return moodle_url
     */
    public function change_password_url()
    {
        return null;
    }

    /**
     * Returns true if plugin allows resetting of internal password.
     *
     * @return bool
     */
    public function can_reset_password()
    {
        return false;
    }

}
