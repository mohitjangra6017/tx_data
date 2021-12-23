<?php

namespace isotopeprovider_record_of_learning\DataSources;

use coding_exception;
use context_course;
use dml_exception;

class Course extends Category
{
    public function __construct()
    {
        $this->filters = [];
    }

    /**
     * Get the data provided by this data source.
     * @return array
     * @throws coding_exception
     * @throws dml_exception
     */
    public function getData(): array
    {
        global $DB;

        // Get visibility
        [$visibilitySql, $visibilityParams] = totara_visibility_where(
            $this->filters['userid'],
            'c.id',
            'c.visible',
            'c.audiencevisible',
            'c'
        );
        $params = array_merge(
            [
                'userid' => $this->filters['userid'],
                'container_type' => \container_course\course::get_type(),
            ],
            $visibilityParams
        );

        if (($gradeBookRole = $this->filters['gradebook-role']) && $this->isRoleListValid($gradeBookRole)) {
            $roleJoinSql =
                " JOIN {role_assignments} ra ON (ra.userid=ue.userid AND ra.contextid=ctx.id AND ra.roleid IN ({$gradeBookRole})) ";
        } else {
            $roleJoinSql = '';
        }

        // Set the default sort here.
        $sort = 'ORDER BY cc.timeenrolled DESC';

        $sql = "SELECT c.id, c.fullname, ccat.name AS categoryname, c.icon, c.cacherev, 
                       cc.timeenrolled, cc.timestarted, cc.timecompleted, ccat.id AS category
                  FROM {user_enrolments} ue
             LEFT JOIN {enrol} e ON ue.enrolid = e.id
             LEFT JOIN {course} c ON e.courseid = c.id
             LEFT JOIN {context} ctx ON ctx.instanceid = c.id AND ctx.contextlevel = " . CONTEXT_COURSE . "
             LEFT JOIN {course_categories} ccat ON c.category = ccat.id
             LEFT JOIN {course_completions} cc ON cc.course = c.id AND cc.userid = ue.userid
             {$roleJoinSql}
                 WHERE ue.userid = :userid AND c.id IS NOT NULL AND ((cc.timecompleted IS NOT NULL) OR {$visibilitySql})
                 AND c.containertype = :container_type
              GROUP BY c.id, c.fullname, ccat.name, c.icon, cc.timeenrolled, cc.timestarted, cc.timecompleted, ccat.id {$sort}";

        $courses = $DB->get_records_sql($sql, $params);

        $courses = $this->filterByPrograms($courses);
        $courses = $this->filterByEnrolment($courses);
        return $this->categoryCheck($courses);
    }

    /**
     * @param string $roleList
     * @return bool
     */
    private function isRoleListValid(string $roleList): bool
    {
        $roleIds = explode(',', $roleList);
        foreach ($roleIds as $roleId) {
            if (!is_number($roleId)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Set the value of the specified filter.
     * @param string $filterName
     * @param mixed $value
     * @return $this
     */
    public function setFilter($filterName, $value): self
    {
        $this->filters[$filterName] = $value;
        return $this;
    }

    /**
     * @param $courses
     * @return array
     * @throws coding_exception
     * @throws dml_exception
     */
    private function filterByPrograms(array $courses): array
    {
        global $DB;

        if (empty($this->filters['hide_prog_courses'])) {
            return $courses;
        }

        $assignedPrograms = array_keys(
            prog_get_all_programs(
                $this->filters['userid'],
                '',
                '',
                '',
                false,
                false,
                false,
                false
            )
        );

        if (empty($assignedPrograms)) {
            return $courses;
        }

        [$inSql, $inParams] = $DB->get_in_or_equal($assignedPrograms);
        $sql = <<<SQL
SELECT COUNT(pcc.id) FROM {prog_courseset_course} pcc
LEFT JOIN {prog_courseset} pc ON pc.id = pcc.coursesetid
WHERE pcc.courseid = ? AND pc.programid $inSql
SQL;
        return array_filter(
            $courses,
            function ($course) use ($DB, $sql, $inParams) {
                return $DB->count_records_sql($sql, array_merge([$course->id], $inParams)) == 0;
            }
        );
    }

    /**
     * @param $courses
     * @return array
     */
    private function filterByEnrolment(array $courses): array
    {
        if (empty($this->filters['hide_disabled_enrolments'])) {
            return $courses;
        }

        return array_filter(
            $courses,
            function ($course) {
                $courseContext = context_course::instance($course->id);
                return is_enrolled($courseContext, $this->filters['userid'], '', true);
            }
        );
    }
}
