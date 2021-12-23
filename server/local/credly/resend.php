<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

use local_credly\Controller\BadgeResend;

require_once(__DIR__ . '/../../config.php');
(new BadgeResend())->process();