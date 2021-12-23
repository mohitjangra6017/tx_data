<?php
/**
 * @copyright 2017-2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

namespace mod_assessment\dialog;

use html_writer;
use mod_assessment\model\rule;
use totara_dialog_content;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/totara/core/dialogs/dialog_content.class.php');

class rulemanager extends totara_dialog_content
{

    /**
     * @param rule $rule
     * @param int $versionid
     * @param int $role see constants in \mod_assessment\helper\role
     */
    public function __construct(rule $rule, int $versionid, int $role)
    {
        $this->items = $this->get_items();
        $this->selected_items = isset($rule) ? $rule->get_selected() : [];
        $this->selected_title = 'itemstoadd';
        $this->type = totara_dialog_content::TYPE_CHOICE_MULTI;
        $this->unremovable_items = $this->selected_items;
        $this->urlparams = [
            'ruleid' => $rule->id,
            'rulesetid' => $rule->rulesetid,
            'type' => $rule->type,
            'versionid' => $versionid,
            'role' => $role
        ];

        // Disallow search.
        unset($this->search_code);
        unset($this->searchtype);
    }

    public function _prepend_markup(): string
    {
        $params = [
            'data-ruleid' => $this->urlparams['ruleid'],
            'data-rulesetid' => $this->urlparams['rulesetid'],
            'data-type' => $this->urlparams['type'],
            'data-versionid' => $this->urlparams['versionid'],
            'data-role' => $this->urlparams['role']
        ];

        return html_writer::div('', 'extradata', $params);
    }

    public function generate_markup(): string
    {
        $markup = $this->_prepend_markup();

        $selected = isset($this->operator) ? $this->operator : '';
        $operatormenu[rule::OP_IN_EQUAL] = get_string('isequalto', 'filters');
        $operatormenu[rule::OP_IN_NOTEQUAL] = get_string('isnotequalto', 'filters');

        $markup .= html_writer::select(
            $operatormenu,
            'operator',
            $selected,
            array(),
            ['id' => 'id_operator', 'class' => 'operatortreeviewsubmitfield']
        );
        $markup .= html_writer::select($this->get_items(), 'select', [], false, ['id' => 'id_dialogselectval']);

        return $markup;
    }

    public function get_items(): array
    {
        return [
            'directmanager' => get_string('directmanager', 'assessment'),
            'indirectmanager' => get_string('indirectmanager', 'assessment'),
        ];
    }
}