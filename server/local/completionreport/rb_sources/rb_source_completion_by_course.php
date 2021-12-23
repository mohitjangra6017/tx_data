<?php
/**
 * Course completion report with one user per line and courses in columns.
 *
 * @package   rb_source_completion_by_course
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

use container_course\course;

defined('MOODLE_INTERNAL') || die;

global $CFG;
require_once($CFG->dirroot . '/completion/completion_completion.php');

class rb_source_completion_by_course extends rb_base_source
{
    use \core_user\rb\source\report_trait;
    use \totara_job\rb\source\report_trait;

    /**
     * @var object[]
     */
    protected $courses;

    /**
     * Constructor
     *
     * @param int $groupid (ignored)
     * @param rb_global_restriction_set $globalrestrictionset
     * @throws coding_exception
     */
    public function __construct($groupid, rb_global_restriction_set $globalrestrictionset = null)
    {
        global $DB;

        if ($groupid instanceof rb_global_restriction_set) {
            throw new coding_exception('Wrong parameter orders detected during report source instantiation.');
        }

        // Remember the active global restriction set.
        $this->globalrestrictionset = $globalrestrictionset;

        $this->base = '{user}';
        $this->courses = $DB->get_records('course', ['containertype' => course::get_type()], 'fullname', 'id, fullname');
        $this->joinlist = $this->define_joinlist();
        $this->columnoptions = $this->define_columnoptions();
        $this->filteroptions = $this->define_filteroptions();
        $this->contentoptions = $this->define_contentoptions();
        $this->paramoptions = $this->define_paramoptions();
        $this->defaultcolumns = $this->define_defaultcolumns();
        $this->defaultfilters = $this->define_defaultfilters();
        $this->requiredcolumns = $this->define_requiredcolumns();
        $this->sourcetitle = get_string('sourcetitle', 'rb_source_completion_by_course');
        $this->sourcelabel = get_string('sourcelabel', 'rb_source_completion_by_course');
        $this->sourcesummary = get_string('sourcesummary', 'rb_source_completion_by_course');
        $this->usedcomponents[] = 'local_completionreport';
        $this->define_excluded_users();

        // Apply global report restrictions.
        $this->add_global_report_restriction_join('base', 'id', 'base');

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
     * Define table joins.
     *
     * @return rb_join[]
     */
    protected function define_joinlist()
    {
        $joinlist = [];

        // Adding course completion tables (one per course).
        foreach ($this->courses as $course) {
            $joinlist[] = new rb_join(
                "cc_{$course->id}",
                'LEFT',
                '{course_completions}',
                "cc_{$course->id}.userid = base.id AND cc_{$course->id}.course = {$course->id}",
                REPORT_BUILDER_RELATION_ONE_TO_ONE,
                ['base']
            );
        }

        $this->add_totara_job_tables($joinlist, 'base', 'id');

        return $joinlist;
    }

    /**
     * Define columns.
     *
     * @return rb_column_option[]
     */
    protected function define_columnoptions()
    {
        $columnoptions = [];

        // Adding course completion columns (one per course).
        foreach ($this->courses as $course) {
            $columnoptions[] = new rb_column_option(
                'cc',
                $course->id,
                $course->fullname,
                "cc_{$course->id}.status",
                [
                    'joins'       => ["cc_{$course->id}"],
                    'displayfunc' => 'course_completion_status',
                    'dbdatatype'  => 'integer',
                ]
            );
        }

        $this->add_core_user_columns($columnoptions, 'base');
        $this->add_totara_job_columns($columnoptions);

        return $columnoptions;
    }

    /**
     * Define filters.
     *
     * @return rb_filter_option[]
     */
    protected function define_filteroptions()
    {
        $filteroptions = [];

        // Adding course completion filters (one per course).
        foreach ($this->courses as $course) {
            $filteroptions[] = new rb_filter_option(
                'cc',
                $course->id,
                $course->fullname,
                'multicheck',
                [
                    'selectfunc' => 'completion_status_list',
                    'attributes' => rb_filter_option::select_width_limiter(),
                    'showcounts' => [
                        'joins'     => ['LEFT JOIN {course_completions} ccs_filter ON base.id = ccs_filter.id'],
                        'dataalias' => 'ccs_filter',
                        'datafield' => 'status',
                    ],
                ]
            );
        }

        $this->add_core_user_filters($filteroptions);
        $this->add_totara_job_filters($filteroptions, 'base');

        return $filteroptions;
    }

    /**
     * Define content filters.
     *
     * @return rb_content_option[]
     */
    protected function define_contentoptions()
    {
        $contentoptions = array();

        // Add the manager/position/organisation content options.
        $this->add_basic_user_content_options($contentoptions, 'base');
        
        return $contentoptions;
    }

    /**
     * Define report parameters.
     *
     * @return rb_param_option[]
     */
    protected function define_paramoptions()
    {
        return [];
    }

    /**
     * Define default columns
     *
     * @return string[][]
     */
    protected function define_defaultcolumns()
    {
        return [
            [
                'type'  => 'user',
                'value' => 'fullname',
            ],
        ];
    }

    /**
     * Define default filters.
     *
     * @return string[][]
     */
    protected function define_defaultfilters()
    {
        return [
            [
                'type'  => 'user',
                'value' => 'fullname',
            ],
        ];
    }

    /**
     * Define required columns.
     *
     * @return array
     */
    protected function define_requiredcolumns()
    {
        return [];
    }

    /**
     * Copied from @see rb_source_course_completion::rb_filter_completion_status_list
     *
     * @return array
     */
    public function rb_filter_completion_status_list()
    {
        global $COMPLETION_STATUS;

        $statuslist = [];
        foreach ($COMPLETION_STATUS as $key => $value) {
            $statuslist[(string)$key] = get_string($value, 'completion');
        }

        return $statuslist;
    }

    /**
     * @throws coding_exception
     * @throws dml_exception
     */
    protected function define_excluded_users()
    {
        global $DB;

        $excludeids = array_values(
            array_map(
                function ($admin) {
                    return $admin->id;
                },
                get_admins()
            )
        );
        $excludeids[] = guest_user()->id;

        [$sql, $exclparams] = $DB->get_in_or_equal($excludeids, SQL_PARAMS_NAMED, 'param', false);

        $excsql = "base.id {$sql}";

        $this->sourcewhere = $excsql;
        $this->sourceparams = $exclparams;
    }

    /**
     * KINEO US
     * Returns expected result for column_test.
     * @param rb_column_option $columnoption
     * @return int
     */
    public function phpunit_column_test_expected_count($columnoption) {
        if (!PHPUNIT_TEST) {
            throw new coding_exception('phpunit_column_test_expected_count() cannot be used outside of unit tests');
        }
        return 0;
    }
}