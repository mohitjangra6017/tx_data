<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

defined('MOODLE_INTERNAL') || die();

function xmldb_theme_kineo_upgrade($oldversion) {
    global $CFG, $DB;

    if ($oldversion < 2021070100) {

        $mainSite[] = 0;
        $tenants = $DB->get_fieldset_select('tenant', 'id', 'id > 0');
        $allTenants = array_merge($mainSite, $tenants);

        foreach ($allTenants as $tenant) {
            $config = json_decode(get_config('theme_kineo', "tenant_{$tenant}_settings"));
            foreach ($config as $category) {
                if ($category->name == 'general') {
                    $category->name = 'brand';
                    set_config("tenant_{$tenant}_settings", json_encode(array_values($config)), 'theme_kineo');
                    continue 2;
                }
            }
        }

        upgrade_plugin_savepoint(true, 2021070100, 'theme', 'kineo');
    }

    if ($oldversion < 2021070101) {
        // In the course page, we have moved one region, and put another region in its place for naming consistency.
        // To avoid re-configuring client sites, we need to move all the blocks from one region to the other.
        $blockInstances = $DB->get_records('block_instances');

        $map = [
            'top' => 'crs-pge-top',
            'bottom' => 'crs-pge-bottom',
        ];

        foreach ($blockInstances as $instance) {
            // Look for all blocks that have been added directly to courses only.
            if (get_class(\context::instance_by_id($instance->parentcontextid)) !== \context_course::class) {
                continue;
            }

            // Ignore all blocks that are not defaulted to one of the regions we care about.
            if (!isset($map[$instance->defaultregion])) {
                continue;
            }

            $instance->defaultregion = $map[$instance->defaultregion];
            $DB->update_record('block_instances', $instance);
        }

        // Now find any blocks that have been manually moved, and make sure to move them to the other regions as well.
        $blockPositions = $DB->get_records('block_positions');

        foreach ($blockPositions as $position) {
            // Look for all blocks that have been added directly to courses only.
            if (get_class(\context::instance_by_id($position->contextid)) !== \context_course::class) {
                continue;
            }

            // Ignore all blocks that are not in one of the regions we care about.
            if (!isset($map[$position->region])) {
                continue;
            }

            $position->region = $map[$position->region];
            $DB->update_record('block_positions', $position);
        }

        upgrade_plugin_savepoint(true, 2021070101, 'theme', 'kineo');
    }

    return true;
}
