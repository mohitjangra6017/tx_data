<?php

 /**
 * Language file
 *
 * @copyright  City & Guilds Kineo 2023-03-23 {@link http://www.kineo.com}
 * @author     tri.le
 * @version    1.0
 */

$string['pluginname'] = 'Config Backup & Restore';
$string['page:backup'] = 'Backup';
$string['page:restore'] = 'Restore';
$string['dashboardconfiguration'] = 'Dashboard and block configuration';
$string['allsiteandplugins'] = 'Whole site and all plugins';
$string['backupdownload'] = 'Download backup file';
$string['uploadbackupfile'] = 'Upload backup file';
$string['uploadbackupfile_help_title'] = 'Restore Configuration Guidance';
$string['uploadbackupfile_help'] = "
<p><strong>Block permissions</strong>
<br>Note that block permissions are not included in the backup.</p>
<p><strong>Unsupported blocks</strong>
<br>The following blocks are not currently supported by this plugin and will not be included in the backup:
<br>
<ul>
<li>Program completions</li>
<li>Random Glossary</li>
<li>Report table</li>
<li>Report graph</li>
</ul>
</p>
<p><strong>Unsupported block data</strong>
<br>Some blocks contain data that may not be present on the destination site, e.g. recommendations, audience IDs, course/program IDs, tags. This data will not be included in the backup for these blocks:
<br>
<ul>
<li>Carousel</li>
<li>Isotope</li>
<li>Recommendations</li>
<li>RSS feeds</li>
</ul>
</p>
";
$string['exportsiteconfiguration'] = 'Export configuration';
$string['importsiteconfiguration'] = 'Import configuration';
$string['deleteotherdashboards'] = 'Delete other dashboards';
$string['createnew'] = 'Create new';
$string['globalblocks'] = 'Global blocks';
$string['frontpage'] = 'Front page';
$string['applychanges'] = 'Apply changes';
$string['needchoosingatleastone'] = 'You need to choose at least one element';
$string['plugin'] = 'Plugin';
$string['configname'] = 'Config name';
$string['sourcevalue'] = 'Source value';
$string['currentvalue'] = 'Current value';
$string['update'] = 'Update';
$string['onthissite'] = 'On this site';
$string['selectedsettingsapplied'] = 'Selected settings applied';
$string['nomatching'] = 'No matching';
$string['warning_label'] = "WARNING!";
$string['warning'] = "
<p><strong>TKE version</strong>
<br>It is not possible to restore configuration from one version of TKE to a different version as this could result in conflicts in the data that might cause the site to stop working as expected.</p>
<p><strong>Dashboards</strong>
<br>If you are updating existing dashboards be aware that they are matched on the dashboard name, which are not necessarily unique. Give your dashboards unique names to ensure they match correctly.</p>
<p>Additional guidance is provided in the upload tooltip.</p>";
$string['version_warning'] = 'This operation is not permitted as the TKE version of "{$a->source}" in your backup does not match the current version of this site "{$a->dest}".';
$string['cancel_restore'] = 'Cancel restore';
$string['restore_cancelled'] = 'Restore cancelled';
