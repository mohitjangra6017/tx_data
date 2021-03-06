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
 * @author Simon Coggins <simon.coggins@totaralms.com>
 * @package totara
 * @subpackage hierarchy
 */

require_once(__DIR__ . '/../../../../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/totara/reportbuilder/lib.php');
require_once($CFG->dirroot.'/totara/core/dialogs/dialog_content_hierarchy.class.php');

require_once($CFG->dirroot.'/totara/hierarchy/prefix/organisation/lib.php');
require_once($CFG->dirroot.'/totara/core/js/lib/setup.php');

use totara_core\advanced_feature;

// Page title
$pagetitle = 'assignorganisations';

///
/// Params
///

// Framework id
$frameworkid = optional_param('frameworkid', 0, PARAM_INT);

// Parent id
$parentid = optional_param('parentid', 0, PARAM_INT);

// Only return generated tree html
$treeonly = optional_param('treeonly', false, PARAM_BOOL);

// Restrict content according to definition in the report
$reportid = optional_param('reportid', 0, PARAM_INT);

///
/// Permissions checks
///

require_login();
$PAGE->set_context(context_system::instance());

// All hierarchy items can be viewed by any real user.
if (isguestuser()) {
    echo html_writer::tag('div', get_string('noguest', 'error'), array('class' => 'notifyproblem'));
    die;
}

// Check if organisations are enabled.
if (advanced_feature::is_disabled('organisations')) {
    echo html_writer::tag('div', get_string('organisationsdisabled', 'totara_hierarchy'), array('class' => 'notifyproblem'));
    die();
}

///
/// Display page
///

// Load dialog content generator
$dialog = new totara_dialog_content_hierarchy_multi('organisation', $frameworkid, false, false, $reportid);

// Toggle treeview only display
$dialog->show_treeview_only = $treeonly;

// Load items to display
$dialog->load_items($parentid);

// Set title
$dialog->selected_title = 'itemstoadd';
$dialog->select_title = '';

// Display
echo $dialog->generate_markup();
