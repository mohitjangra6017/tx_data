<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_credly\testing;

use core\testing\component_generator;
use local_credly\entity\Badge;

final class generator extends component_generator
{
    public function createBadge(array $data = []): Badge
    {
        $badge = new Badge();
        $badge->credlyid = $data['credlyid'] ?? uniqid('badge');
        $badge->name = $data['name'] ?? 'Test Badge';
        $badge->programid = $data['programid'] ?? null;
        $badge->courseid = $data['courseid'] ?? null;
        $badge->certificationid = $data['certificationid'] ?? null;
        $badge->state = $data['state'] ?? 'active';

        $badge->save();

        return $badge;
    }
}