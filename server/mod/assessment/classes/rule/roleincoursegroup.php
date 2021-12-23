<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

namespace mod_assessment\rule;

defined('MOODLE_INTERNAL') || die();

use context_course;
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

class roleincoursegroup extends rule
{

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

        $html = html_writer::span('', 'assignment-rule-type roleincoursegroup');

        if ($this->operator == static::OP_IN_EQUAL) {
            $html .= get_string('ruledesc_roleincoursegroupin', 'assessment') . ': ';
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
        return get_string('ruleroleincoursegroup', 'mod_assessment');
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

        $context = $this->get_course_context();
        list($contextsql, $contextparams) = $DB->get_in_or_equal([$context->id], SQL_PARAMS_NAMED, 'r');
        list($rulesql, $ruleparams) = $DB->get_in_or_equal(json_decode($this->value), SQL_PARAMS_NAMED, null, true);

        $learneridparam = $DB->get_unique_param();
        $ruleparams[$learneridparam] = $attempt->userid;

        $courseidparam = $DB->get_unique_param();
        $ruleparams[$courseidparam] = $this->get_courseid();

        if ($this->operator == self::OP_IN_EQUAL) {
            $sql = "auser.id IN 
                        (
                            SELECT DISTINCT ra.userid FROM {role_assignments} ra
                            JOIN {groups_members} gm ON gm.userid = ra.userid
                            JOIN {groups} g ON g.id = gm.groupid AND g.courseid = :{$courseidparam}
                            JOIN {groups_members} gm_lrn ON gm_lrn.userid = :{$learneridparam} AND gm_lrn.groupid = g.id
                            WHERE ra.roleid {$rulesql} AND ra.contextid {$contextsql}
                        )";
        } else {
            throw new Exception("Unknown rule operator: {$this->operator}"); // TODO: translate!
        }

        return [$sql, $contextparams + $ruleparams];
    }

    /**
     * Check if role_user and user associated with the attempt belong to the same course group
     *
     * @param int $role_userid
     * @param attempt $attempt
     *
     * @return bool
     */
    public function is_role_user_in_same_group(int $role_userid, attempt $attempt): bool
    {
        $courseid = $this->get_courseid();
        $userid = $attempt->userid;

        $user_groups = groups_get_all_groups($courseid, $userid);
        $user_group_ids = array_keys($user_groups);

        $role_user_groups = groups_get_all_groups($courseid, $role_userid);
        $role_user_group_ids = array_keys($role_user_groups);

        $common_groups = array_intersect($user_group_ids, $role_user_group_ids);

        return !empty($common_groups);
    }

    /**
     * @param int $userid
     * @param attempt $attempt
     * @return bool
     */
    public function is_user_role_valid(int $userid, attempt $attempt): bool
    {
        $context = $this->get_course_context();

        $roles = $this->get_roles();
        foreach ($roles as $role) {
            $hasrole = user_has_role_assignment($userid, $role->id, $context->id);
            // we're only allowing "is equal to"
            if ($hasrole && $this->operator == self::OP_IN_EQUAL) {
                // check role user and attempt user (learner) are in same course group
                if ($this->is_role_user_in_same_group($userid, $attempt)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     *
     * @return int Number of users this rule may select, individually
     */
    public function get_potential_users_count(): int
    {
        global $DB;

        $check_equal = ($this->operator == self::OP_IN_EQUAL);

        $context = $this->get_course_context();
        list($contextsql, $contextparams) = $DB->get_in_or_equal([$context->id], SQL_PARAMS_NAMED, 'r');
        list($rulesql, $ruleparams) = $DB->get_in_or_equal(json_decode($this->value), SQL_PARAMS_NAMED, null, $check_equal);

        $courseidparam = $DB->get_unique_param();
        $ruleparams[$courseidparam] = $this->get_courseid();

        $sql = "SELECT COUNT(DISTINCT ra.userid) AS count FROM {role_assignments} ra
                            JOIN {groups_members} gm ON gm.userid = ra.userid
                            JOIN {groups} g ON g.id = gm.groupid AND g.courseid = :{$courseidparam}
                            WHERE ra.roleid {$rulesql} AND ra.contextid {$contextsql}";

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

    public function get_courseid()
    {

        static $courseid = null;

        if (is_null($courseid)) {
            $ruleset = model\ruleset::instance(['id' => $this->rulesetid], MUST_EXIST);
            $version = model\version::instance(['id' => $ruleset->versionid], MUST_EXIST);
            $assessment = model\assessment::instance(['id' => $version->assessmentid], MUST_EXIST);
            $courseid = $assessment->course;
        }

        return $courseid;
    }

    public function get_course_context()
    {
        $courseid = $this->get_courseid();
        return context_course::instance($courseid);
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
        return 'roleincoursegroup';
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
