<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

use theme_kineo\controllers\theme_settings;

require_once(__DIR__ . '/../../config.php');
(new theme_settings())->process();