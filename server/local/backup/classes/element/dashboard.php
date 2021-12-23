<?php

 /**
 * Class for exporting dashboard config & blocks
 *
 * @copyright  City & Guilds Kineo 2023-03-23 {@link http://www.kineo.com}
 * @author     tri.le
 * @version    1.0
 */

namespace local_backup\element;


class dashboard extends base {

    /** @var \ZipArchive */
    private $_zip;

    private static $blocks_not_supported = [
        'totara_program_completion',
        'glossary_random',
        'totara_report_graph',
        'totara_report_table'
        ];

    public function get_name() {
        return get_string('dashboardconfiguration', 'local_backup');
    }

    function config_definition(\MoodleQuickForm $mform) {
        
    }

    function export(\ZipArchive $zip, $config = null) {
        global $DB;

        $this->_zip = $zip;

        // we export the global blocks first - global blocks are blocks that is configured on every page
        $globalblocks = $this->export_page('global');
        $frontpageblocks = $this->export_page('site-index');

        $dashboardblocks = [];
        $dashboards = $DB->get_records_select('totara_dashboard', 'tenantid IS NULL', array(), 'sortorder');
        $zip->addFromString('dashboard.json', json_encode($dashboards));
        foreach ($dashboards as $dashboard) {
            $blocksondashboard = $this->export_page('totara-dashboard-'.$dashboard->id);
            $dashboardblocks+= $blocksondashboard;
        }

        // we also need to export global configuration for blocks
        $allblocks = $globalblocks + $frontpageblocks + $blocksondashboard;
        $blocknames = array_unique(array_map(function($bi) {
            return $bi->blockname;
        }, $allblocks));
        $blocknames = array_filter($blocknames, function ($blockname) {
            return !in_array($blockname, self::$blocks_not_supported);
        });

        $this->global_block_export($blocknames);
    }

    function preview_import(\ZipArchive $zip) {
        $differences = [];
        $global = json_decode($zip->getFromName('global.json'));
        $currentglobal = $this->get_global_blocks();

        $differences['global'] = [count($currentglobal), count((array)$global)];

        $frontpage = json_decode($zip->getFromName('site-index.json'));
        $currentfrontpage = $this->get_block_instances('site-index');
        $differences['site-index'] = [count($currentfrontpage), count((array)$frontpage)];

        $dashboards = json_decode($zip->getFromName('dashboard.json'));
        foreach ($dashboards as $dashboard) {
            $importedblocks = json_decode($zip->getFromName('totara-dashboard-'.$dashboard->id.'.json'));
            if (($matchingdashboard = $this->get_matching_dashboard($dashboard))) {
                $matchingblocks = $this->get_block_instances('totara-dashboard-'.$matchingdashboard->id);
                $differences['totara-dashboard-'.$dashboard->id] = [count($matchingblocks), count((array)$importedblocks)];
            } else {
                $differences['totara-dashboard-'.$dashboard->id] = [null, count((array)$importedblocks)];
            }
        }

        $html = $this->render_table($differences, $dashboards);

        $html .= \html_writer::start_div('deleteothers');
        $html .= \html_writer::checkbox('dashboard[deleteothers]', 1, true, get_string('deleteotherdashboards', 'local_backup'));
        $html .= \html_writer::end_div();

        return $html;
    }

    function import(\ZipArchive $zip, $selected) {
        global $DB;

        $this->_zip = $zip;

        $this->global_block_import();
        if (!empty($selected['global'])) {
            $blocks = json_decode($zip->getFromName('global.json'));
            $this->restore_page('global', $blocks);
        }
        if (!empty($selected['site-index'])) {
            $blocks = json_decode($zip->getFromName('site-index.json'));
            $this->restore_page('site-index', $blocks);
        }

        // dashboard
        $dashboards = json_decode($zip->getFromName('dashboard.json'));
        $matcheddashboard_ids = [];
        foreach ($dashboards as $dashboard) {
            $oldpagetype = 'totara-dashboard-'.$dashboard->id;
            if (!empty($selected[$oldpagetype])) {
                $newdashboard_id = $this->import_dashboard_info($dashboard);
                $matcheddashboard_ids[]= $newdashboard_id;

                $newpagetype = 'totara-dashboard-'.$newdashboard_id;
                $blocks = json_decode($zip->getFromName($oldpagetype.'.json'));
                if ($oldpagetype!=$newpagetype) {
                    foreach ($blocks as $block) {
                        if ($block->pagetypepattern==$oldpagetype) {
                            $block->pagetypepattern = $newpagetype;
                        }
                        if ($block->blockpos_pagetype==$oldpagetype) {
                            $block->blockpos_pagetype = $newpagetype;
                        }
                    }
                }
                $this->restore_page($newpagetype, $blocks);
            }
        }

        if (!empty($selected['deleteothers'])) {
            // delete all other dashboards
            $alldashboard_ids = $DB->get_fieldset_select('totara_dashboard', 'id', 'tenantid IS NULL');
            $todelete = array_diff($alldashboard_ids, $matcheddashboard_ids);
            foreach ($todelete as $dashboard_id) {
                $dashboard = new \totara_dashboard($dashboard_id);
                $dashboard->delete();
            }
        }
    }

