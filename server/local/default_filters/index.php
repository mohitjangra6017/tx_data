<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

use local_default_filters\Controllers\AdminController;

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');

(new AdminController())->process();