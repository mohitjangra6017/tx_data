<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\model;

use Exception;

defined('MOODLE_INTERNAL') || die();

class role
{
    public const ADMIN = 100;
    public const EVALUATOR = 80;
    public const REVIEWER = 50;
    public const LEARNER = 10;

    /** @var int */
    protected int $role;

    public function __construct(int $role)
    {
        if (!array_key_exists($role, $this->get_roles())) {
            throw new Exception("Invalid role: {$role}");
        }

        $this->role = $role;
    }

    public static function get_roles($includeadmin = false): array
    {
        $roles = [
            self::LEARNER => get_string('rolelearner', 'mod_assessment'),
            self::EVALUATOR => get_string('roleevaluator', 'mod_assessment'),
            self::REVIEWER => get_string('rolereviewer', 'mod_assessment'),
        ];

        if ($includeadmin) {
            $roles[self::ADMIN] = get_string('roleadmin', 'mod_assessment');
        }

        return $roles;
    }

    public static function get_assignable_roles(): array
    {
        return [
            self::EVALUATOR => get_string('roleevaluator', 'mod_assessment'),
            self::REVIEWER => get_string('rolereviewer', 'mod_assessment'),
        ];
    }

    public static function get_read_only_roles(): array
    {
        return [
            self::REVIEWER => get_string('rolereviewer', 'mod_assessment'),
        ];
    }

    public function get_string()
    {
        return self::get_roles()[$this->role];
    }

    public function value(): int
    {
        return $this->role;
    }
}