    private function global_block_export($blocknames) {
        $blockconfig = array();
        foreach ($blocknames as $blockname) {
            $component = 'block_'.$blockname;
            if (($config = get_config($component))) {
                unset($config->version);
                $blockconfig[$component] = $config;
            }
            if (method_exists($this, "post_export_block_{$blockname}")) {
                $this->{"post_export_block_{$blockname}"}($blockconfig);
            }
        }
        $this->_zip->addFromString('blockglobalconfig.json', json_encode($blockconfig));
    }

    private function global_block_import() {
        $configs = json_decode($this->_zip->getFromName('blockglobalconfig.json'));
        foreach ($configs as $plugin => $config) {
            if (!get_config($plugin, 'version')) {
                continue;
            }
            foreach ($config as $name => $value) {
                if ($name!='version') {
                    set_config($name, $value, $plugin);
                }
            }
        }
    }

    private function export_page($page) {
        $blocks = $this->export_blocks_on_page($page);
        $this->_zip->addFromString("{$page}.json", json_encode($blocks));
        $this->export_stored_files($blocks);
        return $blocks;
    }

    private function restore_page($pagetype, $block_instances) {
        global $DB;

        if ($pagetype=='global') {
            $this->import_global_blocks($block_instances);
        } else {
            $this->remove_page_blocks($pagetype);
            foreach ($block_instances as $instance) {
                $instance->pagetypepattern = $pagetype;
                $newinstance_id = $this->restore_block_instance($instance);
                if (!$newinstance_id) {
                    // for some reason (missing plugin, a setting or navigation block that doesn't exist)
                    continue;
                }
                $newinstance = $DB->get_record('block_instances', ['id' => $newinstance_id]);
                $context = \context_block::instance($newinstance_id);
                $newinstance->contextid = $context->id;
                if ($newinstance_id && !empty($instance->files)) {
                    $this->restore_files($instance->files, array('contextid' => 'contextid'), $newinstance);
                }
                $customfunction = "import_block_{$instance->blockname}";
                if (method_exists($this, $customfunction)) {
                    $this->$customfunction($instance, $newinstance);
                }
            }
        }
    }

    private function import_global_blocks($blocks) {
        global $DB;
        foreach ($blocks as $blockrecord) {
            $existings = $DB->get_records('block_instances',
                ['blockname' => $blockrecord->blockname, 'parentcontextid' => SYSCONTEXTID], 'pagetypepattern');
            if ($existings) {
                $bi = reset($existings);
                $blockrecord->id = $bi->id;
                $DB->update_record('block_instances', $blockrecord);
                $bi = $blockrecord;
            } else {
                $bi = clone($blockrecord);
                unset($bi->id);
                $bi->id = $DB->insert_record('block_instances', $bi);
            }
            $context = \context_block::instance($bi->id);
            $bi->contextid = $context->id;
            $this->restore_files($blockrecord->files, array('contextid' => 'contextid'), $bi);
        }
    }

    private function import_dashboard_info($dashboard) {
        global $DB;

        $existingdashboard = $this->get_matching_dashboard($dashboard);
        if ($existingdashboard) {
            $updatedashboard = clone($dashboard);
            $updatedashboard->id = $existingdashboard->id;
            unset($updatedashboard->tenantid);
            $DB->update_record('totara_dashboard', $updatedashboard);
            return $updatedashboard->id;
        } else {
            unset($dashboard->id);
            $dashboard->tenantid = null;
            return $DB->insert_record('totara_dashboard', $dashboard);
        }
    }

