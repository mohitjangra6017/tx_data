<?php
/**
 * Helper class
 *
 * @package    block_carousel
 * @copyright  &copy; 2021 Kineo Pty Ltd  {@link http://kineo.com/au}
 * @author     tri.le
 * @version    1.0
*/

namespace block_carousel;

use container_course\course;
use stdClass;

class helper {

    /**
     * Get shortnames of custom fields 
     * These can be of course or users
     * 
     * @param \stdClass $config configuration object
     * @param string $configname name of the configuration
     * @return array custom field values
     */
    static function get_carousel_custom_field_shortnames($config, $configname) {
        if (!empty($config->$configname)) {
            return explode(",", trim($config->$configname));
        } else {
            $global_custom_fields = get_config('block_carousel', $configname);
            if (!empty($global_custom_fields)) {
                return explode(",", trim($global_custom_fields));
            }
        }
        return '';
    }

    /**
     * Get user custom fields
     * 
     * @param int $userid user id
     * @param string[] $custom_field_shortnames array of custom field shortnames
     * @return string[] array of custom field value for the user
     */
    static function get_user_custom_field_data($userid, $user_field_shortnames) {
        global $DB, $CFG;

        if (empty($user_field_shortnames)) {
            return [];
        }

        $trimmed_user_field_shortnames = array_map('trim', $user_field_shortnames);

        $select = $DB->sql_group_concat('uid.data', ',').'  AS customfielddata';

        [$insql, $inparams] = $DB->get_in_or_equal($trimmed_user_field_shortnames, SQL_PARAMS_NAMED);

        $sql = "SELECT $select
                FROM {user} u
            LEFT JOIN {user_info_data} uid
                ON uid.userid = u.id
            LEFT JOIN {user_info_field} uif
                ON uif.id = uid.fieldid
                WHERE u.id = :userid
                AND uif.shortname $insql
        ";

        $custom_fields = $DB->get_record_sql($sql, ['userid' => $userid] + $inparams);

        if (!empty($custom_fields->customfielddata)) {
            return explode(",",trim($custom_fields->customfielddata));
        }
        return [];
    }

    /**
     * Get course custom fields
     * 
     * @param int $courseid
     * @param string[] $custom_field_shortnames
     */
    static function get_course_custom_field_data($courseid, $custom_field_shortnames = []) {
        global $DB;

        if (empty($custom_field_shortnames)) {
            return [];
        }

        $trimmed_custom_field_shortnames = array_map('trim', $custom_field_shortnames);

        [$insql, $inparams] = $DB->get_in_or_equal($trimmed_custom_field_shortnames, SQL_PARAMS_NAMED);

        $sql = "SELECT cid.id, cid.data, cif.datatype
                FROM {course} c
            LEFT JOIN {course_info_data} cid
                ON cid.courseid = c.id
            LEFT JOIN {course_info_field} cif
                ON cif.id = cid.fieldid
                WHERE c.id = :courseid
                AND cif.shortname $insql
        ";

        $records = $DB->get_records_sql($sql, ['courseid' => $courseid] + $inparams);

        if (!empty($records)) {
            return custom_field_data_to_array($records);
        }
        return [];
    }

    /**
     * Get new courses
     * 
     * @param \stdClass $block_config config object of the block
     * @return \stdClass[] array of course records object 
     */
    static function get_new_courses($block_config) {
        global $DB;

        $limit = !empty($block_config->whatsnewlimit) ? $block_config->whatsnewlimit : constants::BKC_DEFAULT_RECORD_LIMIT;

        [$v_sql, $params] = totara_visibility_where(null, 'c.id', 'c.visible', 'c.audiencevisible', 'c');

        $sql = "SELECT c.*
                FROM {course} c 
                WHERE c.visible = 1
                AND c.format != 'site'
                AND c.containertype = :containertype
                AND {$v_sql}
            ORDER BY c.timecreated DESC
                LIMIT {$limit}";

        $params['containertype'] = course::get_type();

        return $DB->get_records_sql($sql, $params);
    }

