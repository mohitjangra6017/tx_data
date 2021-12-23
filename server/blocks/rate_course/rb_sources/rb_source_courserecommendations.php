<?php

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot.'/totara/reportbuilder/rb_sources/rb_source_courses.php');

class rb_source_courserecommendations extends rb_source_courses {
    public function __construct($groupid, \rb_global_restriction_set $globalrestrictionset = null) {
        if ($groupid instanceof rb_global_restriction_set) {
            throw new coding_exception('Wrong parameter orders detected during report source instantiation.');
        }

        $this->globalrestrictionset = $globalrestrictionset;
        $this->add_global_report_restriction_join('auser', 'id', 'auser');
        parent::__construct();
        $this->contentoptions = $this->define_contentoptions();
        $this->sourcetitle = get_string('sourcetitle', 'rb_source_courserecommendations');
        $this->sourcesummary = get_string('sourcesummary', 'rb_source_courserecommendations');
        $this->usedcomponents[] = 'block_rate_course';
    }

    /**
     * @return bool
     */
    public function global_restrictions_supported() {
        return true;
    }

    /**
     * @return rb_join[]
     */
    protected function define_joinlist() {
        $joinlist = parent::define_joinlist();

        $joinlist[] = new rb_join(
            'recommendations',
            '',
            '(SELECT userid, course, useridto, source, status, timecreated FROM {rate_course_recommendations})',
            'recommendations.course = base.id',
            REPORT_BUILDER_RELATION_ONE_TO_MANY
        );
        
        $this->add_core_user_tables($joinlist, 'recommendations', 'useridto', 'auser');
        $this->add_core_user_tables($joinlist, 'recommendations', 'userid', 'userfrom');

        return $joinlist;
    }

    /**
     * @return array|rb_column_option[]
     * @throws coding_exception
     */
    protected function define_columnoptions() {
        $columnoptions = parent::define_columnoptions();
        
        $this->add_core_user_columns($columnoptions, 'auser', 'userto', true);
        $this->add_core_user_columns($columnoptions, 'userfrom', 'user', true);

        $columnoptions[] = new rb_column_option(
            'recommendations',
            'source',
            get_string('source', 'rb_source_courserecommendations'),
            'recommendations.source',
            [
                'displayfunc' => 'plaintext',
                'joins' => 'recommendations'
            ]
        );
        
        $columnoptions[] = new rb_column_option(
            'recommendations',
            'status',
            get_string('status', 'rb_source_courserecommendations'),
            'recommendations.status',
            [
                'joins' => 'recommendations',
                'displayfunc' => 'recommendation_status'
            ]
        );
        
        $columnoptions[] = new rb_column_option(
            'recommendations',
            'timecreated',
            get_string('timecreated', 'rb_source_courserecommendations'),
            'recommendations.timecreated',
            [
                'joins' => 'recommendations',
                'dbdatatype' => 'timestamp',
                'displayfunc' => 'nice_datetime'
            ]
        );

        return $columnoptions;
    }

    /**
     * @return array|rb_filter_option[]
     * @throws coding_exception
     */
    protected function define_filteroptions() {
        $filteroptions = parent::define_filteroptions();
        
        $this->add_core_user_filters($filteroptions, 'userto', true);

        $this->add_core_user_filters($filteroptions, 'user', true);

        $filteroptions[] = new rb_filter_option(
            'recommendations',
            'source',
            get_string('source', 'rb_source_courserecommendations'),
            'text'
        );
        
        $filteroptions[] = new rb_filter_option(
            'recommendations',
            'status',
            get_string('status', 'rb_source_courserecommendations'),
            'select',
            [
                'selectfunc' => 'status_list'
            ]
        );
        
        $filteroptions[] = new rb_filter_option(
            'recommendations',
            'timecreated',
            get_string('timecreated', 'rb_source_courserecommendations'),
            'date'
        );

        return $filteroptions;
    }

    /**
     * @return array|rb_content_option[]
     */
    public function define_contentoptions()
    {
        $contentoptions = parent::define_contentoptions();

        $this->add_basic_user_content_options($contentoptions);

        return $contentoptions;
    }

    /**
     * @return array
     * @throws coding_exception
     */
    function rb_filter_status_list () {
        return [
            0 => get_string('active', 'rb_source_courserecommendations'),
            1 => get_string('dismissed', 'rb_source_courserecommendations')
        ];
    }

    /**
     * @return array|string[][]
     */
    protected function define_defaultcolumns() {
        $defaultcolumns = parent::define_defaultcolumns();

        $defaultcolumns[] = [
            'type' => 'user',
            'value' => 'fullname',
        ];

        $defaultcolumns[] = [
            'type' => 'userto',
            'value' => 'fullname',
        ];

        $defaultcolumns[] = [
            'type' => 'recommendations',
            'value' => 'timecreated',
        ];

        return $defaultcolumns;
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
        if ("{$columnoption->type}_{$columnoption->value}" == 'userto_tenantmember' ||
            "{$columnoption->type}_{$columnoption->value}" == 'user_tenantmember') {
            return 1;
        }
        if ($columnoption->type == 'userto' ||
            $columnoption->type == 'user' ||
            $columnoption->type == 'recommendations') {
            return 0;
        }
        return 1;
    }
}