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
 * @author Yuliya Bozhko <yuliya.bozhko@totaralms.com>
 * @package totara
 * @subpackage program
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/totara/program/lib.php');
require_once($CFG->libdir. '/coursecatlib.php');

$categoryid = optional_param('categoryid', 0, PARAM_INT); // Category id
$viewtype = optional_param('viewtype', 'program', PARAM_TEXT); // Type of a page, program or certification.

if (!$categoryid) {
    if ($CFG->catalogtype === 'enhanced') {
        if ($viewtype == 'program') {
            redirect(new moodle_url('/totara/coursecatalog/programs.php'));
        } else {
            redirect(new moodle_url('/totara/coursecatalog/certifications.php'));
        }
    } else if ($CFG->catalogtype === 'totara') {
        redirect(new moodle_url('/totara/catalog/index.php'));
    }
}

if ($CFG->forcelogin) {
    require_login();
}

// Check if programs or certifications are enabled.
if ($viewtype == 'program') {
    check_program_enabled();
} else {
    check_certification_enabled();
}

$site = get_site();

$category = null;
if ($categoryid) {
    $PAGE->set_category_by_id($categoryid);
    $PAGE->set_url(new moodle_url('/totara/program/index.php', array('categoryid' => $categoryid, 'viewtype' => $viewtype)));
    $PAGE->set_pagetype('course-index-category');
    $category = coursecat::get($categoryid);
    // Add program breadcrumbs.
    $navname = $viewtype == 'program' ? get_string('programs', 'totara_program') : get_string('certifications', 'totara_certification');
    $PAGE->navbar->add($navname, new moodle_url('/totara/program/index.php', ['viewtype' => $viewtype]));
    $category_breadcrumbs = prog_get_category_breadcrumbs($categoryid, $viewtype);
    foreach ($category_breadcrumbs as $crumb) {
        $PAGE->navbar->add($crumb['name'], $crumb['link']);
    }
} else {
    // Check if there is only one category, if so use that.
    if (!empty($USER->tenantid) or coursecat::count_all() == 1) {
        $category = coursecat::get_default();
        $categoryid = $category->id;
        $PAGE->set_category_by_id($categoryid);
        $PAGE->set_pagetype('course-index-category');
    } else {
        $PAGE->set_pagetype('course-index');
        $PAGE->set_context(context_system::instance());
    }
    $PAGE->set_url('/totara/program/index.php', array('viewtype' => $viewtype));
}
$PAGE->set_pagelayout('coursecategory');
$programrenderer = $PAGE->get_renderer('totara_program');

if ($category and !$category->is_uservisible()) {
    throw new moodle_exception('unknowncategory');
}

// This 's' is needed so that the correct Totara menu item has the selected css class added.
$menuitem = $viewtype == 'program' ? '\totara_coursecatalog\totara\menu\programs' : '\totara_coursecatalog\totara\menu\certifications';

$PAGE->set_totara_menu_selected($menuitem);
$PAGE->set_heading($site->fullname);
$content = $programrenderer->program_category($categoryid, $viewtype);

echo $OUTPUT->header();
echo $OUTPUT->skip_link_target();
echo $content;

echo $OUTPUT->footer();
