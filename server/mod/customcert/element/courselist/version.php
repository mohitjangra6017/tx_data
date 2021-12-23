<?php
/**
 * Version details
 *
 * @package   customcertelement_courselist
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2019 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

defined('MOODLE_INTERNAL') || die;

$plugin->version = 20190700500;
$plugin->requires = 2017051509;
$plugin->component = 'customcertelement_courselist';
$plugin->dependencies = [
    'totara_program' => ANY_VERSION
];