<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace theme_kineo\Watcher;

use local_core\Hook\BlockEditForm;
use theme_kineo\Form\NullableSelectGroup;
use theme_kineo\Settings\Colour;
use theme_kineo\SettingsResolver;

class BlockEditFormWatcher
{
    static $insertElementBefore = 'cs_show_header';

    public static function getColours()
    {
        static $colours = [];
        if (!empty($colours)) {
            return $colours;
        }

        $resolver = SettingsResolver::getInstance();
        $themeSettings = $resolver->getThemeSettings();

        foreach ($themeSettings->getSettings() as $setting) {
            $options = $setting->getOptions();
            if (!empty($options[Colour::IS_CORE_KEY])) {
                $colours[] = $setting;
            }
        }

        return $colours;
    }

    public static function addCustomElements(BlockEditForm $hook)
    {
        global $CFG, $PAGE;

        require_once $CFG->libdir . '/formslib.php';

        $form = $hook->getForm();
        $mForm = $form->_form;

        $element = $mForm->getElement(self::$insertElementBefore);
        if (!$element) {
            return;
        }

        $select = new NullableSelectGroup('cs_custom_appearance', get_string('form:block:appearance', 'theme_kineo'));
        $select->setAttributes(['multiple' => 'multiple']);
        $select->setName('cs_custom_appearance');
        $PAGE->requires->js('/local/core/js/shim.js');
        $PAGE->requires->js_call_amd('theme_kineo/block_edit_hook', 'init', []);
        $PAGE->requires->css('/local/core/css/select2.min.css');

        $opacityRange = range(0, 100, 10);
        $opacity = [];
        foreach ($opacityRange as $option) {
            $key = 'opacity-' . $option;
            $opacity[$key] = $option . '%';
            if ($option == 0 || $option == 100) {
                $opacity[$key] = get_string("form:block:appearance:opacity:$option", 'theme_kineo');
            }
        }
        $select->addOptGroup(get_string('form:block:appearance:opacity', 'theme_kineo'), $opacity);

        $widthRange = [20,25,33,50,66,75,100];
        $widths = [];
        foreach ($widthRange as $width) {
            $widths["width-$width"] = "$width%";
        }
        $select->addOptGroup(get_string('form:block:appearance:width', 'theme_kineo'), $widths);

        $colours = ['background', 'text', 'border'];

        foreach ($colours as $colour) {
            $index = count($select->_optGroups);
            $select->addOptGroup(get_string("form:block:appearance:{$colour}_colour", 'theme_kineo'), []);
            foreach (self::getColours() as $option) {
                $cssClass = "{$colour}-colour-{$option->getIdentifier()}";
                $select->addOption(
                    $index,
                    $option->getName(),
                    $cssClass,
                    ['data-type' => 'colour', 'data-class' => $cssClass]
                );
            }
        }

        $mForm->insertElementBefore($select, self::$insertElementBefore);
        $mForm->setDefault($select->getName(), $form->block->get_common_config_value('custom_appearance'));

        $showcase = $mForm->createElement(
            'static',
            'custom_appearance_showcase',
            '',
            '<span class="custom-appearance-showcase">' . get_string('form:block:appearance:example', 'theme_kineo') . '</span>'
        );
        $mForm->insertElementBefore($showcase, self::$insertElementBefore);
    }
}