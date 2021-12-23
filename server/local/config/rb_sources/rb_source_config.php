<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon.Thornett
 */

defined('MOODLE_INTERNAL') || die;

global $CFG;
require_once($CFG->dirroot . '/totara/reportbuilder/classes/rb_base_source.php');

class rb_source_config extends rb_base_source
{
    private static string $langComponent = 'rb_source_config';

    public function __construct()
    {
        global $DB;

        $configId = $DB->sql_concat('id', "'-config'");
        $configPluginsId = $DB->sql_concat('id', "'-config_plugins'");

        $this->base = "(
            SELECT {$configId} AS id, 'core' AS plugin, name, value
            FROM {config}
            WHERE name NOT LIKE '%_adv'
            UNION ALL
            SELECT {$configPluginsId} AS id, plugin, name, value
            FROM {config_plugins}
            WHERE name NOT LIKE '%_adv'
        )";
        $this->requiredcolumns = $this->define_requiredcolumns();

        $this->usedcomponents[] = 'local_config';
        $this->joinlist = $this->define_joinlist();
        $this->columnoptions = $this->define_columnoptions();
        $this->filteroptions = $this->define_filteroptions();
        $this->contentoptions = $this->define_contentoptions();
        $this->paramoptions = $this->define_paramoptions();
        $this->defaultcolumns = $this->define_defaultcolumns();
        $this->defaultfilters = $this->define_defaultfilters();
        $this->sourcetitle = get_string('report:config:sourcetitle', self::$langComponent);
        $this->sourcesummary = get_string('report:config:sourcesummary', self::$langComponent);
        $this->sourcelabel = get_string('report:config:sourcelabel', self::$langComponent);

        parent::__construct();
    }

    protected function define_joinlist()
    {
        $joinList = [];

        $joinList[] = new rb_join(
            'config_log',
            'LEFT',
            "(
                SELECT userid, timemodified, (CASE WHEN plugin IS NULL THEN 'core' ELSE plugin END) AS plugin, name, value, oldvalue
                FROM {config_log}
                WHERE id IN (
                    SELECT MAX(id)
                    FROM {config_log}
                    WHERE userid != 0 AND oldvalue IS NOT NULL
                    GROUP BY plugin, name
                )
            )",
            'config_log.plugin = base.plugin AND config_log.name = base.name'
        );

        $this->add_core_user_tables($joinList, 'config_log', 'userid');

        return $joinList;
    }

    protected function define_columnoptions()
    {
        $columnoptions = parent::define_columnoptions();

        $columnoptions[] = new rb_column_option(
            'base',
            'plugin',
            get_string('report:config:column:plugin', self::$langComponent),
            'base.plugin'
        );

        $columnoptions[] = new rb_column_option(
            'base',
            'name',
            get_string('report:config:column:name', self::$langComponent),
            'base.name'
        );

        $columnoptions[] = new rb_column_option(
            'base',
            'humanname',
            get_string('report:config:column:humanname', self::$langComponent),
            'base.name',
            [
                'displayfunc' => 'readableConfig',
                'extrafields' => [
                    'name' => 'base.name',
                    'plugin' => 'base.plugin',
                    'default' => '0',
                    'text' => '1',
                    'log' => '0',
                ],
                'nosort' => true,
            ]
        );

        $columnoptions[] = new rb_column_option(
            'base',
            'value',
            get_string('report:config:column:value', self::$langComponent),
            'base.value',
            [
                'displayfunc' => 'readableConfig',
                'extrafields' => [
                    'name' => 'base.name',
                    'plugin' => 'base.plugin',
                    'default' => '0',
                    'text' => '0',
                    'log' => '0',
                ],
                'nosort' => true,
           ]
        );

        $columnoptions[] = new rb_column_option(
            'base',
            'default',
            get_string('report:config:column:default', self::$langComponent),
            'base.value',
            [
                'displayfunc' => 'readableConfig',
                'extrafields' => [
                    'name' => 'base.name',
                    'plugin' => 'base.plugin',
                    'default' => '1',
                    'text' => '0',
                    'log' => '0',
                ],
                'nosort' => true,
            ]
        );

        $columnoptions[] = new rb_column_option(
            'base',
            'definedinconfig',
            get_string('report:config:column:definedinconfig', self::$langComponent),
            'base.value',
            [
                'displayfunc' => 'definedInConfig',
                'extrafields' => [
                    'name' => 'base.name',
                    'plugin' => 'base.plugin',
                ],
                'nosort' => true,
            ]
        );

        $columnoptions[] = new rb_column_option(
            'config_log',
            'timemodified',
            get_string('report:config:column:timemodified', self::$langComponent),
            'config_log.timemodified',
            [
                'joins' => ['config_log'],
                'displayfunc' => 'nice_datetime'
            ]
        );

        $columnoptions[] = new rb_column_option(
            'config_log',
            'oldvalue',
            get_string('report:config:column:oldvalue', self::$langComponent),
            'config_log.oldvalue',
            [
                'joins' => ['config_log'],
                'displayfunc' => 'readableConfig',
                'extrafields' => [
                    'name' => 'base.name',
                    'plugin' => 'base.plugin',
                    'default' => '0',
                    'text' => '0',
                    'log' => '1',
                ],
                'nosort' => true,
            ]
        );

        $this->add_core_user_columns($columnoptions);

        return $columnoptions;
    }

    protected function define_filteroptions()
    {
        $filteroptions = parent::define_filteroptions();

        $filteroptions[] = new rb_filter_option(
            'base',
            'plugin',
            get_string('report:config:column:plugin', self::$langComponent),
            'text'
        );

        $filteroptions[] = new rb_filter_option(
            'base',
            'name',
            get_string('report:config:column:name', self::$langComponent),
            'text'
        );

        $filteroptions[] = new rb_filter_option(
            'config_log',
            'timemodified',
            get_string('report:config:column:timemodified', self::$langComponent),
            'date',
            [
                'includetime' => true
            ]
        );

        $this->add_core_user_filters($filteroptions);

        return $filteroptions;
    }

    protected function define_defaultcolumns()
    {
        return [
            [
                'type' => 'base',
                'value' => 'humanname'
            ],
            [
                'type' => 'base',
                'value' => 'name'
            ],
            [
                'type' => 'base',
                'value' => 'plugin'
            ],
            [
                'type' => 'base',
                'value' => 'default'
            ],
            [
                'type' => 'base',
                'value' => 'value'
            ],
            [
                'type' => 'config_log',
                'value' => 'oldvalue'
            ],
            [
                'type' => 'base',
                'value' => 'definedinconfig'
            ],
            [
                'type' => 'config_log',
                'value' => 'timemodified'
            ],
        ];
    }
}
