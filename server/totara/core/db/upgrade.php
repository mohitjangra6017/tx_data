<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010 onwards Totara Learning Solutions LTD
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
 * @author Jonathan Newman <jonathan.newman@catalyst.net.nz>
 * @author Ciaran Irvine <ciaran.irvine@totaralms.com>
 * @package totara
 * @subpackage totara_core
 */

/**
 * Upgrade script execute right after main lib/db/upgrade.php script
 * if version bump is detected in totara_core.
 *
 * NOTE: this file should not be used for core database changes any more.
 *
 * @param   integer $oldversion Current (pre-upgrade) local db version timestamp
 * @return  boolean $result
 */
function xmldb_totara_core_upgrade($oldversion) {
    global $CFG, $DB;
    require_once(__DIR__ . '/upgradelib.php');

    $dbman = $DB->get_manager();

    if ($oldversion < 2020100100) {
        // Somebody must have hacked upgrade checks, stop them here.
        throw new coding_exception('Upgrades are supported only from Totara 13.0 or later!');
    }

    // Totara 13.0 release line.

    if ($oldversion < 2021012000) {
        // NOTE: move this to the end of upgrade if new plugins to be removed
        //       are added to totara_core_upgrade_delete_removed_plugins()
        totara_core_upgrade_delete_removed_plugins();

        unset_config('allowobjectembed');
        unset_config('enabletrusttext');

        upgrade_plugin_savepoint(true, 2021012000, 'totara', 'core');
    }

    if ($oldversion < 2021020400) {
        // Check the default category, and fix it up if need be.
        $changed = totara_core_refresh_default_category();

        // Get the category at the top of the sortorder (ignoring unsorted)
        $topcats = $DB->get_records_select(
            'course_categories',
            'depth = 1 AND sortorder != 0',
            null,
            'sortorder, id',
            'issystem',
            0,
            1
        );
        $topcat = array_pop($topcats); // It's 1 record but in an array anyway.

        /**
         * We've added a new category and need to fix the ordering,
         * or we have a system category at the head of the sortorder.
         * Either way we need to resort the course categories.
         */
        if ($changed || !empty($topcat->issystem)) {
            totara_core_fix_course_sortorder();
        }

        // Main savepoint reached.
        upgrade_plugin_savepoint(true, 2021020400, 'totara', 'core');
    }

    return true;
}
