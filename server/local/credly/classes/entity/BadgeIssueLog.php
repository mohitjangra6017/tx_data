<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_credly\entity;

use core\orm\entity\entity;
use core\orm\entity\relations\belongs_to;

/**
 * @property int $badgeissueid
 * @property BadgeIssue $badgeissue
 * @property string $status
 * @property string|null $response
 *
 * @property-read $createdtime
 */
class BadgeIssueLog extends entity
{
    public const TABLE = 'local_credly_issue_logs';

    public const CREATED_TIMESTAMP = 'createdtime';

    public function badgeissue(): belongs_to
    {
        return $this->belongs_to(BadgeIssue::class, 'badgeissueid');
    }
}