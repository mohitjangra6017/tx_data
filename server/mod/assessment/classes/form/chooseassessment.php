<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

namespace mod_assessment\form;

defined('MOODLE_INTERNAL') || die();

use mod_assessment\model\assessment;
use moodle_database;
use totara_form\form;
use totara_form\form\element;

class chooseassessment extends form
{

    protected function definition()
    {

        $this->model->add(new element\hidden('id', PARAM_INT));

        $assessmentid = $this->model->add(new element\select(
            'assessmentid',
            get_string('chooseassessment', 'rb_source_assessment'),
            $this->get_assessment_menu()
        ));
        $assessmentid->add_help_button('chooseassessment', 'rb_source_assessment');
        $assessmentid->set_attribute('required', true);

        $this->model->add_action_buttons(false, get_string('viewreport', 'rb_source_assessment'));
    }

    /**
     * @return array
     * @global moodle_database $DB
     */
    public function get_assessment_menu(): array
    {
        global $DB;
        return $DB->get_records_menu(assessment::TABLE, null, 'name', 'id, name');
    }
}
