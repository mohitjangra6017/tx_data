<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */


class rb_source_credly_webhook_logs extends rb_base_source
{
    use totara_job\rb\source\report_trait;

    public function __construct($groupId, rb_global_restriction_set $set = null)
    {
        if ($groupId instanceof rb_global_restriction_set) {
            throw new coding_exception('Wrong parameter orders detected during report source instantiation.');
        }

        $this->globalrestrictionset = $set;
        $this->base = '{local_credly_webhook_logs}';
        $this->joinlist = $this->define_joinlist();
        $this->columnoptions = $this->define_columnoptions();
        $this->filteroptions = $this->define_filteroptions();
        $this->defaultcolumns = $this->define_defaultcolumns();
        $this->requiredcolumns = [];
        $this->sourcetitle = get_string('sourcetitle', __CLASS__);
        $this->sourcelabel = get_string('sourcelabel', __CLASS__);
        $this->sourcesummary = get_string('sourcesummary', __CLASS__);
        $this->usedcomponents[] = 'local_credly';

        parent::__construct();
    }

    /**
     * @return bool|null
     */
    public function global_restrictions_supported(): bool
    {
        return false;
    }

    /**
     * @return rb_join[]
     */
    protected function define_joinlist(): array
    {
        $joins = [];

        $joins[] = new rb_join(
            'badge',
            'INNER',
            '{local_credly_badges}',
            'badge.id = base.badgeid',
            REPORT_BUILDER_RELATION_ONE_TO_ONE
        );

        return $joins;
    }

    /**
     * @return rb_column_option[]
     */
    protected function define_columnoptions(): array
    {
        $columns = [];

        $columns[] = new rb_column_option(
            'base',
            'eventid',
            get_string('col:base:eventid', __CLASS__),
            'base.eventid',
            [
                'dbdatatype' => 'char',
                'outputformat' => 'text',
                'displayfunc' => 'plaintext'
            ]
        );

        $columns[] = new rb_column_option(
            'base',
            'eventtype',
            get_string('col:base:eventtype', __CLASS__),
            'base.eventtype',
            [
                'dbdatatype' => 'char',
                'outputformat' => 'text',
                'displayfunc' => 'plaintext',
            ]
        );

        $columns[] = new rb_column_option(
            'base',
            'occurredat',
            get_string('col:base:occurred', __CLASS__),
            'base.occurredat',
            [
                'dbdatatype' => 'integer',
                'displayfunc' => 'nice_datetime_seconds',
            ]
        );

        $columns[] = new rb_column_option(
            'badge',
            'credlyid',
            get_string('col:badge:templateid', __CLASS__),
            'badge.credlyid',
            [
                'dbdatatype' => 'char',
                'outputformat' => 'text',
                'displayfunc' => 'plaintext',
                'joins' => ['badge']
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
                'joins' => ['badge']
            ]
        );

        $columns[] = new rb_column_option(
            'badge',
            'state',
            get_string('col:badge:state', __CLASS__),
            'badge.state',
            [
                'dbdatatype' => 'char',
                'outputformat' => 'text',
                'displayfunc' => 'plaintext',
                'joins' => ['badge']
            ]
        );

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
            get_string('col:badge:templateid', __CLASS__),
            'text'
        );

        $filters[] = new rb_filter_option(
            'base',
            'eventtype',
            get_string('col:base:eventtype', __CLASS__),
            'select',
            [
                'selectchoices' => \local_credly\WebhookEndpoint::VALID_WEBHOOKS
            ]
        );

        $filters[] = new rb_filter_option(
            'base',
            'occurredat',
            get_string('col:base:occurred', __CLASS__),
            'date'
        );

        $filters[] = new rb_filter_option(
            'badge',
            'name',
            get_string('col:badge:name', __CLASS__),
            'text'
        );

        $filters[] =  new rb_filter_option(
            'base',
            'eventid',
            get_string('col:base:eventid', __CLASS__),
            'text'
        );


        return $filters;
    }

    /**
     * @return array
     */
    protected function define_defaultcolumns()
    {
        $columns = [];

        $columns[] = ['type' => 'badge', 'value' => 'credlyid'];
        $columns[] = ['type' => 'badge', 'value' => 'name'];
        $columns[] = ['type' => 'base', 'value' => 'eventtype'];
        $columns[] = ['type' => 'base', 'value' => 'eventid'];
        $columns[] = ['type' => 'base', 'value' => 'occurredat'];
        $columns[] = ['type' => 'badge', 'value' => 'state'];

        return $columns;
    }
}