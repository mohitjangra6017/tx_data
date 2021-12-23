<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

use theme_kineo\controllers\theme_restore;

require_once(__DIR__ . '/../../config.php');
(new theme_restore())->process();