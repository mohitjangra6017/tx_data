<?php
/**
 * @copyright 2017-2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package mod_assessment
 * @subpackage assquestion_file
 */

namespace mod_assessment\question\file\form\element;

use context_module;
use mod_assessment\model\answer;
use mod_assessment\model\question_element;
use mod_assessment\model\role;
use mod_assessment\model\version;
use mod_assessment\question\element;
use moodle_url;
use renderer_base;

defined('MOODLE_INTERNAL') || die();

class filemanager extends \totara_form\form\element\filemanager implements question_element
{

    use element;

    /**
     * @param renderer_base $output
     * @return array
     */
    public function export_for_template(renderer_base $output): array
    {
        $result = parent::export_for_template($output);
        $result['form_item_template'] = 'mod_assessment/question';
        $result['question_number'] = $this->get_question_number();

        foreach (role::get_roles() as $roleid => $rolelabel) {
            $result['roleoptions'][] = $this->export_for_template_role($output, new role($roleid));
        }

        return $result;
    }

    public function get_data(): array
    {
        $qid = 'q_' . $this->get_question()->id;
        return [$qid => $this->get_model()->get_raw_post_data($qid)];
    }

    public function get_field_value(): ?int
    {
        $value = parent::get_field_value();
        if (!$value) {
            // Value not set - get from existing data.
            $version = $this->get_version();
            $answer = answer::instance([
                'attemptid' => $this->get_attempt($version)->id,
                'questionid' => $this->get_question()->id,
                'role' => $this->get_role()
            ]);

            if ($answer) {
                $value = json_decode($answer->value);
            }
        }

        return $value;
    }

    public function get_role_answer($question, $attempt, role $role)
    {
        $answer = answer::instance([
            'attemptid' => $attempt->id,
            'questionid' => $question->id,
            'role' => $role->value(),
        ]);

        if (!$answer) {
            return '';
        }

        $contextId = context_module::instance($this->get_assessment()->get_cmid())->id;

        $fs = get_file_storage();
        $files = $fs->get_area_files(
            $contextId,
            'mod_assessment',
            'answer',
            $answer->id,
            "itemid, filepath, filename",
            false
        );

        $filedata = [];
        foreach ($files as $file) {
            $data = [
                'fname' => $file->get_filename(),
                'furl' => moodle_url::make_pluginfile_url(
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea(),
                    $file->get_itemid(),
                    $file->get_filepath(),
                    $file->get_filename()
                ),
            ];
            $filedata[] = $data;
        }

        return $filedata ? $filedata : '';
    }

    public function export_for_template_role(renderer_base $output, role $questionrole): array
    {
        $versionid = $this->get_model()->get_current_data('versionid')['versionid'];
        $version = version::instance(['id' => $versionid], MUST_EXIST);
        $attempt = $this->get_attempt($version);
        $question = $this->get_question();
        $userrole = $this->get_model()->get_current_data('role')['role'];

        $context = parent::export_for_template($output);
        $context = array_merge($context, $this->export_default_template_params($question, $attempt, $questionrole->value(), $userrole));
        $context['answers'] = $this->get_role_answer($question, $attempt, $questionrole);
        $context['question_template'] = 'assquestion_file/element_filemanager';

        return $context;
    }
}
