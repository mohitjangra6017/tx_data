<?php

use core\orm\query\builder;
use core\tenant_orm_helper;
use report_outline\event\activity_report_viewed;
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
 * This block allows the user to give the course a rating, which
 * is displayed in a custom table (<prefix>_block_rate_course).
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

require_once($CFG->dirroot.'/blocks/moodleblock.class.php');

class block_rate_course extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_rate_course');
    }

    public function get_required_javascript() {
        parent::get_required_javascript();
        $this->page->requires->jquery_plugin('star-rating', 'block_rate_course');
    }

    public function applicable_formats() {
        return array('all' => true, 'tag' => false, 'my' => false);
    }

    public function has_config() {
        return true; // Config only for review part.
    }

    public function get_content() {
        global $CFG, $COURSE, $USER, $DB, $OUTPUT, $PAGE;

        $this->page->requires->css('/blocks/rate_course/jquery/modules/select2.css');

        if ($this->content !== null) {
            return $this->content;
        }

        // Create empty content.
        $this->content = new stdClass();
        if (empty($this->config)) {
            $config = get_config('block_rate_course');
        } else {
            $config = $this->config;
        }


        $this->content->text = '';

        // Output current rating, completion summary and promoted reviews.
        $this->content->text = $this->display_average_rating($COURSE->id, true);
        $this->content->text .= $this->display_course_completions($COURSE->id, true);
        $this->content->text .= $this->display_reviews($COURSE->id, true);

        if ($config->showbuttons) {
            $this->content->text .= $this->display_buttons($COURSE->id, true);
        }

        $this->content->footer = '';

        return $this->content;

    }

    /**
     * Process given form data to save as new or updated record and trigger associated events.
     *
     * @param $data
     * @return bool|int
     * @throws coding_exception
     */
    public function save_rating($data) {
        global $DB;
        if (empty($data->id)) {
            $data->course = $data->courseid;
            $data->timecreated = time();
            $data->timemodified = time();
            $data->reviewlikes = 0;
            $data->id = $DB->insert_record('rate_course_review', $data);
            $context = context_course::instance($data->courseid);
            $event = \block_rate_course\event\review_created::create(array('context' => $context, 'objectid' => $data->id, 'courseid' => $data->course));
            $event->trigger();
        } else {
            // TODO: fix the events. This is not used by Vodafone where updates are not permitted.
            $data->timemodified = time();
            $DB->update_record('rate_course_review', $data);
            //events_trigger('enrol_avetmiss_program_updated', $data);
        }
        return $data->id;
    }

    /**
     * Process given form data to save as new or updated record and trigger associated events.
     *
     * @param $data
     * @return bool|int
     * @throws coding_exception
     */
    public function save_comment($data) {
        global $DB;
        if (empty($data->id)) {
            $data->timecreated = time();
            $data->id = $DB->insert_record('rate_course_review_comments', $data);
            //$context = context_course::instance($data->courseid);
            //$event = \block_rate_course\event\review_created::create(array('context' => $context, 'objectid' => $data->id, 'courseid' => $data->course));
            //$event->trigger();
        } else {
            $data->timemodified = time();
            $DB->update_record('rate_course_review_comments', $data);
            //events_trigger('enrol_avetmiss_program_updated', $data);
        }
        return $data->id;
    }

    /**
     * Save recommendation data and trigger recommendation event.
     * @param $data
     * @return bool|int
     * @throws coding_exception
     */
    public static function save_recommendation($data) {
        global $DB;
        if (empty($data->id)) {
            if (is_int($data->courseid)) {
                $data->course = $data->courseid;
            }
            $data->timecreated = $data->timemodified = time();

            // default source is block rate course
            if (empty($data->source)) {
                $data->source = 'block_rate_course';
            }
            $data->id = $DB->insert_record('rate_course_recommendations', $data);
            $context = context_course::instance($data->courseid);
            $event = \block_rate_course\event\recommendation_created::create(array('context' => $context, 'objectid' => $data->id, 'relateduserid' => $data->useridto));
            $event->trigger();
        } else {
            // TODO: fix the events. This is not used by Vodafone where updates are not permitted.
            $data->timemodified = time();
            $DB->update_record('rate_course_review', $data);
            //events_trigger('enrol_avetmiss_program_updated', $data);
        }
        return $data->id;
    }

    /**
     * Get the average rating for a given course or zero if no rating exist.
     *
     * @param int $courseid The ID of the course.
     * @return int  rating.
     */
    protected function get_rating($courseid) {
        global $DB;
        if (empty($this->config)) {
            $config = get_config('block_rate_course');
        } else {
            // to make sure the exclude zero star rating is always captured
            // RELU-192 - issue experienced in stage and live
            if(!isset($config->exclude_zero_star_rating)) {
                $config = get_config('block_rate_course');
            } else {
                $config = $this->config;
            }
        }

        $sql = "SELECT AVG(rating) AS avg FROM {rate_course_review} WHERE course = :courseid";
        if(!empty($config->exclude_zero_star_rating)) {
            $sql .= " AND rating != 0";
        }
        $avg = 0;
        if ($avgrec = $DB->get_record_sql($sql, array('courseid' => $courseid))) {
            $avg = round($avgrec->avg*2) / 2;
        }
        return $avg;
    }

    /**
     * Returns a count of the number of course completions for a given course.
     *
     * @param $courseid
     * @return int
     */
    protected function count_course_completions($courseid) {
        global $DB;
        return $DB->count_records_select('course_completions', 'timecompleted IS NOT NULL AND course = :course', array('course' => $courseid));
    }

    /**
     * Return or print the current average rating.
     * Can be called outside the block.
     * @param int $courseid the ID of the course
     * @param bool $return return the string (true) or echo it immediately (false)
     * @return string the html to output graphic, alt text and number of ratings
     */
    public function display_average_rating($courseid, $return=false, $withtext = true) {
        global $PAGE, $DB;

        $numratings = $DB->count_records('rate_course_review', array('course' => $courseid));
        $numstars = $this->get_rating( $courseid );

        $output = $PAGE->get_renderer('block_rate_course');
        $o = $output->display_average_rating($numstars, $numratings, 'sm', $withtext);

        if ($return) {
            return $o;
        }
        echo $o;
    }

    /**
     * Get course rating for a user
     * @param $courseid
     * @param $userid
     * @return mixed
     */
    protected function get_user_rating($courseid, $userid) {
        global $DB;
        return $DB->get_field('rate_course_review', 'rating', array('course' => $courseid, 'userid' => $userid));
    }

    public function display_user_rating($courseid, $userid, $return=false) {
        global $PAGE;

        $numstars = $this->get_user_rating( $courseid, $userid );

        $output = $PAGE->get_renderer('block_rate_course');
        $o = $output->display_star_rating($numstars, 'sm');

        if ($return) {
            return $o;
        }
        echo $o;
    }

    /**
     * @param $courseid
     * @return bool|mixed
     * @throws dml_missing_record_exception
     * @throws dml_multiple_records_exception
     */
    public function show_rating($courseid) {
        global $CFG, $DB;
        // Pinned block check once per session for performance.
        if (!isset($_SESSION['starsenabled'])) {
            $_SESSION['starsenabled'] = $DB->get_field('block', 'visible',
                            array('name'=>'rate_course'));
            if ($_SESSION['starsenabled'] && !isset($_SESSION['starspinned'])) {
                $_SESSION['starspinned'] = $DB->get_record_sql(
                                "SELECT * FROM {block_pinned} p
                                JOIN {block} b ON b.id = p.blockid
                                WHERE pagetype = ? AND p.visible = ? AND b.name = ?",
                                array('course-view', 1, 'rate_course'));
            }
        }
        if (!$_SESSION['starsenabled']) {
            return false;
        }
        if ($_SESSION['starspinned']) {
            return true;
        }

        return $DB->get_record_sql("SELECT * FROM {block_instance} i
                        JOIN {block} b ON b.id = i.blockid
                        WHERE pageid = ? and b.name = ?", array($courseid, 'rate_course'));
    }

    /**
     * Return or print a paragraph string displaying the number of course completions for a given course.
     * Can be called outside the block.
     * @param $courseid
     * @param bool $return
     * @return string
     * @throws coding_exception
     */
    protected function display_course_completions($courseid, $return=false) {
        global $PAGE;
        $numcompletions = $this->count_course_completions($courseid);

        $output = $PAGE->get_renderer('block_rate_course');
        $o = $output->display_course_completions($numcompletions);

        if ($return) {
            return $o;
        }
        echo $o;
    }

    /**
     * Get the most useful and recent reviews for a given course.
     * @param $courseid
     * @param $sort
     * @param int $limit
     * @return array
     */
    protected function get_reviews($courseid, $sort='timecreated DESC', $limit=10) {
        global $DB;
        return $DB->get_records('rate_course_review', array('course' => $courseid), $sort, '*', 0, $limit);
    }

    /**
     * Returns a user record for a given id.
     * @param $userid
     * @return mixed
     */
    protected function get_user($userid) {
        global $DB;
        return $DB->get_record('user', array('id' => $userid));
    }

    /**
     * Returns the fullname for a given user record.
     * @param $userid
     * @return string
     */
    protected function get_user_fullname($userid) {
        $user = $this->get_user($userid);
        return fullname($user);
    }

    /**
     * Return or print a list of reviews for a given course.
     * Can be called outside the block.
     * @param $courseid
     * @param bool $return
     * @return string
     */
    public function display_reviews($courseid, $return=false) {
        $o = html_writer::start_div('course-reviews-list');
        $o .= $this->display_review_list($courseid);
        $o .= html_writer::end_div();
        if ($return) {
            return $o;
        }
        echo $o;
    }

    public function display_review_list($courseid) {
        global $PAGE, $USER;
        $output = $PAGE->get_renderer('block_rate_course');
        $recent = $this->get_reviews($courseid);
        $liked = $this->get_reviews($courseid, 'reviewlikes DESC, timecreated DESC');

        $o = $output->display_review_tabs();
        $o .= $output->start_tab_content('course-review-tab-content');
        $o .= $output->start_tab_inner('recent', true);
        if($recent) {
            foreach($recent as $review) {
                $allowlike = $this->check_already_liked_review($USER->id, $review->id);
                $numlikes = $this->count_review_likes($review->id);
                $o .= $output->display_review(
                    $review->id,
                    $this->get_user_fullname($review->userid),
                    $review->rating,
                    empty($review->review) ? get_string('noreview', 'block_rate_course') : '\''.$review->review.'\'',
                    $numlikes,
                    $courseid,
                    $review->userid,
                    $allowlike
                );
            }

        }
        $o .= $output->end_div();
        $o .= $output->start_tab_inner('liked');
        if($liked) {
            foreach($liked as $review) {
                $allowlike = $this->check_already_liked_review($USER->id, $review->id);
                $numlikes = $this->count_review_likes($review->id);
                $o .= $output->display_review(
                    $review->id,
                    $this->get_user_fullname($review->userid),
                    $review->rating,
                    empty($review->review) ? get_string('noreview', 'block_rate_course') : '\''.$review->review.'\'',
                    $numlikes,
                    $courseid,
                    $review->userid,
                    $allowlike
                );
            }

        }
        $o .= $output->end_div();
        $o .= $output->end_div();
        return $o;
    }

    /**
     * Insert a like record for a review.
     * @param $userid
     * @param $reviewid
     * @return bool|int
     */
    public function like_review($userid, $reviewid) {
        global $DB;
        $data = new stdClass();
        $data->userid = $userid;
        $data->reviewid = $reviewid;
        $data->timecreated = time();
        $data->timemodified = time();
        $data->id = $DB->insert_record('rate_course_review_likes', $data);
        $this->update_review_like_counter($reviewid);
        $this->trigger_like_review_event($reviewid, $data);
        return $data->id;
    }

    /**
     * Delete a review.
     * @param $reviewid
     * @return bool|int
     */
    public function delete_review($reviewid) {
        global $DB;
        $DB->delete_records('rate_course_review_likes', array('reviewid' => $reviewid));
        $DB->delete_records('rate_course_review_comments', array('review_id' => $reviewid));

        return $DB->delete_records('rate_course_review', array('id' => $reviewid));
    }

    /**
     * Delete a comment.
     * @param $commentid
     * @return bool|int
     */
    public function delete_comment($commentid) {
        global $DB;

        return $DB->delete_records('rate_course_review_comments', array('id' => $commentid));
    }

    /**
     * Update the like counter for a given review.
     * @param $reviewid
     */
    protected function update_review_like_counter($reviewid) {
        global $DB;
        $review = $this->get_review($reviewid);
        $review->reviewlikes++;
        $DB->update_record('rate_course_review', $review);
    }

    /**
     * Triggers the review_commented event when a user likes a review.
     * @param $reviewid
     * @param $data
     * @throws coding_exception
     */
    protected function trigger_like_review_event($reviewid, $data) {
        $review = $this->get_review($reviewid);
        $context = context_course::instance($review->course);
        $event = \block_rate_course\event\review_commented::create(array('context' => $context, 'objectid' => $data->id));
        $event->trigger();
    }

    /**
     * Insert a like record for a course.
     * @param $userid
     * @param $courseid
     * @return bool|int
     */
    public function like_course($userid, $courseid) {
        global $DB;
        $data = new stdClass();
        $data->userid = $userid;
        $data->course = $courseid;
        $data->timecreated = time();
        $data->timemodified = time();
        $data->id = $DB->insert_record('rate_course_course_likes', $data);
        $this->trigger_like_course_event($courseid);
        return $data->id;
    }

    /**
     * Insert a like record for a course.
     * @param $userid
     * @param $courseid
     * @return bool|int
     */
    public function unlike_course($userid, $courseid) {
        global $DB;
        $DB->delete_records('rate_course_course_likes', array('userid' => $userid, 'course' => $courseid));
        $this->trigger_unlike_course_event($courseid);
        return true;
    }

    /**
     * Triggers the course_commented event when a user likes a course.
     * @param $courseid
     * @throws coding_exception
     */
    protected function trigger_like_course_event($courseid) {
        $course = $this->get_course($courseid);
        $context = context_course::instance($course->id);
        $event = \block_rate_course\event\course_liked::create(array('context' => $context, 'objectid' => $course->id));
        $event->trigger();
    }

    /**
     * Triggers the course_commented event when a user likes a course.
     * @param $courseid
     * @throws coding_exception
     */
    protected function trigger_unlike_course_event($courseid) {
        $course = $this->get_course($courseid);
        $context = context_course::instance($course->id);
        $event = \block_rate_course\event\course_unliked::create(array('context' => $context, 'objectid' => $course->id));
        $event->trigger();
    }

    /**
     * Get a review record for given id.
     * @param $reviewid
     * @return mixed
     */
    protected function get_review($reviewid) {
        global $DB;
        return $DB->get_record('rate_course_review', array('id' => $reviewid));
    }

    /**
     * Get a course record for given id.
     * @param $courseid
     * @return mixed
     */
    protected function get_course($courseid) {
        global $DB;
        return $DB->get_record('course', array('id' => $courseid));
    }

    /**
     * Checks whether a course has already been liked by a given user.
     * @param $userid
     * @param $courseid
     * @return bool
     */
    protected function check_already_liked_course($userid, $courseid) {
        global $DB;
        return $DB->record_exists('rate_course_course_likes', array('userid' => $userid, 'course' => $courseid));
    }

    /**
     * Checks whether a review has already been liked by a given user.
     * @param $userid
     * @param $reviewid
     * @return bool
     */
    protected function check_already_liked_review($userid, $reviewid) {
        global $DB;
        return $DB->record_exists('rate_course_review_likes', array('userid' => $userid, 'reviewid' => $reviewid));
    }

    /**
     * Check if a user has already reviewed a course.
     * @param $userid
     * @param $courseid
     * @return bool
     */
    public function check_already_rated($userid, $courseid) {
        global $DB;
        return $DB->record_exists('rate_course_review', array('userid' => $userid, 'course' => $courseid));
    }

    /**
     * Check if a review has already been responded to.
     * @param $reviewid
     * @return bool
     */
    public function check_already_commented($reviewid) {
        global $DB;
        return $DB->record_exists('rate_course_review_comments', array('review_id' => $reviewid));
    }

    /**
     * Gets a review for a given user in a course.
     * @param $userid
     * @param $courseid
     * @return bool
     */
    public function get_user_review($userid, $courseid) {
        global $DB;
        return $DB->get_record('rate_course_review', array('userid' => $userid, 'course' => $courseid));
    }

    /**
     * Check if a course has already been recomemnded to a user.
     * @param int $userid the user who recommend the course
     * @param int $courseid the recommended course
     * @param int $useridto the user receive the recommendation
     * @param string $source the source of recommendation
     * @return bool
     */
    public static function check_already_recommended($userid, $courseid, $useridto, $source='') {
        global $DB;
        $params = array(
            'userid' => $userid,
            'course' => $courseid,
            'useridto' => $useridto,
        );
        if ($source) {
            $params['source'] = $source;
        }
        return $DB->record_exists('rate_course_recommendations', $params);
    }

    /**
     * Count the number of likes for a given review.
     * @param $reviewid
     * @return int
     */
    protected function count_review_likes($reviewid) {
        global $DB;
        return $DB->count_records('rate_course_review_likes', array('reviewid' => $reviewid));
    }

    /**
     * Count the number of recommendations a user has made for a course.
     * @param $userid
     * @param $courseid
     * @return int
     */
    protected function count_recommendations($userid, $courseid) {
        global $DB;
        return $DB->count_records('rate_course_recommendations', array('userid' => $userid, 'course' => $courseid));
    }

    /**
     * Displays the action buttons.
     * @param $courseid
     * @param bool $return
     * @return mixed
     */
    public function display_buttons($courseid, $return=false, $horizontal = 'left', $vertical = 'top') {
        global $USER, $PAGE, $DB;

        $output = $PAGE->get_renderer('block_rate_course');

        $canenrol = !$this->check_course_enrolment($USER->id, $courseid);

        $enrolids = array();

        if ($canenrol) {
            $canenrol = false;

            $enrols = enrol_get_plugins(true);
            $enrolinstances = enrol_get_instances($courseid, true);

            foreach ($enrolinstances as $enrolinstance) {
                //check if this enrolment allows self enrolment for the user
                if (method_exists($enrols[$enrolinstance->enrol], 'can_self_enrol')) {
                    if ($enrols[$enrolinstance->enrol]->can_self_enrol($enrolinstance) === true) {
                        $enrolids[] = $enrolinstance->id;
                        $canenrol = true;
                    }
                }
            }

            if (empty($this->config)) {
                $config = get_config('block_rate_course');
            } else {
                $config = $this->config;
            }

            if (!$canenrol && !empty($config->auto_create_self_enrol)) {
                // look for a public disabled self enrol instance
                $instance = $DB->get_record_sql("
                        SELECT e.*
                          FROM {enrol} e
                          JOIN {role} r
                            ON (r.shortname = 'student'
                           AND r.id = e.roleid)
                         WHERE courseid = :courseid
                           AND enrol = 'self'
                           AND customint5 IS NULL
                           AND status = :status",
                        array('courseid' => $courseid,
                                'status' => ENROL_INSTANCE_DISABLED));

                if (!$instance) {
                    $instance = new stdClass();
                    $instance->enrol = 'self';
                    $instance->name = 'Rate course auto self enrol';
                    $instance->courseid = $courseid;
                    $instance->timecreated = time();
                    $instance->timemodified = time();
                    $instance->expirythreshold = DAYSECS;
                    $instance->roleid = $DB->get_field('role', 'id', array('shortname' => 'student'));
                    $instance->password = '';
                    $instance->customint6 = true; // Allow new enrolments
                    $instance->status = ENROL_INSTANCE_ENABLED;
                    $instance->id = $DB->insert_record('enrol', $instance, true);
                    //retrieve the instance again
                    $instance = $DB->get_record('enrol', array('id' => $instance->id));
                } else {
                    $instance->status = ENROL_INSTANCE_ENABLED;
                    $DB->update_record('enrol', $instance);
                }

                $enrolids[] = $instance->id;
            }
        }

        $canunenrol = !$canenrol ? $this->check_course_selfenrolment($USER->id, $courseid) : false;

        $liked = $this->check_already_liked_course($USER->id, $courseid);

        $numrecommends = $this->count_recommendations($USER->id, $courseid);

        $review = $this->get_user_review($USER->id, $courseid);
        if($review) {
            $review->liked = $this->check_already_liked_review($USER->id, $review->id);
            $review->numlikes = $this->count_review_likes($review->id);
        }


        $rate_course_config = get_config('block_rate_course');
        $o = $output->display_buttons($courseid, $canenrol, $liked, $numrecommends, $review, $canunenrol, $horizontal, $vertical, $enrolids, $rate_course_config);


        if ($return) {
            return $o;
        }
        echo $o;
    }

    /**
     * Check whether a user is already enrolled to a specific course.
     * @param $userid
     * @param $courseid
     * @return bool
     */
    protected function check_course_enrolment($userid, $courseid) {
        global $DB;
        $sql = "SELECT * ".
            "FROM {user_enrolments} ue INNER JOIN {enrol} e ON ue.enrolid = e.id ".
            "WHERE ue.userid = :userid AND e.courseid = :courseid AND e.status = 0 AND ue.status = 0";
        return $DB->record_exists_sql($sql, array('userid' => $userid, 'courseid' => $courseid));
    }

    /**
     * Check whether a user is already enrolled to a specific course.
     * @param $userid
     * @param $courseid
     * @return bool
     */
    protected function check_course_selfenrolment($userid, $courseid) {
        global $DB;
        $sql = "SELECT * ".
                "FROM {user_enrolments} ue INNER JOIN {enrol} e ON ue.enrolid = e.id ".
                "WHERE ue.userid = :userid AND e.courseid = :courseid AND e.enrol = 'self'";
        return $DB->record_exists_sql($sql, array('userid' => $userid, 'courseid' => $courseid));
    }

    /**
     * Gets a list of users that a course can be recommended to and disables those already recommended.
     * @param $courseid
     * @param bool $query
     * @param int $limit
     * @return array
     */
    public function get_recommendee_list($courseid, $query=false, $limit=10) {
        global $USER;

        $config = get_config('block_rate_course');

        $users = (new builder())->from('user', 'u')
            ->where('u.username', '<>', 'guest')
            ->where('u.deleted', '=', 0)
            ->where('u.suspended', '=', 0)
            ->where('u.auth', '<>', 'nologin')
            ->where('u.id', '<>', $USER->id)
            ->when(
                true,
                function (builder $builder) {
                    global $USER;
                    tenant_orm_helper::restrict_users($builder, 'u.id', context_user::instance($USER->id));
                }
            )
            ->when(
                $query,
                function (builder $builder) use ($query) {
                    global $DB;
                    $builder->where_raw(
                        $DB->sql_fullname('u.firstname', 'u.lastname') . 'ILIKE :query',
                        ['query' => "%{$query}%"]
                    );
                }
            )
            ->when(
                !empty($config->limitrecommend),
                function (builder $builder) use ($config) {
                    global $USER;
                    $builder
                        ->select(['u.*', 'd1.data'])
                        ->join(['user_info_data', 'd1'], 'u.id', '=', 'd1.userid')
                        ->join(
                            ['user_info_data', 'd2'],
                            function (builder $joining) {
                                $joining
                                    ->where_field('d2.fieldid', 'd1.fieldid')
                                    ->where_field('d1.data', 'd2.data');
                            }
                        )
                        ->where('d2.fieldid', $config->limitrecommend)
                        ->where('d2.userid', '=', $USER->id);
                }
            )
            ->get();

        $userlist = array();
        if($users->valid()) {
            foreach($users as $user) {
                if(!is_siteadmin($user)) {
                    if(self::check_already_recommended($USER->id, $courseid, $user->id)) {
                        $userlist[] = array("id" => $user->id, "text" => fullname($user), 'disabled' => true);
                    } else {
                        $userlist[] = array("id" => $user->id, "text" => fullname($user));
                    }
                }
            }
        } else {
            $userlist[] = array("id" => "0","text"=>"No Results Found..");
        }

        return $userlist;
    }

}
