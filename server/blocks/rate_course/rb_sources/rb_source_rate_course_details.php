<?php

/**
 * Details of course rating
 *
 * @package    block
 * @subpackage rate_course
 * @copyright  &copy; 2017 Kineo Pacific {@link http://kineo.com.au}
 * @author     tri.le
 * @version    1.0
 */

class rb_source_rate_course_details extends rb_base_source
{
    use \core_course\rb\source\report_trait;
    use \totara_job\rb\source\report_trait;
    use \totara_reportbuilder\rb\source\report_trait;

    public function __construct($groupid, \rb_global_restriction_set $globalrestrictionset = null)
    {
        if ($groupid instanceof rb_global_restriction_set) {
            throw new coding_exception('Wrong parameter orders detected during report source instantiation.');
        }

        // Required css to renders div into stars.
        global $OUTPUT, $PAGE;
        if (!$OUTPUT->has_started() && !is_ajax_request($_SERVER)) {
            try {
                $PAGE->requires->css('/blocks/rate_course/styles.css');
            } catch (Exception $exc) {
                // Fix issue where $OUTPUT->has_started() giving wrong status
            }
        }

        $this->base = '(SELECT review.id, review.course, review.userid, review.rating, review.review, review.reviewlikes, review.timecreated
                          FROM {rate_course_review} review)';
        $this->joinlist = $this->define_joinlist();
        $this->columnoptions = $this->define_columnoptions();
        $this->filteroptions = $this->define_filteroptions();
        $this->contentoptions = $this->define_contentoptions();
        $this->paramoptions = $this->define_paramoptions();
        $this->defaultcolumns = $this->define_defaultcolumns();
        $this->defaultfilters = $this->define_defaultfilters();
        $this->requiredcolumns = [];
        $this->sourcetitle = get_string('detailsourcetitle', 'rb_source_rate_course_details');
        $this->globalrestrictionset = $globalrestrictionset;
        $this->add_global_report_restriction_join('base', 'userid', 'base');
        $this->sourcelabel = get_string('sourcelabel', 'rb_source_courses');
        $this->sourcesummary = get_string('sourcesummary', 'rb_source_rate_course_details');

        parent::__construct();
        $this->usedcomponents[] = 'block_rate_course';
        $this->usedcomponents[] = 'totara_cohort';
        [$this->sourcewhere, $this->sourceparams, $this->sourcejoins] = $this->define_source_args();
    }

    /**
     * @return bool|null
     */
    public function global_restrictions_supported() {
        return true;
    }

    /**
     * @return array
     */
    protected function define_joinlist() {
        $joinlist = [];
        $this->add_context_tables($joinlist, 'base', 'course', CONTEXT_COURSE, 'INNER');
        $this->add_core_user_tables($joinlist, 'base', 'userid');
        $this->add_core_course_tables($joinlist, 'base', 'course');
        $this->add_totara_job_tables($joinlist, 'base', 'userid');
        return $joinlist;
    }

    /**
     * @return array
     * @throws coding_exception
     */
    protected function define_columnoptions() {
        $columnoptions = [];
        $this->add_core_user_columns($columnoptions);
        $this->add_totara_job_columns($columnoptions);
        $this->add_core_course_columns($columnoptions);

        $columnoptions[] = new rb_column_option(
            'review',
            'rating',
            get_string('rating', 'rb_source_rate_course_details'),
            'base.rating',
            [
                'displayfunc' => 'rating',
                'dbdatatype' => 'integer'
            ]
        );

        $columnoptions[] = new rb_column_option(
            'review',
            'review',
            get_string('review', 'rb_source_rate_course_details'),
            'base.review',
            [
                'displayfunc' => 'plaintext',
            ]
        );

        $columnoptions[] = new rb_column_option(
            'review',
            'timecompleted',
            get_string('reviewdate', 'rb_source_rate_course_details'),
            'base.timecreated',
            ['dbdatatype' => 'timestamp', 'displayfunc' => 'nice_date']
        );

        $columnoptions[] = new rb_column_option(
            'review',
            'reviewlikes',
            get_string('reviewlikes', 'rb_source_rate_course_details'),
            'base.reviewlikes',
            [
                'displayfunc' => 'plaintext',
            ]
        );

        return $columnoptions;
    }

    /**
     * @return array
     */
    protected function define_filteroptions() {
        $filteroptions = [];
        $this->add_totara_job_filters($filteroptions);
        return $filteroptions;
    }

    /**
     * @return array
     */
    protected function define_contentoptions()
    {
        $contentoptions = [];
        $this->add_basic_user_content_options($contentoptions);
        return $contentoptions;
    }

    /**
     * @return array|rb_param_option[]
     */
    protected function define_paramoptions()
    {
        return [
            new rb_param_option(
                'deleted',
                'auser.deleted'
            ),
        ];
    }

    /**
     * @return array|string[][]
     */
    protected function define_defaultcolumns()
    {
        return [
            [
                'type' => 'user',
                'value' => 'firstname',
            ],
            [
                'type' => 'user',
                'value' => 'lastname',
            ],
            [
                'type' => 'course',
                'value' => 'courselink',
            ],
            [
                'type' => 'review',
                'value' => 'timecompleted',
            ],
            [
                'type' => 'review',
                'value' => 'review',
            ],
            [
                'type' => 'review',
                'value' => 'rating',
            ],
        ];
    }

    /**
     * @return array|string[][]
     */
    protected function define_defaultfilters()
    {
        return [
            [
                'type' => 'user',
                'value' => 'fullname',
            ],
            [
                'type' => 'course',
                'value' => 'fullname',
            ],
        ];
    }

    /**
     * @return array
     * @throws coding_exception
     */
    protected function define_source_args()
    {
        // Only include courses the user is allowed to see.
        [$sql, $params] = totara_visibility_where();

        // Exclude the site course and all the non courses container.
        $sql = "(course.containertype = :containertype) AND ({$sql})";
        $params['containertype'] = \container_course\course::get_type();

        $joins = ['ctx', 'course'];

        return [$sql, $params, $joins];
    }

    /**
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
