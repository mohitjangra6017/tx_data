<?php

defined('MOODLE_INTERNAL') || die;

global $CFG, $DB, $PAGE;
require_once($CFG->dirroot . '/blocks/carousel/lib.php');

if ($ADMIN->fulltree) {
    $settings->add(
        new admin_setting_heading(
            'block_carousel/defaults',
            get_string('defaults', 'block_carousel'),
            ''
        )
    );

    $settings->add(
        new admin_setting_configselect(
            'block_carousel/template',
            get_string('template', 'block_carousel'),
            null,
            \block_carousel\constants::BKC_TEMPLATE_RED,
            \block_carousel\constants::get_templates()
        )
    );

    $settings->add(
        new admin_setting_configselect(
            'block_carousel/cardsize',
            get_string('cardsize', 'block_carousel'),
            null,
            \block_carousel\constants::BKC_CARD_SMALL,
            \block_carousel\constants::get_cardsizes()
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'block_carousel/coursecustomfields',
            get_string('coursecustomfields', 'block_carousel'),
            get_string('coursecustomfields_desc', 'block_carousel'),
            null,
            PARAM_TEXT
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'block_carousel/coursecustomfieldsdetails',
            get_string('coursecustomfieldsdetails', 'block_carousel'),
            get_string('coursecustomfieldsdetails_desc', 'block_carousel'),
            null,
            PARAM_TEXT
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'block_carousel/usercustomfields',
            get_string('usercustomfields', 'block_carousel'),
            get_string('usercustomfields_desc', 'block_carousel'),
            null,
            PARAM_TEXT
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'block_carousel/usercustomfieldsdetails',
            get_string('usercustomfieldsdetails', 'block_carousel'),
            get_string('usercustomfieldsdetails_desc', 'block_carousel'),
            null,
            PARAM_TEXT
        )
    );

// VODHAS-1864
// Custom field to filter F2F cards based on
// User custom field mapped to F2F event custom field
// User custom field mapped to course custom field
// E.g State - mapped to - F2F event custom field
// Channel - mapped to - Course custom field
    $settings->add(
        new admin_setting_configtext(
            'block_carousel/mappedtof2f',
            get_string('mappedtof2f', 'block_carousel'),
            get_string('mappedtof2f_desc', 'block_carousel'),
            null,
            PARAM_TEXT
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'block_carousel/mappedtocourse',
            get_string('mappedtocourse', 'block_carousel'),
            get_string('mappedtocourse_desc', 'block_carousel'),
            null,
            PARAM_TEXT
        )
    );

    $settings->add(
        new admin_setting_configcheckbox(
            'block_carousel/hidecardexpanddetails',
            get_string('hidecardexpanddetails', 'block_carousel'),
            get_string('hidecardexpanddetails_desc', 'block_carousel'),
            0
        )
    );

    $settings->add(
        new admin_setting_heading(
            'block_carousel/mode_filteredenrolledlist_defaults',
            get_string('mode_filteredenrolledlist_defaults', 'block_carousel'),
            ''
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'block_carousel/progress_threshold_lower',
            get_string('progress_threshold_lower', 'block_carousel'),
            get_string('progress_threshold_lower_desc', 'block_carousel'),
            \block_carousel\constants::BKC_LOWER_PROGRESS_THRESHOLD,
            PARAM_INT
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'block_carousel/progress_threshold_upper',
            get_string('progress_threshold_upper', 'block_carousel'),
            get_string('progress_threshold_upper_desc', 'block_carousel'),
            \block_carousel\constants::BKC_UPPER_PROGRESS_THRESHOLD,
            PARAM_INT
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'block_carousel/buttonlabel_fallback',
            get_string('buttonlabel_fallback', 'block_carousel'),
            get_string('buttonlabel_fallback_desc', 'block_carousel'),
            get_string('btn_launch', 'block_carousel'),
            PARAM_TEXT
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'block_carousel/buttonlabel_lowerunreached',
            get_string('buttonlabel_lowerunreached', 'block_carousel'),
            get_string('buttonlabel_lowerunreached_desc', 'block_carousel'),
            get_string('btn_getstarted', 'block_carousel'),
            PARAM_TEXT
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'block_carousel/buttonlabel_lowerreached',
            get_string('buttonlabel_lowerreached', 'block_carousel'),
            get_string('buttonlabel_lowerreached_desc', 'block_carousel'),
            get_string('btn_continue', 'block_carousel'),
            PARAM_TEXT
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'block_carousel/buttonlabel_upperreached',
            get_string('buttonlabel_upperreached', 'block_carousel'),
            get_string('buttonlabel_upperreached_desc', 'block_carousel'),
            get_string('btn_finishitoff', 'block_carousel'),
            PARAM_TEXT
        )
    );
}