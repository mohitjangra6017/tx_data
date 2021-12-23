<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2017 Kineo
 * @package totara
 * @subpackage assquestion_html
 */

namespace mod_assessment\question\html\form;

defined('MOODLE_INTERNAL') || die();

use mod_assessment\question\edit_form;
use mod_assessment\question\html;

class edit extends edit_form
{

    public function get_data(): ?object
    {
        $data = parent::get_data();
        if ($data) {
            $data->config_html['text'] = file_save_draft_area_files(
                $data->config_html['itemid'],
                $this->get_context()->id,
                'mod_assessment',
                'html',
                $data->id,
                null,
                $data->config_html['text']
            );
        }

        return $data;
    }

    public static function get_type(): string
    {
        return 'html';
    }

    public function set_data($default_values)
    {
        if (!empty($default_values['config_html']->text)) {
            $default_values['config_html']->text =
                file_rewrite_pluginfile_urls(
                    $default_values['config_html']->text,
                    'pluginfile.php',
                    $this->get_context()->id,
                    'mod_assessment',
                    'html',
                    $default_values['id']
                );
        }

        if ($default_values instanceof html\model\question) {
            $config = $default_values->get_config();

            foreach ($config as $field => $value) {
                if ($field == 'html') {
                    $draftid = file_get_submitted_draft_itemid('config_html');
                    $value->text = file_prepare_draft_area(
                        $draftid,
                        $this->get_context()->id,
                        'mod_assessment',
                        'html',
                        $default_values->id,
                        [],
                        $value->text
                    );
                    $value->itemid = $draftid;
                }

                $default_values->{"config_$field"} = $value;
            }
        }

        parent::set_data($default_values);
    }

    protected function add_js()
    {
        // JS not required.
    }

    protected function add_question_fields()
    {
        $this->_form->addElement('editor', 'config_html', get_string('display', 'assquestion_html'), null, $this->get_fileoptions());
        $this->_form->addRule('config_html', get_string('required'), 'required');
    }

    protected function get_fileoptions(): array
    {
        return [
            'context' => $this->get_context(),
            'maxfiles' => EDITOR_UNLIMITED_FILES,
        ];
    }
}


