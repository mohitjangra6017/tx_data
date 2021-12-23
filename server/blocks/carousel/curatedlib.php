<?php

/**
 * Comment
 *
 * @package    package
 * @subpackage sub_package
 * @copyright  &copy; 2020 CG Kineo {@link http://www.kineo.com}
 * @author     kaushtuv.gurung
 * @version    1.0
 */

namespace block_carousel;


use container_course\course;

/**
 * 
 * @param string $search
 * @return \stdClass[] tags array object
 */
function get_tags($search = null) {
    global $DB;

    if (empty($search)) {
        return $DB->get_records('tag', array('flag' => 0));
    }

    $sql = "SELECT id, name
              FROM {tag}
             WHERE " . $DB->sql_like("name", ":search") ."
               AND flag = :flag
            ";
    return $DB->get_records_sql($sql, array('search' => $search.'%', 'flag' => 0));
}


/**
 * 
 * @param int[] $courseids
 * @param int[] $tagids
 * @param string $tags_join_condition, either constants:BKC_TAG_JOIN_AND or constants:BKC_TAG_JOIN_OR
 * @param \stdClass $block_config config object of the block
 * @return \stdClass[] object of course records
 */
function get_carousel_curated_course($courseids = [], $tagids = [], $tags_join_condition = \block_carousel\constants::BKC_TAG_JOIN_AND, $block_config = null) {
    global $DB, $USER;

    $core_sort_fields = [
        'c.sortorder',
        'c.fullname',
        'lastaccess.timeaccess'
    ];

    // TOTDEV-758
    $secondary_sort = "c.sortorder";
    $secondary_sort_direction = "ASC";

    $sortfield = $block_config->coursesortfield ?? "c.sortorder";
    $sortdirection = $block_config->coursesortdirection ?? "ASC";

    $params = [];    

    $select = "SELECT c.*, lastaccess.timeaccess";
    $from = " FROM {course} c 
         LEFT JOIN {user_lastaccess} lastaccess
                ON (c.id = lastaccess.courseid AND lastaccess.userid = :userid) ";
    
    $groupby = "GROUP BY c.id, lastaccess.timeaccess";

    if (!in_array($sortfield, $core_sort_fields)) {
        // this is a custom sort field
        $fieldid = $DB->get_field('course_info_field', 'id', ['shortname' => $sortfield]);
        $from .= "LEFT JOIN {course_info_data} sortingdata 
                         ON sortingdata.courseid = c.id AND sortingdata.fieldid = :fieldid";

        $params['fieldid'] = $fieldid;
        $sortfield = " sortingdata.data ";
        $groupby .= " ,sortingdata.data";
    }

    if (!empty($tagids)) {
        $course_with_tags = get_course_with_tags($tagids, $tags_join_condition, false);
        
        foreach ($course_with_tags as $course) {
            if (!in_array($course->id, $courseids)) {
                $courseids[] = $course->id;
            }
        }
    }

    $where[] = " c.format != :courseformat";
    $where[] = " c.containertype = :containertype";
    $params['containertype'] = course::get_type();
    $params['courseformat'] = 'site';
    $params['userid'] = $USER->id;
    
    // courseids
    if (!empty($courseids)) {
        [$course_insql, $course_inparams] = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);
        $where[] = " c.id $course_insql";
        $params += $course_inparams;
    }

    [$vSql, $vParams] = totara_visibility_where(null, 'c.id', 'c.visible', 'c.audiencevisible', 'c');
    $where[] = $vSql;
    $params += $vParams;

    if (!empty($where)) {
        $from .= " WHERE " . implode(" AND ", $where);
    }

    $from .= " $groupby
               ORDER BY $sortfield IS NOT NULL $sortdirection, $sortfield $sortdirection, $secondary_sort $secondary_sort_direction";

    $courses =  $DB->get_records_sql($select.$from, $params);

    return $courses;
}

/**
 * Return course with tags
 * 
 * @param int[] $tagids
 * @param string $tags_join_condition, either constants::BKC_TAG_JOIN_AND or constants::BKC_TAG_JOIN_OR
 * @param bool $return_full_results
 * @return \stdClass[] array of course record objects
 */
function get_course_with_tags($tagids = [], $tags_join_condition = \block_carousel\constants::BKC_TAG_JOIN_AND, $return_full_results = true) {
    global $DB;
    
    if (empty($tagids)) {
        return false;
    }
    
    list($tags_insql, $tags_inparams) = $DB->get_in_or_equal($tagids, SQL_PARAMS_NAMED);
    $params['itemtype'] = 'course';
    $params += $tags_inparams;
    
    $select = "c.*";
    if (empty($return_full_results)) {
        $select = "c.id";
    }
    
    switch ($tags_join_condition) {
        case \block_carousel\constants::BKC_TAG_JOIN_AND:
            $sql = "SELECT DISTINCT $select
                      FROM {course} c 
                      JOIN 
                        (SELECT ti.itemid 
                           FROM {tag} tag 
                           JOIN {tag_instance} ti 
                             ON tag.id = ti.tagid AND ti.itemtype = :itemtype 
                          WHERE tag.id $tags_insql
                       GROUP BY ti.itemid 
                            HAVING COUNT(DISTINCT ti.tagid) >= :tagscount
                        ) AS tags 
                        ON tags.itemid = c.id";
            $params['tagscount'] = count($tagids);
            break;
        case \block_carousel\constants::BKC_TAG_JOIN_OR:
            $sql = "SELECT DISTINCT $select
                      FROM {course} c 
                 LEFT JOIN {tag_instance} ti 
                      ON ti.itemid = c.id 
                      AND ti.itemtype = 'course' 
                  WHERE ti.tagid $tags_insql";
            break;
    }

    return $DB->get_records_sql($sql, $params);
}

