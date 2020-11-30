<?php
/**
 * This file is part of Totara Learn
 *
 * Copyright (C) 2020 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Kunle Odusan <kunle.odusan@totaralearning.com>
 */

$string['activity_name_with_section_name'] = '{$a->activity_name} : {$a->section_name}';
$string['activity_name_with_status'] = '{$a->activity_name} ({$a->activity_status})';
$string['anonymous_responses'] = 'Anonymous responses';
$string['current_activity'] = 'Current activity';
$string['instruction_text'] = 'Instruction text';
$string['name'] = 'Response redisplay';
$string['modal_can_not_delete_activity_message'] = "This activity cannot be deleted, because it contains questions that are being referenced in a response redisplay element in:";
$string['modal_can_not_delete_activity_title'] = "Cannot delete activity";
$string['modal_can_not_delete_element_message'] = "This question cannot be deleted, because it is being referenced in a response redisplay element in:";
$string['modal_can_not_delete_element_title'] = "Cannot delete question element";
$string['modal_can_not_delete_section_message'] = "This section cannot be deleted, because it contains questions that are being referenced in a response redisplay element in:";
$string['modal_can_not_delete_section_title'] = "Cannot delete section";
$string['no_responding_relationships'] = '{No responding relationships added yet}';
$string['no_available_questions'] = 'No available questions to select';
$string['pluginname'] = 'Redisplay element';
$string['redisplayed_element_admin_preview'] = 'Response redisplay from "{$a->activity_name} ({date source subject instance created})" – responses last updated {date last modified}.';
$string['redisplayed_summary'] = 'Response redisplay from "{$a->activity_name} ({$a->date_created})" – responses last updated {$a->date_updated}.';
$string['redisplay_no_subject_instance_for_same_activity'] = 'Response redisplay to the following question cannot be shown, because there is no previous participation associated with the activity "{$a}".';
$string['redisplay_no_subject_instance_for_another_activity'] = 'Response redisplay cannot be shown, because there is no participation associated with the activity "{$a}".';
$string['redisplay_no_participants'] = 'Response redisplay cannot be shown, because there is no participation associated with the activity "{$a->activity_name} ({$a->subject_instance_date})".';
$string['responses_from_anonymous_relationships'] = '{Anonymous responses}';
$string['responses_from_relationships'] = '{Responses from: {$a->relationships}}';
$string['select_activity'] = 'Select activity...';
$string['select_question_element'] = 'Select question element...';
$string['source_activity_value'] = 'Source activity';
$string['source_activity_value_help'] = 'The activity containing the question whose responses you would like to redisplay. The subject of the current activity will also need to be assigned to the source activity for responses to exist. Responses displayed here will always reflect the current state of the source responses – thus while a source subject instance is open, there is the possibility that they may change.

Where a repeating activity is selected, responses from the most recently created subject instance will be displayed. Where the current activity is selected, responses from the most recent previous instance will be displayed. Where both the current and source activity are job assignment-specific, responses from the most recent instance from the matching job assignment will be displayed. Where the current activity is set to one per user, but the source activity is job assignment specific, responses from the most recent instance (regardless of job assignment will be displayed).
Note that if the source activity is still in draft, the source question and responding participants may be modified (although not deleted).';
$string['source_element_option'] = '{$a->element_title} ({$a->element_plugin_name})';
$string['source_question_element_value'] = 'Source question element';
$string['source_question_element_value_help'] = 'Only question elements can be selected for response redisplay. Responses from all responding participants will be displayed, regardless of who the responding participants are in the current activity, and the viewing permissions of the source activity. Where the source activity is anonymised, anonymity of the responses will be preserved when redisplayed.';