    private function restore_block_instance($instance) {
        global $DB, $SITE;

        $sitecontext = \context_course::instance($SITE->id);
        if ($instance->parentcontextid!=SYSCONTEXTID) {
            // the export only export block that has parentcontext is system or site
            // syscontextid is always = 1. However, sitecontext is sometimes different
            $instance->parentcontextid = $sitecontext->id;
        }

        if ($instance->blockname=='settings' || $instance->blockname=='navigation') {
            // we don't bring over these blocks. If a specific position exists, we will place the global instance to the correct place on the page
            $currentinstance = $DB->get_record('block_instances',
                    array('blockname' => $instance->blockname, 'parentcontextid' => SYSCONTEXTID, 'showinsubcontexts' => 1));
            if ($currentinstance && $instance->blockpos_blockinstanceid) {
                if (!($pos = $DB->get_record('block_positions',
                        array('blockinstanceid' => $currentinstance->id, 'contextid' => $instance->blockpos_contextid, 'pagetype' => $instance->blockpos_pagetype)))) {
                    $pos = (object) array(
                        'blockinstanceid' => $currentinstance->id,
                        'contextid' => $instance->blockpos_contextid,
                        'pagetype' => $instance->blockpos_pagetype,
                    );
                }
                $pos->subpage = $instance->blockpos_subpage;
                $pos->visible = $instance->blockpos_visible;
                $pos->region = $instance->blockpos_region;
                $pos->weight = $instance->blockpos_weight;
                if (empty($pos->id)) {
                    $DB->insert_record('block_positions', $pos);
                } else {
                    $DB->update_record('block_positions', $pos);
                }
            }
            return null;
        }

        // if block has positions set
        if(!empty($instance->blockpos_blockinstanceid)) {
            // note block position
            $block_position = new \stdClass();
            $block_position->pagetype = $instance->blockpos_pagetype;
            $block_position->subpage = $instance->blockpos_subpage;
            $block_position->visible = $instance->blockpos_visible;
            $block_position->region = $instance->blockpos_region;
            $block_position->weight = $instance->blockpos_weight;
        }

        if ($instance->pagetypepattern=='*' && $instance->showinsubcontexts
                && ($existinginstance = $DB->get_record('block_instances', array('blockname' => $instance->blockname, 'parentcontextid' => $instance->parentcontextid, 'showinsubcontexts' => 1)))) {
            $existinginstance->defaultregion = $instance->defaultregion;
            $existinginstance->configdata = $instance->configdata;
            $DB->update_record('block_instances', $existinginstance);
            $instance_id = $existinginstance->id;
        } else {
            $instance_id = $DB->insert_record('block_instances', $instance);
        }

        // create positions if position is set
        if(!empty($block_position)) {
            // set this up with the newly created block
            $block_position->blockinstanceid = $instance_id;
            $block_position->contextid = $instance->parentcontextid;
            $DB->insert_record('block_positions', $block_position);
        }
        return $instance_id;
    }

    private function export_stored_files($records) {
        $fs = get_file_storage();
        foreach ($records as $record) {
            if (empty($record->files)) {
                continue;
            }
            foreach ($record->files as $filerecord) {
                $file = $fs->get_file_instance($filerecord);
                $this->_zip->addFromString($file->get_contenthash(), $file->get_content());
            }
        }
    }

    private function export_blocks_on_page($pagetype) {
        global $DB;
        if ($pagetype=='global') {
            // global blocks are blocks that appears on every page
            // we treat global blocks differently
            $instances = $this->get_global_blocks();
        } else {
            $instances = $this->get_block_instances($pagetype);
        }
        foreach ($instances as $instance) {
            if ($instance->blockname == 'isotope') {
                $this->remove_isotope_specific_data($instance);
            }

            $context = \context_block::instance($instance->id);
            $instance->files = $DB->get_records_select('files', "component=:component AND contextid=:contextid AND filename!='.'",
                array('component' => "block_{$instance->blockname}", 'contextid' => $context->id));
            if (method_exists($this, "export_block_{$instance->blockname}")) {
                $this->{"export_block_{$instance->blockname}"}($instance);
            }
        }
        return $instances;
    }

    private function remove_isotope_specific_data(&$instance) {
        $config_data = unserialize(base64_decode($instance->configdata));

        if (!empty($config_data->{'record_of_learning_category-path'})) {
            $config_data->{'record_of_learning_category-path'} = '';
        }

        if (!empty($config_data->{'programs_category-path'})) {
            $config_data->{'programs_category-path'} = '';
        }

        if (!empty($config_data->record_of_learning_tags)) {
            $config_data->record_of_learning_tags = [];
        }

        $instance->configdata = base64_encode(serialize($config_data));
    }

