<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

require_once(__DIR__ . '/../../config.php');
global $CFG, $DB, $PAGE;

require_login();

$controller = new mod_assessment\controller\override($PAGE, $DB);
$controller->render();
