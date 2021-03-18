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
 * @author Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package totara_notification
 */
use totara_tui\output\component;
use totara_notification\model\notification_preference;

global $CFG, $OUTPUT, $PAGE;

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir.'/adminlib.php');

$context_id = optional_param('context_id', null, PARAM_INT);
$context = null === $context_id ? context_system::instance() : context::instance_by_id($context_id);
$extended_context = extended_context::make_with_context($context);

if (CONTEXT_SYSTEM == $context->contextlevel) {
    // If it is under the context system, we will redirect the user to the admin page
    // rather than use this page. Because this page must only be used for lower context purpose.
    // Note: in the future we might want to do sort of component,area and instance id check as well
    redirect(new moodle_url("/totara/notification/notifications.php"));
}

require_login();
if (!notification_preference::can_manage($extended_context)) {
    throw new coding_exception(get_string('error_manage_notification', 'totara_notification'));
}

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url("/totara/notification/context_notifications.php", ['context_id' => $context->id]));
$PAGE->set_pagelayout('noblocks');
$PAGE->set_url(new moodle_url('/totara/notification/notifications.php'));

$tui = new component(
    'totara_notification/pages/NotificationPage',
    [
        'title' => get_string('notifications', 'totara_notification'),
        'context-id' => $context->id
    ]
);

$tui->register($PAGE);

echo $OUTPUT->header();
echo $OUTPUT->render($tui);
echo $OUTPUT->footer();