    /**
     * Get a course link
     * 
     * @param \stdClass $course course object record
     * @param string $module_name module name 
     * @param int $cmid course module id
     * @param mixed $cm_instance 
     * @param bool $justtheanchor 
     * @return string html code to display a courselet link 
     */
    static function get_courselet_link($course, $module_name, $cmid, $cm_instance, $justtheanchor = false) {
        global $DB, $CFG;
        $cm = get_coursemodule_from_id($module_name, $cmid, 0, false, MUST_EXIST);
        $module = $DB->get_record($module_name, ['id' => $cm_instance], '*', MUST_EXIST);
        $context = \context_module::instance($cm->id);
        $courseid = $course->id;

        switch ($module_name) {
            case 'url':
                require_once($CFG->dirroot . '/mod/url/locallib.php');
                $url = $module;

                $fullurl = url_get_full_url($url, $cm, $course);
                $display = url_get_final_display_type($url);
                if ($display == RESOURCELIB_DISPLAY_POPUP) {
                    $jsfullurl = addslashes_js($fullurl);
                    $options = empty($url->displayoptions) ? array() : unserialize($url->displayoptions);
                    $width  = empty($options['popupwidth'])  ? 620 : $options['popupwidth'];
                    $height = empty($options['popupheight']) ? 450 : $options['popupheight'];
                    $wh = "width=$width,height=$height,toolbar=no,location=no,menubar=no,copyhistory=no,status=no,directories=no,scrollbars=yes,resizable=yes";
                    $extra = "onclick=\"window.open('$jsfullurl', '', '$wh'); return false;\"";
                } else if ($display == RESOURCELIB_DISPLAY_NEW) {
                    $extra = "onclick=\"this.target='_blank';\"";
                } else {
                    $extra = '';
                }
            
                if ($justtheanchor) {
                    return '<a class="course-link launch-courselet" data-courseid="' . $courseid . '" href="' . $fullurl . '"' . $extra . '>';
                } else {
                    return '<div class="urlworkaround">
                    <a class="btn btn-raised btn-red launch-courselet" data-courseid="' . $courseid . '" href="' . $fullurl . '"' . $extra . '>' . get_string('launch', 'block_carousel') . '</a>
                </div>';
                }
            
                break;

            case 'resource':
                require_once($CFG->dirroot . '/mod/resource/locallib.php');
                $fs = get_file_storage();
                $files = $fs->get_area_files($context->id, 'mod_resource', 'content', 0, 'sortorder DESC, id ASC', false); // TODO: this is not very efficient!!
                if (count($files) < 1) {
                    return '';
                } else {
                    $resource = $module;
                    $file = reset($files);
                    unset($files);

                    $module->mainfile = $file->get_filename();

                    $resource_link = '<div class="resourceworkaround">';
                    $resouce_courselet_link = '';
                    switch (resource_get_final_display_type($resource)) {
                        case RESOURCELIB_DISPLAY_POPUP:
                            $path = '/'.$file->get_contextid().'/mod_resource/content/'.$resource->revision.$file->get_filepath().$file->get_filename();
                            $fullurl = file_encode_url($CFG->wwwroot.'/pluginfile.php', $path, false);
                            $options = empty($resource->displayoptions) ? array() : unserialize($resource->displayoptions);
                            $width  = empty($options['popupwidth'])  ? 620 : $options['popupwidth'];
                            $height = empty($options['popupheight']) ? 450 : $options['popupheight'];
                            $wh = "width=$width,height=$height,toolbar=no,location=no,menubar=no,copyhistory=no,status=no,directories=no,scrollbars=yes,resizable=yes";
                            $extra = "onclick=\"window.open('$fullurl', '', '$wh'); return false;\"";
                            $resouce_courselet_link .= self::resource_get_clicktoopen($courseid, $file, $resource->revision, $extra, $justtheanchor);
                            break;

                        case RESOURCELIB_DISPLAY_NEW:
                            $extra = 'onclick="this.target=\'_blank\'"';
                            $resouce_courselet_link .= self::resource_get_clicktoopen($courseid, $file, $resource->revision, $extra, $justtheanchor);
                            break;

                        case RESOURCELIB_DISPLAY_DOWNLOAD:
                            $resouce_courselet_link .= self::resource_get_clicktodownload($courseid, $file, $resource->revision, $justtheanchor);
                            break;

                        case RESOURCELIB_DISPLAY_OPEN:
                        default:
                            $resouce_courselet_link .= self::resource_get_clicktoopen($courseid, $file, $resource->revision, $justtheanchor);
                            break;
                    }
                    if ($justtheanchor) {
                        return $resouce_courselet_link;
                    } else {
                        $resource_link .= $resouce_courselet_link.'</div>';
                        return $resource_link;
                    }
                }
                break;
        }
    }

