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
 * Strings for component 'block_rate_course', language 'en', branch 'MOODLE_20_STABLE'
 *
 * @package    block
 * @subpackage rate_course
 * @copyright  2009 Jenny Gray
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * Code was Rewritten for Moodle 2.X By Atar + Plus LTD for Comverse LTD.
 * @copyright &copy; 2011 Comverse LTD.
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

$string['rate_course:addinstance'] = 'Add a new course ratings block';
$string['rate_course:myaddinstance'] = 'Add course ratings to my page';
$string['rate_course:delete'] = 'Delete reviews made for the current course';
$string['pluginname'] = 'Rate Course';
$string['courserating'] = 'Course ratings';

$string['star1'] = '1';
$string['star2'] = '2';
$string['star3'] = '3';
$string['star4'] = '4';
$string['star5'] = '5';

$string['avgrating'] = 'Average rating';
$string['completed'] = 'Thank you for your review.';
$string['completions'] = 'Course completions';
$string['courserating'] = 'Course Rating';
$string['deletereview'] = 'Delete review';
$string['reviewdeleted'] = 'The review has been deleted';
$string['editingsitehome'] = 'This block will display ratings on course pages only, and is hidden elsewhere.';
$string['enrol'] = 'Enrol';
$string['unenrol'] = 'Unenrol';
$string['finduseful'] = 'I found this review useful';
$string['founduseful'] = 'You and {$a} others found this review useful';
$string['foundusefulsingle'] = 'You found this review useful.';
$string['foundusefulcouple'] = 'You and {$a} other found this review useful.';
$string['giverating'] = 'Give a rating';
$string['intro'] = 'Please indicate how highly you rate this course.';
$string['likecourse'] = 'Like course';
$string['likereview'] = 'Like review';
$string['mostliked'] = 'Most liked';
$string['mostrecent'] = 'Most recent';
$string['myrating'] = 'My rating';
$string['myrecommendations'] = 'You have recommended to {$a} people.';
$string['myrecommendationssingle'] = 'You have recommended to {$a} person.';
$string['noguestuseage'] = 'To rate this course you will need to log in.';
$string['rate_course:rate'] ='Webservice permission to give a rating and or recommendation to a course';
$string['rating'] = 'Rating';
$string['rating_help'] = 'Rate your experience of the course from 1-star (lowest) to 5-star (highest)';
$string['rating_alt0'] ='Course rating: No ratings given.';
$string['rating_altnum'] ='Course rating: {$a} stars.';
$string['rating_users'] ='Rated by {$a} user(s)';
$string['recommendcourse'] = 'Recommend Course';
$string['review'] = 'Review';
$string['review_help'] = 'Provide your review of the course in 140 characters or less.';
$string['reviewliked'] = 'Review liked';
$string['startdate'] = 'Start';
$string['submitrating'] = 'Submit my rating';
$string['submitrecommendation'] = 'Recommend course';
$string['useridto'] = 'User';
$string['useridto_help'] = 'Select a user who you feel will enjoy or benefit from this course. Start typing to search users.';
$string['unenroltext'] = 'Are you sure you want to unenrol from this course?';
$string['enrolmentnotfound'] = 'Enrolment types not found for this course.';
$string['showbuttons'] = 'Show buttons';
$string['showbuttons_help'] = 'Display the course rating buttons on the block. Useful if there is no custom course renderer.';
$string['courseliked'] = 'Course liked';
$string['courseunliked'] = 'Course unliked';


// Events.
$string['eventcourseliked'] = 'Liked course';
$string['eventcourseunlike'] = 'Unliked course';
$string['eventrecommendationadded'] = 'Recommended course';
$string['eventreviewadded'] = 'Course reviewed';
$string['eventreviewcommented'] = 'Liked course review';

// Questionnaire integration.
$string['survey'] = 'What is the questionnaire name?';
$string['survey_help'] = 'This is the questionnaire module which will be linked to (leave blank to stop link to questionnaire)';
$string['viewreview'] = 'View questionnaire responses';
$string['noreview'] = 'No review provided';

$string['webservice_param_userid_desc'] = 'The id of the user that received suggestion';
$string['webservice_param_courseid_desc'] = 'The id of the course that is suggested';
$string['webservice_param_source_desc'] = 'The source of the suggestion. Default to skill_assessor but could be any other sources';
$string['webservice_return_suggest_course'] = 'true when success';

$string['auto_create_self_enrol'] = 'Automatically create self enrol';
$string['auto_create_self_enrol_help'] = 'Automatically create a self enrolment instance if one is not found or reenable the enrol instance if one is found which has been disabled.';

$string['enable_course_suggestions'] = 'Enable course suggestion';
$string['enable_course_suggestions_help'] = 'Enable course suggestion button and suggestion accordion';
$string['exclude_zero_star_rating'] = 'Exclude zero star rating';
$string['exclude_zero_star_rating_help'] = 'When checked excludes zero star rating from being included in the average rating of a course';
$string['limitrecommenddesc'] = 'Users can only recommend to other users who have the same profile field data.';
$string['limitrecommend'] = 'Restrict recommend users';
$string['norestrictions'] = 'No restrictions';

$string['replyreview'] = 'Reply to a review';
$string['rate_course:comment'] = 'Respond to a course review left by a student';
$string['commentadded'] = 'Reply added to review';
$string['commentheader'] = 'Add reply to review';
$string['comment'] = 'Reply';
$string['comment_help'] = 'Provide your reply of the review in 140 characters or less.';
$string['submitcomment'] = 'Submit reply';
$string['replyby'] = 'Reply by';
$string['rate_course:delete_comment'] = 'Delete a response made to a review.';
$string['deletecomment'] = 'Delete reply';
$string['enablecomments'] = 'Enable review replies';
$string['enablecomments_help'] = 'Allow an admin to reply to a course review made by a user.';
$string['commentdeleted'] = 'The reply has been deleted.';
// MBIHAS-197
$string['getstarted'] = 'Get Started';
$string['userdataitem-user-review_like'] = 'Course review likes';
$string['userdataitem-user-review_comment'] = 'Course review comments';
$string['userdataitem-user-review'] = 'Course reviews';
$string['userdataitem-user-course_like'] = 'Course likes';
$string['userdataitem-user-recommendation'] = 'Course recommendations';
$string['userdataitem-user-recommendation_help'] = 'Course recommendations made to or from the target user. When purging, both of these types are purged';
$string['userdataitem-user-review_help'] = 'Course reviews for the rate course block. When purging, any likes or comments related to the users reviews will also be purged';