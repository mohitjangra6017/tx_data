<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon.Thornett
 */

namespace theme_kineo\output;

use block_contents;
use context_system;
use moodle_page;
use theme_kineo\SettingsResolver;

class core_renderer extends \core_renderer
{
    public function __construct(moodle_page $page, $target)
    {
        parent::__construct($page, $target);
        $page->requires->js_call_amd('theme_kineo/theme', 'init');
    }

    public function block(block_contents $bc, $region)
    {
        global $PAGE;

        if (empty($bc->blockinstanceid)) {
            return parent::block($bc, $region);
        }

        $block = $PAGE->blocks->find_instance($bc->blockinstanceid);
        $classes = $block->get_common_config_value('custom_classes', ' ');
        $classes .= ' '.implode(' ', $block->get_common_config_value('custom_appearance', []));
        $bc->add_class($classes);

        return parent::block($bc, $region);
    }

    public function navbar()
    {
        global $USER;

        if (SettingsResolver::getInstance()->getResolvedVariableValue('hide-breadcrumb', $USER->tenantid ?? 0) === 'false') {
            return parent::navbar();
        }

        $capabilities = ['moodle/site:config', 'moodle/course:create', 'moodle/course:update', 'moodle/user:create'];

        if (has_any_capability($capabilities, context_system::instance())) {
            return parent::navbar();
        }

        return '';
    }
}