    private function get_global_blocks() {
        global $DB;

        [$in_sql, $params] = $DB->get_in_or_equal(self::$blocks_not_supported, SQL_PARAMS_NAMED, 'block_type', false);

        $sql = "SELECT bi.*
                  FROM {block_instances} bi
                 WHERE parentcontextid = :syscontext
                   AND pagetypepattern NOT LIKE 'totara-dashboard%'
                   AND pagetypepattern != 'site-index'
                   AND blockname {$in_sql}";

        $params['syscontext'] = SYSCONTEXTID;

        return $DB->get_records_sql($sql, $params);
    }

    private function get_block_instances($pagetype, $get_all = false) {
        global $DB, $SITE;
        // Attention, we only export the blocks with SYSCONTEXTID and SITE context id
        $sitecontext = \context_course::instance($SITE->id);

        if (!$get_all) {
            [$in_sql, $in_params] = $DB->get_in_or_equal(self::$blocks_not_supported, SQL_PARAMS_NAMED, 'block_type', false);
            $in_sql = "AND bi.blockname {$in_sql}";
        } else {
            $in_sql = '';
            $in_params = [];
        }

        $sql = "SELECT
                    bi.*,
                    parent_context.contextlevel AS parent_contextlevel,
                    parent_context.instanceid AS parent_instanceid,
                    context.id AS block_contextid,
                    bp.blockinstanceid AS blockpos_blockinstanceid,
                    bp.contextid AS blockpos_contextid,
                    bp.pagetype AS blockpos_pagetype,
                    bp.subpage AS blockpos_subpage,
                    bp.visible AS blockpos_visible,
                    bp.region AS blockpos_region,
                    bp.weight AS blockpos_weight
                FROM
                    {block_instances} bi
            LEFT JOIN {context} context
                ON context.instanceid = bi.id AND context.contextlevel=:contextlevel
            LEFT JOIN {block_positions} bp
                    ON (bi.id = bp.blockinstanceid AND bp.contextid IN (:syscontextid1,:sitecontextid1))
            LEFT JOIN {context} parent_context
                    ON bi.parentcontextid = parent_context.id
                WHERE (bi.pagetypepattern=:pagetype1 OR bp.pagetype=:pagetype2)
                AND bi.parentcontextid IN (:syscontextid2, :sitecontextid2)
                {$in_sql}";

        $params = array('contextlevel' => CONTEXT_BLOCK, 'pagetype1' => $pagetype, 'pagetype2' => $pagetype,
            'syscontextid1' => SYSCONTEXTID, 'syscontextid2' => SYSCONTEXTID,
            'sitecontextid1' => $sitecontext->id, 'sitecontextid2' => $sitecontext->id);
        $params = array_merge($params, $in_params);

        return $DB->get_records_sql($sql, $params);
    }

    private function render_table($differences, $dashboards) {
        global $OUTPUT;

        $table = new \html_table();
        $table->head = [get_string('page'), get_string('onthissite', 'local_backup'), get_string('action')];
        foreach ($differences as $itemname => $difference) {
            switch ($itemname) {
                case 'global':
                    $table->data[]= [get_string('globalblocks', 'local_backup')." ({$difference[1]} blocks)",
                        "{$difference[0]} blocks",
                        \html_writer::checkbox("dashboard[{$itemname}]", 1, true, get_string('update'))];
                    break;
                case 'site-index':
                    $table->data[]= [get_string('frontpage', 'local_backup')." ({$difference[1]} blocks)",
                        "{$difference[0]} blocks",
                        \html_writer::checkbox("dashboard[{$itemname}]", 1, true, get_string('update'))];
                    break;
                default: // Totara dashboard
                    $dashboard_id = explode('-', $itemname)[2];
                    $checkboxlabel = !isset($difference[0]) ? get_string('createnew', 'local_backup') : get_string('update');
                    $table->data[]= [$dashboards->$dashboard_id->name." ({$difference[1]} blocks)",
                        !isset($difference[0]) ? get_string('nomatching', 'local_backup') : "{$difference[0]} blocks",
                        \html_writer::checkbox("dashboard[{$itemname}]", 1, true, $checkboxlabel)];
            }
        }
        return $OUTPUT->render($table);
    }

    private function remove_page_blocks($pagetype) {
        global $CFG, $DB;
        require_once($CFG->libdir. '/blocklib.php');

        $block_instances = $this->get_block_instances($pagetype, true);
        foreach ($block_instances as $instance) {
            // delete block instance
            blocks_delete_instance($instance);
        }

        $DB->delete_records('block_positions', array('pagetype' => $pagetype));
    }

