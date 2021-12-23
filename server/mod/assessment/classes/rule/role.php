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

namespace mod_assessment\rule;

defined('MOODLE_INTERNAL') || die();

use context_module;
use Exception;
use html_writer;
use mod_assessment\form\rules;
use mod_assessment\model;
use mod_assessment\model\attempt;
use mod_assessment\model\rule;
use moodle_database;
use moodle_page;
use pix_icon;
use stdClass;

class role extends rule
{

    /** @var int */
    public int $attemptrole;

    /**
     * @param rules $form
     * @global moodle_page $PAGE
     */
    public function add_to_form(rules $form)
    {
        global $PAGE;

        $mform = $form->_form;
        $renderer = $PAGE->get_renderer('assessment');

        $jquerydata = ['data-type' => $this->type, 'data-versionid' => $form->get_version()->id, 'data-ruleid' => $this->id];

        $html = html_writer::span('', 'assignment-rule-type role');

        if ($this->operator == static::OP_IN_EQUAL) {
            $html .= get_string('ruledesc_rolein', 'assessment') . ': ';
        } elseif ($this->operator == static::OP_IN_NOTEQUAL) {
            $html .= get_string('ruledesc_rolenotin', 'assessment') . ': ';
        }

        // Convert values to list of role names.
        $roles = $this->get_roles();
        while ($role = current($roles)) {
            $icondelete = '';
            if ($form->get_version()->is_draft()) {
                $deletedata = $jquerydata + ['data-itemid' => $role->id];
                $icondelete = $renderer->action_icon(
                    '#',
                    new pix_icon('t/delete', get_string('delete')),
                    null,
                    array_merge($deletedata, ['data-action' => 'deleteitem'])
                );
            }

            $html .= html_writer::span("\"{$role->localname}\"" . $icondelete, 'ruleparamcontainer');

            if (next($roles)) {
                $html .= html_writer::span(', ', 'ruleparamseparator');
            }
        }

        // Add actions if draft.
        if ($form->get_version()->is_draft()) {
            $html .= $renderer->action_icon(
                '#',
                new pix_icon('t/edit', get_string('edit')),
                null,
                array_merge($jquerydata, ['data-action' => 'edit'])
            );

            $html .= $renderer->action_icon(
                '#',
                new pix_icon('t/delete', get_string('delete')),
                null,
                array_merge($jquerydata, ['data-action' => 'delete'])
            );
        }
        $mform->addElement('html', html_writer::tag('li', $html, ['id' => 'rule' . $this->id]));
    }

    /**
     * @return string
     */
    public function get_name(): string
    {
        return get_string('rulerole', 'mod_assessment');
    }

    /**
     * @return array
     * @global moodle_database $DB
     */
    public function get_roles(): array
    {
        global $DB;

        $roleids = json_decode($this->value);
        list($rolesql, $params) = $DB->get_in_or_equal($roleids, SQL_PARAMS_NAMED);
        $roledata = $DB->get_records_sql("SELECT * FROM {role} WHERE id {$rolesql}", $params);

        return role_fix_names($roledata);
    }

    /**
     * @param attempt $attempt
     * @return array [$sql, $params]
     * @global moodle_database $DB
     */
    public function get_sql(attempt $attempt): array
    {
        global $DB;

        $context = $this->get_context();
        $parents = $context->get_parent_context_ids(true);
        list($contextsql, $contextparams) = $DB->get_in_or_equal($parents, SQL_PARAMS_NAMED, 'r');
        list($rulesql, $ruleparams) = $DB->get_in_or_equal(json_decode($this->value), SQL_PARAMS_NAMED, null, true);

        if ($this->operator == self::OP_IN_EQUAL) {
            $sql = "auser.id IN (SELECT DISTINCT userid FROM {role_assignments} WHERE roleid {$rulesql} AND contextid {$contextsql})";
        } elseif ($this->operator == self::OP_IN_NOTEQUAL) {
            $sql = "auser.id NOT IN (SELECT DISTINCT userid FROM {role_assignments} WHERE roleid {$rulesql} AND contextid {$contextsql})";
        } else {
            throw new Exception("Unknown rule operator: {$this->operator}"); // TODO: translate!
        }

        return [$sql, $contextparams + $ruleparams];
    }

    /**
     * @param int $userid
     * @param attempt $attempt
     * @return bool
     */
    public function is_user_role_valid(int $userid, attempt $attempt): bool
    {
        $context = $this->get_context();

        $roles = $this->get_roles();
        foreach ($roles as $role) {
            $hasrole = user_has_role_assignment($userid, $role->id, $context->id);
            if ($hasrole && $this->operator == self::OP_IN_NOTEQUAL) {
                return false;
            } elseif (!$hasrole && $this->operator == self::OP_IN_NOTEQUAL) {
                return false;
            }
        }

        return true;
    }

    /**
     *
     * @return int Number of users this rule may select, individually
     */
    public function get_potential_users_count(): int
    {
        global $DB;

        $check_equal = ($this->operator == self::OP_IN_EQUAL);

        $context = $this->get_context();
        $parents = $context->get_parent_context_ids(true);
        list($contextsql, $contextparams) = $DB->get_in_or_equal($parents, SQL_PARAMS_NAMED, 'r');
        list($rulesql, $ruleparams) = $DB->get_in_or_equal(json_decode($this->value), SQL_PARAMS_NAMED, null, $check_equal);

        $sql = "SELECT COUNT(DISTINCT ra.userid) AS count FROM {role_assignments} ra WHERE ra.roleid {$rulesql} AND contextid {$contextsql}";
        $params = $contextparams + $ruleparams;
        return $DB->count_records_sql($sql, $params);
    }

    /**
     * @return context_module
     */
    public function get_context(): context_module
    {
        $ruleset = model\ruleset::instance(['id' => $this->rulesetid], MUST_EXIST);
        $version = model\version::instance(['id' => $ruleset->versionid], MUST_EXIST);
        $assessment = model\assessment::instance(['id' => $version->assessmentid], MUST_EXIST);

        return context_module::instance($assessment->get_cmid());
    }

    /**
     * @return stdClass[]
     * @global moodle_database $DB
     */
    public function get_selected(): array
    {
        global $DB;

        $selected = [];

        if (empty($this->value)) {
            return $selected;
        }

        $roleids = json_decode($this->value);

        list($rolesql, $params) = $DB->get_in_or_equal($roleids, SQL_PARAMS_NAMED);
        $roledata = $DB->get_records_sql("SELECT * FROM {role} WHERE id {$rolesql}", $params);
        $roles = role_fix_names($roledata);

        foreach ($roles as $role) {
            $selectedobj = new stdClass();
            $selectedobj->id = $role->id;
            $selectedobj->fullname = $role->localname;
            $selected[] = $selectedobj;
        }

        return $selected;
    }

    /**
     * @return string
     */
    public function get_type(): string
    {
        return 'role';
    }

    /**
     * @param string $value
     * @return self
     */
    public function encode_value(string $value): rule
    {
        $roles = explode(',', $value);
        $this->value = json_encode($roles);
        return $this;
    }
}
