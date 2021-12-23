<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

use theme_kineo\Service\ThemeBackupService;
use totara_tenant\entity\tenant;

define('NO_DEBUG_DISPLAY', true);
require_once(__DIR__ . '/../../config.php');

$tenantId = optional_param('tenant_id', 0, PARAM_INT);
require_login(null, false);
require_capability('moodle/site:config', context_system::instance());

if (!empty($tenantId)) {
    $tenant = tenant::repository()->find($tenantId);
    if (empty($tenant)) {
        throw new moodle_exception('errorinvalidtenant', 'totara_tenant');
    }
}

$service = new ThemeBackupService($tenantId);
$backup = $service->createBackup();
$jsonEncoded = json_encode($backup);
send_file($jsonEncoded, 'theme_backup.json', 0, 0, true, true);