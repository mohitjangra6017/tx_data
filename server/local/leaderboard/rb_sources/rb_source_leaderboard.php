<?php

use local_leaderboard\Utils;

defined('MOODLE_INTERNAL') || die();

/**
 * Scores report source.
 *
 * @package   local_score
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2016 City and Guilds Kineo
 * @license   http://www.kineo.com
 */
class rb_source_leaderboard extends rb_base_source
{
    use \totara_job\rb\source\report_trait;
    use \core_course\rb\source\report_trait;
    use \totara_program\rb\source\program_trait;

    public $base;
    public $joinlist;
    public $columnoptions;
    public $filteroptions;
    public $contentoptions;
    public $defaultcolumns;
    public $defaultfilters;
    public $sourcetitle;
    private $report;
    private $excludedusers;

    /**
     * rb_source_leaderboard constructor.
     * @param $groupid
     * @param rb_global_restriction_set|null $globalrestrictionset
     * @throws ReportBuilderException
     * @throws coding_exception
     * @throws dml_exception
     */
    public function __construct($groupid, rb_global_restriction_set $globalrestrictionset = null)
    {
        if ($groupid instanceof rb_global_restriction_set) {
            throw new coding_exception('Wrong parameter orders detected during report source instantiation.');
        }
        $this->globalrestrictionset = $globalrestrictionset;

        $this->base = '{local_leaderboard}';
        $this->excludedusers = Utils::getExcludedUserIds() ? implode(',', Utils::getExcludedUserIds()) : '';
        $this->joinlist = $this->define_joinlist();
        $this->columnoptions = $this->define_columnoptions();
        $this->filteroptions = $this->define_filteroptions();
        $this->contentoptions = $this->define_contentoptions();
        $this->defaultcolumns = $this->define_defaultcolumns();
        $this->defaultfilters = $this->define_defaultfilters();
        $this->requiredcolumns = $this->define_requiredcolumns();
        $this->sourcetitle = get_string('sourcetitle', 'rb_source_leaderboard');
        $this->sourcewhere = $this->excludedusers ? "leaderboard_user.userid NOT IN ({$this->excludedusers})" : '';
        $this->usedcomponents[] = 'local_leaderboard';
        $this->usedcomponents[] = 'totara_cohort';
        $this->usedcomponents[] = 'totara_program';
        $this->add_global_report_restriction_join('leaderboard_user', 'userid', 'leaderboard_user');
        $this->sourcelabel = get_string('sourcelabel', 'rb_source_leaderboard');
        $this->sourcesummary = get_string('sourcesummary', 'rb_source_leaderboard');

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

        $joinlist[] =
            new rb_join(
                'leaderboard_user',
                'RIGHT',
                '{local_leaderboard_user}',
                'base.id = leaderboard_user.leaderboardid',
                REPORT_BUILDER_RELATION_ONE_TO_MANY,
                'base'
            );

        $where = $this->excludedusers ? "WHERE userid NOT IN ({$this->excludedusers})" : "WHERE (1 = 1)";

        if (Utils::isActiveUsersOnly()) {
            $where .= " AND auser.suspended = 0 AND auser.deleted = 0 ";
        }

        $joinlist[] = new rb_join(
            'leaderboard_user_rank',
            'INNER',
            "(SELECT
                    userid,
                    DENSE_RANK() OVER (ORDER BY SUM(score) DESC) AS ranking
                    FROM {local_leaderboard_user} AS lboard
                    JOIN {user} AS auser ON auser.id = lboard.userid 
                    {$where}
                GROUP BY userid)",
            'leaderboard_user.userid = leaderboard_user_rank.userid',
            REPORT_BUILDER_RELATION_ONE_TO_ONE,
            'leaderboard_user'
        );

        $joinlist[] = new rb_join(
            'context',
            'INNER',
            '{context}',
            'context.id = leaderboard_user.contextid',
            REPORT_BUILDER_RELATION_ONE_TO_ONE,
            'leaderboard_user'
        );

        $joinlist[] = new rb_join(
            'prog',
            'LEFT',
            '{prog}',
            'prog.id = context.instanceid AND context.contextlevel = ' . CONTEXT_PROGRAM,
            REPORT_BUILDER_RELATION_ONE_TO_ONE,
            'context'
        );

        $this->add_core_user_tables($joinlist, 'leaderboard_user', 'userid');
        $this->add_totara_job_tables($joinlist, 'leaderboard_user', 'userid');
        $this->add_core_course_tables($joinlist, 'leaderboard_user', 'courseid');

