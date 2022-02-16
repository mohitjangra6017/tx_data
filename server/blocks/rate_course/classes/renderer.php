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
 * Renderer
 *
 * @package    block
 * @subpackage rate_course
 * @copyright  &copy; 2014 Nine Lanterns Pty Ltd  {@link http://www.ninelanterns.com.au}
 * @author     james.ballard
 * @version    1.0
 */

class block_rate_course_renderer extends plugin_renderer_base {

    /**
     * Displays organised tabs for reviews.
     * @return string
     * @throws coding_exception
     */
    public function display_review_tabs() {
        $o = html_writer::start_tag('ul', array('id' => 'course-review-tab',
            'class' => 'nav nav-tabs', 'role' => 'tablist'));
        $o .= html_writer::tag('li',
                html_writer::link(new moodle_url('#recent'),
                    get_string('mostrecent', 'block_rate_course'),
                    array('role' => 'tab', 'data-toggle' => 'tab')
                ),
                array('class' => 'active')
            );
        $o .= html_writer::tag('li',
                html_writer::link(new moodle_url('#liked'),
                    get_string('mostliked', 'block_rate_course'),
                    array('role' => 'tab', 'data-toggle' => 'tab')
                )
            );
        $o .= html_writer::end_tag('ul');
        return $o;
    }

    /**
     * Starts a tab content area.
     * @param $id
     * @return string
     */
    public function start_tab_content($id) {
        return html_writer::start_div('tab-content', array('id' => $id));
    }

    /**
     * Closes a div
     * @return string
     */
    public function end_div() {
        return html_writer::end_div();
    }

    /**
     * Starts inner tab content to be switched.
     * @param $id
     * @param bool $active
     * @return string
     */
    public function start_tab_inner($id, $active=false) {
        $class = $active ? ' active in' : '';
        return html_writer::start_div("tab-pane fade$class", array('id' => $id));
    }

    /**
     * Display an individual review from given information.
     *
     * @param $id
     * @param $author
     * @param $numstars
     * @param $content
     * @param $liked
     * @return string
     * @throws coding_exception
     */
    public function display_review($id, $author, $numstars, $content, $numlikes, $courseid, $userid, $liked = false) {
        global $CFG, $DB;
        
        require_once $CFG->dirroot.'/blocks/rate_course/block_rate_course.php';
        
        $o = html_writer::start_div('well well-sm course-review-display');
        $o .= html_writer::tag('p', $this->display_star_rating($numstars).$author,
            array('class' => 'course-review-rating'));
        
        $o .= html_writer::tag('p',"$content", array('class' => 'course-review-review'));
        
        $comment = $DB->get_record('rate_course_review_comments', array('review_id' => $id));
        
        if (!empty($comment)) {
            $commentuser = $DB->get_record('user', array('id' => $comment->user_id));
            $o .= html_writer::start_div('comments-div'); 
                $o .= html_writer::div('<span class="replyby">'.get_string('replyby', 'block_rate_course').'</span> '.fullname($commentuser).' <span class="title">(Admin)</span>', 'comments-fullname');
                $o .= html_writer::div($comment->comment, 'comments-comment');
                if (has_capability('block/rate_course:delete_comment', context_course::instance($courseid))) {
                    $o .= html_writer::tag('p',
                        html_writer::link(
                            new moodle_url('/blocks/rate_course/delete_comment.php', array('commentid' => $comment->id, 'post' => 1)),
                            get_string('delete')
                        ),
                        array());
                }
            $o .= html_writer::end_div();
        }
        
        if(!$liked) {
            $o .= $this->display_review_like_link($id, $numlikes);
        } else {
            $o .= $this->display_review_liked_text($numlikes);
        }

        if (has_capability('block/rate_course:delete', context_course::instance($courseid))) {
            $o .= $this->display_review_delete_button($id, $numlikes);
        }
        
        $config = get_config('block_rate_course');
        
        if (!empty($config->enablecomments) && has_capability('block/rate_course:comment', context_course::instance($courseid)) && empty($comment)) {
            $o .= $this->display_review_comment_button($id);
        }
        
        $o .= html_writer::div('', 'clearfix');

        $o .= html_writer::end_div();
        return $o;
    }

    /**
     * Display an individual review for the current user.
     *
     * @param $id
     * @param $numstars
     * @param $content
     * @param $liked
     * @return string
     * @throws coding_exception
     */
    public function display_myreview($id, $numstars, $content,  $numlikes, $liked=false) {
        $o = html_writer::start_div('well well-sm my-course-review-display');
        $o .= html_writer::tag('p', get_string('completed', 'block_rate_course').$this->display_star_rating($numstars),
            array('class' => 'course-review-rating'));
        $o .= html_writer::tag('p',"'$content'", array('class' => 'course-review-review'));
        
        if(!$liked) {
            $o .= $this->display_review_like_link($id, $numlikes);
        } else {
            $o .= $this->display_review_liked_text($numlikes);
        }

        $o .= html_writer::end_div();
        return $o;
    }

    /**
     * Return a link to like a review.
     * @param $reviewid
     * @return string
     * @throws coding_exception
     */
    protected function display_review_like_link($reviewid, $numlikes) {
        return html_writer::tag('p',
            html_writer::link(
                new moodle_url('/blocks/rate_course/like_review.php', array('reviewid' => $reviewid, 'post' => 1)),
                $this->thumbsup().' '.get_string('finduseful', 'block_rate_course').' ('.$numlikes.')',
                array('onClick' => "block_rate_course_like_review($reviewid); return false;")
            ),
            array('id' => "course-review-$reviewid-like"));
    }

    /**
     * Return a button link to like a course.
     * @param $courseid
     * @return string
     * @throws coding_exception
     */
    public function display_course_like_button($courseid, $like=1) {
        $class = $like ? '' : ' course-review-liked';
        $thumb = $like ? 'fa-thumbs-o-up' : 'fa-thumbs-up';
        $tooltip = $like ? 'Like this course' : 'Unlike this course';
        return html_writer::link(
                new moodle_url('/blocks/rate_course/like_course.php', array('courseid' => $courseid, 'post' => 1, 'like' => $like)),
                html_writer::tag('i', '', array('class' => "fa $thumb fa-lg")),
                array('class' => 'btn btn-default'.$class, 'onClick' => "block_rate_course_like_course($courseid,$like); return false;")
            );
    }

    /**
     * Displays text to indicate that the user has already liked the review.
     * @param $numlikes
     * @return string
     * @throws coding_exception
     */
    public function display_review_liked_text($numlikes=1) {
        $numlikes--;
        $string = 'founduseful';
        if($numlikes == 0) {
            $string = 'foundusefulsingle';
        }
        if($numlikes == 1) {
            $string = 'foundusefulcouple';
        }
        return html_writer::tag('p',
            $this->thumbsup().' '.get_string($string, 'block_rate_course', $numlikes),
            array('class' => "course-review-liked"));
    }

    /**
     * Displays button to delete the review.
     * @param $numlikes
     * @return string
     * @throws coding_exception
     */
    public function display_review_delete_button ($reviewid, $numlikes=1) {
        return html_writer::tag('p',
            html_writer::link(
                new moodle_url('/blocks/rate_course/delete_review.php', array('reviewid' => $reviewid, 'post' => 1)),
                get_string('delete')
            ),
            array('class' => "course-review-delete"));
    }
    
    /**
     * Displays button to comment on the review.
     * @param $reviewid
     * @return string
     * @throws coding_exception
     */
    public function display_review_comment_button ($reviewid) {
        global $PAGE;
        
        $o = html_writer::tag('a',
            get_string('comment', 'block_rate_course'),
            array('id' => 'review-comment', 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'));
        $o .= html_writer::start_tag('ul', array('class' => 'dropdown-menu', 'role' => 'menu', 'aria-labelled-by' => 'btn-review-rate'));
        $o .= html_writer::start_tag('li');

        $o .= html_writer::start_tag('h2');
        $o .= html_writer::start_tag('span', array('class' => 'fa-stack fa-lg'));
        $o .= html_writer::tag('i', '', array('class' => 'fa fa-circle fa-stack-2x'));
        $o .= html_writer::tag('i', '', array('class' => 'fa fa-desktop fa-stack-1x fa-inverse'));
        $o .= html_writer::end_tag('span');
        $o .= ' '.get_string('commentheader', 'block_rate_course');
        $o .= html_writer::end_tag('h2');

        $o .= html_writer::start_tag('form', array('action' => '/blocks/rate_course/comment.php',
            'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post'));
        $o .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'reviewid', 'value' => $reviewid));
        $o .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'external', 'value' => true));
        $o .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'redirecturl', 'value' => $PAGE->url));

        $o .= html_writer::start_div('form-group');
        $o .= html_writer::tag('label', get_string('comment', 'block_rate_course'),
                array('class' => 'col-sm-3 control-label', 'for' => 'comment'));
        $o .= html_writer::start_div('col-sm-9');
        $o .= html_writer::tag('textarea', '', array('id' => 'comment', 'name' => 'comment',
            'class' => 'courserating', 'maxlength' => 140));
        $o .= html_writer::end_div();
        $o .= html_writer::end_div();

        $o .= html_writer::start_div('form-group');
        $o .= html_writer::start_div('col-sm-offset-3 col-sm-9');
        $o .= html_writer::tag('button', get_string('submitcomment', 'block_rate_course'),
            array('type' => 'submit', 'class' => 'btn btn-primary'));
        $o .= html_writer::end_div();
        $o .= html_writer::end_div();

        $o .= html_writer::end_tag('form');
        
        $o .= html_writer::end_tag('li');
        $o .= html_writer::end_tag('ul');
        
        return $o;
    }

    /**
     * Provides a thumbs up glyphicon to indicate a like.
     * @return string
     */
    protected function thumbsup() {
        return html_writer::tag('span', '', array('class' => 'glyphicon glyphicon-thumbs-up'));
    }

    /**
     * Displays single a star rating f
     *
     * @param $numstars
     * @return string
     */
    public function display_star_rating($numstars, $size='xs') {
        return html_writer::empty_tag('input', array(
            'class' => 'block_rate_course_show_rating form-control',
            'value' => $numstars,
            'data-size' => $size,
            'type' => 'hidden'
        ));
    }

    /**
     * Displays Average Rating as a single line (label/value/sample) based on given number of stars and ratings.
     *
     * @param $numstars - the number of stars to fill
     * @param $numratings - the number of ratings made
     * @param $size - xs, sm, md, lg, xl
     * @return string
     * @throws coding_exception
     */
    public function display_average_rating($numstars, $numratings, $size, $withtext = true) {
        $o = html_writer::start_span('ratingavg');
        if ($withtext) {
            $o .= get_string('avgrating', 'block_rate_course') . ': ';
        }
        $o .= $this->display_star_rating($numstars, $size);
        $o .= html_writer::tag('span', " ($numratings)", array('id' => 'ratingcount'));
        $o .= html_writer::end_span();
        return $o;
    }

    /**
     * Displays a single line (label/value) for a given number of course completion.
     *
     * @param $numcompletions
     * @return string
     * @throws coding_exception
     */
    public function display_course_completions($numcompletions) {
        return html_writer::tag('p', get_string('completions', 'block_rate_course') . ': ' . $numcompletions, array('id' => 'rating-completion-count'));
    }

    /**
     * Display a set of buttons for social interaction within a course.
     * @param $courseid
     * @param bool $enrol
     * @return string
     */
    public function display_buttons($courseid, $enrol=false, $liked=false, $numrecommends=0, $review=false, $unenrol=false, $horizontal = 'left', $vertical = 'top', $enrolids = array(), $config = null) {


        $o = html_writer::start_div('btn-toolbar course-review-buttons');

        $buttongroup = 'btn-group ';
        if ($vertical === 'top') {
            $buttongroup .= 'dropup';
        }

        $dropdownmenu = 'dropdown-menu dropdown-menu-'.$horizontal;
        if ($enrol && !empty($enrolids)) {
            $o .= html_writer::start_div($buttongroup, array('data-toggle' => 'tooltip', 'data-placement' => 'bottom', 'title' => 'Enrol in this course'));
            $o .= html_writer::tag('button',
                html_writer::tag('i','', array('class' => 'fa fa-graduation-cap fa-lg')) . ' ' . get_string('getstarted', 'block_rate_course'),
                array('id' => 'btn-enrol', 'class' => 'btn btn-default dropdown-toggle', 'data-toggle' => 'dropdown'));
            $o .= html_writer::start_tag('ul', array('class' => $dropdownmenu, 'role' => 'menu',
                'aria-labelled-by' => 'btn-enrol'));
            $o .= html_writer::start_tag('li');
            $o .= $this->enrol_course_form($courseid, $enrolids);
            $o .= html_writer::end_tag('li');
            $o .= html_writer::end_tag('ul');
            $o .= html_writer::end_div();
        } else if ($unenrol){
            $o .= html_writer::start_div($buttongroup, array('data-toggle' => 'tooltip', 'data-placement' => 'bottom', 'title' => 'Unenrol from this course'));
            $o .= html_writer::tag('button',
                html_writer::tag('i','', array('class' => 'fa fa-graduation-cap fa-lg')) . ' ' . get_string('unenrol', 'block_rate_course'),
                array('id' => 'btn-enrol-enrolled', 'class' => 'btn btn-default dropdown-toggle', 'data-toggle' => 'dropdown'));
            $o .= html_writer::start_tag('ul', array('class' => $dropdownmenu, 'role' => 'menu',
                'aria-labelled-by' => 'btn-enrol'));
            $o .= html_writer::start_tag('li');
            $o .= $this->unenrol_course_form($courseid);
            $o .= html_writer::end_tag('li');
            $o .= html_writer::end_tag('ul');
            $o .= html_writer::end_div();
        } else {
            $o .= html_writer::start_div($buttongroup);
            $o .= html_writer::tag('button',
                    html_writer::tag('i','', array('class' => 'fa fa-graduation-cap fa-lg')) . ' ' . get_string('getstarted', 'block_rate_course'),
                    array('class' => 'btn btn-default', 'disabled' => 'disabled'));
            $o .= html_writer::end_div();
        }


        $tooltiplike = $liked ? 'Unlike this course' : 'Like this course';
        $o .= html_writer::start_div('btn-group rate-course-like-course', array('data-toggle' => 'tooltip', 'data-placement' => 'bottom', 'title' => $tooltiplike));
        if (!$liked) {
            $o .= $this->display_course_like_button($courseid, 1);
        } else {
            $o .= $this->display_course_like_button($courseid, 0);
        }
        $o .= html_writer::end_div();
        
        if(!empty($config) && !empty($config->enable_course_suggestions)){
            $o .= html_writer::start_div($buttongroup, array('data-toggle' => 'tooltip', 'data-placement' => 'bottom', 'title' => 'Recommend this course'));
            $o .= html_writer::link(new moodle_url('#'),
                html_writer::tag('i', '', array('class' => 'fa fa-mail-forward fa-lg')),
                array('id' => 'btn-review-recommend', 'class' => 'btn btn-default dropdown-toggle', 'data-toggle' => 'dropdown'));
            $o .= html_writer::start_tag('ul', array('class' => $dropdownmenu, 'role' => 'menu', 'aria-labelled-by' => 'btn-review-recommend'));
            $o .= html_writer::start_tag('li');
            $o .= $this->recommend_course_form($courseid, $numrecommends);
            $o .= html_writer::end_tag('li');
            $o .= html_writer::end_tag('ul');
            $o .= html_writer::end_div();
        }


        $o .= html_writer::start_div($buttongroup, array('data-toggle' => 'tooltip', 'data-placement' => 'bottom', 'title' => 'Rate this course'));
        $o .= html_writer::tag('button',
            html_writer::tag('i', '', array('class' => 'fa fa-star fa-lg')),
            array('id' => 'btn-review-rate', 'class' => 'btn btn-default dropdown-toggle', 'data-toggle' => 'dropdown'));
        $o .= html_writer::start_tag('ul', array('class' => $dropdownmenu, 'role' => 'menu', 'aria-labelled-by' => 'btn-review-rate'));
        $o .= html_writer::start_tag('li');
        if(!$review) {
            $o .= $this->rate_course_form($courseid);
        } else {
            $o .= $this->display_myreview($review->id, $review->rating,$review->review, $review->liked, $review->numlikes);
        }
        $o .= html_writer::end_tag('li');
        $o .= html_writer::end_tag('ul');
        $o .= html_writer::end_div();

        $o .= html_writer::end_div();
        return $o;
    }

    /**
     * Display the enrol course form for use in drop-down display.
     * @param $courseid
     * @return string
     * @throws coding_exception
     */
    public function enrol_course_form($courseid, $enrolids) {
        global $PAGE;

        $o = html_writer::start_tag('h2');
        $o .= html_writer::start_tag('span', array('class' => 'fa-stack fa-lg'));
        $o .= html_writer::tag('i', '', array('class' => 'fa fa-circle fa-stack-2x'));
        $o .= html_writer::tag('i', '', array('class' => 'fa fa-desktop fa-stack-1x fa-inverse'));
        $o .= html_writer::end_tag('span');
        $o .= ' '.get_string('enrol', 'block_rate_course');
        $o .= html_writer::end_tag('h2');

        $o .= html_writer::start_tag('form', array('action' => '/blocks/rate_course/enrol.php',
            'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post'));
        $o .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'courseid', 'value' => $courseid));
        $o .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'external', 'value' => true));
        $o .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'redirecturl', 'value' => $PAGE->url));
        $enrolids = json_encode($enrolids);
        $o .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'enrolids', 'value' => $enrolids));

        $o .= html_writer::start_div('form-group');
        $o .= html_writer::tag('label', get_string('startdate', 'block_rate_course'),
            array('class' => 'col-sm-3 control-label', 'for' => 'useridto'));
        $o .= html_writer::start_div('col-sm-9');
        $o .= html_writer::start_div('input-group ');
        $value = date('d/m/Y');
        $o .= html_writer::empty_tag('input', array('type' => 'text', 'name' => 'startdate',
            'class' => 'form-control datepicker', 'placeholder' => 'Select start date', 'value' => $value, 'style' => 'position:relative;z-index:1000'));

        $o .= html_writer::tag('span', html_writer::tag('span', '', array('class' => "glyphicon glyphicon-calendar")),
            array('class' => "input-group-addon"));
        $o .= html_writer::end_div();
        $o .= html_writer::end_div();
        $o .= html_writer::end_div();

        $o .= html_writer::start_div('form-group');
        $o .= html_writer::start_div('col-sm-offset-3 col-sm-9');
        $o .= html_writer::tag('button', get_string('enrol', 'block_rate_course'),
            array('type' => 'submit', 'class' => 'btn btn-primary'));
        $o .= html_writer::end_div();
        $o .= html_writer::end_div();

        $o .= html_writer::end_tag('form');
        return $o;
    }

    /**
     * Display the unenrol course form for use in drop-down display.
     * @param $courseid
     * @return string
     * @throws coding_exception
     */
    public function unenrol_course_form($courseid) {
        global $PAGE;

        $o = html_writer::start_tag('h2');
        $o .= html_writer::start_tag('span', array('class' => 'fa-stack fa-lg'));
        $o .= html_writer::tag('i', '', array('class' => 'fa fa-circle fa-stack-2x'));
        $o .= html_writer::tag('i', '', array('class' => 'fa fa-desktop fa-stack-1x fa-inverse'));
        $o .= html_writer::end_tag('span');
        $o .= ' '.get_string('unenrol', 'block_rate_course');
        $o .= html_writer::end_tag('h2');

        $o .= html_writer::start_tag('form', array('action' => '/blocks/rate_course/unenrol.php',
                'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post'));
        $o .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'courseid', 'value' => $courseid));
        $o .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'external', 'value' => true));
        $o .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'redirecturl', 'value' => $PAGE->url));
        $o .= html_writer::div(get_string('unenroltext','block_rate_course'));

        $o .= html_writer::start_div('form-group');
        $o .= html_writer::start_div('col-sm-offset-3 col-sm-9');
        $o .= html_writer::tag('button', get_string('unenrol', 'block_rate_course'),
                array('type' => 'submit', 'class' => 'btn btn-primary'));
        $o .= html_writer::end_div();
        $o .= html_writer::end_div();

        $o .= html_writer::end_tag('form');
        return $o;
    }

    /**
     * Display the recommend course form for use in drop-down display.
     * @param $courseid
     * @return string
     * @throws coding_exception
     */
    protected function recommend_course_form($courseid, $numrecommends) {
        global $PAGE;

        $o = html_writer::start_tag('h2');
        $o .= html_writer::start_tag('span', array('class' => 'fa-stack fa-lg'));
        $o .= html_writer::tag('i', '', array('class' => 'fa fa-circle fa-stack-2x'));
        $o .= html_writer::tag('i', '', array('class' => 'fa fa-desktop fa-stack-1x fa-inverse'));
        $o .= html_writer::end_tag('span');
        $o .= ' '.get_string('recommendcourse', 'block_rate_course');
        $o .= html_writer::end_tag('h2');

        $string = 'myrecommendations';
        if($numrecommends == 1) {
            $string = 'myrecommendationssingle';
        }
        $o .= html_writer::tag('p', get_string($string, 'block_rate_course', $numrecommends));

        $o .= html_writer::start_tag('form', array('action' => '/blocks/rate_course/recommend.php',
            'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post'));
        $o .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'courseid', 'value' => $courseid));
        $o .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'external', 'value' => true));
        $o .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'redirecturl', 'value' => $PAGE->url));

        $o .= html_writer::start_div('form-group');
        $o .= html_writer::tag('label', get_string('user'),
            array('class' => 'col-sm-3 control-label', 'for' => 'useridto'));
        $o .= html_writer::start_div('col-sm-9');
        $o .= html_writer::empty_tag('input', array('type' => 'text', 'name' => 'useridto', 'class' => 'userselect2'));
        $o .= html_writer::end_div();
        $o .= html_writer::end_div();

        $o .= html_writer::start_div('form-group');
        $o .= html_writer::start_div('col-sm-offset-3 col-sm-9');
        $o .= html_writer::tag('button', get_string('submitrecommendation', 'block_rate_course'),
            array('type' => 'submit', 'class' => 'btn btn-primary'));
        $o .= html_writer::end_div();
        $o .= html_writer::end_div();

        $o .= html_writer::end_tag('form');
        return $o;
    }

    /**
     * Display the review course form for use in drop-down display.
     * @param $courseid
     * @return string
     * @throws coding_exception
     */
    protected function rate_course_form($courseid) {
        global $PAGE;

        $o = html_writer::start_tag('h2');
        $o .= html_writer::start_tag('span', array('class' => 'fa-stack fa-lg'));
        $o .= html_writer::tag('i', '', array('class' => 'fa fa-circle fa-stack-2x'));
        $o .= html_writer::tag('i', '', array('class' => 'fa fa-desktop fa-stack-1x fa-inverse'));
        $o .= html_writer::end_tag('span');
        $o .= ' '.get_string('courserating', 'block_rate_course');
        $o .= html_writer::end_tag('h2');

        $o .= html_writer::start_tag('form', array('action' => '/blocks/rate_course/rate.php',
            'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post'));
        $o .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'courseid', 'value' => $courseid));
        $o .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'external', 'value' => true));
        $o .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'redirecturl', 'value' => $PAGE->url));

        $o .= html_writer::start_div('form-group');
        $o .= html_writer::tag('label', get_string('rating', 'block_rate_course'),
                array('class' => 'col-sm-3 control-label', 'for' => 'rating'));
        $o .= html_writer::start_div('col-sm-9');
        $o .= html_writer::empty_tag('input', array('id' => 'rating', 'type' => 'text', 'name' => 'rating',
            'class' => 'form-control rating', 'data-step' => 1));
        $o .= html_writer::end_div();
        $o .= html_writer::end_div();

        $o .= html_writer::start_div('form-group');
        $o .= html_writer::tag('label', get_string('review', 'block_rate_course'),
                array('class' => 'col-sm-3 control-label', 'for' => 'review'));
        $o .= html_writer::start_div('col-sm-9');
        $o .= html_writer::tag('textarea', '', array('id' => 'review', 'name' => 'review',
            'class' => 'courserating', 'maxlength' => 140));
        $o .= html_writer::end_div();
        $o .= html_writer::end_div();

        $o .= html_writer::start_div('form-group');
        $o .= html_writer::start_div('col-sm-offset-3 col-sm-9');
        $o .= html_writer::tag('button', get_string('submitrating', 'block_rate_course'),
            array('type' => 'submit', 'class' => 'btn btn-primary'));
        $o .= html_writer::end_div();
        $o .= html_writer::end_div();

        $o .= html_writer::end_tag('form');
        return $o;
    }
}