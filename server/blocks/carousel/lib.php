<?php

/**
 * Library file
 *
 * @package    block
 * @subpackage kineo_carousel
 * @copyright  &copy; 2020 CG Kineo {@link http://www.kineo.com}
 * @author     kaushtuv.gurung
 * @version    1.0
 */

/**
 * Server plugin file
 */
function block_carousel_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_BLOCK and $filearea === 'curated') {
        $itemid = array_shift($args);

        $filename = array_pop($args);
        if (!$args) {
            $filepath = '/';
        } else {
            $filepath = '/'.implode('/', $args).'/';
        }

        // Retrieve the file from the Files API.
        $fs = get_file_storage();
        $file = $fs->get_file($context->id, 'block_carousel', $filearea, $itemid, $filepath, $filename);

        if (!$file) {
            return false; // The file does not exist.
        }
        send_stored_file($file, 86400, 0, $forcedownload, $options);
    } else { 
        send_file_not_found();
    }
}

/**
 * Returns db row for a particular global configuration for the block carousel
 * 
 * @param string $config_name
 * @return \stdClass
 */
function get_plugin_global_config($config_name) {
    global $DB;

    return $DB->get_record('config_plugins', ['plugin' => 'block_carousel', 'name' => $config_name]);
}

/**
 * Get course detail
 * 
 * @global \block_carousel\type $DB
 * @param int $courseid
 * @return course record object
 */
function get_course_detail($courseid) {
    global $DB;

    return $DB->get_record('course', ['id' => $courseid]);
}

/**
 * Get f2f session custom fields
 * 
 * @param int $sessionid
 * @param string[] $custom_field_shortnames
 */
function get_f2f_sessions_custom_field_data($sessionid, $custom_field_shortnames = []) {
    global $DB;

    if (empty($custom_field_shortnames)) {
        return [];
    }

    $trimmed_custom_field_shortnames = array_map('trim', $custom_field_shortnames);

    list($insql, $inparams) = $DB->get_in_or_equal($trimmed_custom_field_shortnames, SQL_PARAMS_NAMED);

    $sql = "SELECT sid.id, sid.data, sif.datatype
            FROM {facetoface_sessions} s
        LEFT JOIN {facetoface_session_info_data} sid
            ON sid.facetofacesessionid = s.id
        LEFT JOIN {facetoface_session_info_field} sif
            ON sif.id = sid.fieldid
            WHERE s.id = :sessionid
            AND sif.shortname $insql
    ";

    $records = $DB->get_records_sql($sql, ['sessionid' => $sessionid] + $inparams);

    if (!empty($records)) {
        return custom_field_data_to_array($records);
    }
    return [];
}

/**
 * Check custom fields type
 * And return the data as an array
 * 
 * @param obj database object
 * @return array of custom fields
 */
function custom_field_data_to_array($records) {
    $custom_fields = [];
    foreach ($records as $record) {
        if (empty($record->data)) {
            continue;
        }
        switch ($record->datatype) {
            case 'multiselect':
                $options = json_decode($record->data);
            
                foreach ($options as $option) {
                    if (!empty($option->option)) {
                        $custom_fields[] = $option->option;
                    }
                }
            
                break;

            case 'duration':
                $duration = $record->data;
                if ($duration < HOURSECS) {
                    $duration = round($duration / MINSECS). ' minutes';
                } else if ($duration < DAYSECS) {
                    $duration = round($duration / HOURSECS). ' hours';
                } else {
                    $duration = round($duration / DAYSECS). ' days';
                }
                $custom_fields[] = $duration;
                break;

            default:
                $custom_fields[] = $record->data;
                break;
        }
    }
    return $custom_fields;
}

