<?php
$string['pluginname'] = 'Totara: Kineo Edition Core';

$string['subplugintype_migration'] = 'Migration';
$string['subplugintype_migration_plural'] = 'Migrations';
$string['tke_version'] = 'TKE Version {$a}';

$string['local_core:page'] = 'T:KE Settings';

// Settings
$string['sendcancellationical'] = 'Set iCalendar cancellations to cancel existing event';
$string['sendcancellationical_desc'] = 'This setting changes the iCalendar cancellation behaviour to cancel the existing calendar event instead of sending as a new calendar request. Note that this setting may be incompatible with some email software.';
$string['additionalhtml_disclaimer_heading'] = 'WARNING!';
$string['additionalhtml_disclaimer_desc'] = 'Custom CSS/HTML can cause issues with maintenance upgrades that may cause pages or content within the site to become unavailable. Please contact Kineo before making changes to these settings as there may be costs to recover settings in some cases.';

// Report Builder Work Mem Setting
$string['heading:work_mem'] = 'Report Builder Work Memory';
$string['heading:work_mem:info'] = '';
$string['setting:work_mem_enabled:label'] = 'Report Builder Work Memory Enabled?';
$string['setting:work_mem_enabled:desc'] = 'Enable the work memory setting for all reports.';
$string['setting:work_mem:label'] = 'Report Builder Work Memory Size';
$string['setting:work_mem:desc'] = 'Set the size of PostgreSQL\'s "work_mem" setting when a report is being run. Increasing this size is one tool to increase the performance of large reports considerably.
The format of this is a whole number, followed by "KB", "MB", or "GB" for Kilobytes, Megabytes, Gigabytes respectively; e.g. 32MB.';
$string['error:invalid_work_mem'] = 'The given work memory of {$a} is not in the format of a whole number, followed by "KB", "MB", or "GB".';
$string['error:invalid_work_mem_size'] = 'The given work memory of {$a} is not a valid size measurement';
$string['error:work_mem_exceeds_limit'] = 'The given work memory of {$a->value} exceeds the limit of {$a->limit}';

// Optimised SCORM Report Setting
$string['heading:scorm_optimisation'] = 'Optimised SCORM Report';
$string['heading:scorm_optimisation:info'] = '';
$string['setting:scorm_opt_enabled'] = 'Enable Optimised SCORM Report?';
$string['setting:scorm_opt_enabled:desc'] = 'Modifies any reports that use the SCORM source to instead make use of an optimised SCORM data set.';