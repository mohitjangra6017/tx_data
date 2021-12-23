<?php
/**
 * Created by PhpStorm.
 * User: kineojoj
 * Date: 08/01/18
 * Time: 16:05
 */

defined('MOODLE_INTERNAL') || die;

global $CFG;

require_once($CFG->dirroot . '/totara/program/program.class.php');

class rb_source_prog_courseset_completion extends rb_base_source
{
    use \core_user\rb\source\report_trait;
    use \totara_job\rb\source\report_trait;

    /**
     * @var string
     */
    protected $coursesetSQL;

    /**
     * @var string
     */
    protected $plugin;

    /**
     * Constructor
     *
     * @param int $groupid (ignored)
     * @param rb_global_restriction_set $globalrestrictionset
     * @throws coding_exception
     */
    public function __construct($groupid, rb_global_restriction_set $globalrestrictionset = null)
    {
        if ($groupid instanceof rb_global_restriction_set) {
            throw new coding_exception('Wrong parameter orders detected during report source instantiation.');
        }

        // Remember the active global restriction set.
        $this->globalrestrictionset = $globalrestrictionset;

        // Apply global report restrictions
        $this->add_global_report_restriction_join('base', 'userid', 'base');

        $this->plugin = 'local_program_report';
        $this->usedcomponents[] = $this->plugin;
        
        $this->sourcelabel = get_string('sourcelabel', 'rb_source_prog_courseset_completion');

        $this->base = '(SELECT p.*, pua.userid, cc.certifpath 
                        FROM {prog} p
                        INNER JOIN {prog_user_assignment} pua
                            ON pua.programid = p.id
                        INNER JOIN {prog_completion} pc
                            ON pc.programid = p.id AND pc.userid = pua.userid AND pc.coursesetid = 0
                        LEFT JOIN {certif_completion} cc
                            ON cc.certifid = p.certifid AND cc.userid = pua.userid)';

        $this->coursesetSQL = <<<SQL
SELECT pcc.*, pc.label, p.fullname progname, c.fullname coursename, c.id as courseid, pc.certifpath, p.certifid
FROM {prog_courseset} pc
INNER JOIN {prog_courseset_course} pcc
    ON pcc.coursesetid = pc.id
INNER JOIN {prog} p
    ON p.id = pc.programid
INNER JOIN {course} c
    ON c.id = pcc.courseid
ORDER BY p.sortorder, programid, certifpath, pc.sortorder, pcc.id
SQL;

        $this->sourcetitle = get_string('sourcetitle', 'rb_source_prog_courseset_completion');

        $this->joinlist = $this->define_joinlist();
        $this->columnoptions = $this->define_columnoptions();
        $this->filteroptions = $this->define_filteroptions();
        $this->contentoptions = $this->define_contentoptions();
        $this->paramoptions = $this->define_paramoptions();
        $this->defaultcolumns = $this->define_defaultcolumns();
        $this->defaultfilters = $this->define_defaultfilters();
        $this->requiredcolumns = $this->define_requiredcolumns();
        $this->sourcelabel = get_string('sourcelabel', 'rb_source_prog_courseset_completion');
        $this->sourcesummary = get_string('sourcesummary', 'rb_source_prog_courseset_completion');

        parent::__construct();
    }

    /**
     * @return bool|null
     */
    public function global_restrictions_supported()
    {
        return true;
    }

    /**
     * @return array
     */
    protected function define_joinlist()
    {
        global $DB;

        $joins = [];

        $coursesetCourses = $DB->get_records_sql($this->coursesetSQL);
        foreach($coursesetCourses as $coursesetCourse) {
            $alias ='pc' . $coursesetCourse->id;
            $joins[] = new rb_join(
                $alias,
                'LEFT',
                "(SELECT pc.userid, 
                           cc.status coursestatus, 
                           pc.status coursesetstatus, 
                           cc.course, 
                           pc.programid,
                           pcs.certifpath
                       FROM {prog_courseset_course} pcc
                       INNER JOIN {prog_courseset} pcs
                           ON pcs.id = pcc.coursesetid
                       INNER JOIN {prog_completion} pc
                           ON pc.coursesetid = pcs.id
                       LEFT JOIN {course_completions} cc
                           ON cc.course = pcc.courseid AND pc.userid = cc.userid
                       WHERE pcc.coursesetid = {$coursesetCourse->coursesetid}
                           AND pcc.courseid = {$coursesetCourse->courseid}
                           AND pcs.id = {$coursesetCourse->coursesetid})",
                "base.userid = {$alias}.userid AND base.id = {$alias}.programid",
                REPORT_BUILDER_RELATION_ONE_TO_ONE
            );
        }

        $this->add_core_user_tables($joins, 'base', 'userid');
        $this->add_totara_job_tables($joins, 'base', 'userid');

        return $joins;
    }

    /**
     * @return array
     */
    protected function define_columnoptions()
    {
        global $DB;

        $columns = [
            new rb_column_option(
                'program',
                'fullname',
                get_string('program:name', 'rb_source_prog_courseset_completion'),
                'base.fullname',
                [
                    'displayfunc' => 'plaintext',
                ]
            ),
        ];

        $coursesetCourses = $DB->get_records_sql($this->coursesetSQL);
        foreach($coursesetCourses as $coursesetCourse) {
            $alias ='pc' . $coursesetCourse->id;

            if (empty($coursesetCourse->certifid)) {
                $prefix = get_string('prefix:program', 'rb_source_prog_courseset_completion');
            } elseif ($coursesetCourse->certifpath == 1) {
                $prefix = get_string('prefix:certification', 'rb_source_prog_courseset_completion');
            } else {
                $prefix = get_string('prefix:recertification', 'rb_source_prog_courseset_completion');
            }

            $columnName = "({$prefix}) - " .
                $coursesetCourse->progname . ' - ' .
                $coursesetCourse->label . ' - ' .
                $coursesetCourse->coursename;

            $columns[] = new rb_column_option(
                'coursesetcompletion',
                $alias,
                $columnName,
                "{$alias}.coursestatus",
                [
                    'joins' => $alias,
                    'extrafields' => [
                        'coursesetstatus' => "{$alias}.coursesetstatus",
                        'coursesetcertifpath' => "{$alias}.certifpath",
                        'programcertifpath' => "base.certifpath"
                    ],
                    'displayfunc' => 'program_courseset_completion_status'
                ]
            );
        }

        $this->add_core_user_columns($columns);
        $this->add_totara_job_columns($columns);

        return $columns;
    }

    /**
     * @return array
     */
    protected function define_filteroptions()
    {
        $filters = [];

        $this->add_core_user_filters($filters);
        $this->add_totara_job_filters($filters);

        return $filters;
    }

    protected function define_defaultcolumns()
    {
        return [
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
    }

    protected function define_contentoptions() {
        $contentoptions = [];

        // Add the manager/position/organisation content options.
        $this->add_basic_user_content_options($contentoptions);

        return $contentoptions;
    }

    protected function define_paramoptions()
    {
        return [
            new rb_param_option(
                'programid',
                'base.id'
            )
        ];
    }
}