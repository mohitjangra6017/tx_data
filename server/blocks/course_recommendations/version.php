<?php

/**
 * Comment
 *
 * @package    block
 * @subpackage course_recommendations
 * @copyright  &copy; 2017 CG Kineo {@link http://www.kineo.com}
 * @author     kaushtuv.gurung
 * @version    1.0
 */


defined('MOODLE_INTERNAL') || die();

$plugin->version = 2021111100;
$plugin->requires = 2021110500;
$plugin->component = 'block_course_recommendations';
$plugin->release = \local_core\ComposerPluginInfo::getInstance()->getInstalledPluginVersion('block-course-recommendations');
$plugin->dependencies = array('block_rate_course' => 2014081213);

