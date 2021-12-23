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
 * SimpleSAMLphp module file
 *
 * @package   auth_simplesamlidp
 * @author    Ben Lobo <ben.lobo@kineo.com>
 * @copyright 2019 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace SimpleSAML\Module\totara\Auth\Source;

class External extends \SimpleSAML\Auth\Source
{
    const STATE_IDENT = 'totara:External';
    private $config;

    public function __construct($info, $config)
    {
        assert(is_array($info));
        assert(is_array($config));

        parent::__construct($info, $config);

        if (!isset($config['cookie_name'])) {
            throw new \SimpleSAML\Error\Exception('Misconfiguration in authsources');    # in the Totara part in config/authsources.php there must be 'cookie_name' setting
        }

        $sspConfig = \SimpleSAML\Configuration::getInstance();
        $config['cookie_path'] = $sspConfig->getValue('session.cookie.path');
        $config['cookie_salt'] = $sspConfig->getValue('secretsalt');
        $this->config = $config;
    }

    public function authenticate(&$state)
    {
        assert(is_array($state));

        $user = $this->getUser();
        if ($user) {
            // User authenticated, nothing to do
            $state['Attributes'] = $user;
            return;
        } else {
            // Redirect to a login page

            /*
             * First we add the identifier of this authentication source
             * to the state array, so that we know where to resume.
             */
            $state['totara:AuthID'] = $this->authId;

            $stateId = \SimpleSAML\Auth\State::saveState($state, self::STATE_IDENT);

            $returnTo = \SimpleSAML\Module::getModuleURL('totara/resume.php', ['State' => $stateId]);
            $authPage = $this->config['login_url'] . '?ReturnTo=' . $returnTo;
            \SimpleSAML\Utils\HTTP::redirectTrustedURL($authPage, [
                'ReturnTo' => $returnTo,
            ]);
        }
    }

    public static function resume()
    {
        if (!isset($_REQUEST['State'])) {
            throw new \SimpleSAML\Error\BadRequest('Missing "State" parameter.');
        }

        $state = \SimpleSAML\Auth\State::loadState($_REQUEST['State'], self::STATE_IDENT);

        $source = \SimpleSAML\Auth\Source::getById($state['totara:AuthID']);

        if ($source === null) {
            throw new \SimpleSAML\Error\Exception('Could not find authentication source with id ' . $state['totara:AuthID']);
        }

        if (!($source instanceof self)) {
            throw new \SimpleSAML\Error\Exception('Authentication source type changed.');
        }

        $user = $source->getUser();
        if ($user === null) {
            throw new \SimpleSAML\Error\Exception('User not authenticated after login page.');
        }
        $state['Attributes'] = $user;

        \SimpleSAML\Auth\Source::completeAuth($state);
    }

    private function getUser()
    {
        if (!session_id()) {
            // session_start not called before. Do it here
            session_start();
        }

        $uid = 0;
        if (isset($_COOKIE[$this->config['cookie_name']]) && $_COOKIE[$this->config['cookie_name']]) {
            $strCookie = $_COOKIE[$this->config['cookie_name']];
            // cookie created by: "setcookie($cookieName['cookie_name'], sha1($salt . $uid).':'.$uid, 0, $sspConfig->getValue('session.cookie.path'));"
            // in auth/simplesamlidp/auth.php in Totara
            $arrCookie = explode(':', $strCookie);

            if ((isset($arrCookie[0]) && $arrCookie[0]) 
                && (isset($arrCookie[1]) && $arrCookie[1]) 
            ) {
                # make sure no one manipulated the hash or the uid in the cookie before we trust the uid
                if (sha1($this->config['cookie_salt'] . $arrCookie[1]) == $arrCookie[0]) {
                    $uid = (int)$arrCookie[1];
                } else {
                    throw new \SimpleSAML\Error\Exception('Cookie hash invalid.');
                }
            }
        }

        // Our cookie must be removed here.
        if (isset($_COOKIE[$this->config['cookie_name']])) {
            setcookie($this->config['cookie_name'], "", time() - 3600, $this->config['cookie_path']);
        }

        if ($uid) {
            // Bootstrap Totara
            global $CFG, $DB;
            define('WEB_CRON_EMULATED_CLI', 'defined');
            $configPhp = $this->config['totara_coderoot']."/config.php";
            if (file_exists($configPhp)) {
                require_once($configPhp);
            } else {
                throw new \SimpleSAML\Error\Exception('Totara app instantiation failure: cannot require()' . $configPhp);
            }

            // Query for a user
            $user = $DB->get_record('user', array('id' => $uid));
            // simplesaml is expecting a very weird structure in attributes ($user)
            // we also don't reveal user password, auth method, secret
            if ($user) {
                unset($user->auth);
                unset($user->password);
                unset($user->secret);
                $user->uid = $user->id;     // this may not be strictly necessary. just nice-to-have
                foreach ((array)$user as $param => $value) {
                    $userAttr[$param] = array($value);
                }
            }
            return $userAttr;
        } else {
            return null;
        }
        return null;
    }

    public function logout(&$state)
    {
        assert(is_array($state));

        if (!session_id()) {
            // session_start not called before. Do it here
            session_start();
        }

        if (isset($_COOKIE[$this->config['cookie_name']])) {
            setcookie($this->config['cookie_name'], "", time() - 3600, $this->config['cookie_path']);
        }

    }

}
