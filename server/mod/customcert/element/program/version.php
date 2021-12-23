<?php
/**
 * Version details
 *
 * @package   customcertelement_program
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2019 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

defined('MOODLE_INTERNAL') || die;

$plugin->version = 2019070800;
$plugin->requires = 2017051509;
$plugin->component = 'customcertelement_program';
$plugin->dependencies = [
    'totara_program' => ANY_VERSION
];