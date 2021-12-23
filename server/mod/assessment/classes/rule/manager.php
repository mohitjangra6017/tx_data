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
use totara_job\job_assignment;

class manager extends rule
{

    public const MANAGER_DIRECT = 'directmanager';
    public const MANAGER_INDIRECT = 'indirectmanager';

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

        $html = html_writer::span('', 'assignment-rule-type manager');

        if ($this->operator == static::OP_IN_EQUAL) {
            $html .= get_string('ruledesc_managerin', 'assessment') . ' ';
        } elseif ($this->operator == static::OP_IN_NOTEQUAL) {
            $html .= get_string('ruledesc_managernotin', 'assessment') . ' ';
        }

        $value = $this->get_value();
        try {
            $html .= get_string($value, 'assessment');
        } catch (Exception $ex) {
            $html .= $value;
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
        return get_string('rulemanager', 'mod_assessment');
    }

    /**
     * @param attempt $attempt
     * @return array [$sql, $params]
     * @global moodle_database $DB
     */
    public function get_sql(attempt $attempt): array
    {
        global $DB;

        $nullresult = array("1=0", []);

        // Find all job assignments for attempt user.
        $jobassignments = $DB->get_records('job_assignment', ['userid' => $attempt->get_userid()]);
        if (empty($jobassignments)) {
            return $nullresult;
        }

        if (self::MANAGER_DIRECT == $this->get_value()) {
            $jobassignments = array_map(function ($ja) {
                return $ja->managerjaid;
            }, $jobassignments);
            if (empty($jobassignments)) {
                return $nullresult;
            }

            list($insql, $ruleparams) = $DB->get_in_or_equal($jobassignments, SQL_PARAMS_NAMED);
            $sql = "SELECT DISTINCT ja.userid FROM {job_assignment} ja WHERE ja.id $insql";
        } elseif (self::MANAGER_INDIRECT == $this->get_value()) {
            $jaids = [];
            foreach ($jobassignments as $jobassignment) {
                $managerjapath = array_filter(explode('/', $jobassignment->managerjapath));
                $indirectjapath = array_slice($managerjapath, 0, count($managerjapath) - 2);

                foreach ($indirectjapath as $indirectjaid) {
                    $jaids[$indirectjaid] = $indirectjaid;
                }
            }
            if (empty($jaids)) {
                return $nullresult;
            }

            list($insql, $ruleparams) = $DB->get_in_or_equal($jaids, SQL_PARAMS_NAMED);
            $sql = "SELECT DISTINCT ja.userid FROM {job_assignment} ja WHERE ja.id $insql";
        } else {
            throw new Exception("Bad value detected in manager user assignment rule: {$this->get_value()}");
        }

        // Alter sql for inclusion/exclusion.
        if ($this->operator == self::OP_IN_EQUAL) {
            $rulesql = "auser.id IN ({$sql})";
        } elseif ($this->operator == self::OP_IN_NOTEQUAL) {
            $rulesql = "auser.id NOT IN ({$sql})";
        } else {
            throw new Exception("Unknown rule operator: {$this->operator}"); // TODO: translate.
        }

        return [$rulesql, $ruleparams];
    }

    // DEVIOTIS2 Start

    /**
     * @param int $userid
     * @param attempt $attempt
     * @return bool
     * @global moodle_database $DB
     */
    public function is_user_role_valid(int $userid, attempt $attempt): bool
    {
        global $DB;

        if ($this->get_value() == 'directmanager') {
            $ismanager = job_assignment::is_managing($userid, $attempt->userid);

            if ($ismanager && $this->operator == self::OP_IN_NOTEQUAL) {
                return false;
            } elseif (!$ismanager && $this->operator == self::OP_IN_EQUAL) {
                return false;
            }
        } elseif ($this->get_value() == 'indirectmanager') {
            list($sql, $params) = $this->get_sql($attempt);
            $params += ['userid' => $userid];
            return $DB->record_exists_sql("SELECT 1 FROM {user} AS auser WHERE {$sql} AND auser.id = :userid", $params);
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

        // If doing equals comparison, the rule will at most return one user. If not equals, it may return all active users.
        return ($this->operator == self::OP_IN_EQUAL ? 1 : $DB->count_records('user', ['deleted' => 0, 'suspended' => 0]));
    }
    // DEVIOTIS2 End

    /**
     * @return stdClass[]
     */
    public function get_selected(): array
    {
        return [];  // Nothing to select for manager type.
    }

    /**
     * @return string
     */
    public function get_type(): string
    {
        return 'manager';
    }

    public function get_value()
    {
        return json_decode($this->value);
    }

    /**
     * @param string $value
     * @return \self
     */
    public function encode_value(string $value): rule
    {
        $this->value = json_encode($value);
        return $this;
    }
}