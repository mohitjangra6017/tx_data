<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010 onwards Totara Learning Solutions LTD
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
 * @author Piers Harding <piers@catalyst.net.nz>
 * @package totara
 * @subpackage message
 */

/**
 * For listing message histories between any two users
 */

require_once(__DIR__ . '/../../config.php');
require_once('lib.php');

global $CFG, $DB, $USER, $PAGE;

require_login();
$PAGE->set_context(context_system::instance());

if (isguestuser()) {
    redirect($CFG->wwwroot);
}

/// Script parameters
$msgid = required_param('id', PARAM_INT);
$reasonfordecision = optional_param('reasonfordecision', '', PARAM_TEXT);
$returnto = optional_param('returnto', null, PARAM_LOCALURL);
$processor_type = optional_param('processor_type', null, PARAM_AREA);

// check message ownership
$message = $DB->get_record('notifications', array('id' => $msgid));
if (!$message || $message->useridto != $USER->id || !confirm_sesskey()) {
    print_error('notyours', 'totara_message', null, $msgid);
}

// onaccept the message and then return
tm_message_task_accept($msgid, $reasonfordecision, $processor_type);

if ($returnto) {
    redirect($returnto);
}
