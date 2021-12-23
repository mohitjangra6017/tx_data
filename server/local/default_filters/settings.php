<?php
/**
 * @copyright City & Guilds Kineo 2017
 * @author Michael Geering <michael.geering@kineo.com>
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    $page = new admin_externalpage(
        'local_default_filters_settings',
        get_string('pluginname', 'local_default_filters'),
        '/local/default_filters/index.php'
    );
    $ADMIN->add('localplugins', $page);
}

