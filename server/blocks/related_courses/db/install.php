<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

function xmldb_block_related_courses_install()
{
    // Are we installing this because it's an upgrade to the old recommend block?
    $pluginManager = core_plugin_manager::instance();
    $recommend = $pluginManager->get_plugin_info('block_recommend');

    if ($recommend !== null && $recommend->get_status() == core_plugin_manager::PLUGIN_STATUS_MISSING) {
        // Grab all the config we can and set it on our new plugin.
        $oldConfig = get_config('block_recommend');
        foreach ($oldConfig as $key => $value) {
            if ($key === 'version') {
                continue;
            }
            set_config($key, $value, 'block_related_courses');
        }

        // Move any files from the recommend block.
        if (!empty($oldConfig->defaultimage)) {
            $fs = get_file_storage();
            $files = $fs->get_area_files(context_system::instance()->id, 'block_recommend', 'defaults');
            foreach ($files as $file) {
                $fs->create_file_from_storedfile(['component' => 'block_related_courses'], $file);
            }
            $fs->delete_area_files(context_system::instance()->id, 'block_recommend', 'defaults');
        }

        // Now uninstall the old plugin.
        if ($pluginManager->can_uninstall_plugin('block_recommend')) {
            $pluginManager->uninstall_plugin('block_recommend', new null_progress_trace());
        } else {
            debugging(
                'Cannot uninstall block_recommend automatically. Please uninstall it from the front end interface.'
            );
        }
    }
}