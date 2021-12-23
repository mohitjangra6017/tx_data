<?php
/**
 * Created by PhpStorm.
 * User: gavin
 * Date: 31/07/19
 * Time: 12:45
 */

namespace local_leaderboard\Form;

use coding_exception;

defined('MOODLE_INTERNAL') || die;

class UserBulkScoreForm extends \moodleform
{
    /**
     * @throws coding_exception
     */
    protected function definition()
    {
        $form = $this->_form;

        $form->addElement('text', 'score', get_string('form:userbulk:allocate', 'local_leaderboard'), ['size' => '6']);
        $form->setType('score', PARAM_TEXT);
        $this->add_action_buttons(false, get_string('form:userbulk:submit', 'local_leaderboard'));
    }

    /**
     * @param array $data
     * @param array $files
     * @return array
     * @throws coding_exception
     */
    function validation($data, $files)
    {
        $errors = parent::validation($data, $files);

        if (isset($data['score']) && (!is_number($data['score']) || $data['score'] <= 0)) {
            $errors['score'] = get_string('error:positive_integers_only', 'local_leaderboard');
        }

        return $errors;
    }
}