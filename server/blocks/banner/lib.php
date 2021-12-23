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
use block_banner\ImageStrategies\First;
use block_banner\ImageStrategies\Random;
use block_banner\StrategyManager;
use block_banner\VariablesStrategies\Variables;
use core\session\manager;

/**
 * Form for editing HTML block instances.
 *
 * @copyright 2010 Petr Skoda (http://skodak.org)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package   block_banner
 * @category  files
 * @param stdClass $course course object
 * @param stdClass $birecord_or_cm block instance record
 * @param stdClass $context context object
 * @param string $filearea file area
 * @param array $args extra arguments
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return void
 * @throws coding_exception
 * @throws dml_exception
 * @throws moodle_exception
 * @throws require_login_exception
 * @throws required_capability_exception
 * @todo MDL-36050 improve capability check on stick blocks, so we can check user capability before sending images.
 */
function block_banner_pluginfile(
    $course,
    $birecord_or_cm,
    $context,
    $filearea,
    $args,
    $forcedownload,
    array $options = []
) {
    global $DB, $CFG, $USER;

    if ($context->contextlevel != CONTEXT_BLOCK) {
        send_file_not_found();
    }

    // If block is in course context, then check if user has capability to access course.
    if ($context->get_course_context(false)) {
        require_course_login($course, true, null, false);
    } else if ($CFG->forcelogin) {
        require_login();
    } else {
        // Get parent context and see if user have proper permission.
        $parentcontext = $context->get_parent_context();
        if ($parentcontext->is_user_access_prevented()) {
            send_file_not_found();
        }
        if ($parentcontext->contextlevel === CONTEXT_COURSECAT) {
            // Check if category is visible and user can view this category.
            $category = $DB->get_record('course_categories', ['id' => $parentcontext->instanceid], '*', MUST_EXIST);
            if (!$category->visible) {
                require_capability('moodle/category:viewhiddencategories', $parentcontext);
            }
        } else if ($parentcontext->contextlevel === CONTEXT_USER && $parentcontext->instanceid != $USER->id) {
            // The block is in the context of a user, it is only visible to the user who it belongs to.
            send_file_not_found();
        }
        // At this point there is no way to check SYSTEM context, so ignoring it.
    }

    if ($filearea !== 'content') {
        send_file_not_found();
    }

    $fs = get_file_storage();

    $filename = array_pop($args);
    $filepath = $args ? '/' . implode('/', $args) . '/' : '/';

    if (!$file = $fs->get_file($context->id, 'block_banner', 'content', 0, $filepath, $filename) or $file->is_directory(
        )) {
        send_file_not_found();
    }

    if ($parentcontext = context::instance_by_id($birecord_or_cm->parentcontextid, IGNORE_MISSING)) {
        if ($parentcontext->contextlevel == CONTEXT_USER) {
            $forcedownload = true;
        }
    } else {
        $forcedownload = true;
    }
    manager::write_close();
    send_stored_file($file, null, 0, $forcedownload, $options);
}

/**
 * Perform global search replace such as when migrating site to new URL.
 * @param  $search
 * @param  $replace
 * @throws dml_exception
 */
function block_banner_global_db_replace($search, $replace): void
{
    global $DB;

    $instances = $DB->get_recordset('block_instances', ['blockname' => 'banner']);
    foreach ($instances as $instance) {
        // TODO: intentionally hardcoded until MDL-26800 is fixed.
        $config = unserialize(base64_decode($instance->configdata));
        if (isset($config->text) and is_string($config->text)) {
            $config->text = str_replace($search, $replace, $config->text);
            $DB->set_field('block_instances', 'configdata', base64_encode(serialize($config)), ['id' => $instance->id]);
        }
    }
    $instances->close();
}

/**
 * @return StrategyManager
 */
function block_banner_get_strategy_manager(): StrategyManager
{
    global $CFG;
    $strategies = [];

    $strategies['first'] = new First();
    $strategies['random'] = new Random();
    $strategies['variables'] = new Variables();

    foreach (glob("{$CFG->dirroot}/local/custom/classes/Plugins/Block/Banner/Strategies/*") as $customStrategy) {
        require_once($customStrategy);
        $namespace = 'local_custom\Plugins\Block\Banner\Strategies\\';
        $class = str_replace('.php', '', array_reverse(explode('/', $customStrategy))[0]);
        $strategyClass = $namespace . $class;
        if (class_exists($strategyClass)) {
            $strategyShortname = strtolower($class);
            $strategy = new $strategyClass();
            if (isset($strategies[$strategyShortname])) {
                $strategies[$strategyShortname] = $strategy;
            } else {
                $strategies = [$strategyShortname => $strategy] + $strategies;
            }
        }
    }

    return new StrategyManager($strategies);
}
