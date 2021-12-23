<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon Adams <simon.adams@kineo.com>
 */

defined('MOODLE_INTERNAL') || die;

if (has_capability('local/email_log:access', context_system::instance())) {
    $ADMIN->add(
        'localplugins',
        new admin_category(
            'local_email_log',
            new lang_string('pluginname', 'local_email_log')
        )
    );
    $ADMIN->add(
        'local_email_log',
        new admin_externalpage(
            'local_email_log/edit',
            get_string('page:edit', 'local_email_log'),
            "{$CFG->wwwroot}/local/email_log/edit.php"
        )
    );
    $ADMIN->add(
        'local_email_log',
        new admin_externalpage(
            'local_email_log/index',
            get_string('page:report', 'local_email_log'),
            "{$CFG->wwwroot}/local/email_log/index.php",
            'local/email_log:access'
        )
    );
}