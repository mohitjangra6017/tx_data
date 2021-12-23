<?php
/**
 * English language strings.
 *
 * @package   local_realtimecohortassignment
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2016 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

$string['pluginname'] = 'Real-time audience assignments';

$string['totarasync'] = 'Assign during HR sync';
$string['totarasync_desc'] = 'Will run audience assignment on user updates triggered by HR sync. (Warning! Causes HR Sync to slow down significantly)';

$string['enable_userlogin'] = 'Enable audiences updates on user login';
$string['enable_userlogin_desc'] = 'Defines when user audience assignments should be updated. "Every login" will ensure the user is always up to date, but has the largest performance impact.';

$string['user_login_event_none'] = 'No';
$string['user_login_event_first'] = 'First login only';
$string['user_login_event_every'] = 'Every login';

$string['enable_course_completed'] = 'Enable update on every course completion';
$string['enable_course_completed_desc'] = 'When enabled, user cohorts updated when a course is completed';
