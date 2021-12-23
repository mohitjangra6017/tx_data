<?php

namespace local_credly\Observer;

use core\orm\query\builder;
use local_credly\entity\Badge;
use local_credly\entity\BadgeIssue;

class Learning
{
    public static function onProgramDeleted(\totara_program\event\program_deleted $event)
    {
        $eventData = $event->get_data();

        if (!empty($eventData['other']['certifid'])) {
            $badge = Badge::repository()->findByCertificationId($eventData['other']['certifid']);
            if (!$badge) {
                return;
            }
            $badge->certificationid = null;
        } else {
            $badge = Badge::repository()->findByProgramId($eventData['objectid']);
            if (!$badge) {
                return;
            }
            $badge->programid = null;
        }

        $badge->save();
    }

    public static function onCourseDeleted(\core\event\course_deleted $event)
    {
        $eventData = $event->get_data();

        $badge = Badge::repository()->findByCourseId($eventData['objectid']);
        if (!$badge) {
            return;
        }
        $badge->courseid = null;
        $badge->save();
    }

    public static function onCertificationCompleted(\totara_program\event\program_completed $event)
    {
        $eventData = $event->get_data();

        if (empty($eventData['other']['certifid'])) {
            return;
        }

        $userId = $eventData['userid'];
        $certificationid = $eventData['other']['certifid'];

        if (!$badgeIssue = BadgeIssue::repository()->findByUserIdAndCertificationId($certificationid, $userId)) {
            return;
        }

        $certifCompletion = builder::table('certif_completion')
                                   ->where('certifid', '=', $certificationid)
                                   ->where('userid', '=', $userId)
                                   ->one();

        $badgeIssue->status = BadgeIssue::STATUS_REPLACE;
        $badgeIssue->issuetime = $certifCompletion->timecompleted;
        $badgeIssue->timeexpires = $certifCompletion->timeexpires;
        $badgeIssue->save();
    }
}