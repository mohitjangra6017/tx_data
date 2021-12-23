<?php

use local_credly\entity\BadgeIssue;

/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

class rb_source_credly_badges extends rb_base_source
{
    use totara_job\rb\source\report_trait;

    public function __construct($groupId, rb_global_restriction_set $set = null)
    {
        if ($groupId instanceof rb_global_restriction_set) {
            throw new coding_exception('Wrong parameter orders detected during report source instantiation.');
        }

        $this->globalrestrictionset = $set;
        $this->base = '{local_credly_badge_issues}';
        $this->joinlist = $this->define_joinlist();
        $this->columnoptions = $this->define_columnoptions();
        $this->filteroptions = $this->define_filteroptions();
        $this->contentoptions = $this->define_contentoptions();
        $this->paramoptions = $this->define_paramoptions();
        $this->defaultcolumns = $this->define_defaultcolumns();
        $this->defaultfilters = $this->define_defaultfilters();
        $this->requiredcolumns = [];
        $this->sourcetitle = get_string('sourcetitle', __CLASS__);
        $this->sourcelabel = get_string('sourcelabel', __CLASS__);
        $this->sourcesummary = get_string('sourcesummary', __CLASS__);
        $this->add_global_report_restriction_join('base', 'userid');
        $this->usedcomponents[] = 'local_credly';

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
     * @return rb_join[]
     */
    protected function define_joinlist(): array
    {
        $joins = [];

        $learningJoin = <<<SQL
(
    SELECT c.id, c.fullname, c.shortname, c.category, 'course' AS type FROM "ttr_course" c
    UNION
    SELECT p.id, p.fullname, p.shortname, p.category, 'program' AS type FROM "ttr_prog" p
    UNION
    SELECT p.certifid AS id, p.fullname, p.shortname, p.category, 'certification' AS type FROM "ttr_prog" p
)
SQL;

        $joins[] = new rb_join(
            'learning',
            'INNER',
            $learningJoin,
            "(learning.id = base.courseid AND learning.type = 'course') 
            OR (learning.id = base.programid AND learning.type = 'program') 
            OR (learning.id = base.certificationid AND learning.type = 'certification')",
            REPORT_BUILDER_RELATION_ONE_TO_ONE
        );

        $joins[] = new rb_join(
            'badge',
            'INNER',
            '{local_credly_badges}',
            'badge.id = base.badgeid',
            REPORT_BUILDER_RELATION_ONE_TO_ONE
        );

        $joins[] = new rb_join(
            'category',
            'INNER',
            '{course_categories}',
            'category.id = learning.category',
            REPORT_BUILDER_RELATION_ONE_TO_ONE,
            ['learning']
        );

        $logJoin = <<<SQL
(
    SELECT m.*, l.log_count FROM "ttr_local_credly_issue_logs" m
    INNER JOIN (
        SELECT MAX(id) AS id, badgeissueid, COUNT(1) AS log_count
        FROM "ttr_local_credly_issue_logs"
        GROUP BY badgeissueid
    ) l ON l.id = m.id
)
SQL;
        $joins[] = new rb_join(
            'log',
            'INNER',
            $logJoin,
            'base.id = log.badgeissueid',
            REPORT_BUILDER_RELATION_ONE_TO_ONE
        );

        $this->add_core_user_tables($joins, 'base', 'userid');

        return $joins;
    }

    /**
     * @return rb_column_option[]
     */
    protected function define_columnoptions(): array
    {
        $columns = [];

        $columns[] = new rb_column_option(
            'badge',
            'credlyid',
            get_string('col:badge:credlyid', __CLASS__),
            'badge.credlyid',
            [
                'dbdatatype' => 'char',
                'outputformat' => 'text',
                'displayfunc' => 'plaintext',
                'joins' => ['badge'],
            ]
        );

        $columns[] = new rb_column_option(
            'badge',
            'name',
            get_string('col:badge:name', __CLASS__),
            'badge.name',
            [
                'dbdatatype' => 'char',
                'outputformat' => 'text',
                'displayfunc' => 'plaintext',
                'joins' => ['badge'],
            ]
        );

        $columns[] = new rb_column_option(
            'badge',
            'issuetime',
            get_string('col:badge:issuetime', __CLASS__),
            'base.issuetime',
            [
                'dbdatatype' => 'integer',
                'displayfunc' => 'nice_datetime_seconds',
            ]
        );

        $columns[] = new rb_column_option(
            'badge',
            'expirytime',
            get_string('col:badge:expirytime', __CLASS__),
            'base.timeexpires',
            [
                'dbdatatype' => 'integer',
                'displayfunc' => 'nice_datetime_seconds',
            ]
        );

        $columns[] = new rb_column_option(
            'badge',
            'createdtime',
            get_string('col:badge:createdtime', __CLASS__),
            'base.createdtime',
            [
                'dbdatatype' => 'integer',
                'displayfunc' => 'nice_datetime_seconds'
            ]
        );

        $columns[] = new rb_column_option(
            'badge',
            'updatedtime',
            get_string('col:badge:updatedtime', __CLASS__),
            'base.updatedtime',
            [
                'dbdatatype' => 'integer',
                'displayfunc' => 'nice_datetime_seconds',
            ]
        );

        $columns[] = new rb_column_option(
            'badge',
            'status',
            get_string('col:badge:status', __CLASS__),
            'base.status',
            [
                'dbdatatype' => 'char',
                'displayfunc' => 'badge_status',
            ]
        );

        $successStatus = BadgeIssue::STATUS_SUCCESS;
        $recoverableFailure = BadgeIssue::STATUS_RECOVERABLE_FAILURE;
        $unrecoverableFailure = BadgeIssue::STATUS_UNRECOVERABLE_FAILURE;

        $statusSelect = <<<SQL
(
    CASE 
        WHEN base.status = '$successStatus' THEN 'success'
        WHEN base.status = '$recoverableFailure' OR base.status = '$unrecoverableFailure' THEN 'failure' 
    END
)
SQL;

        $columns[] = new rb_column_option(
            'badge',
            'filter_status',
            get_string('col:badge:status', __CLASS__),
            $statusSelect,
            [
                'hidden' => 1,
                'selectable' => false,
            ]
        );

        $columns[] = new rb_column_option(
            'badge',
            'response',
            get_string('col:badge:response', __CLASS__),
            'log.response',
            [
                'dbdatatype' => 'char',
                'displayfunc' => 'badge_log',
                'extrafields' => [
                    'count' => 'log.log_count',
                ],
                'joins' => ['log']
            ]
        );

        $columns[] = new rb_column_option(
            'learning',
            'fullname',
            get_string('col:learning:fullname', __CLASS__),
            'learning.fullname',
            [
                'dbdatatype' => 'char',
                'displayfunc' => 'plaintext',
                'outputformat' => 'text',
                'joins' => ['learning'],
            ]
        );

        $columns[] = new rb_column_option(
            'learning',
            'shortname',
            get_string('col:learning:shortname', __CLASS__),
            'learning.shortname',
            [
                'dbdatatype' => 'char',
                'displayfunc' => 'plaintext',
                'outputformat' => 'text',
                'joins' => ['learning'],
            ]
        );

        $columns[] = new rb_column_option(
            'learning',
            'type',
            get_string('col:learning:type', __CLASS__),
            'learning.type',
            [
                'displayfunc' => 'badge_learning_type',
                'joins' => ['learning'],
            ]
        );

        $columns[] = new rb_column_option(
            'learning',
            'category_name',
            get_string('col:learning:category_name', __CLASS__),
            'category.name',
            [
                'dbdatatype' => 'char',
                'displayfunc' => 'plaintext',
                'outputformat' => 'text',
                'joins' => ['category'],
            ]
        );

        $columns[] = new rb_column_option(
            'badge',
            'actions',
            get_string('col:badge:actions', __CLASS__),
            'base.id',
            [
                'displayfunc' => 'badge_actions',
                'noexport' => true,
                'extrafields' => [
                    'status' => 'base.status',
                ]
            ]
        );

        $this->add_core_user_columns($columns);

        return $columns;
    }

    /**
     * @return array
     */
    protected function define_filteroptions()
    {
        $filters = [];

        $filters[] = new rb_filter_option(
            'badge',
            'credlyid',
            get_string('col:badge:credlyid', 'rb_source_credly_badges'),
            'text'
        );

        $filters[] = new rb_filter_option(
            'badge',
            'name',
            get_string('col:badge:name', 'rb_source_credly_badges'),
            'text'
        );

        $filters[] = new rb_filter_option(
            'badge',
            'filter_status',
            get_string('col:badge:status', 'rb_source_credly_badges'),
            'select',
            [
                'selectchoices' => [
                    'success' => get_string('badge_issue:status:success', 'local_credly'),
                    'failure' => get_string('badge_issue:status:recoverable_failure', 'local_credly'),
                ]
            ]
        );

        $filters[] = new rb_filter_option(
            'learning',
            'type',
            get_string('col:learning:type', 'rb_source_credly_badges'),
            'select',
            [
                'selectchoices' => [
                    'course' => get_string('course'),
                    'program' => get_string('program', 'totara_program'),
                ]
            ]
        );

        $filters[] = new rb_filter_option(
            'learning',
            'fullname',
            get_string('col:learning:fullname', 'rb_source_credly_badges'),
            'text'
        );

        $filters[] = new rb_filter_option(
            'learning',
            'shortname',
            get_string('col:learning:shortname', 'rb_source_credly_badges'),
            'text'
        );

        $filters[] = new rb_filter_option(
            'learning',
            'category_name',
            get_string('col:learning:category_name', 'rb_source_credly_badges'),
            'text'
        );

        $filters[] = new rb_filter_option(
            'badge',
            'issuetime',
            get_string('col:badge:issuetime', 'rb_source_credly_badges'),
            'date',
            [
                'includetime' => true,
            ]
        );

        $this->add_core_user_filters($filters);

        return $filters;
    }

    /**
     * @return array
     */
    protected function define_defaultcolumns()
    {
        $columns = [];
        
        $columns[] = ['type' => 'badge', 'value' => 'name'];
        $columns[] = ['type' => 'user', 'value' => 'fullname'];
        $columns[] = ['type' => 'learning', 'value' => 'fullname'];
        $columns[] = ['type' => 'learning', 'value' => 'type'];
        $columns[] = ['type' => 'badge', 'value' => 'issuetime'];
        $columns[] = ['type' => 'badge', 'value' => 'status'];
        $columns[] = ['type' => 'badge', 'value' => 'response'];
        $columns[] = ['type' => 'badge', 'value' => 'actions'];

        return $columns;
    }

    /**
     * @return array
     */
    protected function define_contentoptions()
    {
        $content = [];

        $this->add_basic_user_content_options($content);

        return $content;
    }
}