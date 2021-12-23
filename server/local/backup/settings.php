<?php

 /**
 * Settings file: Site admin -> Local plugin -> Kineo Site Config Backup -> Backup and Restore
 *
 * @copyright  City & Guilds Kineo 2023-03-23 {@link http://www.kineo.com}
 * @author     tri.le
 * @version    1.0
 */

if ($hassiteconfig) {
    $ADMIN->add(
        'localplugins',
        new admin_category(
            'local_backup',
            new lang_string('pluginname', 'local_backup')
        )
    );
    $ADMIN->add(
        'local_backup',
        new admin_externalpage(
            'local_backup/backup',
            get_string('page:backup', 'local_backup'),
            "{$CFG->wwwroot}/local/backup/backup.php"
        )
    );
    $ADMIN->add(
        'local_backup',
        new admin_externalpage(
            'local_backup/restore',
            get_string('page:restore', 'local_backup'),
            "{$CFG->wwwroot}/local/backup/restore.php"
        )
    );
}