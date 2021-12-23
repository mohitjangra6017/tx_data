<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\model;

use Exception;

defined('MOODLE_INTERNAL') || die();

class import_error
{
    public const USER_NOT_FOUND = 400;
    public const LEARNER_NOT_FOUND = 401;
    public const LEARNER_NOT_ENROLLED = 402;
    public const ASSIGNMENT_EXISTS = 403;

    /** @var int|null */
    protected ?int $error;

    public function __construct(int $error = null)
    {
        if ($error && !array_key_exists($error, $this->get_errors())) {
            throw new Exception("Invalid error code: {$error}");
        }

        $this->error = $error;
    }

    public static function get_errors(): array
    {
        return [
            self::ASSIGNMENT_EXISTS => get_string('error:import_assignmentexists', 'assessment'),
            self::LEARNER_NOT_FOUND => get_string('error:import_learnernotfound', 'assessment'),
            self::LEARNER_NOT_ENROLLED => get_string('error:import_learnernotenrolled', 'assessment'),
            self::USER_NOT_FOUND => get_string('error:import_usernotfound', 'assessment'),
        ];
    }

    public function get_string(): string
    {
        if (!$this->error) {
            return '';
        }

        return self::get_errors()[$this->error];
    }

    public function value(): ?int
    {
        return $this->error;
    }
}
