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
 * @author Valerii Kuznetsov <valerii.kuznetsov@totaralms.com>
 * @package totara
 * @subpackage totara_appraisal
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot . '/totara/appraisal/lib.php');
require_once($CFG->dirroot . '/totara/appraisal/lib/assign/lib.php');
require_once($CFG->dirroot . '/totara/appraisal/appraisal_forms.php');

// Check if Appraisals are enabled.
appraisal::check_feature_enabled();

$id = required_param('id', PARAM_INT);
$action = required_param('action', PARAM_ALPHA);
$confirm = optional_param('confirm', 0, PARAM_INT);

admin_externalpage_setup('manageappraisals');
$systemcontext = context_system::instance();
require_capability('totara/appraisal:manageactivation', $systemcontext);

$output = $PAGE->get_renderer('totara_appraisal');

$appraisal = new appraisal($id);

switch ($action) {
    case 'activate':
        list($errors, $warnings) = $appraisal->validate();
        if (empty($errors) && $confirm) {
            if (!confirm_sesskey()) {
                print_error('confirmsesskeybad', 'error');
            }
            try {
                $appraisal->activate();
                \core\notification::success(get_string('appraisalactivated', 'totara_appraisal', format_string($appraisal->name)));
                redirect(new moodle_url('/totara/appraisal/manage.php'));
            }
            catch (ddl_change_structure_exception $e) {
                if (strstr($e->debuginfo, 'Row size too large') !== false) {
                    // This can happen for MySQL when there are too many questions. Display a helpful error message.
                    $errors['toomanyquestions'] = get_string('error:toomanyquestions', 'totara_appraisal');
                } else {
                    // Unknown reason. Re-throw exception.
                    throw $e;
                }
            }
        }
        break;
    case 'close':
        $appraisal->alertbody = get_string('closealertbodydefault', 'totara_appraisal', $appraisal);
        $appraisal->alertbodyformat = FORMAT_HTML;
        $appraisal = file_prepare_standard_editor($appraisal, 'alertbody', $TEXTAREA_OPTIONS, $TEXTAREA_OPTIONS['context']);
        $form = new appraisal_close_form(null, array('appraisal' => $appraisal), 'post', '', null, true, null, 'appraisalclose');
        if ($form->is_submitted()) {
            if (!confirm_sesskey()) {
                print_error('confirmsesskeybad', 'error');
            }
            $formdata = $form->get_data();
            if ($formdata) {
                $appraisal->close($formdata);
                if (isset($formdata->sendalert) && $formdata->sendalert) {
                    \core\notification::success(get_string('appraisalclosedalertoncron', 'totara_appraisal', format_string($appraisal->name)));
                    redirect(new moodle_url('/totara/appraisal/manage.php'));
                } else {
                    \core\notification::success(get_string('appraisalclosed', 'totara_appraisal', format_string($appraisal->name)));
                    redirect(new moodle_url('/totara/appraisal/manage.php'));
                }
            } else {
                redirect(new moodle_url('/totara/appraisal/manage.php'));
            }
        }
        break;
}

echo $output->header();

switch ($action) {
    case 'activate':
        echo $output->heading(format_string($appraisal->name));
        echo $output->confirm_appraisal_activation($appraisal, $errors, $warnings);
        break;
    case 'close':
        echo $output->heading(get_string('closeappraisal', 'totara_appraisal'));
        echo $output->confirm_appraisal_close($appraisal->count_incomplete_userassignments());
        $form->display();
        break;
    default:
        echo $output->heading(format_string($appraisal->name));
        echo get_string('unrecognizedaction', 'totara_appraisal');
}

echo $output->footer();