    /**
     * Get html code for displaying a resource download link
     * (Clone from mod/resource/locallib.php with minor changes)
     * @param int $courseid course id
     * @param \stored_file $file the file for create a link to
     * @param int $revision
     * @param bool $justtheanchor only output anchor instead of full link
     * @return string html code for displaying the link
     */
    static function resource_get_clicktodownload($courseid, $file, $revision, $justtheanchor = false) {
        $fullurl = \moodle_url::make_pluginfile_url($file->get_contextid(), 'mod_resource', 'content', $revision, $file->get_filepath(), $file->get_filename(), true);

        if ($justtheanchor) {
            return '<a class="course-link launch-courselet" href="' . $fullurl . '"  data-courseid="' . $courseid . '">';
        }
        return '<a class="btn btn-raised btn-red launch-courselet" href="' . $fullurl . '"  data-courseid="' . $courseid . '">' . get_string('launch', 'block_carousel') . '</a>';
    }

    /**
     * Get html code for displaying a resource open link
     * (Clone from mod/resource/locallib.php with minor changes)
     * @param int $courseid course id
     * @param \stored_file $file the file for create a link to
     * @param int $revision
     * @param bool $justtheanchor only output anchor instead of full link
     * @return string html code for displaying the link
     */
    static function resource_get_clicktoopen($courseid, $file, $revision, $extra='', $justtheanchor = false) {
        $fullurl = \moodle_url::make_pluginfile_url($file->get_contextid(), 'mod_resource', 'content', $revision, $file->get_filepath(), $file->get_filename(), false);

        if ($justtheanchor) {
            return '<a class="course-link launch-courselet"  data-courseid="' . $courseid . '" href="' . $fullurl . '"' . $extra . '>';
        }
        return '<a class="btn btn-raised btn-red launch-courselet"  data-courseid="' . $courseid . '" href="' . $fullurl . '"' . $extra . '>' . get_string('launch', 'block_carousel') . '</a>';
    }

    /**
     * Get course additional details
     * 
     * @param int $courseid course id
     * @param int $userid user id
     * @return \stdClass
     */
    static function get_course_additional_details($courseid, $userid) {
        global $DB, $CFG;

        $tags_sql = 'SELECT '.$DB->sql_group_concat('tag.name', ',').' ';

        $tags_sql .= "FROM {tag} tag
                            JOIN {tag_instance} ti
                            ON tag.id = ti.tagid AND ti.itemtype = :itemtype1
                        WHERE ti.itemid = c.id ";
        $params['itemtype1'] = 'course';

        $sql = "SELECT 
            c.id,
            c.fullname,
            c.icon,
            cc.timecompleted,
            CASE
                WHEN c.coursetype = 0 
                THEN 'E-learning' 
                WHEN c.coursetype = 1 
                THEN 'Blended' 
                WHEN c.coursetype = 2 
                THEN 'Seminar' 
            END AS coursetype, 
            (" . $tags_sql . ") as course_tags
                FROM {course} c  
            LEFT JOIN {course_completions} cc 
                ON c.id = cc.course AND cc.userid = :userid
                WHERE c.id = :courseid
            ";

        $params['courseid'] = $courseid;
        $params['userid'] = $userid;

        $additional_details = $DB->get_record_sql($sql, $params);
        if (empty($additional_details)) {
            $additional_details = new \stdClass();
        }
        $additional_details->course_rating = self::get_course_ratings($courseid);

        // Course likes
        $additional_details->course_likes = self::get_course_likes($courseid);

        // Course completions
        $additional_details->course_completions = self::get_course_completions($courseid);

        return $additional_details;
    }

    /**
     * Get get course likes tag
     * 
     * @param int $courseid
     * @return html 
     */
    static function get_course_likes($courseid) {
        global $DB, $PAGE;

        $likes =  $DB->count_records('rate_course_course_likes', ['course' => $courseid]);

        $data = [
            'total_likes' => $likes
        ];

        $renderer = $PAGE->get_renderer('block_carousel');

        return $renderer->render_likes($data);    
    }

