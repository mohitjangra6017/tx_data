<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    $ADMIN->add('localplugins', new admin_category('local_credly', new lang_string('pluginname', 'local_credly')));

    $settingspage = new admin_settingpage('credly_settings', new lang_string('settings:header', 'local_credly'));

    if ($ADMIN->fulltree) {
        $settingspage->add(
            new admin_setting_configcheckbox(
                'local_credly/enabled',
                new lang_string('setting:enable', 'local_credly'),
                '',
                0
            )
        );
        $endpoint = new admin_setting_configtext(
            'local_credly/endpoint_url',
            new lang_string('setting:endpoint_url', 'local_credly'),
            '',
            '',
            PARAM_URL
        );
        $endpoint->set_updatedcallback('\local_credly\Helper::testCredlyAuthentication');
        $settingspage->add($endpoint);

        $organisation = new admin_setting_configtext(
            'local_credly/organisation_id',
            new lang_string('setting:organisation_id', 'local_credly'),
            '',
            '',
            PARAM_ALPHANUMEXT
        );
        $organisation->set_updatedcallback('\local_credly\Helper::testCredlyAuthentication');
        $settingspage->add($organisation);

        $token = new admin_setting_configtext(
            'local_credly/auth_token',
            new lang_string('setting:auth_token', 'local_credly'),
            '',
            '',
            PARAM_ALPHANUMEXT
        );
        $token->set_updatedcallback('\local_credly\Helper::testCredlyAuthentication');
        $settingspage->add($token);

        $settingspage->add(
            new admin_setting_configcheckbox(
                'local_credly/allow_opt_out',
                new lang_string('setting:allow_opt_out', 'local_credly'),
                new lang_string('setting:allow_opt_out:desc', 'local_credly'),
                0
            )
        );

        $settingspage->add(
            new admin_setting_confightmleditor(
                'local_credly/opt_out_disclaimer',
                get_string('setting:opt_out_disclaimer', 'local_credly'),
                get_string('setting:opt_out_disclaimer:desc', 'local_credly'),
                ''
            )
        );

        $webHookToken = new admin_setting_configcheckbox(
                'local_credly/regentoken',
                get_string('setting:tokenregen', 'local_credly'),
                get_string('setting:tokenregen:desc', 'local_credly'),
                0
            );
        $webHookToken->set_updatedcallback('\local_credly\Helper::regenWebHookToken');
        $settingspage->add($webHookToken);


        $settingspage->add(
            new \local_credly\Setting\TextOnly(
                'local_credly/webhookurl',
                get_string('setting:webhookurl', 'local_credly'),
                get_string('setting:webhookurl:desc', 'local_credly')
            )
        );

        $settingspage->add(
            new admin_setting_configtext(
                'local_credly/group_tag',
                new lang_string('setting:group_tag', 'local_credly'),
                get_string('setting:group_tag_desc', 'local_credly'),
                '',
                PARAM_ALPHANUMEXT
            )
        );
    }

    $ADMIN->add('local_credly', $settingspage);

    $badgesPage = new admin_externalpage('credly_badges', 'Credly Badges', '/local/credly/badges.php');
    $ADMIN->add('local_credly', $badgesPage);
}