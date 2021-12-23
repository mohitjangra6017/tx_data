<?php
 
 /**
 * Description
 *
 * @copyright  &copy; 2020-11-27 15:05:42 CG Kineo {@link http://www.kineo.com}
 * @author     kaushtuv.gurung
 * @version    1.0
 */

define('AJAX_SCRIPT', true);

require_once('../../../config.php');
global $DB, $PAGE, $CFG;
require_once($CFG->dirroot . '/blocks/carousel/lib.php');
require_once($CFG->dirroot . '/totara/coursecatalog/lib.php');

require_login();

use block_carousel\helper;

$courseid = required_param('courseid', PARAM_INT);
$nodisplay = optional_param('nodisplay', 0, PARAM_INT);
// We will query the activity id based on the course id
// this will be based on the assumption that it is a single activity course
// there will only be one activity attached to it
[$v_sql, $params] = totara_visibility_where(null, 'c.id', 'c.visible', 'c.audiencevisible', 'c');
$sql = "SELECT c.id, m.name, cm.instance, cm.id AS cmid
          FROM {course} c
     LEFT JOIN {course_modules} cm
            ON cm.course = c.id
     LEFT JOIN {modules} m 
            ON m.id = cm.module 
         WHERE c.id = :courseid
         AND c.containertype = :containertype
         AND {$v_sql}
         LIMIT 1
";
$params['courseid'] = $courseid;
$params['containertype'] = \container_course\course::get_type();
// Limit 1 is just a precaution
$record = $DB->get_record_sql($sql, $params);

$out['success'] = true;

if (empty($record)) {
    echo json_encode($out + ['html' => 'No record found']);
    die();
}

$module_name = $record->name;
$allowed_modules = ['url', 'resource'];
if (!in_array($module_name, $allowed_modules)) {
    echo json_encode($out + ['html' => 'Invalid module type']);
    die();
}

// else continue processing


$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$cm = get_coursemodule_from_id($module_name, $record->cmid, 0, false, MUST_EXIST);
$module = $DB->get_record($module_name, ['id' => $record->instance], '*', MUST_EXIST);

require_course_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability("mod/$module_name:view", $context);

// Completion and trigger events.
$module_viewed_trigger_function = $module_name."_view";
$module_viewed_trigger_function($module, $course, $cm, $context);

// If the rquest is to just process the 
// enrolment / completions etc
// end here
if ($nodisplay) {
    echo json_encode($out + ['html' => '']);
    die();
}


$renderer = $PAGE->get_renderer('block_carousel');
$template_context = new stdClass();
$template_context->name = $module->name;
$template_context->intro = $module->intro;

// Process 
switch ($module_name) {
    case 'url':
        require_once($CFG->dirroot . '/mod/url/locallib.php');
        $extra = "target='_blank';\"";
        $template_context->resource_link = get_string('clicktoopen', 'url', "<a href=\"$module->externalurl\" $extra>$module->externalurl</a>");
        break;

    case 'resource':
        require_once($CFG->dirroot . '/mod/resource/locallib.php');
        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'mod_resource', 'content', 0, 'sortorder DESC, id ASC', false); // TODO: this is not very efficient!!
        if (count($files) < 1) {
            $template_context->resource_link = '';
        } else {
            $file = reset($files);
            unset($files);

            $module->mainfile = $file->get_filename();

            switch (resource_get_final_display_type($module)) {
                case RESOURCELIB_DISPLAY_NEW:
                    $extra = "target='_blank';\"";
                    $template_context->resource_link = helper::resource_get_clicktoopen($courseid, $file, $module->revision, $extra);
                    break;
        
                case RESOURCELIB_DISPLAY_DOWNLOAD:
                    $template_context->resource_link = helper::resource_get_clicktodownload($courseid, $file, $module->revision);
                    break;
        
                case RESOURCELIB_DISPLAY_OPEN:
                default:
                    $extra = "target='_blank';\"";
                    $template_context->resource_link = helper::resource_get_clicktoopen($courseid, $file, $module->revision, $extra);
                    break;
            }
        }
        break;
}

$out['html'] = $renderer->render_courselets_modal_info($template_context);

echo json_encode($out);