<?php

/**
 * Comment
 *
 * @package    block
 * @subpackage awesome
 * @copyright  &copy; 2017 CG Kineo {@link http://www.kineo.com}
 * @author     kaushtuv.gurung
 * @version    1.0
 */

defined('MOODLE_INTERNAL') || die();

function block_awesome_pluginfile($course, $birecord_or_cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    global $DB, $CFG, $USER;

    if ($context->contextlevel != CONTEXT_BLOCK) {
        send_file_not_found();
    }

    // If block is in course context, then check if user has capability to access course.
    if ($context->get_course_context(false)) {
        require_course_login($course);
    } else if ($CFG->forcelogin) {
        require_login();
    } else {
        // Get parent context and see if user have proper permission.
        $parentcontext = $context->get_parent_context();
        if ($parentcontext->contextlevel === CONTEXT_COURSECAT) {
            if ($parentcontext->is_user_access_prevented()) {
                send_file_not_found();
            }
            // Check if category is visible and user can view this category.
            $category = $DB->get_record('course_categories', array('id' => $parentcontext->instanceid), '*', MUST_EXIST);
            if (!$category->visible) {
                require_capability('moodle/category:viewhiddencategories', $parentcontext);
            }
        } else if ($parentcontext->contextlevel === CONTEXT_USER && $parentcontext->instanceid != $USER->id) {
            // The block is in the context of a user, it is only visible to the user who it belongs to.
            send_file_not_found();
        }
        // At this point there is no way to check SYSTEM context, so ignoring it.
    }

    $fs = get_file_storage();

    $filename = array_pop($args);
    $filepath = array_shift($args) ? '/'.implode('/', array_shift($args)).'/' : '/';

    if (!$file = $fs->get_file($context->id, 'block_awesome', 'image', 0, $filepath, $filename) or $file->is_directory()) {
        send_file_not_found();
    }

    if ($parentcontext = context::instance_by_id($birecord_or_cm->parentcontextid, IGNORE_MISSING)) {
        if ($parentcontext->contextlevel == CONTEXT_USER) {
            // force download on all personal pages including /my/
            //because we do not have reliable way to find out from where this is used
            $forcedownload = true;
        }
    } else {
        // weird, there should be parent context, better force dowload then
        $forcedownload = true;
    }

    // NOTE: it woudl be nice to have file revisions here, for now rely on standard file lifetime,
    //       do not lower it because the files are dispalyed very often.
    \core\session\manager::write_close();
    send_stored_file($file, null, 0, $forcedownload, $options);
}

/**
 * Perform global search replace such as when migrating site to new URL.
 * @param $search
 * @param $replace
 * @throws dml_exception
 */
function block_awesome_global_db_replace($search, $replace) {
    global $DB;

    $instances = $DB->get_recordset('block_instances', array('blockname' => 'awesome'));
    foreach ($instances as $instance) {
        // TODO: intentionally hardcoded until MDL-26800 is fixed
        $config = unserialize(base64_decode($instance->configdata));
        if (isset($config->linktext) and is_string($config->linktext)) {
            $config->linktext = str_replace($search, $replace, $config->linktext);
        }
        
        if (isset($config->url) and is_string($config->url)) {
            $config->url = str_replace($search, $replace, $config->url);
        }
        
        if (isset($config->headertext) and is_string($config->headertext)) {
            $config->headertext = str_replace($search, $replace, $config->headertext);
        }
        
        if (isset($config->subheadertext) and is_string($config->subheadertext)) {
            $config->subheadertext = str_replace($search, $replace, $config->subheadertext);
        }
        
        if (isset($config->subheaderurl) and is_string($config->subheaderurl)) {
            $config->subheaderurl = str_replace($search, $replace, $config->subheaderurl);
        }
        
        if (isset($config->contenttext) and is_string($config->contenttext)) {
            $config->contenttext = str_replace($search, $replace, $config->contenttext);
        }
        
        $DB->set_field('block_instances', 'configdata', base64_encode(serialize($config)), array('id' => $instance->id));
    }
    $instances->close();
}