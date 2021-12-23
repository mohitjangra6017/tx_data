<?php

namespace block_carousel\carouseltype;

use block_carousel\constants;
use block_carousel\helper;
use stdClass;

class shoutouts extends base {
    function generate($data) {
        global $PAGE, $DB, $USER, $CFG;
        $renderer = $PAGE->get_renderer('block_carousel');

        $shoutouts = [];

        $config = $this->get_blockplugin_config('shoutout');

        $limit = !empty($this->config->shoutoutlimit) ? $this->config->shoutoutlimit : constants::BKC_DEFAULT_RECORD_LIMIT;

        $shoutouts = $DB->get_records('block_shoutout', array('deleted' => 0), 'timecreated DESC', '*', 0, $limit);

        $likes = $DB->get_records('block_shoutout_likes', array('user_id' => $USER->id), '', 'shoutout_id');
        $followed = $DB->get_records('block_event_feed_user_follow', array('userid' => $USER->id), '', 'followid');

        $card_size = $data['cardsize'];

        $strip_summary_length = ($card_size == constants::BKC_CARD_LARGE) ? constants::BKC_SUMMARY_LENGHT_LARGE_CARD : constants::BKC_SUMMARY_LENGHT_SMALL_CARD;

        $first = true;

        foreach ($shoutouts as $shoutout) {
            $user = $DB->get_record('user', array('id' => $shoutout->user_id));
            $userpicture = new \user_picture($user);
            $userpicture->size = array('100%', '100%');
            $userprofileurl = $userpicture->get_url($PAGE);
            if ($first) {
                $first = false;
                $duration = get_string('latest', 'block_carousel');
            } else if ($shoutout->timecreated > (time() - DAYSECS)) {
                $duration = get_string('new', 'block_carousel');
            } else {
                $duration = round((time() - $shoutout->timecreated) / DAYSECS) . ' days ago';
            }

            $summary = $shoutout->shoutout;

            // User custom fields in the config
            // for cards
            $userfield_shortnames = helper::get_carousel_custom_field_shortnames($this->config, 'usercustomfields');
            $user_custom_fields = helper::get_user_custom_field_data($user->id, $userfield_shortnames);

            // for details
            $userfield_shortnames_details = helper::get_carousel_custom_field_shortnames($this->config, 'usercustomfieldsdetails');
            $user_custom_fields_details = helper::get_user_custom_field_data($user->id, $userfield_shortnames_details);

            $data['slides'][] =
                [
                    'summary' => !empty($summary) ? substr(strip_tags($summary), 0, $strip_summary_length) . (strlen($summary) > $strip_summary_length ? '...' : '') : null,
                    'fullsummary' => $summary,
                    'name' => get_string('postedshoutout', 'block_carousel', $user->firstname),
                    'duration' => $duration,
                    'thumbnail' => $userprofileurl,
                    'userid' => $user->id,
                    'likebuttonid' => 'shoutout-' . $shoutout->id,
                    'liked' => (empty($likes[$shoutout->id])) ? '' : 'liked',
                    'likeurl' => $CFG->wwwroot . '/blocks/shoutout/ajax/like_shoutout.php?userid=' . $USER->id . '&shoutoutid=' . $shoutout->id,
                    'unlikeurl' => $CFG->wwwroot . '/blocks/shoutout/ajax/like_shoutout.php?userid=' . $USER->id . '&shoutoutid=' . $shoutout->id,
                    'followed' => empty($followed[$shoutout->user_id]),
                    'followuserid' => $shoutout->user_id,
                    'followredirecturl' => $PAGE->url,
                    'hidesocial' => ($USER->id == $shoutout->user_id),
                    'hide_goto_course_btn' => true,
                    'user_custom_fields' => $user_custom_fields,
                    'user_custom_fields_details' => $user_custom_fields_details,
                    'latest' => ($duration == get_string('latest', 'block_carousel'))
                ];
        }

        $data['title'] = empty($data['title']) ? get_string('type:' . constants::BKC_SHOUTOUTS, 'block_carousel') : $data['title'];
        $data['template'] = $data['template'].'_person';
        return $renderer->render_shoutouts($data);
    }

    public static function extend_form(\MoodleQuickForm $form, stdClass $block_instance)
    {
        $form->addElement(
            'text',
            'config_shoutoutlimit',
            get_string('shoutoutlimit', 'block_carousel'),
            ['oninput' => "this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"]
        ); // only allow numeric values
        $form->addHelpButton('config_shoutoutlimit', 'whatshotlimit', 'block_carousel');
        $form->setType('config_shoutoutlimit', PARAM_INT);
        $form->setDefault('config_shoutoutlimit', constants::BKC_DEFAULT_RECORD_LIMIT);
        $form->hideIf('config_shoutoutlimit', 'config_carouseltype', 'neq', constants::BKC_SHOUTOUTS);
    }

}