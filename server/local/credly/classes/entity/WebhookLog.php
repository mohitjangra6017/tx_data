<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace local_credly\entity;

use core\orm\entity\entity;
use core\orm\entity\relations\belongs_to;

/**
 * @property string $eventid
 * @property int $badgeid
 * @property string $eventtype
 * @property string $referer
 * @property int $occurredat
 *
 * @property-read int $createdtime
 */
class WebhookLog extends entity
{
    public const TABLE = 'local_credly_webhook_logs';

    public const CREATED_TIMESTAMP = 'createdtime';

    public const SET_UPDATED_WHEN_CREATED = true;

    public function badge(): belongs_to
    {
        return $this->belongs_to(Badge::class, 'badgeid');
    }
}
