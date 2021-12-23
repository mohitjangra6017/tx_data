<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

defined('MOODLE_INTERNAL') || die();

const CREDLY_OPT_OUT_PREFERENCE = 'local_credly_opt_out';

/**
 * @param navigation_node $node
 * @param stdClass $user
 * @param context $userContext
 * @param stdClass $course
 * @param context $courseContext
 */
function local_credly_extend_navigation_user_settings(navigation_node $node, stdClass $user, context $userContext, stdClass $course, context $courseContext): void
{
    if (!(get_config('local_credly', 'enabled') ?? false)) {
        return;
    }

    $badges = $node->find('badges', navigation_node::TYPE_CONTAINER);

    if (get_config('local_credly', 'allow_opt_out') && $badges) {
        $badges->add(get_string('user:preferences', 'local_credly'), new moodle_url('/local/credly/mypreferences.php'), navigation_node::TYPE_SETTING);
    }
}