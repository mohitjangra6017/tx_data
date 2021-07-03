<?php
/**
 * This file is part of Totara Learn
 *
 * Copyright (C) 2021 onwards Totara Learning Solutions LTD
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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Simon Coggins <simon.coggins@totaralearning.com>
 * @package contentmarketplace_linkedin
 */

defined('MOODLE_INTERNAL') || die();

$string['a11y_appears_in_n_courses'] = 'Appears in {$a} course(s)';
$string['a11y_content_difficulty'] = 'Content difficulty level';
$string['a11y_content_time_to_complete'] = 'Time to complete content';
$string['a11y_content_type'] = 'Type of content';
$string['a11y_search_filter'] = 'Search';
$string['a11y_time_filter'] = 'Time';
$string['a11y_view_courses'] = 'View the {$a->count} course(s) "{$a->course}" appears in';
$string['add_linkedin_courses'] = 'Add courses from the LinkedIn Learning content marketplace';
$string['add_linkedin_courses_description'] = 'Create courses based on what is available in the LinkedIn Learning content marketplace.';
$string['appears_in'] = 'Appears in';
$string['asset_type_course_plural'] = 'Courses';
$string['asset_type_video_plural'] = 'Videos';
$string['assign_to_category'] = 'Assign to category';
$string['browse_learning_content'] = 'Browse LinkedIn Learning content';
$string['catalog_filter_asset_type'] = 'Type';
$string['catalog_filter_subjects'] = 'Subjects';
$string['catalog_filter_time_to_complete'] = 'Time to Complete';
$string['catalog_filter_timespan_10_to_30_minutes'] = '10 - 30 mins';
$string['catalog_filter_timespan_1_to_2_hours'] = '1 - 2 hours';
$string['catalog_filter_timespan_2_to_3_hours'] = '2 - 3 hours';
$string['catalog_filter_timespan_30_to_60_minutes'] = '30 - 60 mins';
$string['catalog_filter_timespan_over_3_hours'] = '3+ hours';
$string['catalog_filter_timespan_under_10_minutes'] = '< 10 mins';
$string['catalog_import_page_placeholder_group'] = 'Catalog import {$a}';
$string['catalog_title'] = 'LinkedIn Learning catalogue';
$string['catalog_review_title'] = 'LinkedIn Learning catalogue - Review selections';
$string['category_label'] ='Category:';
$string['client_id'] = 'Client ID';
$string['client_id_help'] = 'Client ID can be obtained from LinkedIn Learning under the integration settings page.';
$string['client_secret'] = 'Client secret';
$string['client_secret_help'] = 'Client secret can be obtained from LinkedIn Learning under the integration settings page.';
$string['comma_and_space'] = ', ';
$string['content_appears_in'] = 'This LinkedIn Learning content appears in:';
$string['content_creation_failure'] = 'A number of courses failed to be imported due to a system error. Please <u>try again to import the remaining courses</u> ({$a}).';
$string['content_creation_failure_no_course'] = 'All courses failed to be imported due to a system error. Please <u>try again</u>.';
$string['content_creation_unknown_failure'] = 'Unknown issue';
$string['course_content_immediate_creation'] = "The courses have been successfully created";
$string['course_content_delay_creation'] = "It may take several minutes to create courses. If any items fail to be imported, you will
be sent a notification once the process is complete.";
$string['course_difficulty_advanced'] = 'Advanced';
$string['course_difficulty_beginner'] = 'Beginner';
$string['course_difficulty_intermediate'] = 'Intermediate';
$string['course_number'] = '{$a} course';
$string['course_number_plural'] = '{$a} courses';
$string['course_type_course'] = 'Course';
$string['course_type_learning_path'] = 'Learning path';
$string['course_type_video'] = 'Video';
$string['edit_course_category'] = 'Edit course category';
$string['error:fail_json_validation'] = 'Failed to validate the json data: {$a}';
$string['explore_lil_marketplace'] = 'LinkedIn Learning content marketplace';
$string['explore_lil_marketplace_description'] = 'Explore content from LinkedIn Learning';
$string['filters_title'] = 'Filters';
$string['import_course_full_failure_body'] = 'Unfortunately, an error occurred when importing items from LinkedIn Learning causing all of the items to fail to import.
Please re-attempt the import by following this link: [catalog_import:page_link_placeholder]';
$string['import_course_full_failure_resolver_name'] = 'Failed to import all courses';
$string['import_course_full_failure_subject'] = 'LinkedIn Learning import failure';
$string['import_course_full_failure_title'] = 'LinkedIn Learning import failure';
$string['import_course_partial_failure_body'] = "Unfortunately, an error occurred when importing items from LinkedIn Learning. The following items failed to be imported:
[learning_objects:titles_list]
Please re-attempt the import by following this link: [learning_objects:catalog_import_link]
";
$string['import_course_partial_failure_resolver_name'] = "Failed to import several courses";
$string['import_course_partial_failure_subject'] = "LinkedIn Learning import failure";
$string['import_course_partial_failure_title'] = "LinkedIn Learning import failure";
$string['language_en'] = 'English';
$string['language_filter_label'] = 'Language';
$string['learning_objects_group'] = 'Learning objects {$a}';
$string['learning_object_titles'] = 'titles';
$string['learning_object_title_list_item'] = '* {$a}';
$string['page_link_placeholder'] = "page link";
$string['plugin_description'] = 'Online Training Courses for Creative, Technology, Business Skills.';
$string['pluginname'] = 'LinkedIn Learning';
$string['recipient_actor'] = 'User who triggered action';
$string['provider_linkedin'] = 'LinkedIn Learning';
$string['search_filter_placeholder'] = 'Search...';
$string['settings_title'] = 'LinkedIn Learning settings';
$string['sort_filter_alphabetical'] = 'Alphabetical';
$string['sort_filter_latest'] = 'Latest';
$string['timespan_format_hours_minutes'] = '{$a->hours}h {$a->minutes}m';
$string['timespan_format_minutes'] = '{$a->minutes}m';
$string['timespan_format_minutes_seconds'] = '{$a->minutes}m {$a->seconds}s';
$string['timespan_format_seconds'] = '{$a->seconds}s';
$string['update'] = 'Update';
$string['warningdisablemarketplace:body:html'] = '<p>Items from the marketplace will no longer be available to course creators for inclusion in newly created courses.</p>
<p>Users who have previously already started LinkedIn Learning activities will continue to have access to that content, but they will not be able to start new LinkedIn Learning activities.</p>';
$string['warningdisablemarketplace:title'] = 'Disable LinkedIn Learning content';
$string['warningenablemarketplace:body:html'] = 'Items from the marketplace will be available to course creators for inclusion in newly created courses.';
$string['warningenablemarketplace:title'] = 'Enable LinkedIn Learning content';