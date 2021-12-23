<?php
/**
 * Plugin library
 *
 * @package   isotope_provider_programs
 * @author Ben Lobo <ben.lobo@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

use isotopeprovider_programs\Provider;

defined('MOODLE_INTERNAL') || die;

function isotopeprovider_programs_pluginfile($course, $cm, $context, $fileArea, $args, $forceDownload, $options = [])
{
    require_login($course, true, $cm);

    if ($context->contextlevel == CONTEXT_SYSTEM && $fileArea === 'defaults') {
        $itemId = array_shift($args);
        $fileName = array_pop($args);
        $filePath = $args ? '/' . implode('/', $args) . '/' : '/';

        if ($file = get_file_storage()->get_file($context->id, Provider::COMPONENT, $fileArea, $itemId, $filePath, $fileName)) {
            send_stored_file($file, 86400, 0, $forceDownload, $options);
        }
    }
}
