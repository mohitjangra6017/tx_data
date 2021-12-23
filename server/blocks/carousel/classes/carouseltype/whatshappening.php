<?php

namespace block_carousel\carouseltype;

use block_carousel\constants;
use \block_carousel\helper;

class whatshappening extends base
{
    function generate($data)
    {
        global $PAGE, $USER, $DB, $CFG;
        $renderer = $PAGE->get_renderer('block_carousel');

        $timeline = [];

        $config = $this->get_blockplugin_config('whatshappening');

        $card_size = $data['cardsize'];

        $strip_title_length = ($card_size == \block_carousel\constants::BKC_CARD_LARGE) ?
            \block_carousel\constants::BKC_TITLE_LENGHT_LARGE_CARD :
            \block_carousel\constants::BKC_TITLE_LENGHT_SMALL_CARD;
        $strip_inner_card_title_lenght = \block_carousel\constants::BKC_TITLE_LENGHT_SMALL_CARD;

        require_once($CFG->dirroot . '/blocks/event_feed/classes/carousel_event_feed.php');

        $filter = empty($this->config->whatshappening_filter)
            ? 2
            : $this->config->whatshappening_filter; // default filter to 'social'

        $page = 0;

        $specificevents = (!empty($config->eventsmodeselect) && $config->eventsmodeselect == 'specific')
            ? $config->eventselect
            : null;
        $timeline = \block_event_feed\event_feed::fetch($filter, $config, $page, true, $specificevents);

        foreach ($timeline as $periods => $items) {
            foreach ($items as $item) {
                // get user data
                $user = $DB->get_record('user', ['id' => $item->userid]);
                $userpicture = new \user_picture($user);
                $userpicture->size = ['100%', '100%'];

                // User custom fields in the config
                // for cards
                $userfield_shortnames = helper::get_carousel_custom_field_shortnames($this->config, 'usercustomfields');
                $user_custom_fields = helper::get_user_custom_field_data($user->id, $userfield_shortnames);

                // for details
                $userfield_shortnames_details = helper::get_carousel_custom_field_shortnames($this->config, 'usercustomfieldsdetails');
                $user_custom_fields_details = helper::get_user_custom_field_data($user->id, $userfield_shortnames_details);

                // get course data
                $course = $DB->get_record('course', ['id' => $item->course]);

                $isreview = (!empty($item->eventname) && $item->eventname == '\block_rate_course\event\review_created');

                // VODHAS-1990
                // caption icon
                $card_icon_class = 'far fa-check-square';
                if ($isreview) {
                    $card_icon_class = 'fas fa-star';
                }
                $courselike_event = (!empty($item->eventname) && $item->eventname == '\block_rate_course\event\course_liked');
                if ($courselike_event) {
                    $card_icon_class = 'far fa-thumbs-up';
                }

                $stardisplay = '';
                $reviewreply = '';
                if ($isreview) {
                    $stardata = [];
                    for ($i = 0; $i < (int)$item->object->rating; $i++) {
                        $stardata['fullstars'][] = true;
                    }

                    $stardisplay = $renderer->render_star_ratings($stardata);

                    $replycomment = $DB->get_record('rate_course_review_comments', ['review_id' => $item->object->id]);

                    if (!empty($replycomment)) {
                        $reviewreply = '<div class="reviewreply">';
                        $reviewreply .= '<div class="reviewreply-user">';
                        $replyuser = $DB->get_record('user', ['id' => $replycomment->user_id]);
                        $reviewreply .= '<strong>Reply by</strong> ' . fullname($replyuser) . '(Admin)';
                        $reviewreply .= '</div>';
                        $reviewreply .= '<div class="reviewreply-reply">';
                        $reviewreply .= '"' . $replycomment->comment . '"';
                        $reviewreply .= '</div>';
                        $reviewreply .= '</div>';
                    }

                    $reviewliked = $DB->record_exists(
                        'rate_course_review_likes',
                        ['userid' => $USER->id, 'reviewid' => $item->object->id]
                    );

                    if ($reviewliked) {
                        $likereviewurl = '<div class="like-review"><i class="fas fa-thumbs-up"></i> I found this review useful (' .
                            $item->object->reviewlikes .
                            ')</div>';
                    } else {
                        $likereviewurl = '<div class="like-review"><a href="/course/format/concertina/like_review.php?reviewid=' .
                            $item->object->id .
                            '&json=1"><i class="far fa-thumbs-up"></i> I find this review useful</a> (' .
                            $item->object->reviewlikes .
                            ')</div>';
                    }
                }

                $slide = $this->get_course_slide_data(
                    $course,
                    get_string('type:' . \block_carousel\constants::BKC_WHATS_HAPPENING, 'block_carousel'),
                    $data
                );
                $slide['userpix'] = $userpicture->get_url($PAGE)->out();
                $slide['custom_fields'][] = $item->time;
                $slide['time'] = $item->time;
                $slide['user_custom_fields'] = $user_custom_fields;
                $slide['user_custom_fields_details'] = $user_custom_fields_details;

                if ($item->eventname == "\\block_rate_course\\event\\course_liked") {
                    $slide['fulltitle'] = $slide['title'] = $user->firstname . ' liked the course ' . $course->fullname;
                } else {
                    $slide['fulltitle'] = $slide['title'] = strip_tags($item->title);
                }

                $slide['innercoursecardtitle'] = substr(strip_tags($course->fullname), 0, $strip_inner_card_title_lenght) .
                    (strlen($course->fullname) > $strip_inner_card_title_lenght ? '...' : '');
                $slide['likebuttonid'] = 'whatshappening-' . $item->logid;
                $slide['liked'] = ($item->liked) ? 'liked' : '';
                $slide['likeurl'] = $CFG->wwwroot . '/blocks/event_feed/like_event.php?eventid=' . $item->logid . '&like=1&ajax=1';
                $slide['unlikeurl'] = $CFG->wwwroot . '/blocks/event_feed/like_event.php?eventid=' . $item->logid . '&like=0&ajax=1';
                $slide['followed'] = $item->followed;
                $slide['followuserid'] = $item->userid;
                $slide['followredirecturl'] = $PAGE->url;
                $slide['hidesocial'] = ($USER->id == $item->userid);
                $slide['hide_goto_course_btn'] = true;
                $slide['isreview'] = $isreview;
                $slide['stardisplay'] = $stardisplay;
                $slide['card_icon_class'] = $card_icon_class;
                if ($isreview) {
                    $slide['summary'] = '"' . $item->object->review . '"';
                    $slide['fullsummary'] = '"' . $item->object->review . '"';
                    $slide['reviewreply'] = $reviewreply;
                    $slide['likereviewurl'] = $likereviewurl;
                }
                $data['slides'][] = $slide;
            }
        }

        $data['title'] = empty($data['title'])
            ? get_string('type:' . \block_carousel\constants::BKC_WHATS_HAPPENING, 'block_carousel')
            : $data['title'];

        $data['template'] = $data['template'] . '_events';

        return $renderer->render_whatshappening($data);
    }

