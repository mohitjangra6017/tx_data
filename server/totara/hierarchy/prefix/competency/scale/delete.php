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
 * @subpackage totara_hierarchy
 */

use totara_competency\models\scale;

require_once(__DIR__ . '/../../../../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/totara/hierarchy/prefix/competency/lib.php');
require_once('lib.php');


///
/// Setup / loading data
///

// Get params
$id = required_param('id', PARAM_INT);
$prefix = required_param('prefix', PARAM_ALPHA);
// Delete confirmation hash
$delete = optional_param('delete', '', PARAM_ALPHANUM);

// Cache user capabilities.
$sitecontext = context_system::instance();

// Check if Competencies are enabled.
competency::check_feature_enabled();

// Permissions.
require_capability('totara/hierarchy:deletecompetencyscale', $sitecontext);

// Set up the page.
admin_externalpage_setup($prefix.'manage');

if (!$scale = $DB->get_record('comp_scale', array('id' => $id))) {
    print_error('error:invalidscaleid', 'totara_hierarchy');
}

$returnurl = "{$CFG->wwwroot}/totara/hierarchy/framework/index.php?prefix=competency";
$deleteurl = "{$CFG->wwwroot}/totara/hierarchy/prefix/competency/scale/delete.php?id={$scale->id}&amp;delete=".md5($scale->timemodified)."&amp;sesskey={$USER->sesskey}&amp;prefix=competency";

$scale_model = scale::load_by_id_with_values($scale->id);

// Can't delete if the scale is in use or assigned
if ($scale_model->is_in_use()) {
    print_error('error:nodeletecompetencyscaleinuse', 'totara_hierarchy', $returnurl);
}
if ($scale_model->is_assigned()) {
    print_error('error:nodeletecompetencyscaleassigned', 'totara_hierarchy', $returnurl);
}

if (!$delete) {
    echo $OUTPUT->header();
    $strdelete = get_string('deletecheckscale', 'totara_hierarchy');

    echo $OUTPUT->confirm($strdelete . html_writer::empty_tag('br') . html_writer::empty_tag('br') . format_string($scale->name), $deleteurl, $returnurl);

    echo $OUTPUT->footer();
    exit;
}


///
/// Delete competency scale
///

if ($delete != md5($scale->timemodified)) {
    print_error('checkvariable', 'totara_hierarchy');
}

if (!confirm_sesskey()) {
    print_error('confirmsesskeybad', 'error');
}

$transaction = $DB->start_delegated_transaction();

// Delete assignment of scale to frameworks
$DB->delete_records('comp_scale_assignments', array('scaleid' => $scale->id));

// Delete scale values
$DB->delete_records('comp_scale_values', array('scaleid' => $scale->id));

// Delete scale itself
$DB->delete_records('comp_scale', array('id' => $scale->id));

$transaction->allow_commit();

\hierarchy_competency\event\scale_deleted::create_from_instance($scale)->trigger();

// redirect
\core\notification::success(get_string('deletedcompetencyscale', 'totara_hierarchy', format_string($scale->name)));
redirect($CFG->wwwroot . '/totara/hierarchy/framework/index.php?prefix=competency');
