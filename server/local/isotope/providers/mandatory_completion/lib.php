<?php
/**
 * Plugin library
 *
 * @package isotopeprovider_mandatory_completion
 * @author Ben Lobo <ben.lobo@kineo.com>
 * @copyright City & Guilds Kineo 2019
 * @license http://www.kineo.com
 */

use isotopeprovider_mandatory_completion\Provider;

defined('MOODLE_INTERNAL') || die;

function isotopeprovider_mandatory_completion_pluginfile($course, $cm, $context, $fileArea, $args, $forceDownload, $options = [])
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
