<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

defined('MOODLE_INTERNAL') || die;

if (has_capability('moodle/site:config', context_system::instance())) {
    $page = new admin_settingpage('local_core', get_string('local_core:page', 'local_core'));

    $page->add(
        new admin_setting_heading(
            'local_core/work_mem',
            get_string('heading:work_mem', 'local_core'),
            get_string('heading:work_mem:info', 'local_core')
        )
    );

    $page->add(
        new admin_setting_configcheckbox(
            'local_core/work_mem_enabled',
            get_string('setting:work_mem_enabled:label', 'local_core'),
            get_string('setting:work_mem_enabled:desc', 'local_core'),
            '0'
        )
    );
    $page->add(
        new admin_setting_configtext(
            'local_core/work_mem',
            get_string('setting:work_mem:label', 'local_core'),
            get_string('setting:work_mem:desc', 'local_core'),
            \local_core\Hook\ReportBuilder\PostgresWorkMem::$default,
            PARAM_ALPHANUM
        )
    );

    $page->add(
        new admin_setting_heading(
            'local_core/scorm_optimisation',
            get_string('heading:scorm_optimisation', 'local_core'),
            get_string('heading:scorm_optimisation:info', 'local_core')
        )
    );
    $page->add(
        new admin_setting_configcheckbox(
            'local_core/scorm_opt_enabled',
            get_string('setting:scorm_opt_enabled', 'local_core'),
            get_string('setting:scorm_opt_enabled:desc', 'local_core'),
            0
        )
    );

    $ADMIN->add('localplugins', $page);
}