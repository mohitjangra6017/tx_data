<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

use theme_kineo\controllers\settings;

require_once dirname(dirname(dirname(__FILE__))) . '/config.php';
(new settings('kineo'))->process();