    /**
     * Get course ratings
     * 
     * @param int $courseid
     */
    static function get_course_ratings($courseid) {
        global $DB, $CFG, $PAGE;

        if (file_exists($CFG->dirroot . '/blocks/rate_course/block_rate_course.php')) {
            require_once($CFG->dirroot . '/blocks/rate_course/block_rate_course.php');
        
            // Not getting ratings directly form DB because plugin has different
            // settings to calculate ratings based on different configs
            $reflection = new \ReflectionMethod('block_rate_course', 'get_rating');
            $reflection->setAccessible(true);
            $average_rating = $reflection->invoke(new \block_rate_course(), $courseid);
            $total_ratings = $DB->count_records('rate_course_review', ['course' => $courseid]);
        
            $data = [
                'condensed' => true,
                'total_ratings' => $total_ratings,
                'average_rating' => round($average_rating, 1)
            ];
        
            $renderer = $PAGE->get_renderer('block_carousel');
        
            return $renderer->render_star_ratings($data);
        }

        return null;
    }

    /**
     * Get course completions tag
     * 
     * @param int $courseid
     * @return html
     */
    static function get_course_completions($courseid) {
        global $DB, $PAGE;

        $completions = $DB->get_record_sql(
            "SELECT COUNT(*) AS completion
                        FROM {course_completions} cc
                        WHERE cc.course = :courseid
                        AND cc.timecompleted IS NOT NULL", 
            ['courseid' => $courseid]
        );

        $data = [
            'total_completion_count' => $completions->completion
        ];

        $renderer = $PAGE->get_renderer('block_carousel');

        return $renderer->render_completion_count($data);                 
    }

    /**
     * Get number of course in a carousel or completion percentage for the Carousel 
     * 
     * @param stdClass[] $courses array of course object records
     * @param int $count_type either BKC_SHOW_COURSE_COUNT or BKC_SHOW_PERCENTAGE_COMP
     * @return int|float number of course completed or percentage of course completed
     */
    static function get_course_count_or_completion_percentage($courses, $count_type) {
        global $CFG, $USER;
        require_once($CFG->dirroot . '/lib/completionlib.php');

        if ($count_type == \block_carousel\constants::BKC_SHOW_COURSE_COUNT) {
            return count($courses) . ' ' . get_string('courses', 'block_carousel');
        }

        // if count type
        // is equal to BKC_SHOW_PERCENTAGE_COMP
        $total_courses = count($courses);

        $completion_count = 0;
        foreach ($courses as $course) {
            $completion = new \completion_info($course);
            if ($completion->is_course_complete($USER->id)) {
                $completion_count++;
            }
        }

        return round((($completion_count / $total_courses) * 100),0) . '% ' . get_string('complete', 'block_carousel');
    }

    static function get_journey_type($format=null) {
        switch ($format) {
            case 'spacedlearning':
                return 'Journey';
            break;

            case 'singleactivity':
                return 'Activity';
            break;

            default:
                return 'course';
            break;
        }
    }
    /**
     * Get hot courses
     * 
     * @param \stdClass $block_config block configuration object
     * @return \stdClass[] array of courses record object for hot courses
     */
    static function get_hot_courses($block_config) {
        $courses = $hot_courses = null;

        if (class_exists('\block_whats_hot\helper')) {
            $courses = \block_whats_hot\helper::get_hot_courses();
        }

        $limit = !empty($block_config->whatshotlimit) ? $block_config->whatshotlimit : \block_carousel\constants::BKC_DEFAULT_RECORD_LIMIT;

        if (!empty($courses)) {
            $hot_courses = count($courses) > $limit ? array_splice($courses, $limit) : $courses;
        }

        return $hot_courses;
    }

