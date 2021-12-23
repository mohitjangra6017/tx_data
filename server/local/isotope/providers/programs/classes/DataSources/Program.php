<?php

namespace isotopeprovider_programs\DataSources;

global $CFG;

use coding_exception;
use course_set;
use dml_exception;
use Exception;
use lang_string;
use ProgramException;
use stdClass;

require_once($CFG->dirroot . '/totara/program/lib.php');

class Program extends Category
{
    const STATUS_UNSET = 'unset';
    const STATUS_NOTSTARTED = 'notstarted';
    const STATUS_STARTED = 'started';
    const STATUS_COMPLETED = 'completed';
    const STATUS_UNKNOWN = 'unknown';
    const STATUS_EXPIRED = 'expired';

    protected $certstatusmap = [
        CERTIFSTATUS_UNSET => self::STATUS_UNSET,
        CERTIFSTATUS_ASSIGNED => self::STATUS_NOTSTARTED,
        CERTIFSTATUS_INPROGRESS => self::STATUS_STARTED,
        CERTIFSTATUS_COMPLETED => self::STATUS_COMPLETED,
        CERTIFSTATUS_EXPIRED => self::STATUS_EXPIRED,
    ];

    public function __construct()
    {
        $this->filters = [];
    }

    /**
     * @param $filterName
     * @param $value
     * @return $this|mixed
     */
    public function setFilter($filterName, $value)
    {
        $this->filters[$filterName] = $value;
        return $this;
    }

    /**
     * @return array|mixed
     * @throws Exception
     */
    public function getData()
    {
        if (!isset($this->filters['userid'])) {
            throw new Exception("You must set a userid filter.");
        }

        $programs =
            prog_get_all_programs(
                $this->filters['userid'],
                ' ORDER BY p.fullname ASC ',
                '',
                '',
                '',
                false,
                true,
                false
            );
        $programs = $this->categoryCheck($programs);

        foreach ($programs as $program) {
            $this->decorate($program, $this->filters['userid']);
        }

        return $programs;
    }

    /**
     * @param $program
     * @param $userId
     * @throws ProgramException
     * @throws coding_exception
     * @throws dml_exception
     */
    protected function decorate($program, $userId)
    {
        $progContent = new \prog_content($program->id);
        $certifPath = 1;
        $program->coursesetgroups = $progContent->get_courseset_groups($certifPath);
        $program->progress = $this->getProgramProgress($this->filters['userid'], $program);

        $progCompletion = $this->getProgramCompletion($program->id, $userId);

        // Set status.
        if ($program->certifid > 0) {
            if (isset($this->certstatusmap[$progCompletion->status])) {
                $program->status = $this->certstatusmap[$progCompletion->status];
            } else {
                $program->status = self::STATUS_UNKNOWN;
            }
        } else {
            if ($progCompletion->status == STATUS_PROGRAM_COMPLETE) {
                $program->status = self::STATUS_COMPLETED;
            } else if ($progCompletion->status == STATUS_PROGRAM_INCOMPLETE) {
                if ($this->hasAnyCourseSetStarted($program->id, $userId)) {
                    $program->status = self::STATUS_STARTED;
                } else {
                    $program->status = self::STATUS_NOTSTARTED;
                }
            } else {
                $program->status = self::STATUS_UNKNOWN;
            }
        }
    }

    /**
     * @param $programId
     * @param $userId
     * @return false|mixed|stdClass
     * @throws dml_exception
     */
    protected function getProgramCompletion($programId, $userId)
    {
        global $DB;

        return $DB->get_record(
            'prog_completion',
            [
                'programid' => $programId,
                'userid' => $userId,
                'coursesetid' => 0,
            ]
        );
    }

    /**
     * @param $userId
     * @param $program
     * @return float
     * @throws ProgramException
     * @throws coding_exception
     * @throws dml_exception
     */
    protected function getProgramProgress($userId, $program)
    {
        $progress = 0;
        $accessible = true;

        foreach ($program->coursesetgroups as $courseSetGroup) {
            $this->updateCourseSets($userId, $courseSetGroup, $accessible);
            $accessible = $this->courseSetGroupAccessible($userId, $courseSetGroup);
            $progress += $accessible ? 1 : 0;
        }

        return round((empty($program->coursesetgroups) ? 1 : $progress / count($program->coursesetgroups)) * 100);
    }

    /**
     * @param $userId
     * @param $courseSetGroup
     * @return bool
     * @throws ProgramException
     */
    protected function courseSetGroupAccessible($userId, $courseSetGroup)
    {
        return prog_courseset_group_complete($courseSetGroup, $userId, false);
    }

