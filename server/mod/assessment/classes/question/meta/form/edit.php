<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\question\meta\form;

use html_table;
use mod_assessment\factory\assessment_question_factory;
use mod_assessment\form\choosequestion;
use mod_assessment\model\question;
use mod_assessment\question\edit_form;
use moodle_url;
use pix_icon;

class edit extends edit_form
{

    public static function get_type(): string
    {
        return 'meta';
    }

    public function set_data($default_values)
    {
        if ($default_values instanceof question) {
            $config = $default_values->get_config();

            foreach ($config as $field => $value) {
                $default_values->{"config_$field"} = $value;
            }
        }

        parent::set_data($default_values);
    }

    protected function add_js()
    {
        // JS not required
    }

    protected function add_question_fields()
    {
        global $OUTPUT, $PAGE;

        $html = '';
        $questions = [];
        if ($this->get_question()->exists()) {
            $questions = assessment_question_factory::fetch_from_parentid($this->get_question()->get_id(), $this->get_version()->get_id());
        }

        if ($questions) {
            $table = new html_table();
            $table->head = ['question', 'type', 'actions'];

            $index = 0;
            foreach ($questions as $question) {
                $index++;

                $qparams = [
                    'id' => $question->get_id(),
                    'parentid' => $this->get_question()->get_id(),
                    'stageid' => $this->get_stage()->get_id(),
                    'versionid' => $this->get_version()->get_id(),
                    'returnurl' => $PAGE->url,
                ];

                $editurl = new moodle_url('/mod/assessment/admin/editquestion.php', $qparams);
                $deleteurl = new moodle_url('/mod/assessment/admin/deletequestion.php', $qparams);
                $moveupurl = new moodle_url('/mod/assessment/admin/movequestion.php', array_merge($qparams, ['direction' => 'up', 'sesskey' => sesskey()]));
                $movedownurl = new moodle_url('/mod/assessment/admin/movequestion.php', array_merge($qparams, ['direction' => 'down', 'sesskey' => sesskey()]));

                $actions = [];
                $actions[] = $OUTPUT->action_icon($editurl, new pix_icon('t/edit', get_string('edit')));
                if ($this->get_version()->is_draft()) {
                    $actions[] = $OUTPUT->action_icon($deleteurl, new pix_icon('t/delete', get_string('delete')));
                }

                if ($index > 1) {
                    $actions[] = $OUTPUT->action_icon($moveupurl, new pix_icon('t/up', get_string('up')));
                } else {
                    $actions[] = $OUTPUT->flex_icon('spacer');
                }

                if (count($questions) > $index) {
                    $actions[] = $OUTPUT->action_icon($movedownurl, new pix_icon('t/down', get_string('down')));
                } else {
                    $actions[] = $OUTPUT->flex_icon('spacer');
                }

                $table->data[] = [format_text($question->question), $question->get_displayname(), implode(' ', $actions)];
            }

            $html = $OUTPUT->render($table);
        }

        $this->_form->addElement('static', 'questions', get_string('associatedquestion', 'assquestion_meta'), $html);

        $addquestion = [
            $this->_form->createElement('selectgroups', 'newtype', null, choosequestion::get_questions_menu($this->get_version())),
            $this->_form->createElement('submit', 'addquestion', get_string('add', 'assessment'))
        ];
        $this->_form->addGroup($addquestion, 'addquestiongroup', '', null, false);
    }
}
