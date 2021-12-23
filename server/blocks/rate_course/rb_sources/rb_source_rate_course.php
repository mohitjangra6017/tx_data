<?php

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot.'/totara/reportbuilder/rb_sources/rb_source_courses.php');

class rb_source_rate_course extends rb_source_courses {
    public function __construct() {
        parent::__construct();
        $this->sourcetitle = get_string('sourcetitle', 'rb_source_rate_course');
        $this->usedcomponents[] = 'block_rate_course';
        $this->sourcesummary = get_string('sourcesummary', 'rb_source_rate_course');
    }

    /**
     * @return bool|null
     */
    public function global_restrictions_supported() {
        return false;
    }

    /**
     * @return rb_join[]
     */
    protected function define_joinlist() {
        $joinlist = parent::define_joinlist();

        // Course rating join (leftie).
        $joinlist[] = new rb_join(
            'rating',
            'LEFT',
            '(SELECT course, AVG(rating) as rating, COUNT(rating) as ratingcount FROM {rate_course_review} GROUP BY course)',
            'rating.course = base.id',
            REPORT_BUILDER_RELATION_ONE_TO_ONE
        );

        return $joinlist;
    }

    /**
     * @return array|rb_column_option[]
     * @throws coding_exception
     */
    protected function define_columnoptions() {
        $columnoptions = parent::define_columnoptions();

        $columnoptions[] = new rb_column_option(
            'rating',
            'rating',
            get_string('courserating', 'rb_source_rate_course'),
            'rating.rating',
            [
                'joins' => 'rating',
                'displayfunc' => 'rating',
                'dbdatatype' => 'integer'
            ]
        );

        $columnoptions[] = new rb_column_option(
            'rating',
            'ratingcount',
            get_string('ratingcount', 'rb_source_rate_course'),
            'rating.ratingcount',
            [
                'displayfunc' => 'plaintext',
                'joins' => 'rating'
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

        $filteroptions[] = new rb_filter_option(
            'rating',
            'rating',
            get_string('courserating', 'rb_source_rate_course'),
            'rating',
            [
                'selectfunc' => 'course_ratings_list'
            ]
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
     * @return array|string[][]
     */
    protected function define_defaultcolumns() {
        $defaultcolumns = parent::define_defaultcolumns();

        $defaultcolumns[] = [
            'type' => 'rating',
            'value' => 'rating',
        ];

        return $defaultcolumns;
    }

    /**
     * Returns available options for rating filter
     *
     * @return array
     */
    public function rb_filter_course_ratings_list() {
        return array_combine(range(1, 5), range(1, 5));
    }
}