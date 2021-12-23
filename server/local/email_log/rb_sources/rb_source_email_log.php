<?php
/**
 * @package   local_email_log
 * @author    Jo Jones <jo.jones@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

defined('MOODLE_INTERNAL') || die();
global $CFG;

/**
 * A report builder source for the "email_log" table.
 */
class rb_source_email_log extends rb_base_source
{
    use totara_job\rb\source\report_trait;

    public $base, $joinlist, $columnoptions, $filteroptions;
    public $contentoptions, $paramoptions, $defaultcolumns;
    public $defaultfilters, $requiredcolumns, $sourcetitle;

    public function __construct($groupid, rb_global_restriction_set $globalrestrictionset = null)
    {
        if ($groupid instanceof rb_global_restriction_set) {
            throw new coding_exception('Wrong parameter orders detected during report source instantiation.');
        }

        $this->globalrestrictionset = $globalrestrictionset;
        $this->base = '{local_email_log}';
        $this->joinlist = $this->define_joinlist();
        $this->columnoptions = $this->define_columnoptions();
        $this->filteroptions = $this->define_filteroptions();
        $this->contentoptions = $this->define_contentoptions();
        $this->paramoptions = $this->define_paramoptions();
        $this->defaultcolumns = $this->define_defaultcolumns();
        $this->defaultfilters = $this->define_defaultfilters();
        $this->requiredcolumns = [];
        $this->sourcetitle = get_string('sourcetitle', 'rb_source_email_log');
        $this->sourcelabel = get_string('sourcelabel', 'rb_source_email_log');
        $this->sourcesummary = get_string('sourcesummary', 'rb_source_email_log');
        $this->usedcomponents[] = 'local_email_log';
        $this->add_global_report_restriction_join('base', 'usertoid');

        parent::__construct();
    }

    /**
     * @return bool|null
     */
    public function global_restrictions_supported(): bool
    {
        return true;
    }

    /**
     * Creates the array of rb_join objects required for this->joinlist
     *
     * @return array
     */
    protected function define_joinlist(): array
    {
        $joinlist = [];

        $joinlist[] = new rb_join(
            'userfrom',
            'LEFT',
            '{user}',
            "userfrom.id = base.userfromid",
            REPORT_BUILDER_RELATION_ONE_TO_ONE,
            'base'
        );

        $this->add_core_user_tables($joinlist, 'base', 'usertoid');
        $this->add_totara_job_tables($joinlist, 'base', 'usertoid');

        return $joinlist;
    }

    /**
     * Creates the array of rb_column_option objects required for
     * $this->columnoptions
     *
     * @return array
     */
    protected function define_columnoptions(): array
    {
        $columnoptions = [
            new rb_column_option(
                'base',
                'usertoid',
                get_string('usertoid', 'rb_source_email_log'),
                'base.usertoid',
                [
                    'displayfunc' => 'plaintext',
                ]
            ),
            new rb_column_option(
                'base',
                'usertoemail',
                get_string('usertoemail', 'rb_source_email_log'),
                'base.usertoemail',
                [
                    'displayfunc' => 'plaintext',
                ]
            ),
            new rb_column_option(
                'base',
                'userfromid',
                get_string('userfromid', 'rb_source_email_log'),
                'base.userfromid',
                [
                    'displayfunc' => 'plaintext',
                ]
            ),
            new rb_column_option(
                'base',
                'userfromemail',
                get_string('userfromemail', 'rb_source_email_log'),
                'base.userfromemail',
                [
                    'displayfunc' => 'plaintext',
                ]
            ),
            new rb_column_option(
                'base',
                'subject',
                get_string('subject', 'rb_source_email_log'),
                "CASE WHEN LENGTH(base.subject) <= 100 THEN base.subject ELSE SUBSTRING(base.subject, 1, 100) || '...' END",
                [
                    'displayfunc' => 'html_to_text',
                ]
            ),
            new rb_column_option(
                'base',
                'message',
                get_string('message', 'rb_source_email_log'),
                'base.message',
                [
                    'displayfunc' => 'html_to_text',
                ]
            ),
            new rb_column_option(
                'base',
                'status',
                get_string('status', 'rb_source_email_log'),
                'base.status',
                [
                    'displayfunc' => 'email_status',
                ]
            ),
            new rb_column_option(
                'base',
                'timesent',
                get_string('timesent', 'rb_source_email_log'),
                'base.timesent',
                [
                    'displayfunc' => 'nice_datetime',
                ]
            ),
            new rb_column_option(
                'base',
                'actions',
                get_string('actions', 'rb_source_email_log'),
                'base.id',
                [
                    'displayfunc' => 'email_actions',
                ]
            ),
        ];

        $this->add_totara_job_columns($columnoptions);
        $this->add_core_user_columns($columnoptions);

        return $columnoptions;
    }

    /**
     * Creates the array of rb_filter_option objects required for $this->filteroptions
     * @return array
     */
    protected function define_filteroptions(): array
    {
        $filteroptions = [
            new rb_filter_option(
                'base',
                'usertoemail',
                get_string('usertoemail', 'rb_source_email_log'),
                'text'
            ),
            new rb_filter_option(
                'base',
                'subject',
                get_string('subject', 'rb_source_email_log'),
                'text'
            ),
            new rb_filter_option(
                'base',
                'message',
                get_string('message', 'rb_source_email_log'),
                'text'
            ),
            new rb_filter_option(
                'base',
                'status',
                get_string('status', 'rb_source_email_log'),
                'select',
                [
                    'selectfunc' => 'email_status',
                    'attributes' => rb_filter_option::select_width_limiter(),
                ]
            ),
            new rb_filter_option(
                'base',
                'timesent',
                get_string('timesent', 'rb_source_email_log'),
                'date',
                [
                    'includetime' => true,
                ]
            ),

        ];

        $this->add_core_user_filters($filteroptions);
        $this->add_totara_job_filters($filteroptions);

        return $filteroptions;
    }


    protected function define_defaultcolumns(): array
    {
        return [
            [
                'type' => 'base',
                'value' => 'subject',
            ],
            [
                'type' => 'base',
                'value' => 'message',
            ],
            [
                'type' => 'base',
                'value' => 'userfromid',
            ],
            [
                'type' => 'user',
                'value' => 'fullname',
            ],
            [
                'type' => 'base',
                'value' => 'timesent',
            ],
        ];
    }

    public function define_contentoptions(): array
    {
        $contentOptions = parent::define_contentoptions();
        $this->add_basic_user_content_options($contentOptions);

        $contentOptions[] = new \rb_content_option(
            'from_user_visibility',
            get_string('from_user_visibility', 'local_email_log'),
            "userfrom.id",
            "userfrom"
        );

        return $contentOptions;
    }

    public function rb_filter_email_status(): array
    {
        return [
            0 => get_string('sent', 'local_email_log'),
            1 => get_string('resent', 'local_email_log'),
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
        return 0;
    }
}