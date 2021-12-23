<?php
/**
 * External services definitions.
 *
 * @package   block_related_courses
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2016 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

defined('MOODLE_INTERNAL') || die;

$functions = [
    'block_related_courses_enrol' => [
        'classname' => 'block_related_courses_external',
        'methodname' => 'enrol',
        'classpath' => 'blocks/related_courses/externallib.php',
        'description' => get_string('service:enrol', 'block_related_courses'),
        'capabilities' => '',
        'type' => 'write',
        'ajax' => true,
    ],
];