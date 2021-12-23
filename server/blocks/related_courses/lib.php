<?php
/**
 * @copyright City & Guilds Kineo 2018
 * @author Michael Geering <michael.geering@kineo.com>
 */

/**
 * @param int $course
 * @param int $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @throws coding_exception
 * @throws moodle_exception
 * @throws require_login_exception
 */
function block_related_courses_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, $options)
{
    require_login();
    $component = 'block_related_courses';
    $itemid = $args[0];
    $filename = $args[1];
    $fs = get_file_storage();

    $file = $fs->get_file($context->id, $component, $filearea, $itemid, '/', $filename);

    if (empty($file)) {
        send_file_not_found();
    }

    send_stored_file($file, 60 * 60 * 24, 0, false, $options); // Enable long cache and disable forcedownload.
}