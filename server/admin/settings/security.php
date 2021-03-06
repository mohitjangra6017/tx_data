<?php

defined('MOODLE_INTERNAL') || die();
/** @var admin_root $ADMIN */

if ($hassiteconfig) { // speedup for non-admins, add all caps used on this page

    // "ip blocker" settingpage
    $temp = new admin_settingpage('ipblocker', new lang_string('ipblocker', 'admin'));
    $temp->add(new admin_setting_configcheckbox('allowbeforeblock', new lang_string('allowbeforeblock', 'admin'), new lang_string('allowbeforeblockdesc', 'admin'), 0));
    $temp->add(new admin_setting_configiplist('allowedip', new lang_string('allowediplist', 'admin'),
                                                new lang_string('ipblockersyntax', 'admin'), ''));
    $temp->add(new admin_setting_configiplist('blockedip', new lang_string('blockediplist', 'admin'),
                                                new lang_string('ipblockersyntax', 'admin'), ''));
    $ADMIN->add('security', $temp);

    // "sitepolicies" settingpage
    $temp = new admin_settingpage('sitepolicies', new lang_string('securitysettings', 'tool_sitepolicy'));
    $temp->add(new admin_setting_configcheckbox('protectusernames', new lang_string('protectusernames', 'admin'), new lang_string('configprotectusernames', 'admin'), 1));
    $temp->add(new admin_setting_configcheckbox('forcelogin', new lang_string('forcelogin', 'admin'), new lang_string('configforcelogintotara', 'totara_core'), 1));
    $temp->add(new admin_setting_configcheckbox('forceloginforprofiles', new lang_string('forceloginforprofiles', 'admin'), new lang_string('configforceloginforprofiles', 'admin'), 1));
    $temp->add(new admin_setting_configcheckbox('forceloginforprofileimage', new lang_string('forceloginforprofileimage', 'admin'), new lang_string('forceloginforprofileimage_help', 'admin'), 1)); // Totara: enabled by default for privacy reasons.
    $temp->add(new admin_setting_configcheckbox('preventmultiplelogins', new lang_string('preventmultiplelogins', 'admin'), new lang_string('preventmultiplelogins_help', 'admin'), 0));
    $temp->add(new admin_setting_configcheckbox('opentogoogle', new lang_string('opentogoogle', 'admin'), new lang_string('configopentogoogle', 'admin'), 0));
    $temp->add(new admin_setting_configcheckbox('publishgridcatalogimage', new lang_string('publishgridcatalogimage', 'admin'), new lang_string('publishgridcatalogimage_help', 'admin'), 0)); // Totara: disabled by default for privacy reasons.
    $temp->add(new admin_setting_pickroles('profileroles',
        new lang_string('profileroles','admin'),
        new lang_string('configprofileroles', 'admin'),
        array('student', 'teacher', 'editingteacher')));

    $maxbytes = 0;
    if (!empty($CFG->maxbytes)) {
        $maxbytes = $CFG->maxbytes;
    }
    $max_upload_choices = get_max_upload_sizes(0, 0, 0, $maxbytes);
    // maxbytes set to 0 will allow the maximum server limit for uploads
    $temp->add(new admin_setting_configselect('maxbytes', new lang_string('maxbytes', 'admin'), new lang_string('configmaxbytes', 'admin'), 0, $max_upload_choices));

    // The max size of extracted archive file contents.
    $maxbytesextracted = $maxbytes;
    if (!empty($CFG->maxbytesextracted)) {
        $maxbytesextracted = $CFG->maxbytesextracted;
    }
    $max_upload_choices = get_max_upload_sizes(0, 0, 0, $maxbytesextracted);
    $temp->add(
        new admin_setting_configselect(
            'maxbytesextracted',
            new lang_string('maxbytesextracted', 'admin'),
            new lang_string('configmaxbytesextracted', 'admin'),
            $maxbytes,
            $max_upload_choices
        )
    );

    // 100MB
    $defaultuserquota = 104857600;
    $params = new stdClass();
    $params->bytes = $defaultuserquota;
    $params->displaysize = display_size($defaultuserquota);
    $temp->add(new admin_setting_configtext('userquota', new lang_string('userquota', 'admin'),
                new lang_string('configuserquota', 'admin', $params), $defaultuserquota, PARAM_INT, 30));

    // Totara: setting to enable the old noclean operation.
    $temp->add(new admin_setting_configcheckbox('disableconsistentcleaning', new lang_string('disableconsistentcleaning', 'admin'), new lang_string('disableconsistentcleaning_help', 'admin'), 0));

    $temp->add(new admin_setting_configselect('maxeditingtime', new lang_string('maxeditingtime','admin'), new lang_string('configmaxeditingtime','admin'), 1800,
                 array(60 => new lang_string('numminutes', '', 1),
                       300 => new lang_string('numminutes', '', 5),
                       900 => new lang_string('numminutes', '', 15),
                       1800 => new lang_string('numminutes', '', 30),
                       2700 => new lang_string('numminutes', '', 45),
                       3600 => new lang_string('numminutes', '', 60))));

    $temp->add(new admin_setting_configcheckbox('extendedusernamechars', new lang_string('extendedusernamechars', 'admin'), new lang_string('configextendedusernamechars', 'admin'), 0));
    //When Site Policies are not enabled
    if (empty($CFG->enablesitepolicies)) {
        $temp->add(new admin_setting_configtext('sitepolicy', new lang_string('sitepolicy', 'admin'), new lang_string('sitepolicy_help', 'admin'), '', PARAM_RAW));
        $temp->add(new admin_setting_configtext('sitepolicyguest', new lang_string('sitepolicyguest', 'admin'), new lang_string('sitepolicyguest_help', 'admin'), (isset($CFG->sitepolicy) ? $CFG->sitepolicy : ''), PARAM_RAW));
    }
    $temp->add(new admin_setting_configcheckbox('extendedusernamechars', new lang_string('extendedusernamechars', 'admin'), new lang_string('configextendedusernamechars', 'admin'), 0));
    $temp->add(new admin_setting_configcheckbox('keeptagnamecase', new lang_string('keeptagnamecase','admin'),new lang_string('configkeeptagnamecase', 'admin'),'1'));

    $temp->add(new admin_setting_configcheckbox('profilesforenrolledusersonly', new lang_string('profilesforenrolledusersonly','admin'),new lang_string('configprofilesforenrolledusersonly', 'admin'),'1'));

    $temp->add(new admin_setting_configcheckbox('cronclionly', new lang_string('cronclionly', 'admin'), new lang_string
            ('configcronclionly', 'admin'), 1));
    $temp->add(new admin_setting_configpasswordunmask('cronremotepassword', new lang_string('cronremotepassword', 'admin'), new lang_string('configcronremotepassword', 'admin'), ''));

    $options = array(0=>get_string('no'), 3=>3, 5=>5, 7=>7, 10=>10, 20=>20, 30=>30, 50=>50, 100=>100);
    $temp->add(new admin_setting_configselect('lockoutthreshold', new lang_string('lockoutthreshold', 'admin'), new lang_string('lockoutthreshold_desc', 'admin'), 20, $options));
    $temp->add(new admin_setting_configduration('lockoutwindow', new lang_string('lockoutwindow', 'admin'), new lang_string('lockoutwindow_desc', 'admin'), 60*30));
    $temp->add(new admin_setting_configduration('lockoutduration', new lang_string('lockoutduration', 'admin'), new lang_string('lockoutduration_desc', 'admin'), 60*30));

    $temp->add(new admin_setting_configcheckbox('passwordpolicy', new lang_string('passwordpolicy', 'admin'), new lang_string('configpasswordpolicy', 'admin'), 1));
    $temp->add(new admin_setting_configtext('minpasswordlength', new lang_string('minpasswordlength', 'admin'), new lang_string('configminpasswordlength', 'admin'), 8, PARAM_INT));
    $temp->add(new admin_setting_configtext('minpassworddigits', new lang_string('minpassworddigits', 'admin'), new lang_string('configminpassworddigits', 'admin'), 1, PARAM_INT));
    $temp->add(new admin_setting_configtext('minpasswordlower', new lang_string('minpasswordlower', 'admin'), new lang_string('configminpasswordlower', 'admin'), 1, PARAM_INT));
    $temp->add(new admin_setting_configtext('minpasswordupper', new lang_string('minpasswordupper', 'admin'), new lang_string('configminpasswordupper', 'admin'), 1, PARAM_INT));
    $temp->add(new admin_setting_configtext('minpasswordnonalphanum', new lang_string('minpasswordnonalphanum', 'admin'), new lang_string('configminpasswordnonalphanum', 'admin'), 1, PARAM_INT));
    $temp->add(new admin_setting_configtext('maxconsecutiveidentchars', new lang_string('maxconsecutiveidentchars', 'admin'), new lang_string('configmaxconsecutiveidentchars', 'admin'), 0, PARAM_INT));

    $temp->add(new admin_setting_configtext('passwordreuselimit',
        new lang_string('passwordreuselimit', 'admin'),
        new lang_string('passwordreuselimit_desc', 'admin'), 0, PARAM_INT));

    $pwresetoptions = array(
        300 => new lang_string('numminutes', '', 5),
        900 => new lang_string('numminutes', '', 15),
        1800 => new lang_string('numminutes', '', 30),
        2700 => new lang_string('numminutes', '', 45),
        3600 => new lang_string('numminutes', '', 60),
        7200 => new lang_string('numminutes', '', 120),
        14400 => new lang_string('numminutes', '', 240)
    );
    $adminsetting = new admin_setting_configselect(
            'pwresettime',
            new lang_string('passwordresettime','admin'),
            new lang_string('configpasswordresettime','admin'),
            1800,
            $pwresetoptions);
    $temp->add($adminsetting);
    $temp->add(new admin_setting_configcheckbox('passwordchangelogout',
        new lang_string('passwordchangelogout', 'admin'),
        new lang_string('passwordchangelogout_desc', 'admin'), 0));

    $temp->add(new admin_setting_configcheckbox('passwordchangetokendeletion',
        new lang_string('passwordchangetokendeletion', 'admin'),
        new lang_string('passwordchangetokendeletion_desc', 'admin'), 0));

    $temp->add(new admin_setting_configcheckbox('groupenrolmentkeypolicy', new lang_string('groupenrolmentkeypolicy', 'admin'), new lang_string('groupenrolmentkeypolicy_desc', 'admin'), 1));
    $temp->add(new admin_setting_configcheckbox('disableuserimages', new lang_string('disableuserimages', 'admin'), new lang_string('configdisableuserimages', 'admin'), 0));
    $temp->add(new admin_setting_configcheckbox('emailchangeconfirmation', new lang_string('emailchangeconfirmation', 'admin'), new lang_string('configemailchangeconfirmation', 'admin'), 1));
    $setting = new admin_setting_configcheckbox('persistentloginenable', new lang_string('persistentloginenable', 'totara_core'), new lang_string('persistentloginenable_desc', 'totara_core'), 0);
    $setting->set_updatedcallback('\totara_core\persistent_login::settings_updated');
    $temp->add($setting);
    $temp->add(new admin_setting_configselect('rememberusername', new lang_string('rememberusername','admin'), new lang_string('rememberusername_desc','admin'), 2, array(1=>new lang_string('yes'), 0=>new lang_string('no'), 2=>new lang_string('optional'))));
    $temp->add(new admin_setting_configcheckbox('strictformsrequired', new lang_string('strictformsrequired', 'admin'), new lang_string('configstrictformsrequired', 'admin'), 0));
    $ADMIN->add('security', $temp);




    // "httpsecurity" settingpage
    $temp = new admin_settingpage('httpsecurity', new lang_string('httpsecurity', 'admin'));
    $temp->add(new admin_setting_configcheckbox('cookiesecure', new lang_string('cookiesecure', 'admin'), new lang_string('configcookiesecure', 'admin'), 1));
    $temp->add(new admin_setting_configcheckbox('cookiehttponly', new lang_string('cookiehttponly', 'admin'), new lang_string('configcookiehttponly', 'admin'), 1));
    $temp->add(new admin_setting_configcheckbox('stricttransportsecurity', new lang_string('stricttransportsecurity', 'totara_core'), new lang_string('stricttransportsecurity_desc', 'totara_core'), 0));
    $temp->add(new admin_setting_configcheckbox('securereferrers', new lang_string('securereferrers', 'totara_core'), new lang_string('securereferrers_desc', 'totara_core'), 0));
    $temp->add(new admin_setting_configcheckbox('allowframembedding', new lang_string('allowframembedding', 'admin'), new lang_string('allowframembedding_help', 'admin'), 0));
    $options = array('' => new lang_string('default'), 'none' => 'none', 'master-only' => 'master-only');
    $temp->add(new admin_setting_configselect('permittedcrossdomainpolicies', new lang_string('permittedcrossdomainpolicies', 'totara_core'), new lang_string('permittedcrossdomainpolicies_desc', 'totara_core'), '', $options));

    // Settings elements used by the \core\files\curl_security_helper class.
    $temp->add(new admin_setting_configmixedhostiplist('curlsecurityblockedhosts',
               new lang_string('curlsecurityblockedhosts', 'admin'),
               new lang_string('curlsecurityblockedhostssyntax', 'admin'), ""));
    $temp->add(new admin_setting_configportlist('curlsecurityallowedport',
               new lang_string('curlsecurityallowedport', 'admin'),
               new lang_string('curlsecurityallowedportsyntax', 'admin'), ""));
    $ADMIN->add('security', $temp);

    // "loginfailures" settingpage
    $temp = new admin_settingpage('loginfailures', new lang_string('loginfailures', 'admin'));
    $temp->add(new admin_setting_configcheckbox('displayloginfailures', new lang_string('displayloginfailures', 'admin'),
            new lang_string('configdisplayloginfailures', 'admin'), 0));
    $temp->add(new admin_setting_users_with_capability('notifyloginfailures', new lang_string('notifyloginfailures', 'admin'), new lang_string('confignotifyloginfailures', 'admin'), array(), 'moodle/site:config'));
    $options = array();
    for ($i = 1; $i <= 100; $i++) {
        $options[$i] = $i;
    }
    $temp->add(new admin_setting_configselect('notifyloginthreshold', new lang_string('notifyloginthreshold', 'admin'), new lang_string('confignotifyloginthreshold', 'admin'), '10', $options));
    $ADMIN->add('security', $temp);
} // end of speedup
