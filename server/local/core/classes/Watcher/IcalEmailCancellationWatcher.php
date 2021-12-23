<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_core\Watcher;

use admin_setting_configcheckbox;
use local_core\Hook\AdminTreeInitHook;
use local_core\Hook\Email\PreSend;

class IcalEmailCancellationWatcher
{
    /**
     * Grab all emails that contain an iCal attachment, and check if we need to modify the iCal at all.
     * @param PreSend $hook
     */
    public static function onEmailPreSend(PreSend $hook)
    {
        if (!get_config(null, 'local_core_sendcancellationical')) {
            return;
        }

        $ical = $hook->getMailer()->Ical;
        if (strpos($ical, 'STATUS:CANCELLED') === false) {
            return;
        }

        // Internally PHPMailer checks for this string, and will also change the Content-Type header of the email to be
        // "text/calendar; METHOD=CANCEL", which is the actual fix needed for email software that isn't Outlook.
        $hook->getMailer()->Ical = str_replace('METHOD:REQUEST', 'METHOD:CANCEL', $ical);
    }

    /**
     * Inject our setting into the Seminar global settings page.
     * @param AdminTreeInitHook $hook
     */
    public static function onAdminSettings(AdminTreeInitHook $hook)
    {
        $root = $hook->getAdminRoot();
        $currentPage = $root->locate('modsettingfacetoface');
        if (!$currentPage instanceof \admin_settingpage) {
            return;
        }

        $hook->addNewSettingAfter(
            $currentPage,
            new admin_setting_configcheckbox(
                'local_core_sendcancellationical',
                get_string('sendcancellationical', 'local_core'),
                get_string('sendcancellationical_desc', 'local_core'),
                false
            ),
            'facetoface_disableicalcancel'
        );
    }
}