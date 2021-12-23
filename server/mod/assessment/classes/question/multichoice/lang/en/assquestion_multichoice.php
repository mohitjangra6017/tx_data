<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package mod_assessment
 * @subpackage assquestion_text
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Multiple choice';

$string['addresponse'] = 'Add another response';
$string['availableresponses'] = 'Available responses';
$string['choicetype'] = 'One or multiple answers?';
$string['choicetype_help'] = 'Choose how question can be answered and graded.

* One answer only: Available responses will display as radio buttons and only one response may be chosen at a time.  Only one response may be assigned a value greater than 0.  This will be the answer that awards credit for the question.
* Multiple answers allowed: Available answers will display as checkboxes and multiple answers may be chosen at a time.  The "Numerical Value(s) of Response" are totalled and partial credit is awarded based on the number of responses a user selects.';
$string['choicetypemulti'] = 'Multiple answers allowed';
$string['choicetypesingle'] = 'Single answer only';
$string['defaultselected'] = 'Make selected by default';
$string['responsepenalty'] = 'Penalty points';
$string['response'] = 'Response';
$string['responsevalues'] = 'Points earned';

$string['error:badvalue'] = 'Points must be positive integers.';
$string['error:norightanswer'] = 'You have not configured a correct response.';
$string['error:rightandwrong'] = 'You have configured a response to both earn and penalize points.  Please choose one or the other.';
$string['error:toomanyanswers'] = 'You have configured more than one correct response for this "Single answer only" question.';
$string['error:toomanydefaults'] = 'You have configured more than one default response for this "Single answer only" question.';
$string['error:singlechoice:penalty'] = 'You cannot set a penalty score for a "Single answer only" question';
