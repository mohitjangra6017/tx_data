<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2017 Kineo
 * @package totara
 * @subpackage assquestion_html
 */

namespace mod_assessment\question\html\form\element;

use context_module;
use mod_assessment\model\role;
use mod_assessment\model\version;
use mod_assessment\question\element;
use renderer_base;

defined('MOODLE_INTERNAL') || die();

class html extends \totara_form\element
{

    use element;

    /**
     * @param renderer_base $output
     * @return array
     */
    public function export_for_template(renderer_base $output): array
    {

        $versionid = $this->get_model()->get_current_data('versionid')['versionid'];
        $version = version::instance(['id' => $versionid], MUST_EXIST);
        $attempt = $this->get_attempt($version);
        $question = $this->get_question();
        $role = $this->get_model()->get_current_data('role')['role'];

        $result = $this->export_default_template_params($question, $attempt, $role, $role);
        $result['html'] = $this->get_question()->get_html(context_module::instance($this->get_assessment()->get_cmid()));
        $result['form_item_template'] = 'assquestion_html/element_html';
        return $result;
    }

    /**
     * @return array
     */
    public function get_data(): array
    {
        return [$this->get_name() => null];
    }

    public function get_field_value()
    {
        return null;
    }

}
