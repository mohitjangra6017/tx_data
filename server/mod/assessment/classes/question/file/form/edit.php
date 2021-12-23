<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2017 Kineo
 * @package mod_assessment
 * @subpackage assquestion_file
 */

namespace mod_assessment\question\file\form;

use mod_assessment\model\question;
use mod_assessment\question\edit_form;
use mod_assessment\question\file;

class edit extends edit_form
{

    public static function get_type(): string
    {
        return 'file';
    }

    public function validation($data, $files): array
    {
        $errors = parent::validation($data, $files);
        if (empty($data['config_maxuploads']) || $data['config_maxuploads'] < 1) {
            $errors['config_maxuploads'] = get_string('error_lessthanone', 'assquestion_file');
        }
        return $errors;
    }

    protected function add_js()
    {
        // JS not required.
    }

    protected function add_question_fields()
    {
        $this->_form->addElement('header', 'head_options', get_string('uploadoptions', 'assquestion_file'));
        $this->_form->addElement('text', 'config_maxuploads', get_string('maxuploads', 'assquestion_file'));
        $this->_form->addRule('config_maxuploads', get_string('required'), 'required');
        $this->_form->setType('config_maxuploads', PARAM_INT);
        $this->freeze_field('config_maxuploads');
    }
}
