<?php
defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $settings->add(
        new admin_setting_configcheckbox(
            'block_banner_allowcssclasses',
            get_string('allowadditionalcssclasses', 'block_banner'),
            get_string('configallowadditionalcssclasses', 'block_banner'),
            0
        )
    );
}


