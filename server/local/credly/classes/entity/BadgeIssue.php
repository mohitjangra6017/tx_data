<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_credly\entity;

use core\entity\user;
use core\collection;
use core\orm\entity\entity;
use core\orm\entity\relations\belongs_to;
use core\orm\entity\relations\has_many;
use InvalidArgumentException;

/**
 * @property int $badgeid
 * @property string|null $issueid
 * @property int|null $programid
 * @property int|null $certificationid
 * @property int|null $courseid
 * @property int $userid
 * @property int $issuetime
 * @property int|null $timeexpires
 * @property string $status
 *
 * @property-read int $createdtime
 * @property-read int $updatedtime
 * @property-read user $user
 * @property-read Badge $badge
 * @property-read BadgeIssueLog[]|collection $logs
 *
 * @method static BadgeIssueRepository repository()
 */
class BadgeIssue extends entity
{

    public const TABLE = 'local_credly_badge_issues';

    public const CREATED_TIMESTAMP = 'createdtime';
    public const UPDATED_TIMESTAMP = 'updatedtime';

    public const SET_UPDATED_WHEN_CREATED = true;

    public const STATUS_NOT_SENT = 'not_sent';
    public const STATUS_RESEND = 'resend';
    public const STATUS_RECOVERABLE_FAILURE = 'recoverable_failure';
    public const STATUS_UNRECOVERABLE_FAILURE = 'unrecoverable_failure';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_REPLACE = 'replace';

    public static function repository_class_name(): string
    {
        return BadgeIssueRepository::class;
    }

    public function user(): belongs_to
    {
        return $this->belongs_to(user::class, 'userid');
    }

    public function badge(): belongs_to
    {
        return $this->belongs_to(Badge::class, 'badgeid');
    }

    public function logs(): has_many
    {
        return $this->has_many(BadgeIssueLog::class, 'badgeissueid');
    }

    public function addLog(BadgeIssueLog $log): void
    {
        $this->logs()->save($log);
        $this->status = $log->status;
    }

    public function set_status_attribute($value): void
    {
        self::validateStatus($value);

        $this->set_attribute_raw('status', $value, false);
    }

    public static function validateStatus(string $status): void
    {
        $validStatuses = [
            static::STATUS_NOT_SENT,
            static::STATUS_SUCCESS,
            static::STATUS_RECOVERABLE_FAILURE,
            static::STATUS_UNRECOVERABLE_FAILURE,
            static::STATUS_RESEND,
            static::STATUS_REPLACE,
        ];
        if (!in_array($status, $validStatuses)) {
            throw new InvalidArgumentException(
                sprintf('Status %s invalid for BadgeIssue, must be one of: %s', $status, implode(', ', $validStatuses))
            );
        }
    }

    public function isFailure(): bool
    {
        return self::statusIsFailure($this->status);
    }

    public function isSuccessful(): bool
    {
        return self::statusIsSuccessful($this->status);
    }

    public function isUnsent(): bool
    {
        return self::statusIsUnsent($this->status);
    }

    public static function statusIsFailure(string $status): bool
    {
        return $status === static::STATUS_UNRECOVERABLE_FAILURE || $status === static::STATUS_RECOVERABLE_FAILURE;
    }

    public static function statusIsSuccessful(string $status): bool
    {
        return $status === static::STATUS_SUCCESS;
    }

    public static function statusIsUnsent(string $status): bool
    {
        return $status === static::STATUS_NOT_SENT
               || $status === static::STATUS_RESEND
               || $status === static::STATUS_REPLACE;
    }
}


