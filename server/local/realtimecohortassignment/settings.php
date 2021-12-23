<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright Kineo 2019
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    $settings = new admin_settingpage(
        'realtimecohortassignment',
        get_string('pluginname', 'local_realtimecohortassignment')
    );

    $settings->add(
        new admin_setting_configcheckbox(
            'local_realtimecohortassignment/totarasync',
            get_string('totarasync', 'local_realtimecohortassignment'),
            get_string('totarasync_desc', 'local_realtimecohortassignment'),
            0
        )
    );

    $settings->add(new admin_setting_configselect(
        'local_realtimecohortassignment/event_userlogin',
        get_string('enable_userlogin', 'local_realtimecohortassignment'),
        get_string('enable_userlogin_desc', 'local_realtimecohortassignment'),
        'none',
        \local_realtimecohortassignment\Observer::getUserLoginEvents()
    ));

    $settings->add(new admin_setting_configcheckbox('local_realtimecohortassignment/event_course_completed',
        get_string('enable_course_completed', 'local_realtimecohortassignment'),
        get_string('enable_course_completed_desc', 'local_realtimecohortassignment'), 0));

    /** @var admin_root $ADMIN */
    $ADMIN->add('localplugins', $settings);
}