    private function restore_files($filerecords, $mappingarray=array(), $record=null) {
        $fs = get_file_storage();

        $areadeleted = array();
        foreach ($filerecords as $filerecord) {
            if ($filerecord->filename=='.') {
                continue;
            }
            if (($content = $this->_zip->getFromName($filerecord->contenthash))) {
                foreach ($mappingarray as $filefield => $recordfield) {
                    $filerecord->$filefield = $record->$recordfield;
                }
                unset($filerecord->pathnamehash);
                unset($filerecord->id);
                if (empty($areadeleted[$filerecord->contextid][$filerecord->component][$filerecord->filearea][$filerecord->itemid])) {
                    $fs->delete_area_files($filerecord->contextid, $filerecord->component, $filerecord->filearea, $filerecord->itemid);
                    $areadeleted[$filerecord->contextid][$filerecord->component][$filerecord->filearea][$filerecord->itemid] = true;
                }
                $fs->create_file_from_string($filerecord, $content);
            }
        }
    }

    private function get_matching_dashboard($dashboard) {
        global $DB;
        return $DB->get_record('totara_dashboard', ['name' => $dashboard->name]);
    }

    /**
     * Specific export for block totara_featured_links
     */
    private function export_block_totara_featured_links($instance) {
        global $DB;
        $instance->tiles = $DB->get_records('block_totara_featured_links_tiles', ['blockid' => $instance->id]);
    }

    private function import_block_totara_featured_links($instance, $newinstance) {
        global $USER, $DB;
        static $overlapping = [];

        $fs = get_file_storage();

        // for safety, remove the tile records (shouldn't have any)
        $DB->delete_records('block_totara_featured_links_tiles', ['blockid' => $newinstance->id]);
        foreach ($instance->tiles as $tile) {
            // insert the title record
            $newtile = clone($tile);
            unset($newtile->id);
            $newtile->blockid = $newinstance->id;
            $newtile->userid = $USER->id;
            $newtile->id = $DB->insert_record('block_totara_featured_links_tiles', $newtile);

            $context = \context_block::instance($newinstance->id);

            // there is a small issue when changing the itemid to the new tile id, because if the new tile id also has some files in it
            // (belong to other tiles that is being processed soon)
            $overlappingfiles = $DB->get_records('files', ['contextid' => $context->id, 'component' => 'block_totara_featured_links', 'filearea' => 'tile_background', 'itemid' => $newtile->id]);
            if ($overlappingfiles) {
                $overlapping[$newtile->id] = $overlappingfiles;
            }
            // process file for the tile
            if (!empty($overlapping[$tile->id])) {
                $files = $overlapping[$tile->id];
            } else {
                $files = $DB->get_records('files', ['contextid' => $context->id, 'component' => 'block_totara_featured_links', 'filearea' => 'tile_background', 'itemid' => $tile->id]);
            }
            foreach ($files as $fileobj) {
                if ($fileobj->filename=='.') {
                    $DB->delete_records('files', ['id' => $fileobj->id]);
                    continue;
                }
                $fileobj->itemid = $newtile->id;
                $fs->create_file_from_storedfile($fileobj, $fileobj->id);
                $fileinstance = $fs->get_file_by_id($fileobj->id);
                $fileinstance->delete();
            }
        }
    }

    private function export_block_quicklinks($instance) {
        global $DB;
        $instance->quicklinks = $DB->get_record('block_quicklinks', ['block_instance_id' => $instance->id]);
    }

    private function import_block_quicklinks($instance, $newinstance) {
        global $DB;
        foreach ($instance->quicklinks as $quicklink) {
            $new_quicklink = clone($quicklink);
            unset($new_quicklink->id);
            $new_quicklink->block_instance_id = $newinstance->id;
            $DB->insert_record('block_quicklinks', $new_quicklink);
        }
    }

    private function post_export_block_html(&$blockconfig) {
        global $CFG;
        $blockconfig['_empty_']['block_html_allowcssclasses'] = $CFG->block_html_allowcssclasses;
    }

    private function post_import_block_html($blockconfig) {
        if (isset($blockconfig['_empty_']['block_html_allowcssclasses'])) {
            set_config('block_html_allowcssclasses', $blockconfig['_empty_']['block_html_allowcssclasses']);
        }
    }
}