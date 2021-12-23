<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

$string['pluginname'] = 'Credly';

// Settings
$string['settings:header'] = 'Credly settings';
$string['setting:enable'] = 'Enable Credly plugin';
$string['setting:endpoint_url'] = 'Credly Endpoint URL';
$string['setting:organisation_id'] = 'Credly Organisation ID';
$string['setting:auth_token'] = 'Credly API Auth Token';
$string['setting:allow_opt_out'] = 'Allow users to opt-out';
$string['setting:allow_opt_out:desc'] = 'Users are automatically opted-in to receiving Credly badges. Enable this setting to give users the option to opt-out. A user who opts-out will not receive new badges, and existing badges that have been awarded will not be revoked.';
$string['setting:opt_out_disclaimer'] = 'Credly Badge Opt-in/Opt-out Disclaimer';
$string['setting:opt_out_disclaimer:desc'] = 'This disclaimer is shown to users on their preferences page where they can choose the opt-in or opt-out of receiving Credly Badges.';
$string['setting:tokenregen'] = 'Regenerate webhook URL';
$string['setting:tokenregen:desc'] = 'Check this and save changes to regenerate the token for the webhook url.';
$string['setting:webhookurl'] = 'Webhook URL';
$string['setting:webhookurl:desc'] = 'This url is saved on the Credly instance configuration to help keep Totara up to date with events triggered there';
$string['setting:group_tag'] = 'Group tag';
$string['setting:group_tag_desc'] = 'Add a Group Tag identifier to be passed to Credly with each badge request';

// User Preferences
$string['user:preferences'] = 'Credly preferences';
$string['user_preference:updated'] = 'Your Credly preferences have been updated';
$string['user_preferences:opt_out'] = 'Opt-out of receiving Credly digital credentials';
$string['user_preferences:opt_out_help'] = 'Use this setting to opt-in/opt-out of receiving digital credentials from Credly. Note that if you opt-out after receiving a digital credential, please contact support if you want to delete this record.';
$string['user_preferences:opt_out:disclaimer'] = 'On completion of your learning, you may be eligible to receive a digital credential.  You will receive an email a few days after the event to claim your digital credential.  To find out more about how we process your personal data in relation to this event and your digital credential please see our customer privacy policy. To opt out of receiving digital credentials from Credly please use the setting below to confirm your preference.';

// Badge Statuses
$string['badge_issue:status:success'] = 'Success';
$string['badge_issue:status:not_sent'] = 'Not sent yet';
$string['badge_issue:status:resend'] = 'Being resent';
$string['badge_issue:status:recoverable_failure'] = 'Failed';
$string['badge_issue:status:unrecoverable_failure'] = 'Failed';
$string['badge_issue:status:replace'] = 'Being replaced';

$string['badge:resend'] = 'Resend badge';

// Errors
$string['err:credly_not_enabled'] = 'Credly integration has not been enabled';
$string['err:no_badges'] = 'There is currently no badge data in Totara. Run the manual synchronisation to populate Totara with the latest badge data.';
$string['err:badge_not_found'] = 'There is no badge for the provided ID';
$string['err:badge_issue_failed'] = 'Failed to issue the badge. Check the Badges Report for details.';
$string['err:cannot_reissue_badge'] = 'Cannot issue a badge that has already been successfully issued.';

// Endpoint
$string['endpoint:auth_success'] = 'Testing Credly authentication successful';
$string['endpoint:auth_fail'] = 'Testing Credly authentication failed with: {$a}';

// Linked Learning
$string['linktype:not_linked'] = 'Not set';

// Badges Page
$string['page:badges:heading'] = 'Credly Badges';
$string['page:badges:headerName'] = 'Name';
$string['page:badges:headerId'] = 'Badge ID';
$string['page:badges:headerLinkToLearning'] = 'Link To Learning';
$string['page:badges:headerType'] = 'Type';
$string['page:badges:headerLearning'] = 'Learning';
$string['page:badges:notSet'] = 'Not Set';
$string['page:badges:moreMenu'] = 'More Menu';
$string['page:badges:linkProgram'] = 'Link Program';
$string['page:badges:linkCertification'] = 'Link Certification';
$string['page:badges:linkCourse'] = 'Link Course';
$string['page:badges:unlinkLearning'] = 'Remove';
$string['page:badges:programAdderTitle'] = 'Link Program to {$a}';
$string['page:badges:certificationAdderTitle'] = 'Link Certification to {$a}';
$string['page:badges:courseAdderTitle'] = 'Link Course to {$a}';
$string['page:badges:page:badges:modalHeading'] = 'Link Program';
$string['page:badges:loadmore'] = 'Load More';
$string['page:badges:showing_x_of_y_badges'] = 'Showing {$a->count} of {$a->total} badges';
$string['page:badges:successfully_sent'] = 'Badge issued successfully';
$string['page:badges:sync'] = 'Sync badges';
$string['page:badges:synchronising'] = 'Please wait a moment while we synchronise badges with Credly';

//Adder String
$string['adder:search'] = 'Search';
$string['course_adder:showing_x_of_y_courses'] = 'Showing {$a->count} of {$a->total} courses';
$string['certification_adder:showing_x_of_y_certifications'] = 'Showing {$a->count} of {$a->total} certifications';
$string['program_adder:showing_x_of_y_programs'] = 'Showing {$a->count} of {$a->total} programs';

// Task
$string['task:badge'] = 'Credly badge issue';

// Userdata
$string['userdataitem-issue'] = 'Badge issue data for a user';
