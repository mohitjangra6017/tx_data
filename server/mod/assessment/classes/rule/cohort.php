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

use coding_exception;
use html_writer;
use mod_assessment\form\rules;
use mod_assessment\model\attempt;
use mod_assessment\model\rule;
use moodle_database;
use moodle_page;
use pix_icon;
use stdClass;

class cohort extends rule
{

    /**
     * @param rules $form
     * @global moodle_page $PAGE
     * @global moodle_database $DB
     */
    public function add_to_form(rules $form)
    {
        global $DB, $PAGE;

        $mform = $form->_form;
        $renderer = $PAGE->get_renderer('assessment');

        $jquerydata = ['data-type' => $this->type, 'data-versionid' => $form->get_version()->id, 'data-ruleid' => $this->id];

        $html = html_writer::span('', 'assignment-rule-type cohort');

        if ($this->operator == static::OP_IN_EQUAL) {
            $html .= get_string('ruledesc_cohortin', 'assessment') . ': ';
        } elseif ($this->operator == static::OP_IN_NOTEQUAL) {
            $html .= get_string('ruledesc_cohortnotin', 'assessment') . ': ';
        }

        // Convert values to list of cohort names.
        $cohortids = json_decode($this->value);
        while ($cohortid = current($cohortids)) {
            $cohort = $DB->get_record('cohort', ['id' => $cohortid], '*', MUST_EXIST);

            $icondelete = '';
            if ($form->get_version()->is_draft()) {
                $deletedata = $jquerydata + ['data-itemid' => $cohortid, 'data-action' => 'deleteitem'];
                $icondelete = $renderer->action_icon(
                    '#',
                    new pix_icon('t/delete', get_string('delete')),
                    null,
                    $deletedata
                );
            }

            $html .= html_writer::span("\"$cohort->name ($cohort->idnumber)\"" . $icondelete, 'ruleparamcontainer');

            if (next($cohortids)) {
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
                array_merge($jquerydata,
                    ['data-action' => 'delete'])
            );
        }
        $mform->addElement('html', html_writer::tag('li', $html, ['id' => 'rule' . $this->id]));
    }

    /**
     * @return string
     */
    public function get_name(): string
    {
        return get_string('rulecohort', 'mod_assessment');
    }

    /**
     * @param attempt $attempt
     * @return array [$sql, $params]
     * @throws coding_exception
     * @global moodle_database $DB
     */
    public function get_sql(attempt $attempt): array
    {
        global $DB;

        list($rulesql, $params) = $DB->get_in_or_equal(json_decode($this->value), SQL_PARAMS_NAMED, null, true);
        if ($this->operator == self::OP_IN_EQUAL) {
            $sql = "auser.id IN (SELECT userid FROM {cohort_members} WHERE cohortid {$rulesql})";
        } else if ($this->operator == self::OP_IN_NOTEQUAL) {
            $sql = "auser.id NOT IN (SELECT userid FROM {cohort_members} WHERE cohortid {$rulesql})";
        } else {
            throw new coding_exception("Unknown rule operator: {$this->operator}");
        }

        return [$sql, $params];
    }

    /**
     *
     * @return int Number of users this rule may select, individually
     */
    public function get_potential_users_count(): int
    {
        global $DB;

        $check_equal = ($this->operator == self::OP_IN_EQUAL);
        list($rulesql, $params) = $DB->get_in_or_equal(json_decode($this->value), SQL_PARAMS_NAMED, null, $check_equal);

        $sql = "SELECT COUNT(cm.id) AS count FROM {cohort_members} cm WHERE cm.cohortid {$rulesql}";
        $count = $DB->count_records_sql($sql, $params);

        return $count;
    }

    /**
     * @param int $userid
     * @param attempt $attempt
     * @return bool
     */
    public function is_user_role_valid(int $userid, attempt $attempt): bool
    {
        $cohortids = json_decode($this->value);
        foreach ($cohortids as $cohortid) {
            $ismember = \cohort_is_member($cohortid, $userid);
            if ($ismember && $this->operator == self::OP_IN_NOTEQUAL) {
                return false;
            } elseif (!$ismember && $this->operator == self::OP_IN_EQUAL) {
                return false;
            }
        }
        return true;
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

        $cohortids = json_decode($this->value);
        foreach ($cohortids as $cohortid) {
            $selectedobj = new stdClass();
            $selectedobj->id = $cohortid;
            $selectedobj->fullname = $DB->get_field('cohort', 'name', ['id' => $cohortid]);
            $selected[] = $selectedobj;
        }
        return $selected;
    }

    /**
     * @return string
     */
    public function get_type(): string
    {
        return 'cohort';
    }

    /**
     * @param string $value
     * @return self;
     */
    public function encode_value(string $value): rule
    {
        $cohorts = explode(',', $value);
        $this->value = json_encode($cohorts);
        return $this;
    }
}