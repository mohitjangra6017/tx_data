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

namespace mod_assessment\model;

use coding_exception;
use mod_assessment\entity\SimpleDBO;
use moodle_database;

defined('MOODLE_INTERNAL') || die();

class ruleset extends SimpleDBO
{

    public const TABLE = 'assessment_ruleset';

    /** @var int $versionid */
    public int $versionid;

    /** @var int operator */
    public int $operator;

    /** @var int role */
    public int $role;

    /**
     * @param version $version
     * @param int $role DEVTIOTIS2 see constants in \mod_assessment\helper\role
     * @return self[]
     * @global moodle_database $DB
     */
    public static function instances_from_version(version $version, $role = null): array
    {
        global $DB;

        $instances = [];

        $params = ['versionid' => $version->id];
        if (!empty($role)) {
            $params['role'] = $role;
        }
        $records = $DB->get_records(self::TABLE, $params, 'id');

        foreach ($records as $record) {
            $instance = new static();
            $instance->set_data($record);
            $instances[] = $instance;
        }

        return $instances;
    }

    public function delete(): bool
    {
        global $DB;

        $transaction = $DB->start_delegated_transaction();

        // Delete all child rules.
        $rules = rule::instances(['rulesetid' => $this->id]);
        foreach ($rules as $rule) {
            $rule->delete();
        }
        parent::delete();

        $transaction->allow_commit();

        return true;
    }

    /**
     * @param int $operator
     * @return self
     * @throws coding_exception
     */
    public function set_operator(int $operator): ruleset
    {
        // Validate operator.
        if (!in_array($operator, [rule::OP_AND, rule::OP_OR])) {
            throw new coding_exception('Invalid operator for evaluator ruleset(' . $this->id . '): (' . $operator . ')');
        }
        $this->operator = $operator;
        return $this;
    }

    /**
     * @param int $versionid
     * @return self
     */
    public function set_versionid(int $versionid): ruleset
    {
        $this->versionid = $versionid;
        return $this;
    }

    /**
     * @param int $role
     * @return self
     */
    public function set_role(int $role): ruleset
    {
        $this->role = $role;
        return $this;
    }
}

