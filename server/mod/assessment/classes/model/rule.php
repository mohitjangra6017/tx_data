<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

namespace mod_assessment\model;

use coding_exception;
use mod_assessment\entity\SimpleDBO;
use mod_assessment\form\rules;
use moodle_database;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/cohort/lib.php');

abstract class rule extends SimpleDBO
{

    public const TABLE = 'assessment_rule';

    public const OP_AND = 0;
    public const OP_OR = 10;

    public const OP_IN_EQUAL = 100;
    public const OP_IN_NOTEQUAL = 101;

    /** @var int $rulesetid */
    public int $rulesetid;

    /** @var string $type */
    public string $type;

    /** @var int operator */
    public int $operator;

    /** @var string $value */
    public string $value;

    public function __construct()
    {
        $this->type = $this->get_type();
        parent::__construct();
    }

    /**
     * @param string $type
     * @return rule
     */
    public static function class_from_type(string $type): rule
    {
        $class = '\\mod_assessment\\rule\\' . $type;
        return new $class();
    }

    public static function instance($conditions, $strictness = IGNORE_MISSING): ?rule
    {
        global $DB;
        if (!$data = $DB->get_record(static::TABLE, $conditions, '*', $strictness)) {
            return null;
        }

        $object = self::class_from_type($data->type);
        $object->set_data($data);
        return $object;
    }

    public static function instances($conditions): array
    {
        global $DB;

        $instances = [];
        $records = $DB->get_records(static::TABLE, $conditions, 'id');
        foreach ($records as $data) {
            $object = self::class_from_type($data->type);
            $object->set_data($data);
            $instances[$object->id] = $object;
        }
        return $instances;
    }

    public function delete(): bool
    {
        $success = parent::delete();

        // Delete ruleset if ruleset is empty.
        $ruleset = ruleset::instance(['id' => $this->rulesetid]);
        $rules = self::instances(['rulesetid' => $ruleset->id]);
        if ($success && empty($rules)) {
            $success = $ruleset->delete();
        }

        return $success;
    }

    /**
     * @param rules $form
     * @global moodle_database $DB
     */
    abstract public function add_to_form(rules $form);

    /**
     * @return string
     */
    abstract public function get_name(): string;

    abstract public function get_selected();

    /**
     * @param attempt $attempt
     * @return array [$sql, $params]
     * @global moodle_database $DB
     */
    abstract public function get_sql(attempt $attempt): array;

    /**
     * @return string
     */
    abstract public function get_type(): string;

    /**
     * @param int $userid
     * @param attempt $attempt
     */
    abstract public function is_user_role_valid(int $userid, attempt $attempt);

    /**
     *
     * @return int Number of users this rule may select, individually
     */
    public function get_potential_users_count(): int
    {
        return -1; // -1 = unknown
    }

    /**
     * @param int $operator
     * @return self
     * @throws coding_exception
     */
    public function set_operator(int $operator): rule
    {
        // Validate operator.
        if (!in_array($operator, [self::OP_IN_EQUAL, self::OP_IN_NOTEQUAL])) {
            throw new coding_exception('Invalid operator for rule type(' . $this->type . '): (' . $operator . ')');
        }
        $this->operator = $operator;
        return $this;
    }

    /**
     * @param int $rulesetid
     * @return self
     */
    public function set_rulesetid(int $rulesetid): rule
    {
        $this->rulesetid = $rulesetid;
        return $this;
    }

    /**
     * @param string $value
     * @return self
     */
    abstract public function encode_value(string $value): rule;

}

