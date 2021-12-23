<?php

/**
 * Comment
 *
 * @package    package
 * @subpackage sub_package
 * @copyright  &copy; 2017 CG Kineo {@link http://www.kineo.com}
 * @author     kaushtuv.gurung
 * @version    1.0
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    // template choice
    $settings->add(
        new admin_setting_configselect(
            'block_awesome/global_defaulttemplate',
            get_string('global_defaulttemplate', 'block_awesome'),
            get_string('global_defaulttemplatedesc', 'block_awesome'),
            block_awesome::DEFAULT_TEMPLATE,
            block_awesome::get_templates_select()
        )
    );

    // background colour
    $settings->add(
        new admin_setting_configcolourpicker(
            'block_awesome/global_defaultbackgroundcolour',
            get_string('global_defaultbackgroundcolour', 'block_awesome'),
            get_string('global_defaultbackgroundcolourdesc', 'block_awesome'),
            block_awesome::DEFAULT_BG
        )
    );

    // header text colour
    $settings->add(
        new admin_setting_configcolourpicker(
            'block_awesome/global_defaultheadertextcolour',
            get_string('global_defaultheadertextcolour', 'block_awesome'),
            get_string('global_defaultheadertextcolourdesc', 'block_awesome'),
            block_awesome::DEFAULT_TEXTCOLOUR
        )
    );

    // text colour
    $settings->add(
        new admin_setting_configcolourpicker(
            'block_awesome/global_defaulttextcolour',
            get_string('global_defaulttextcolour', 'block_awesome'),
            get_string('global_defaulttextcolourdesc', 'block_awesome'),
            block_awesome::DEFAULT_TEXTCOLOUR
        )
    );

    // link text
    $settings->add(
        new admin_setting_configtext(
            'block_awesome/global_defaultlinktext',
            get_string('global_defaultlinktext', 'block_awesome'),
            get_string('global_defaultlinktextdesc', 'block_awesome'),
            block_awesome::DEFAULT_LINK_TEXT,
            PARAM_TEXT
        )
    );

    // icon colour
    $settings->add(
        new admin_setting_configcolourpicker(
            'block_awesome/global_defaulticoncolour',
            get_string('global_defaulticoncolour', 'block_awesome'),
            get_string('global_defaulticoncolourdesc', 'block_awesome'),
            block_awesome::DEFAULT_ICONCOLOUR
        )
    );

    // icon background colour
    $settings->add(
        new admin_setting_configcolourpicker(
            'block_awesome/global_defaulticonbackgroundcolour',
            get_string('global_defaulticonbackgroundcolour', 'block_awesome'),
            get_string('global_defaulticonbackgroundcolour', 'block_awesome'),
            block_awesome::DEFAULT_ICONBG
        )
    );
}