    public static function extend_form(\MoodleQuickForm $form, \stdClass $block_instance)
    {
        global $CFG, $DB;
        if (!file_exists($CFG->dirroot . '/blocks/event_feed/version.php')) {
            return;
        }

        require_once($CFG->dirroot . '/blocks/event_feed/block_event_feed.php');

        $block = new \block_event_feed();

        $filters = [
            $block::BLOCK_EVENT_FEED_FILTER_SOCIAL => 'Social',
            $block::BLOCK_EVENT_FEED_FILTER_PROFILE => 'Profile',
            $block::BLOCK_EVENT_FEED_FILTER_NEWS => 'News',
            $block::BLOCK_EVENT_FEED_FILTER_FOLLOW => 'Following',
            $block::BLOCK_EVENT_FEED_FILTER_ACHIEVEMENT => 'Achievements',
            $block::BLOCK_EVENT_FEED_FILTER_TEAM => 'Team',
            $block::BLOCK_EVENT_FEED_FILTER_GROUP => 'Group',
        ];

        $mform->addElement(
            'select',
            'config_whatshappening_filter',
            get_string('whatshappening_filter', 'block_carousel'),
            $filters
        );
        $mform->hideIf('config_whatshappening_filter', 'config_carouseltype', 'neq', constants::BKC_WHATS_HAPPENING);

        $mform->addElement(
            'checkbox',
            'config_whatshappening_showprogramcompletion',
            get_string('showprogramcompletion', 'block_event_feed')
        );
        $mform->hideIf(
            'config_whatshappening_showprogramcompletion',
            'config_carouseltype',
            'neq',
            constants::BKC_WHATS_HAPPENING
        );

        $sql = "SELECT cm.id, forum.name, c.fullname AS coursename
              FROM {forum} forum
              JOIN {course_modules} cm
                ON (cm.instance=forum.id)
              JOIN {modules} m
                ON (m.id = cm.module AND m.name='forum')
              JOIN {course} c
                ON (c.id = cm.course)
             WHERE cm.visible = 1
               AND c.visible = 1";
        $records = $DB->get_records_sql($sql);

        $forums = [];

        foreach ($records as $record) {
            $forums[$record->id] = $record->name . ' < ' . $record->coursename;
        }

        $forumselect =
            $mform->addElement(
                'select',
                'config_whatshappening_forums',
                get_string('showpostinforums', 'block_event_feed'),
                $forums
            );
        $forumselect->setMultiple(true);
        $mform->hideIf('config_whatshappening_forums', 'config_carouseltype', 'neq', constants::BKC_WHATS_HAPPENING);

        $eventsmode = [
            'all' => 'Show all',
            'specific' => 'Show specific',
        ];

        $mform->addElement(
            'select',
            'config_whatshappening_eventsmodeselect',
            get_string('whatshappening_eventsmodeselect', 'block_carousel'),
            $eventsmode
        );
        $mform->hideIf('config_whatshappening_eventsmodeselect', 'config_carouseltype', 'neq', constants::BKC_WHATS_HAPPENING);

        $eventtypes = [
            '\core\event\course_completed' => 'Course completed',
            '\core\event\badge_awarded' => 'Badge awarded',
            '\local_f2fattendanceevents\event\attendance_fully' => 'Session fully attended',
            '\local_f2fattendanceevents\event\attendance_partially' => 'Session partially attended',
            '\block_rate_course\event\review_created' => 'Course reviewed',
            '\block_rate_course\event\course_liked' => 'Liked course',
            /*'\block_rate_course\event\review_liked' => 'liked course review',*/ // does not exists yet
            '\block_rate_course\event\recommendation_created' => 'Recommend course',
        ];

        $eventselect = $mform->addElement(
            'select',
            'config_whatshappening_eventselect',
            get_string('whatshappening_eventselect', 'block_carousel'),
            $eventtypes
        );
        $eventselect->setMultiple(true);
        $mform->hideIf(
            'config_whatshappening_eventselect',
            'config_whatshappening_eventsmodeselect',
            'neq',
            'specific'
        );
    }
}