    /**
     * @param $userId
     * @param $courseSetGroup
     * @param $accessible
     * @throws coding_exception
     * @throws dml_exception
     */
    protected function updateCourseSets($userId, $courseSetGroup, $accessible)
    {
        global $DB;

        foreach ($courseSetGroup as $courseSet) {
            $courseSet->accessible = $accessible;

            $params = [
                'coursesetid' => $courseSet->id,
                'programid' => $courseSet->programid,
                'userid' => $userId,
            ];

            $completionStatus = $DB->get_field('prog_completion', 'status', $params);
            if ($completionStatus === STATUS_COURSESET_COMPLETE) {
                $courseSet->status = 'completed';
                continue;
            }

            $params = [
                COMPLETION_STATUS_COMPLETE,
                COMPLETION_STATUS_INPROGRESS,
                COMPLETION_STATUS_COMPLETE,
                $userId,
                $courseSet->coursesumfield ?: 0,
                $courseSet->id,
            ];

            $record = $DB->get_record_sql(
                'SELECT SUM(CASE WHEN cc.status >= ? THEN 1 ELSE 0 END) AS completed,
                        SUM(CASE WHEN cc.status >= ? THEN 1 ELSE 0 END) AS started,
                        SUM(CASE WHEN cc.status >= ? THEN d.data::int ELSE 0 END) AS sumfield,
                        COUNT(pcc.id) AS total
                   FROM {prog_courseset_course} pcc
              LEFT JOIN {course_completions} cc ON cc.course = pcc.courseid AND cc.userid = ?
              LEFT JOIN {course_info_data} d ON d.courseid = pcc.courseid AND d.fieldid = ?
                  WHERE pcc.coursesetid = ?',
                $params
            );

            $fieldComplete = true;
            if ($courseSet->coursesumfield && $courseSet->coursesumfieldtotal > 0) {
                $fieldComplete = $record->sumfield >= $courseSet->coursesumfieldtotal;
            }

            if ($courseSet->completiontype == COMPLETIONTYPE_ALL
                && $record->completed >= $record->total
                || $courseSet->completiontype == COMPLETIONTYPE_ANY
                   && $record->completed > 0
                || $courseSet->completiontype == COMPLETIONTYPE_SOME
                   && $record->completed >= $courseSet->mincourses
                   && $fieldComplete
            ) {
                $courseSet->status = 'completed';
            } else if ($record->started > 0 || $record->sumfield > 0) {
                $courseSet->status = 'started';
            } else {
                $courseSet->status = 'notstarted';
            }

            $courseSet->completionhtml = $this->getCourseSetCompletionExplanationHtml($courseSet);
        }
    }

    /**
     * @param $programId
     * @param $userId
     * @return bool
     * @throws dml_exception
     */
    protected function hasAnyCourseSetStarted($programId, $userId)
    {
        global $DB;

        $sql = "SELECT cc.status
                 FROM {prog} p
                 JOIN {prog_courseset} pc
                     ON p.id = pc.programid
                 JOIN {prog_courseset_course} pcc
                     ON pc.id = pcc.coursesetid
                 JOIN {course_completions} cc
                     ON cc.course = pcc.courseid
                 WHERE p.id = ? 
                 AND cc.userid = ?
                 AND cc.status >= ?
                ";

        return $DB->record_exists_sql($sql, [$programId, $userId, COMPLETION_STATUS_INPROGRESS]);
    }

    /**
     * Get html definition list of completion settings details (any, some, or all courses, min. number)
     * This does not include list of courses itself.
     *
     * @param course_set $courseSet
     * @return lang_string|string
     * @throws coding_exception
     * @throws dml_exception
     */
    protected function getCourseSetCompletionExplanationHtml(course_set $courseSet)
    {
        global $DB;
        // Explain some or all courses must be completed.
        $setCompletionHtml = get_string('completeallcourses', 'totara_program');
        if ($courseSet->completiontype != COMPLETIONTYPE_ALL) {
            if ($courseSet->completiontype == COMPLETIONTYPE_ANY) {
                // Only one course must be completed.
                $setCompletionHtml = get_string('completeanycourse', 'totara_program');
            } else {
                $str = new stdClass();
                $str->mincourses = $courseSet->mincourses;
                $str->sumfield = '';
                if ($courseCustomField = $DB->get_record('course_info_field', ['id' => $courseSet->coursesumfield])) {
                    $str->sumfield = format_string($courseCustomField->fullname);
                }
                $str->sumfieldtotal = $courseSet->coursesumfieldtotal;
                if (!empty($str->mincourses) && !empty($str->sumfield) && !empty($str->sumfieldtotal)) {
                    $setCompletionHtml = get_string('completemincoursesminsum', 'totara_program', $str);
                } else if (!empty($this->mincourses)) {
                    $setCompletionHtml = get_string('completemincourses', 'totara_program', $str);
                } else {
                    $setCompletionHtml = get_string('completeminsumfield', 'totara_program', $str);
                }

            }
        }

        return $setCompletionHtml;
    }
}
