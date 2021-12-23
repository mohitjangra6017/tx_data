<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

use local_credly\Controller\BadgeAdmin;

require_once(__DIR__ . '/../../config.php');
(new BadgeAdmin())->process();