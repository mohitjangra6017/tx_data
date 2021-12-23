<?php
/*
 * Copyright City & Guilds Kineo 2021
 * Author: Michael Geering <michael.geering@kineo.com>
 */

namespace local_credly\webapi\resolver\mutation;

use core\webapi\execution_context;
use core\webapi\middleware;
use core\webapi\middleware\require_login;
use core\webapi\mutation_resolver;
use core\webapi\resolver\has_middleware;
use local_credly\Endpoint;
use local_credly\entity\Badge;
use local_credly\Helper;

final class link_badge implements mutation_resolver, has_middleware
{
    /**
     * Query resolver.
     *
     * @param array $args
     * @param execution_context $ec
     * @return mixed
     */
    public static function resolve(array $args, execution_context $ec): array
    {
        if (!Helper::createCredlyEndpoint()->isEnabled()) {
            print_error('err:credly_not_enabled', 'local_credly');
        }

        $link = $args['link'];
        $credlyId = $link['credlyId'];

        $badge = Badge::repository()->findByCredlyId($credlyId);
        if (!$badge) {
            print_error('err:badge_not_found', 'local_credly');
        }

        switch ($link['learningType']) {
            case 'program':
                $badge->programid = $link['learningId'];
                $learningId = $badge->programid;
                break;
            case 'certification':
                $badge->certificationid = $link['learningId'];
                $learningId = $badge->certificationid;
                break;
            case 'course':
                $badge->courseid = $link['learningId'];
                $learningId = $badge->courseid;
                break;
            case 'unlinked':
                $badge->programid = null;
                $badge->certificationid = null;
                $badge->courseid = null;
                $learningId = null;
                break;
            default:
                print_error('err:unknown_learning_type', 'local_credly');
        }

        $badge->save();

        return [
            'credlyId' => $credlyId,
            'learningId' => $learningId,
            'learningName' => $badge->linkedlearningname,
            'learningType' => $badge->linktype,
        ];
    }

    /**
     * @return array|middleware[]
     */
    public static function get_middleware(): array
    {
        return [
            new require_login(),
        ];
    }
}