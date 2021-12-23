<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage assquestion_rating
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Rating';

$string['availableresponses'] = 'Available responses';
$string['default'] = 'Default';
$string['maxval'] = 'To';
$string['maxval_help'] = 'The highest selectable number.

* If grading is enabled for this question, this value is worth 100%.';
$string['minval'] = 'From';
$string['minval_help'] = 'The lowest selectable number.

* If grading is enabled for this question, this value is worth 0%.';
$string['noanswer'] = 'No response';
$string['selected'] = 'Selection';
$string['usedefault'] = 'Use default';

$string['error_badrange'] = '"To" value must be an integer larger than the "From" value';
$string['error:badvalue'] = 'Range value must be a positive integer';
$string['error:outsiderange'] = 'Default must be within configured range';
