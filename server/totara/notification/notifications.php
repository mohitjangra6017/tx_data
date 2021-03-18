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
 * @author Qingyang Liu <qingyang.liu@totaralearning.com>
 * @package totara_notification
 */

use totara_core\extended_context;
use totara_tui\output\component;
use totara_notification\model\notification_preference;

global $CFG, $OUTPUT, $PAGE;

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir.'/adminlib.php');

require_login();

//require manage capability to access this page
$extended_context = extended_context::make_with_context(context_system::instance());
if (!notification_preference::can_manage($extended_context)) {
    throw new coding_exception(get_string('error_manage_notification', 'totara_notification'));
}

$PAGE->set_context($extended_context->get_context());
$PAGE->set_url(new moodle_url('/totara/notification/notifications.php'));

admin_externalpage_setup('notifications_setup', '', null, '', ['pagelayout' => 'noblocks']);

$tui = new component(
    'totara_notification/pages/NotificationPage',
    [
        'title' => get_string('notifications', 'totara_notification'),
        'context-id' => $extended_context->get_context_id()
    ]
);

$tui->register($PAGE);

echo $OUTPUT->header();
echo $OUTPUT->render($tui);
echo $OUTPUT->footer();