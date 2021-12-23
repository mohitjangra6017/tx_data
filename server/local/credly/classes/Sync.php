<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace local_credly;

use local_credly\entity\Badge;

class Sync
{
    private Endpoint $endpoint;

    public function __construct()
    {
        $this->endpoint = Helper::createCredlyEndpoint();
    }

    public function synchroniseWithCredly(): void
    {
        $response = $this->endpoint->getBadges();
        $firstPage = $response->metadata->current_page;
        $lastPage = $response->metadata->total_pages;

        $allOurBadges = Badge::repository()->fetchAllBadges();
        $allCredlyBadges = [];

        for ($i = $firstPage; $i <= $lastPage; $i++) {
            $currentBatch = $this->endpoint->getBadges('', $i)->data;
            $currentBatch = array_combine(array_column($currentBatch, 'id'), $currentBatch);
            $allCredlyBadges = array_merge($allCredlyBadges, $currentBatch);
        }

        foreach ($allCredlyBadges as $credlyBadge) {
            if (isset($allOurBadges[$credlyBadge->id])) {
                $allOurBadges[$credlyBadge->id]->name = $credlyBadge->name;
                $allOurBadges[$credlyBadge->id]->state = $credlyBadge->state;
                if ($credlyBadge->state != 'active') {
                    $allOurBadges[$credlyBadge->id]->courseid = null;
                    $allOurBadges[$credlyBadge->id]->programid = null;
                    $allOurBadges[$credlyBadge->id]->certificationid = null;
                }
                $allOurBadges[$credlyBadge->id]->save();
            } else {
                $newBadge = new Badge();
                $newBadge->credlyid = $credlyBadge->id;
                $newBadge->name = $credlyBadge->name;
                $newBadge->state = $credlyBadge->state;
                $newBadge->save();
            }
        }
    }
}