/**
 * Insert curated course
 * 
 * @param int $curatedid
 * @param int[] $courseids
 */
function insert_curated_course($curatedid, $courseids) {
    global $DB;
    
    // Add courses
    if (!empty($courseids)) {
        $data_course = [];
        foreach ($courseids as $courseid) {
            $data = new \stdClass();
            $data->curatedid = $curatedid;
            $data->courseid = $courseid;
            
            $data_course[] = $data;
        }
        $DB->insert_records('block_carousel_curated_course', $data_course);
    }
}

/**
 * Insert curated tags
 * 
 * @param int $curatedid
 * @param int[] $tagids
 */
function insert_curated_tags($curatedid, $tagids) {
    global $DB;
    
    // Add Tags
    if (!empty($tagids)) {
        $data_tags = [];
        foreach ($tagids as $tagid) {
            $data = new \stdClass();
            $data->curatedid = $curatedid;
            $data->tagid = $tagid;
            
            $data_tags[] = $data;
        }
        $DB->insert_records('block_carousel_curated_tags', $data_tags);
    }
}

/**
 * Get curated course
 * 
 * @param int $blockinstanceid
 * @return \stdClass[] array of currated course record objects
 */
function get_curated_courses($blockinstanceid) {
    global $DB;
    
    return $DB->get_records_sql("SELECT bccc.*
                                FROM {block_carousel_curated_course} bccc
                                JOIN {block_carousel_curated} bcc
                                  ON bcc.id = bccc.curatedid
                               WHERE bcc.blockinstanceid = :blockinstanceid
                                ", ['blockinstanceid' => $blockinstanceid]);
}

/**
 * Return curated tags
 * 
 * @param int $blockinstanceid
 * @return \stdClass[] array of record objects of tag
 */
function get_curated_tags($blockinstanceid) {
    global $DB;
    
    return $DB->get_records_sql("SELECT bcct.*
                                FROM {block_carousel_curated_tags} bcct
                                JOIN {block_carousel_curated} bcc
                                  ON bcc.id = bcct.curatedid
                               WHERE bcc.blockinstanceid = :blockinstanceid
                                ", ['blockinstanceid' => $blockinstanceid]);
}

/**
 * Get clusters
 * 
 * @param int $blockinstanceid block instance id
 * @return \stdClass[] currated object records along with tags and courseids
 */
function get_clusters($blockinstanceid) {
    global $DB, $CFG;

    $tagids_sql = 'SELECT '.$DB->sql_group_concat('cct.tagid', ',').' ';
    $courseids_sql = 'SELECT '.$DB->sql_group_concat('ccc.courseid', ',').' ';
    
    $tagids_sql .= "FROM
                    {block_carousel_curated_tags} cct  
                  WHERE cct.curatedid = cc.id ";
    $courseids_sql .= "FROM
                    {block_carousel_curated_course} ccc  
                  WHERE ccc.curatedid = cc.id ";
    
    $sql = "SELECT cc.*,
                (" . $tagids_sql . ") AS tagids,
                (" . $courseids_sql . ") AS courseids
                FROM {block_carousel_curated} cc
                WHERE cc.blockinstanceid = :blockinstanceid
                ";
    
    return $DB->get_records_sql($sql, ['blockinstanceid' => $blockinstanceid]);
}

/**
 * 
 * @param int $blockinstanceid
 * @return \stdClass array object for the slides, used in mustache template
 */
function get_cluster_slides($blockinstanceid) {
    global $DB;
    
    // get all curated contents for the cluster for the block instance
    $curated_clusters = get_clusters($blockinstanceid);
    
    foreach ($curated_clusters as $key => $curated) {
        $courseids = !empty(trim($curated->courseids)) ? explode(',', trim($curated->courseids)) : [];
        $tagids = !empty(trim($curated->tagids)) ? explode(',', trim($curated->tagids)) : [];
        $courses = get_carousel_curated_course($courseids, $tagids);
        if (!empty($courses)) {
            // Cluster hero image
            $curated_clusters[$key]->thumbnail = get_cluster_slide_image_url($blockinstanceid, $curated);
            // Cluster nested slides
            $curated_clusters[$key]->courses = $courses; 
        }
    }

    return $curated_clusters;
}

/**
 * Get cluster slide image
 * 
 * @param int $blockinstanceid
 * @param \stdClass $curated currated record
 * @return string html code for the image tag
 */
function get_cluster_slide_image_url($blockinstanceid, $curated, $return_url = true) {
    global $DB, $CFG;
    
    if (empty($curated->filename)) {
        return null;
    }
    
    $context = \context_block::instance($blockinstanceid);
        
    $src = \moodle_url::make_pluginfile_url($context->id, 'block_carousel', 'curated', $curated->id, '/', $curated->filename);
    if ($return_url) {
        return $src;
    }
    return \html_writer::img($src, $curated->filename);
}

/**
 * Delete curated image
 * 
 * @param int $blockinstanceid
 * @param object $curated
 */
function delete_curated_image($blockinstanceid, $curated) {
    
    $context = \context_block::instance($blockinstanceid);

    $fs = get_file_storage();
    // Prepare file record object
    $fileinfo = array(
        'component' => 'block_carousel',
        'filearea' => 'curated',     
        'itemid' => $curated->id,              
        'contextid' => $context->id, 
        'filepath' => '/',          
        'filename' => $curated->filename
    ); 

    // Get file
    $file = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'], 
    $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);

    // Delete it if it exists
    if ($file) {
        $file->delete();
    }
}