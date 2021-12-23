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
use moodle_database;
use totara_dialog_content;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/totara/core/dialogs/dialog_content.class.php');

class ruleroleincoursegroup extends totara_dialog_content
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

    /**
     * @global moodle_database $DB
     */
    public function get_items(): array
    {
        global $DB;

        $roleids = get_roles_for_contextlevels(CONTEXT_COURSE);

        list($rolesql, $params) = $DB->get_in_or_equal($roleids, SQL_PARAMS_NAMED);

        $params['shortname'] = 'student';
        $rolerecords = $DB->get_records_sql("SELECT * FROM {role} WHERE id {$rolesql} AND shortname <> :shortname", $params);
        $roles = role_fix_names($rolerecords);
        $roles = array_map(function ($role) {
            $role->fullname = $role->localname;
            return $role;
        }, $roles);

        return $roles;
    }

    public function populate_selected_items_pane($elements): string
    {
        $operatormenu = array();
        $operatormenu[rule::OP_IN_EQUAL] = get_string('isequalto', 'filters'); // only allow "equal to"

        // we only have one operator option, so create a hidden select menu
        $selected = isset($this->operator) ? $this->operator : '';
        $html = html_writer::select(
            $operatormenu,
            'operator',
            $selected,
            array(),
            ['id' => 'id_operator', 'class' => 'operatortreeviewsubmitfield', 'style' => 'display: none;']
        );

        return $html . parent::populate_selected_items_pane($elements);
    }
}

;
