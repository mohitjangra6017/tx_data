<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     block_carousel
 * @category    string
 * @copyright   2019 Hoogesh Dawoodarry <hoogesh.dawoodarry@kineo.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Carousel';
$string['title'] = 'Title';

$string['carousel:addinstance'] = 'Add a new Carousel block';
$string['carousel:myaddinstance'] = 'Add a new Carousel block to the My Moodle page';

$string['carouseltype'] = 'Carousel type';
$string['showdesc'] = 'Show description';
$string['description'] = 'Carousel description';
$string['captionshortname'] = 'Course field for captions';
$string['enrolmenttype'] = 'Enrolment Types';
$string['allenrolments'] = 'All Enrolment Types';
$string['sortfield'] = 'Sorting field';
$string['sortdirection'] = 'Sorting direction';
$string['coursedurationestimate'] = 'Course duration estimate field';
$string['lowerthreshold'] = 'Lower threshold for button label change';
$string['upperthreshold'] = 'Upper threshold for button label change';
$string['shortsummary'] = 'Short summary field';
$string['subheading'] = 'Subheading field';
$string['namefield'] = 'Heading field';
$string['progress_threshold_lower'] = 'Lower progress threshold(%)';
$string['progress_threshold_lower_desc'] = 'The course progress threshold for being considered as started in a course';
$string['progress_threshold_upper'] = 'Upper progress threshold(%)';
$string['progress_threshold_upper_desc'] = 'The course progress threshold for being considered as almost finishing a course';
$string['buttonlabel_fallback'] = 'Button label fallback';
$string['buttonlabel_fallback_desc'] = 'Label for the button if progress stage could not be determined';
$string['buttonlabel_lowerunreached'] = 'Button label start';
$string['buttonlabel_lowerunreached_desc'] = 'Label for the button if barely any progress has been made (progress is lower than the lower threshold)';
$string['buttonlabel_lowerreached'] = 'Button label continue';
$string['buttonlabel_lowerreached_desc'] = 'Label for the button if some progress has been made (progress is higher than the lower threshold but lower than the upper threshold)';
$string['buttonlabel_upperreached'] = 'Button label finishing';
$string['buttonlabel_upperreached_desc'] = 'Label for the button if the course is almost complete (progress is higher than the upper threshold)';
$string['mode_filteredenrolledlist_defaults'] = 'Enrolled Courses (filtered) mode defaults';
$string['stylepanel'] = 'Block styles';
$string['headertextcolour'] = 'Header text colour';
$string['headerfontsize'] = 'Header font size';
$string['headerfontweight'] = 'Header font weight';
$string['progressbarbg'] = 'Progress bar background colour';
$string['progressbarcolour'] = 'Progress bar colour';
$string['btn_launch'] = 'Launch';
$string['btn_getstarted'] = 'Get Started';
$string['btn_continue'] = 'Continue';
$string['btn_finishitoff'] = 'Finish It Off';
$string['blockinstancesetting'] = 'Block instance settings';
$string['type:jumpbackin'] = 'Jump back in';
$string['type:recommended'] = 'Recommended for you';
$string['type:events'] = 'Events';
$string['type:shoutouts'] = 'Shoutouts';
$string['type:facetoface'] = 'Face to Face';
$string['type:filteredenrolled'] = 'Enrolled Courses (filtered)';
$string['type:whatshot'] = 'What\'s Hot';
$string['type:whatsnew'] = 'What\'s New';
$string['whatshotlimit'] = 'Maximum number of courses to display.';
$string['whatshotlimit_help'] = 'Defines the total number of courses shown in the Carousel. If left blank, it will be set to zero.';
$string['whatsnewlimit'] = 'Maximum number of courses to display.';
$string['whatsnewlimit_help'] = 'Defines the total number of courses shown in the Carousel. If left blank, it will be set to zero.';
$string['gotocourse'] = 'Go to course page';
$string['type:todolist'] = 'User To Do';
$string['type:whatshappening'] = 'What\'s Happening';
$string['whatshappening_filter'] = 'What\'s Happening filter';
$string['whatshappening_eventselect'] = 'What\'s Happening specific events to show';
$string['whatshappening_eventsmodeselect'] = 'What\'s Happening events to show';
$string['type:curated'] = 'Curated';
$string['curate_contents_config'] = 'Configure cluster';
$string['course_search'] = 'Courses';
$string['tags_search'] = 'Tags';
$string['cohort_search'] = 'Cohorts';
$string['save'] = 'Save';
$string['clustername'] = 'Cluster name';
$string['addcourse'] = 'Add Course';
$string['addcohort'] = 'Add Cohort';
$string['cohortconfig'] = 'Cohort configuration';
$string['cardsize'] = 'Carousel card size';
$string['size:small'] = 'Small';
$string['size:large'] = 'Large';
$string['hidecompletedcourses'] = 'Hide completed courses';
$string['curated_courselist'] = 'Curated course list';
$string['addnewcluster'] = 'Add new cluster';
$string['type:cluster'] = 'Cluster';
$string['curatecontent'] = 'Curate content';
$string['image'] = 'Image';
$string['facetofacelimit'] = 'Maximum number of events to display.';
$string['shoutoutlimit'] = 'Maximum number of shoutouts to display.';
$string['facetofacelimit_help'] = 'Defines the total number of events shown in the Carousel. If left blank, it will be set to zero.';
$string['coursecustomfields'] = 'Course custom fields for cards';
$string['coursecustomfields_help'] = 'Add a comma separated shortname of course custom fields to be displayed in the carousel cards.';
$string['coursecustomfields_desc'] = 'Add a comma separated shortname of course custom fields to be displayed in the carousel cards.';
$string['showdetail'] = 'Show detail';
$string['confirmdelete'] = 'Confirm Delete?';
$string['currentimage'] = 'Current image';
$string['hot'] = 'Hot';
$string['new'] = 'New';
$string['postedshoutout'] = '{$a} posted a Shout Out';
$string['usercustomfields'] = 'User custom fields for cards';
$string['usercustomfields_help'] = 'Add a comma separated shortname of user custom fields to be displayed in the carousel cards.';
$string['usercustomfields_desc'] = 'Add a comma separated shortname of user custom fields to be displayed in the carousel cards.';
$string['show:1'] = 'Show course count';
$string['show:2'] = 'Show percentage completed';
$string['counttype'] = 'Count type';
$string['existing_cohorts'] = 'Existing Cohorts';
$string['courses'] = 'Courses';
$string['complete'] = 'Complete';
$string['coursecustomfieldsdetails'] = 'Course custom fields for details';
$string['coursecustomfieldsdetails_help'] = 'Add a comma separated shortname of course custom fields to be displayed in the carousel details.';
$string['coursecustomfieldsdetails_desc'] = 'Add a comma separated shortname of course custom fields to be displayed in the carousel details.';
$string['usercustomfieldsdetails'] = 'User custom fields for details';
$string['usercustomfieldsdetails_help'] = 'Add a comma separated shortname of user custom fields to be displayed in the carousel details.';
$string['usercustomfieldsdetails_desc'] = 'Add a comma separated shortname of user custom fields to be displayed in the carousel details.';
$string['mappedtof2f'] = 'Custom field map to Facetoface';
$string['mappedtof2f_desc'] = 'Add the shortname of one custom field that is common for User custom profile field and F2F event custom field. This will be used to filter sessions on F2F carousel cards.';
$string['mappedtof2f_help'] = 'Add the shortname of one custom field that is common for User custom profile field and F2F event custom field. This will be used to filter sessions on F2F carousel cards.';
$string['mappedtocourse'] = 'Custom field map to course';
$string['mappedtocourse_desc'] = 'Add the shortname of one custom field that is common for User custom profile field and Course custom field. This will be used to filter sessions on F2F carousel cards.';
$string['mappedtocourse_help'] = 'Add the shortname of one custom field that is common for User custom profile field and Course custom field. This will be used to filter sessions on F2F carousel cards.';
$string['facetofacesessiontitle'] = 'Add the shortname of seminar custom field for the title';
$string['facetofacesessiontitle_help'] = 'If there is a shortname and a value assigned to it, this value will be used for card title.';
$string['type:courselets'] = 'Courselets';
$string['launch'] = 'Launch';
$string['latest'] = 'Latest';
$string['type:programs'] = 'Programs and Certifications';
$string['programslimit'] = 'Maximum number of Programs to display.';
$string['programslimit_help'] = 'Defines the total number of Programs shown in the Carousel. If left blank, it will be set to zero.';
$string['gotoprogram'] = 'Go to program page';
$string['netflix'] = 'Use Red (Video streaming site style) template';
$string['template:_red'] = 'Red (Video streaming site style)';
$string['template:_blue'] = 'Blue (simplified style)';
$string['template'] = 'Template';
$string['defaults'] = 'Block setting defaults';
$string['lastcourseaccess'] = 'Last course access';
$string['coursesortorder'] = 'Course sortorder';
$string['coursefullname'] = 'Course fullname';
$string['descending'] = 'Descending';
$string['ascending'] = 'Ascending';
$string['tiletextcolour'] = 'Tile text colour';
$string['detailstextcolour'] = 'Details text colour';
$string['tagtextcolour'] = 'Tag text colour';
$string['tagbgcolour'] = 'Tag background colour';
$string['tagbordercolour'] = 'Tag border colour';
$string['showprogressbar'] = 'Show progressbar';
$string['type:filteredenrolledlist'] = 'Filtered enrolled list';
$string['hidecardexpanddetails'] = "Hide card expand details";
$string['hidecardexpanddetails_desc'] = 'Sitewide configuration for the Carousel block that disables the Details panel';
$string['allowtagsfilter'] = "Allow filter tags";
$string['tagsfiltertype'] = 'Filter type';
$string['listoftags'] = 'List of tags';