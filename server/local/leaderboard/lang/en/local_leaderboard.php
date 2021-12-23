<?php
/**
 * English language strings.
 *
 * @package   local_score
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2016 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Leaderboard';

// Settings.
$string['settings:title'] = 'Leaderboard';
$string['settings:config'] = 'Configure Scores';
$string['settings:adhoc'] = 'Ad hoc Scoring';
$string['settings:title:config'] = 'Scoring';
$string['settings:title:adhoc'] = 'Ad hoc Scoring';
$string['settings:header:settings'] = 'Settings';
$string['settings:header:config'] = 'Score Config';
$string['settings:header:adhoc'] = 'Allocate Scores to User';
$string['settings:description:config'] = 'Add events and configure associated scoring';
$string['settings:description:adhoc'] = 'Ad hoc scoring description';
$string['settings:score'] = 'Score';
$string['settings:frequency'] = 'Award score frequency (0 = always)';
$string['settings:coursemultiplierfieldid'] = 'Course multiplier field';
$string['settings:coursemultiplierfieldid_help'] = 'Select the course field that will be used to multiply the score on course level events.';
$string['settings:progmultiplierfieldid'] = 'Program multiplier field';
$string['settings:progmultiplierfieldid_help'] = 'Select the program field that will be used to multiply the score on program level events.';
$string['settings:addnewscore'] = 'Add a new score';
$string['settings:coursemultiplierfieldid:success'] = 'Course multiplier field successfully updated';
$string['settings:coursemultiplierfieldid:problem'] = 'There was a problem updating the course multiplier field';
$string['settings:progmultiplierfieldid:success'] = 'Program multiplier field successfully updated';
$string['settings:progmultiplierfieldid:problem'] = 'There was a problem updating the program multiplier field';
$string['settings:excludeusersfieldid'] = 'Excluded users field';
$string['settings:excludeusersfieldid_help'] = 'Select the custom user profile field that will be used to determine which users should be excluded when reporting';
$string['settings:excludeusersfieldid:success'] = 'Excluded user profile field successfully updated';
$string['settings:excludeusersfieldid:problem'] = 'There was a problem updating the excluded user profile field';
$string['settings:activeonly:success'] = 'Exclude non active users field successfully updated';
$string['settings:activeonly:problem'] = 'There was a problem updating exclude non active users checkbox';
$string['settings:rankuserswithscores:success'] = 'Rank users with scores field successfully updated';
$string['settings:rankuserswithscores:problem'] = 'There was a problem updating rank users with scores checkbox';
$string['settings:unknown:problem'] = 'There was a problem updating the field {$a}, this is not a valid field';

// Errors.
$string['error:invalidscore'] = 'Invalid score';
$string['error:integersonly'] = 'Only whole numbers are accepted';
$string['error:positive_integers_only'] = 'Only positive whole numbers are accepted';
$string['error:gradeandscore'] = 'Scores cannot be input when using grades';
$string['error:eventconfigured'] = 'This event has already been configured';
$string['error:required'] = 'Either {$a->mod_completions}, or {$a->area} and {$a->event} together are required fields!';
$string['error:threshold'] = 'Award threshold cannot be used with event: "{$a}"';

// Forms
$string['form:settings:header'] = 'Settings';
$string['form:userbulk:allocate'] = 'Allocate this score to the selected users';
$string['form:userbulk:submit'] = 'GO';
$string['form:warning:nousers'] = 'No users selected';
$string['form:notification:scoreadded'] = 'A score of {$a} was added for the selected users';
$string['form:warning:scorenotadded'] = 'There was a problem adding the score';
$string['form:editscore:award'] = 'Award Threshold';
$string['form:editscore:score'] = 'Score';
$string['form:editscore:usegrades'] = 'Use grades';
$string['form:editscore:usegrades_help'] = "When checked the grade (if applicable) is used rather than a score. Note that this only applies to a small number of
events. Currently only the following event types support scoring by grade:
<br/>Quiz: Quiz attempt submitted
<br/>Assignment: The submission has been graded
<br/>Lesson: Essay assessed
<br/>SCORM package: Submitted SCORM status";
$string['form:editscore:activeonly'] = 'Report on suspended and deleted users';
$string['form:editscore:activeonly_help'] = 'Check this box to have reports include suspended and deleted users when calculating ranks, scores etc';
$string['form:editscore:rankuserswithscores'] = 'Calculate total ranks from users with scores';
$string['form:editscore:rankuserswithscores_help'] = 'Check this box to have the total ranks displayed in the leaderboard block only include users with scores. If not checked we show based on the number of users on the site. This setting can be used in conjunction with the "active users" and "excluded users" settings above';
$string['form:settings:mod_completions'] = 'Course activity/resource completion';

// Events
$string['event:name:adhoc_score'] = 'Ad hoc score event';

// UI
$string['button:newevent'] = 'Add a new event';

// Misc
$string['excluded'] = 'N/A';

// Capabilities
$string['leaderboard:allocate'] = 'Allow ad-hoc scoring of users';
$string['leaderboard:config'] = 'Allow configuration of the plugin';

// GDPR
$string['userdataitem-user-score'] = 'Score';