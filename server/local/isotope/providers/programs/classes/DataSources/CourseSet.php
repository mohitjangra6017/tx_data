<?php

namespace isotopeprovider_programs\DataSources;

use coding_exception;
use container_course\course;
use isotopeprovider_programs\Contracts\DataSource;

global $CFG;

require_once($CFG->dirroot . '/totara/program/lib.php');

class CourseSet implements DataSource
{
    protected $courseSetId;

    public function __construct($db, $courseSetId)
    {
        $this->db = $db;
        $this->filters = [];
        $this->courseSetId = $courseSetId;
    }

    /**
     * Get the data provided by this data source.
     *
     * @return mixed
     * @throws coding_exception
     */
    public function getData()
    {
        if (!isset($this->filters['userid'])) {
            throw new \Exception("You must set a userid filter.");
        }

        $params = [
            'userid' => $this->filters['userid'],
            'coursesetid' => $this->courseSetId,
        ];

        // Set the default sort here.
        $sort = 'ORDER BY pc.sortorder ASC, pcc.id, c.fullname ASC';

        [$visibleSql, $visibleParams] = totara_visibility_where(
            $this->filters['userid'],
            'c.id',
            'c.visible',
            'c.audiencevisible',
            'c'
        );

        $sql = "SELECT c.id, c.fullname, c.summary, c.summaryformat, ccat.name AS categoryname, c.icon, c.cacherev, 
                       cc.timeenrolled, cc.timestarted, cc.timecompleted, ccat.id AS category,
                       pc.programid, pc.label AS coursesetname, pc.id AS coursesetid
                  FROM {course} c
             JOIN {prog_courseset_course} pcc ON pcc.courseid = c.id
             JOIN {prog_courseset} pc ON pc.id = pcc.coursesetid
             LEFT JOIN {context} ctx ON ctx.instanceid = c.id AND ctx.contextlevel = " . CONTEXT_COURSE . "
             LEFT JOIN {course_categories} ccat ON c.category = ccat.id
             LEFT JOIN {course_completions} cc ON cc.course = c.id AND cc.userid = :userid
                 WHERE pc.id = :coursesetid
                 AND {$visibleSql}
                 AND c.containertype = :containertype
              GROUP BY c.id, c.fullname, pc.sortorder, pcc.id, pc.id, pc.label, pc.programid, ccat.name, cc.timeenrolled, cc.timestarted, cc.timecompleted, ccat.id {$sort}";

        $params += $visibleParams;
        $params += ['containertype' => course::get_type()];
        return $this->db->get_records_sql($sql, $params);
    }

    /**
     * Set the value of the specified filter.
     *
     * @param $filterName
     * @param $value
     * @return mixed
     */
    public function setFilter($filterName, $value)
    {
        $this->filters[$filterName] = $value;
        return $this;
    }
}