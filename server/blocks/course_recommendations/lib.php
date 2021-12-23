<?php

/**
 * Comment
 *
 * @package
 * @subpackage
 * @copyright  &copy; 2016 CG Kineo {@link http://www.kineo.com}
 * @author     kaushtuv.gurung
 * @version    1.0
 */

use core\orm\query\builder;
use core\tenant_orm_helper;

define('BLOCK_COURSE_RECOMMENDATIONS_DELETED', 1);

/**
 * @param $userid
 * @param false $exclude_ignored
 * @return array[]
 * @throws coding_exception
 */
function get_course_recommendations($userid, $exclude_ignored = true) {

    [$where_sql, $where_params] = totara_visibility_where($userid, 'c.id', 'c.visible', 'c.audiencevisible', 'c');

    return builder::table('rate_course_recommendations', 'cr')
        ->select('c.*')
        ->add_select('count(*) as frequency')
        ->join(['course', 'c'], 'cr.course', '=', 'c.id')
        ->left_join(
            ['block_course_recommendations_ignore', 'cri'],
            function (builder $builder) {
                $builder->where_field('cri.userid', 'cr.useridto')
                    ->where_field('cri.courseid', 'cr.course');
            }
        )
        ->where('cr.useridto', '=', $userid)
        ->where('cr.status', '<>', BLOCK_COURSE_RECOMMENDATIONS_DELETED)
        ->where_raw($where_sql, $where_params)
        ->when(
            $exclude_ignored,
            function (builder $builder) {
                $builder->where_null('cri.id');
            }
        )
        ->when(
            true,
            function (builder $builder) {
                global $USER;
                tenant_orm_helper::restrict_users($builder, 'cr.useridto', context_user::instance($USER->id));
            }
        )
        ->group_by('c.id')
        ->fetch();
}

/**
 * @param $userid
 * @param $courseid
 * @return array[]
 */
function get_course_recommenders($userid, $courseid) {

    return builder::table('rate_course_recommendations', 'cr')
        ->select('u.*')
        ->join(['user', 'u'], 'u.id', '=', 'cr.userid')
        ->where('cr.useridto', '=', $userid)
        ->where('cr.course', '=', $courseid)
        ->when(
            true,
            function (builder $builder) use ($userid) {
                tenant_orm_helper::restrict_users($builder, 'cr.useridto', context_user::instance($userid));
            }
        )
        ->fetch();
}

/**
 * @param $courseid
 * @return int
 */
function disable_recommendation($courseid) {
    global $USER;

    if (builder::table('block_course_recommendations_ignore')
        ->where('courseid', $courseid)
        ->where('userid', $USER->id)
        ->does_not_exist()
    ) {
        $record = new stdClass();
        $record->courseid = $courseid;
        $record->userid = $USER->id;
        return builder::table('block_course_recommendations_ignore')->insert($record);
    }
}

/**
 * @param $courseid
 * @throws dml_exception
 */
function delete_recommendation($courseid) {
    global $DB, $USER;
    $sql = "UPDATE {rate_course_recommendations}
               SET status = :deleted
             WHERE useridto = :useridto
               AND course = :courseid
            ";

    $DB->execute($sql, array(
            'deleted' => BLOCK_COURSE_RECOMMENDATIONS_DELETED,
            'useridto' => $USER->id,
            'courseid' => $courseid
        )
    );
}