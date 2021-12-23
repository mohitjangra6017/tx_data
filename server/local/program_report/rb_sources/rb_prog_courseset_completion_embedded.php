<?php

defined('MOODLE_INTERNAL') || die;

class rb_prog_courseset_completion_embedded extends rb_base_embedded
{
    protected $plugin = 'local_program_report';
    public $defaultsortcolumn;
    protected $programid;

    public function __construct($data)
    {
        $this->url = '/local/program_report/index.php';
        $this->source = 'prog_courseset_completion';
        $this->shortname = 'prog_courseset_completion';
        $this->fullname = get_string('report:prog_courseset_completion', $this->plugin);
        $this->embeddedparams = $data;

        if (isset($data['programid'])) {
            $this->programid = $data['programid'];
        } else {
            $this->programid = -1;
        }

        $this->coursesetSQL = <<<SQL
SELECT pcc.*, pc.label, p.fullname progname, c.fullname coursename, c.id as courseid, pc.certifpath, p.certifid
FROM {prog_courseset} pc
INNER JOIN {prog_courseset_course} pcc
    ON pcc.coursesetid = pc.id
INNER JOIN {prog} p
    ON p.id = pc.programid
INNER JOIN {course} c
    ON c.id = pcc.courseid
WHERE p.id = ?
ORDER BY p.sortorder, programid, certifpath, pc.sortorder, pcc.id
SQL;

        $this->columns = $this->define_columns();
        $this->filters = $this->define_filters();

        parent::__construct();
    }

    public function define_columns()
    {
        global $DB;

        $columns = [
            [
                'type' => 'program',
                'value' => 'fullname',
                'heading' => get_string('program:name', 'rb_source_prog_courseset_completion'),
            ],
            [
                'type' => 'user',
                'value' => 'firstname',
                'heading' => get_string('user:firstname', 'rb_source_prog_courseset_completion'),
            ],
            [
                'type' => 'user',
                'value' => 'lastname',
                'heading' => get_string('user:lastname', 'rb_source_prog_courseset_completion'),
            ],
            [
                'type' => 'user',
                'value' => 'idnumber',
                'heading' => get_string('user:idnumber', 'rb_source_prog_courseset_completion'),
            ],
            [
                'type' => 'user',
                'value' => 'email',
                'heading' => get_string('user:email', 'rb_source_prog_courseset_completion'),
            ],
        ];

        $coursesetCourses = $DB->get_records_sql($this->coursesetSQL, [ $this->programid ]);
        foreach($coursesetCourses as $coursesetCourse) {
            $alias ='pc' . $coursesetCourse->id;

            if (empty($coursesetCourse->certifid)) {
                $prefix = get_string('prefix:program', 'rb_source_prog_courseset_completion');
            } else {
                if ($coursesetCourse->certifpath == 1) {
                    $prefix = get_string('prefix:certification', 'rb_source_prog_courseset_completion');
                } else {
                    $prefix = get_string('prefix:recertification', 'rb_source_prog_courseset_completion');
                }
            }

            $columnName = "({$prefix}) - " .
                $coursesetCourse->label . ' - ' .
                $coursesetCourse->coursename;

            $columns[] = [
                'type' => 'coursesetcompletion',
                'value' => $alias,
                'heading' => $columnName,
            ];
        }

        return $columns;
    }

    public function define_filters()
    {
        $filters = [

        ];
        return $filters;
    }

    public function is_capable($reportfor, $report)
    {
        return has_capability('local/program_report:viewreport', context_system::instance(), $reportfor);
    }
}