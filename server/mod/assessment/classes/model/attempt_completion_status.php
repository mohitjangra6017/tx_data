<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\model;

defined('MOODLE_INTERNAL') || die();

class attempt_completion_status
{

    public const COMPLETE = 50;
    public const READY = 25;
    public const WAITING = 10;

    protected int $status;

    public static function get_statuses(): array
    {
        return [
            self::READY => get_string('attempt_completion_status_ready', 'assessment'),
            self::WAITING => get_string('attempt_completion_status_waiting', 'assessment'),
            self::COMPLETE => get_string('attempt_completion_status_complete', 'assessment'),
        ];
    }

    public function __construct(int $status)
    {
        $this->status = $status;
    }

    public function get_string()
    {
        return self::get_statuses()[$this->status];
    }

    public function value(): int
    {
        return $this->status;
    }
}
