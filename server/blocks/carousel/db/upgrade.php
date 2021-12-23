<?php
/**
 * Upgrade function
 *
 * @package    block_carousel
 * @copyright  &copy; 2021 Kineo Pty Ltd  {@link http://kineo.com/au}
 * @author     tri.le
 * @version    1.0
*/

defined('MOODLE_INTERNAL') || die;

function xmldb_block_carousel_upgrade($oldVersion)
{
    global $DB;
    $dbMan = $DB->get_manager();

    return true;
}