    /**
     * Get course summary
     * 
     * @param \stdClass[] $enrolledcourses array of course record objects
     * @param \stdClass $block_config config block
     * @return \stdClass[] array of course id, course summary
     */
    static function get_course_summary($enrolledcourses, $block_config) {
        global $DB;

        return $DB->get_records_sql('
                SELECT cd.courseid, cd.data
                    FROM {course_info_field} cf
                    JOIN {course_info_data} cd
                    ON cd.fieldid = cf.id
                    WHERE cf.shortname = :shortsummary
                    AND cd.courseid IN ('.implode(',', array_keys($enrolledcourses)).')
                ', array('shortsummary' => $block_config->shortsummary));
    }

    /**
     * Get course sub-headings
     * 
     * @param \stdClass[] $enrolledcourses
     * @param \stdClass $block_config
     * @return \stdClass[] array of course id, course summary
     */
    static function get_course_subheading($enrolledcourses, $block_config) {
        global $DB;

        return  $DB->get_records_sql('
                SELECT cd.courseid, cd.data
                    FROM {course_info_field} cf
                    JOIN {course_info_data} cd
                    ON cd.fieldid = cf.id
                    WHERE cf.shortname = :subheading
                    AND cd.courseid IN ('.implode(',', array_keys($enrolledcourses)).')
                ', array('subheading' => $block_config->subheading));
    }

    /**
     * get user enrolled courses
     * 
     * @param int $userid user id
     * @param \stdClass $block_config -- Block instance configuration and not the global block setting
     * @return \stdClass[] record object of courses that the user is enrolled
     */
    static function get_enrolled_courses($userid, $block_config = null, $onlyincomplete = false) {
        global $DB, $CFG;

        $captionshortname = 'caption';
        $enrolmenttype = [];
        $sortfield = 'lastaccess.timeaccess';
        $sortdirection = 'DESC';

        if (!empty($block_config->captionshortname)) {
            $captionshortname = $block_config->captionshortname;
        }

        if (!empty($block_config->enrolmenttype) && empty($block_config->allenrolments)) {
            $enrolmenttype = $block_config->enrolmenttype;
        }

        if (!empty($block_config->sortfield)) {
            $sortfield = $block_config->sortfield;
            $sortdirection = $block_config->sortdirection;
        }

        if ($onlyincomplete) {
            $completionfilter = ' AND (cc.timecompleted IS NULL OR cc.timecompleted = 0)';
        } else {
            $completionfilter = '';
        }

        $caption_fieldid = $DB->get_field('course_info_field', 'id', array('shortname' => $captionshortname));

        if ($sortfield != 'lastaccess.timeaccess') {
            $sort_fieldid = $DB->get_field('course_info_field', 'id', array('shortname' => $sortfield));
            if (!empty($sort_fieldid)) {
                $sorttable = ' LEFT JOIN {course_info_data} sortingdata ON sortingdata.courseid = c.id and sortingdata.fieldid='.$sort_fieldid;
                $sortfield = $DB->sql_cast_char2int('sortingdata.data');

                $sortgroupby = ' , sortingdata.data ';
            } else {
                $sorttable = '';
                $sortgroupby = '';
            }
        } else {
            $sorttable = '';
            $sortgroupby = '';
        }


        $enrolmentfilter = '';

        $count = 0;

        foreach ($enrolmenttype as $enrol) {
            if ($count) {
                $enrolmentfilter .= ' OR ';
            }

            $enrolmentfilter .= ' e.enrol = \''.$enrol.'\' ';
            $count += 1;
        }

        if (!empty($enrolmentfilter)) {
            $enrolmentfilter = ' AND ('.$enrolmentfilter.') ';
        }

        if (!empty($block_config->namefield)) {
            $secondarysort = 'c.'.$block_config->namefield;
        } else {
            $secondarysort = 'c.fullname';
        }

        [$v_sql, $v_params] = totara_visibility_where(null, 'c.id', 'c.visible', 'c.audiencevisible', 'c');

        $sql = "SELECT c.id,
                    c.id AS courseid,
                    c.fullname,
                    c.shortname,
                    c.visible, 
                    c.audiencevisible, 
                    lastaccess.timeaccess,
                    c.format,
                    CASE WHEN COUNT(criteria.id)>0 THEN COUNT(compl.id)*100/COUNT(criteria.id) ELSE 0 END AS progress,
                    caption.data as caption,
                    cc.timecompleted,
                    c.icon,
                    c.summary
                FROM {course} c
            LEFT JOIN {user_lastaccess} lastaccess
                ON (c.id = lastaccess.courseid AND lastaccess.userid=:user_id1)
            LEFT JOIN {course_info_data} caption
                ON (caption.courseid = c.id and caption.fieldid=:field_id)
            LEFT JOIN {course_completions} cc
                ON (cc.course=c.id and cc.userid=:user_id2)
            LEFT JOIN {course_completion_criteria} criteria
                ON (criteria.course = c.id)
            LEFT JOIN {course_completion_crit_compl} compl
                ON (criteria.id = compl.criteriaid AND compl.userid=:user_id3)
                {$sorttable}
        WHERE c.id IN (
                SELECT e.courseid
                    FROM {user_enrolments} ue
                    JOIN {enrol} e
                    ON (e.id = ue.enrolid)
                WHERE ue.userid = :user_id4
                    AND e.status = 0
                    AND ue.status=0
                    {$enrolmentfilter}
        ) {$completionfilter}
        AND c.containertype = :container
        AND {$v_sql}
    GROUP BY c.id, c.fullname, lastaccess.timeaccess, c.format, caption.data, cc.timecompleted {$sortgroupby}
    ORDER BY {$sortfield} IS NOT NULL {$sortdirection}, {$sortfield} {$sortdirection}, {$secondarysort} ASC";

        $params = [
            'user_id1' => $userid,
            'user_id2' => $userid,
            'user_id3' => $userid,
            'user_id4' => $userid,
            'field_id' => $caption_fieldid,
            'container' => course::get_type(),
        ];

        $params += $v_params;

        return $DB->get_records_sql($sql, $params);
    }

    /**
     * Get user's recommended courses
     * 
     * @param int $userid user id
     * @return \stdClass[] array of courses record objects
     */
    static function get_recommended_courses($userid) {
        global $DB, $CFG;

        [$v_sql, $v_params] = totara_visibility_where(null, 'c.id', 'c.visible', 'c.audiencevisible', 'c');

        if (file_exists($CFG->dirroot.'/local/course_recommend/version.php')) {
            require_once($CFG->dirroot.'/local/course_recommend/lib.php');
            // get user cohorts
            $cohorts = $DB->get_records_sql('
            SELECT ggc.cohort_id, c.name as cohortname
                FROM {cohort_members} cm
                JOIN {local_audience_grouping_group_cohorts} ggc
                ON ggc.cohort_id = cm.cohortid
                JOIN {local_audience_grouping_group} gg
                ON gg.id = ggc.audiencegroup_id
                JOIN {cohort} c
                ON c.id = ggc.cohort_id
                WHERE cm.userid = :userid
            ORDER BY gg.id, ggc.cohort_id
            ', array('userid' => $userid));
        
            $recommendations = local_course_recommend_get_recomendations(array_keys($cohorts));
        
            if (empty($recommendations)) {
                return array();
            }
        
            $courseids = array_keys($recommendations);
        
            [$insql1, $params] = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);
        
            $params = array_merge($params, array('userid' => $userid, 'userid2' => $userid));
            $params += $v_params;
            $params['containertype'] = course::get_type();
        
            $records = $DB->get_records_sql("
            SELECT c.id AS courseid,
                    c.fullname,
                    c.format,
                    (
                        SELECT cid.data
                            FROM {course_info_data} cid
                            JOIN {course_info_field} cif 
                            ON (cid.fieldid = cif.id AND cif.shortname = 'caption')
                            WHERE cid.courseid = c.id
                    ) AS caption
                FROM {course} c
            LEFT JOIN {course_completions} AS completed 
                ON completed.course = c.id AND completed.timecompleted > 0 AND completed.userid = :userid2
                JOIN (
                        SELECT DISTINCT selfenrol.courseid
                            FROM {enrol} selfenrol
                            WHERE selfenrol.status = 0
                            AND ( selfenrol.enrol = 'self' OR selfenrol.enrol = 'auto' OR selfenrol.enrol = 'singlejourney' )
                            AND ( selfenrol.customint6 = 1 OR selfenrol.customint6 IS NULL )
                            UNION
                        SELECT DISTINCT enrolled.courseid
                            FROM {enrol} enrolled
                            JOIN {user_enrolments} ue
                            ON ( enrolled.id = ue.enrolid AND ue.status = 0 AND enrolled.status = 0 AND ue.userid = :userid )
                    ) AS visible 
                ON visible.courseid = c.id
            WHERE c.id {$insql1}
                AND c.id NOT IN (
                        SELECT DISTINCT c.id
                            FROM {course} c
                            JOIN {enrol} e 
                            ON e.courseid = c.id
                            WHERE e.enrol = 'singlejourney'
                            AND c.format = 'spacedlearning'
                            AND e.status = 0
                    )
                AND completed.id IS NULL
                AND {$v_sql} 
                AND c.containertype = :containertype
            ", $params);
        
            $courses = array();
        
            foreach ($courseids as $courseid) {
                if (!empty($records[$courseid])) {
                    $courses[$courseid] = $records[$courseid];
                }
            }
        } else {
            $sql = "
            SELECT
                c.id AS courseid,
                c.fullname,
                c.format,
                (
                SELECT
                    cid.data
                FROM
                    {course_info_data} cid
                JOIN {course_info_field} cif ON
                    (cid.fieldid = cif.id
                    AND cif.shortname = 'caption')
                WHERE
                    cid.courseid = c.id) AS caption
            FROM
                {course} c
            LEFT JOIN {course_completions} AS completed 
                ON completed.course = c.id AND completed.timecompleted > 0 AND completed.userid = :userid2
            JOIN (
                SELECT
                    DISTINCT selfenrol.courseid
                FROM
                    {enrol} selfenrol
                WHERE
                    selfenrol.status = 0
                    AND ( selfenrol.enrol = 'self'
                    OR selfenrol.enrol = 'auto'
                    OR selfenrol.enrol = 'singlejourney' )
                    AND ( selfenrol.customint6 = 1
                    OR selfenrol.customint6 IS NULL )
            UNION
                SELECT
                    DISTINCT enrolled.courseid
                FROM
                    {enrol} enrolled
                JOIN {user_enrolments} ue ON
                    ( enrolled.id = ue.enrolid
                    AND ue.status = 0
                    AND enrolled.status = 0
                    AND ue.userid = :userid )) AS visible ON
                visible.courseid = c.id
            WHERE
                c.id NOT IN (
                SELECT
                    DISTINCT c.id
                FROM
                    {course} c
                JOIN {enrol} e ON
                    e.courseid = c.id
                WHERE
                    e.enrol = 'singlejourney'
                    AND c.format = 'spacedlearning'
                    AND e.status = 0)
            AND completed.id IS NULL
            AND {$v_sql} 
            AND c.containertype = :containertype
            ORDER BY
                c.sortorder
        ";
            $params = [];
            $params += $v_params;
            $params['userid'] = $userid;
            $params['userid2'] = $userid;

            $courses = $DB->get_records_sql($sql, $params, IGNORE_MISSING);
        }
        
        return $courses;
    }

    /**
     * Get course threshold
     * 
     * @param \stdClass[] $enrolledcourses
     * @param \stdClass $block_config
     * @return array 
     */
    static function get_course_threshold($enrolledcourses, $block_config) {
        global $DB;

        return $thresholddata = $DB->get_records_sql('
                SELECT CONCAT(cf.shortname, \'c\', cd.courseid), cd.data
                    FROM {course_info_field} cf
                    JOIN {course_info_data} cd
                    ON cd.fieldid = cf.id
                    WHERE (cf.shortname = :lowerthreshold
                    OR cf.shortname = :upperthreshold)
                    AND cd.courseid IN ('.implode(',', array_keys($enrolledcourses)).')
                ', array('lowerthreshold' => $block_config->lowerthreshold, 'upperthreshold' => $block_config->upperthreshold));
    }

    static function calculate_remaining_time($course, $config) {
        global $DB;

        if (empty($config->coursedurationestimate)) {
            return '';
        }

        $field = $DB->get_record('course_info_field', array('shortname' => $config->coursedurationestimate));
        $data = $DB->get_record('course_info_data', array('fieldid' => $field->id, 'courseid' => $course->courseid));

        if (empty($data)) {
            return '';
        }

        $remaining = (int)$data->data * ((100 - (float)$course->progress) / 100);

        if (empty($remaining)) {
            return '';
        }

        $output = '';

        $days = (int)($remaining / DAYSECS);

        if ($days > 0) {
            if ($days > 7) {
                $weeks = (int)($days / 7);
                return $weeks.' week(s) to complete';
            } else {
                return $days.' day(s) to complete';
            }
        } else {
            $hours = (int)($remaining / HOURSECS);
            if ($hours > 0) {
                return $hours.' hr to complete';
            } else {
                $minutes = (int)($remaining / 60);
                if ($minutes > 0) {
                    return $minutes.' min to complete';
                } else {
                    return $remaining.' sec to complete';
                }
            }
        }
    } 

    /**
     * Check if the block has cohort visibility and
     * if it exists, check if the user is a member of the cohort
     * 
     * @param int $blockinstanceid block instance id
     * @param int $userid user id
     * @return boolean can the user view the block
     */
    static function check_cohort_visibility($blockinstanceid, $userid) {
        global $DB, $CFG;
        require_once($CFG->dirroot . '/cohort/lib.php');

        $cohorts = $DB->get_records('block_carousel_cohorts', ['blockinstanceid' => $blockinstanceid]);
        if (empty($cohorts)) {
            return true;
        }
        // check if user is a member of the cohort
        foreach ($cohorts as $cohort) {
            if (cohort_is_member($cohort->cohortid, $userid)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if the session is visible to user based on params
     * 
     * @param int $courseid
     * @param int $sessionid
     * @param array $additional_params - ['coursefilter','coursemapped_value', 'f2f_filter', 'f2fmapped_value']
     */
    static function is_session_visible_to_user($courseid, $sessionid, $additional_params) {
        global $DB;

        // Check against course
        if (!empty($additional_params['coursefilter'])) {
            // get field record of the course custom field based on shortname
            $course_custom_fields = self::get_course_custom_field_data($courseid, [$additional_params['coursefilter']]);
            if (!in_array($additional_params['coursemapped_value'], $course_custom_fields)) {
                return false;
            }
        }

        // check against f2f session
        if (!empty($additional_params['f2f_filter'])) {
            // get field record of the f2f session custom field based on shortname
            $f2f_custom_fields = get_f2f_sessions_custom_field_data($sessionid, [$additional_params['f2f_filter']]);
            if (!in_array($additional_params['f2fmapped_value'], $f2f_custom_fields)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get cohorts
     * 
     * @param string $search
     * @return \stdClass[] array of cohort record objects
     */
    static function get_cohorts($search = null) {
        global $DB;

        if (empty($search)) {
            return $DB->get_records('cohort', array('visible' => 1));
        }

        $sql = "SELECT *
                FROM {cohort}
                WHERE " . $DB->sql_like("name", ":search") ."
                AND visible = :visible
            ";
        return $DB->get_records_sql($sql, array('search' => $search.'%', 'visible' => 1));
    }

    /**
     * Return carousel cohorts
     * 
     * @param int $blockinstanceid
     * @return \stdClass[] array of cohort object records
     */
    static function get_carousel_cohorts($blockinstanceid) {
        global $DB;

        $sql = "SELECT c.*
                FROM {cohort} c
                JOIN {block_carousel_cohorts} bcc
                ON bcc.cohortid = c.id
                WHERE bcc.blockinstanceid = :blockinstanceid
        ";

        return $DB->get_records_sql($sql, ['blockinstanceid' => $blockinstanceid]);
    }

    /**
     * Get courselets
     * AKA course with format set as single activity course
     */
    static function get_courslets() {
        global $DB;

        [$v_sql, $params] = totara_visibility_where(null, 'c.id', 'c.visible', 'c.audiencevisible', 'c');

        $sql = "SELECT c.*, c.id as courseid, 
                CASE 
                WHEN m.name = 'url' THEN 1
                WHEN m.name = 'resource' THEN 1
                ELSE 0
            END AS displayinmodal
                FROM {course} c
            LEFT JOIN {course_modules} cm
                ON cm.course = c.id
            LEFT JOIN {modules} m 
                ON m.id = cm.module 
                WHERE c.format = :format
                AND c.containertype = :containertype
                AND {$v_sql}";

        $params['containertype'] = \container_course\course::get_type();
        $params['format'] = 'singleactivity';

        return $DB->get_records_sql($sql, $params);
    }

}
