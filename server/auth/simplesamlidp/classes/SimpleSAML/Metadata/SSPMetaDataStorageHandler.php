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

namespace auth_simplesamlidp\SimpleSAML\Metadata;

use SimpleSAML\Metadata\MetaDataStorageHandler;

/**
 * This class overrides the SimpleSAMLphp MetaDataStorageHandlerdefines class
 * which is a class for metadata handling. This override is required to fix the
 * path constants used in the getGenerated() method so that the URLs in the
 * generated metadata point to the correct location in the auth_simplesamlidp
 * plugin in Totara.
 *
 * @package   auth_simplesamlidp
 * @author    Ben Lobo <ben.lobo@kineo.com>
 * @copyright 2019 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

class SSPMetaDataStorageHandler extends MetaDataStorageHandler
{
    /**
     * This static variable contains a reference to the current
     * instance of the metadata handler. This variable will be null if
     * we haven't instantiated a metadata handler yet.
     *
     * @var SSPMetaDataStorageHandler
     */
    private static $metadataHandler = null;

    /**
     * This function retrieves the current instance of the metadata handler.
     * The metadata handler will be instantiated if this is the first call
     * to this function.
     *
     * @return SSPMetaDataStorageHandler The current metadata handler instance.
     */
    public static function getMetadataHandler()
    {
        if (self::$metadataHandler === null) {
            self::$metadataHandler = new SSPMetaDataStorageHandler();
        }

        return self::$metadataHandler;
    }

    /**
     * This constructor initializes this metadata storage handler. It will load and
     * parse the configuration, and initialize the metadata source list.
     */
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * This function is used to generate some metadata elements automatically.
     *
     * @param string $property The metadata property which should be auto-generated.
     * @param string $set The set we the property comes from.
     *
     * @return string The auto-generated metadata property.
     * @throws \Exception If the metadata cannot be generated automatically.
     */
    public function getGenerated($property, $set)
    {
        // first we check if the user has overridden this property in the metadata
        try {
            $metadataSet = $this->getMetaDataCurrent($set);
            if (array_key_exists($property, $metadataSet)) {
                return $metadataSet[$property];
            }
        } catch (\Exception $e) {
            // probably metadata wasn't found. In any case we continue by generating the metadata
        }

        // get the configuration
        $config = \SimpleSAML\Configuration::getInstance();
        assert($config instanceof \SimpleSAML\Configuration);

        $baseurl = \SimpleSAML\Utils\HTTP::getSelfURLHost().$config->getBasePath();

        if ($set == 'saml20-sp-hosted') {
            if ($property === 'SingleLogoutServiceBinding') {
                return \SAML2\Constants::BINDING_HTTP_REDIRECT;
            }
        } elseif ($set == 'saml20-idp-hosted') {
            switch ($property) {
                case 'SingleSignOnService':
                    return $baseurl.'SSOService.php';

                case 'SingleSignOnServiceBinding':
                    return \SAML2\Constants::BINDING_HTTP_REDIRECT;

                case 'SingleLogoutService':
                    return $baseurl.'SingleLogoutService.php';

                case 'SingleLogoutServiceBinding':
                    return \SAML2\Constants::BINDING_HTTP_REDIRECT;
            }
        } elseif ($set == 'shib13-idp-hosted') {
            if ($property === 'SingleSignOnService') {
                return $baseurl.'shib13/idp/SSOService.php';
            }
        }

        throw new \Exception('Could not generate metadata property '.$property.' for set '.$set.'.');
    }

}