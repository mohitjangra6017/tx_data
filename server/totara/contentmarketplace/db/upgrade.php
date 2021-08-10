<?php
/*
 * This file is part of Totara Learn
 *
 * Copyright (C) 2021 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Qingyang Liu <qingyang.liu@totaralearning.com>
 * @package totara_contentmarketplace
 */

use core\orm\query\builder;

/**
 * Local database upgrade script
 *
 * @param   integer $oldversion Current (pre-upgrade) local db version timestamp
 * @return  boolean $result
 */
function xmldb_totara_contentmarketplace_upgrade($oldversion) {
    $DB = builder::get_db();
    if ($oldversion < 2021061501) {
        $condition = [
            'component' => 'totara_contentmarketplace',
            'classname' => '\totara_contentmarketplace\task\welcome_notification_task'
        ];
        if ($DB->record_exists('task_adhoc', $condition)) {
            $DB->delete_records('task_adhoc', $condition);
        }

        upgrade_plugin_savepoint(true, 2021061501, 'totara', 'contentmarketplace');
    }

    return true;
}