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
 * Admin config settings class
 * 
 * @package   auth_simplesamlidp
 * @author    Ben Lobo <ben.lobo@kineo.com>
 * @copyright 2019 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace auth_simplesamlidp\Settings;

use admin_setting_configtextarea;
use coding_exception;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once("{$CFG->libdir}/adminlib.php");

/**
 * Admin setting class to handle validation and saving of SP Metadata.
 *
 * @package     auth_simplesamlidp
 * @author      Ben Lobo <ben.lobo@kineo.com>
 */
class SpMetadata extends admin_setting_configtextarea
{
    public function __construct()
    {
        // All parameters are hardcoded because there can be only one instance:
        parent::__construct(
            'auth_simplesamlidp/spmetadata',
            get_string('spmetadata', 'auth_simplesamlidp'),
            get_string('spmetadata_help', 'auth_simplesamlidp'),
            '',
            PARAM_NOTAGS,
            80,
            5);
    }

    /**
     * Validate data before storage
     *
     * @param string $value
     * @return true|string Error message in case of error, true otherwise.
     * @throws coding_exception
     */
    public function validate($value)
    {
        if (parent::validate($value) !== true) {
            return get_string('notags', 'auth_simplesamlidp');
        }

        $value = trim($value);

        try {
            $this->processSpMetadata($value);
            $this->saveIdPMetadata();
        } catch (SpMetadataException $exception) {
            return $exception->getMessage();
        }

        return true;
    }

    private function processSpMetadata($spMetadata)
    {
        $this->saveSpMetadata($spMetadata);
    }

    private function saveSpMetadata($spMetadata)
    {
        global $CFG, $simplesamlidpauth;
        require_once("{$CFG->dirroot}/auth/simplesamlidp/setup.php");

        if (!empty($spMetadata)) {
            $spMetadata = "<?php\n" . $spMetadata;
        }

        $file = $simplesamlidpauth->getSpMetadataFile();
        file_put_contents($file, $spMetadata);
    }

    private function saveIdPMetadata()
    {
        global $CFG, $simplesamlidpauth;
        require_once("{$CFG->dirroot}/auth/simplesamlidp/setup.php");

        $privKeyPass = get_site_identifier();

        $metadata = "<?php\n";
        $metadata .= "\$metadata['{$CFG->wwwroot}/auth/simplesamlidp/idp/metadata.php'] = [\n";
        $metadata .= "    'host' => '__DEFAULT__',\n";
        $metadata .= "    'privatekey' => '{$simplesamlidpauth->idpname}.pem',\n";
        $metadata .= "    'privatekey_pass' => '{$privKeyPass}',\n";
        $metadata .= "    'certificate' => '{$simplesamlidpauth->idpname}.crt',\n";
        $metadata .= "    'auth' => '{$simplesamlidpauth->authsource}',\n";
        $metadata .= "];\n";

        $file = $simplesamlidpauth->getIdPMetadataFile();
        file_put_contents($file, $metadata);
    }
}
