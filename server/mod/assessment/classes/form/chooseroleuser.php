<?php
/**
 * @copyright 2017-2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 *
 * DEVIOTIS2
 * - refactored from chooseevaluators.php to choose users with roles generically (i.e. evaluators AND reviewers not just the former).
 */

namespace mod_assessment\form;

defined('MOODLE_INTERNAL') || die();

use totara_form\form;
use totara_form\form\element;

class chooseroleuser extends form
{

    protected function definition()
    {
        $this->model->add(new element\hidden('activetab', PARAM_TEXT));
        $this->model->add(new element\hidden('attemptid', PARAM_INT));
        $this->model->add(new element\hidden('role', PARAM_INT));
        $this->model->add(new element\hidden('selected', PARAM_INT));

        $this->model->add_action_buttons(true, get_string('ok'));
    }

    protected function validation(array $data, array $files): array
    {

        $errors = parent::validation($data, $files);
        if (empty($data['selected'])) {
            $errors[] = get_string('error_nobodyselected', 'assessment');
        }

        return $errors;
    }
}