        return $joinlist;
    }

    /**
     * Define report columns.
     *
     * @return rb_column_option[]
     * @throws coding_exception
     */
    protected function define_columnoptions()
    {
        $columnoptions = [];

        $columnoptions[] = new rb_column_option(
            'base',
            'id',
            get_string('column:id', 'rb_source_leaderboard'),
            'base.id',
            [
                'selectable' => 0,
                'displayfunc' => 'plaintext',
            ]
        );

        $columnoptions[] = new rb_column_option(
            'base',
            'eventname',
            get_string('column:eventname', 'rb_source_leaderboard'),
            'base.eventname',
            [
                'joins' => ['leaderboard_user'],
                'dbdatatype' => 'char',
                'displayfunc' => 'plaintext',
            ]
        );

        $columnoptions[] = new rb_column_option(
            'leaderboard_user',
            'score',
            get_string('column:score', 'rb_source_leaderboard'),
            'leaderboard_user.score',
            [
                'joins' => ['leaderboard_user'],
                'dbdatatype' => 'integer',
                'displayfunc' => 'plaintext',
            ]
        );

        $columnoptions[] = new rb_column_option(
            'leaderboard_user',
            'timescored',
            get_string('column:timescored', 'rb_source_leaderboard'),
            'leaderboard_user.timescored',
            ['displayfunc' => 'nice_datetime', 'joins' => ['leaderboard_user']]
        );

        $columnoptions[] = new rb_column_option(
            'leaderboard_user',
            'filtered_rank',
            get_string('column:filteredrank', 'rb_source_leaderboard'),
            'leaderboard_user.score',
            [
                'columngenerator' => 'rank',
                'displayfunc' => 'plaintext',
            ]
        );

        $columnoptions[] = new rb_column_option(
            'leaderboard_user',
            'global_rank',
            get_string('column:globalrank', 'rb_source_leaderboard'),
            'leaderboard_user_rank.ranking',
            [
                'joins' => ['leaderboard_user_rank'],
                'displayfunc' => 'plaintext',
            ]
        );

        $this->add_core_user_columns($columnoptions);
        $this->add_totara_job_columns($columnoptions);
        $this->add_core_course_columns($columnoptions);
        $this->add_totara_program_columns($columnoptions, 'prog');

        return $columnoptions;
    }

    /**
     * Define report filters.
     *
     * @return rb_filter_option[]
     * @throws coding_exception
     * @throws dml_exception
     */
    protected function define_filteroptions()
    {
        global $DB;

        $scores = $DB->get_records_menu('local_leaderboard', null, 'eventname', 'id, eventname');

        $filteroptions = [];

        $filteroptions[] = new rb_filter_option(
            'base',
            'id',
            get_string('filter:id', 'rb_source_leaderboard'),
            'select',
            ['selectchoices' => $scores]
        );

        $filteroptions[] = new rb_filter_option(
            'leaderboard_user',
            'score',
            get_string('filter:score', 'rb_source_leaderboard'),
            'number'
        );

        $filteroptions[] = new rb_filter_option(
            'leaderboard_user',
            'timescored',
            get_string('filter:timescored', 'rb_source_leaderboard'),
            'date'
        );

        $filteroptions[] = new rb_filter_option(
            'leaderboard_user',
            'global_rank',
            get_string('column:globalrank', 'rb_source_leaderboard'),
            'number'
        );


        $this->add_core_user_filters($filteroptions);
        $this->add_totara_job_filters($filteroptions);
        $this->add_core_course_filters($filteroptions);
        $this->add_totara_program_filters($filteroptions);


        return $filteroptions;
    }

    /**
     * Define content options.
     *
     * @return rb_content_option[]
     * @throws coding_exception
     */
    protected function define_contentoptions()
    {
        $contentoptions = [];

        $contentoptions[] = new rb_content_option(
            'leaderboard_date',
            get_string('leaderboard_date:title', 'rb_source_leaderboard'),
            ['leaderboard_user.timescored'],
            'leaderboard_user'
        );

        $this->add_basic_user_content_options($contentoptions);

        return $contentoptions;
    }

    /**
     * Define default columns.
     *
     * @return array[]
     */
    protected function define_defaultcolumns()
    {
        return [
            [
                'type' => 'user',
                'value' => 'fullname',
            ],
            [
                'type' => 'leaderboard_user',
                'value' => 'score',
            ],
            [
                'type' => 'leaderboard_user',
                'value' => 'timescored',
            ],
        ];
    }

    /**
     * Define default filters.
     *
     * @return array[]
     */
    protected function define_defaultfilters()
    {
        return [
            [
                'type' => 'user',
                'value' => 'fullname',
            ],
            [
                'type' => 'base',
                'value' => 'id',
            ],
            [
                'type' => 'leaderboard_user',
                'value' => 'score',
            ],
            [
                'type' => 'leaderboard_user',
                'value' => 'timescored',
            ],
        ];
    }

    /**
     * @return array
     * @throws coding_exception
     */
    protected function define_requiredcolumns()
    {
        $requiredcolumns = [];
        $requiredcolumns[] = new rb_column(
            'leaderboard_user',
            'userid',
            get_string('column:userid_hidden', 'rb_source_leaderboard'),
            'leaderboard_user.userid',
            ['selectable' => 0, 'hidden' => 1]
        );
        return $requiredcolumns;
    }

    /**
     * @param reportbuilder $report
     */
    public function post_params(reportbuilder $report)
    {
        $this->report = $report;
    }

    /**
     * @param rb_column_option $columnOption
     * @param bool $hidden
     * @return rb_column[]
     * @throws dml_exception
     */
    public function rb_cols_generator_rank(rb_column_option $columnOption, $hidden)
    {
        global $DB;

        // Generated columns do not support custom headings in core - we have to get it here.
        $customHeading = $DB->get_field(
            'report_builder_columns',
            'heading',
            [
                'reportid' => $this->report->_id,
                'type' => $columnOption->type,
                'value' => $columnOption->value,
                'customheading' => 1,
            ]
        );

        $heading = (!empty($customHeading)) ? $customHeading : $columnOption->name;

        return [
            new rb_column(
                $columnOption->type,
                $columnOption->value,
                $heading,
                $columnOption->field,
                [
                    'joins' => ['leaderboard_user'],
                    'aggregate' => 'rank',
                ]
            ),
        ];
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
            "{$columnoption->type}_{$columnoption->value}" == 'user_tenantmember' ||
            strpos("{$columnoption->type}_{$columnoption->value}", 'base_id') === 0) {
            return 1;
        }
        return 0;
    }
}