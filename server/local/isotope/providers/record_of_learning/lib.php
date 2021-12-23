<?php
/**
 * Plugin library
 *
 * @package   isotopeprovider_record_of_learning
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

use isotopeprovider_record_of_learning\Provider;

defined('MOODLE_INTERNAL') || die;

function isotopeprovider_record_of_learning_pluginfile($course, $cm, $context, $fileArea, $args, $forceDownload, $options = [])
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
