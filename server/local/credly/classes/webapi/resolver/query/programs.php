<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_credly\webapi\resolver\query;

use core\orm\query\builder;
use core\webapi\execution_context;
use core\webapi\middleware;
use core\webapi\middleware\require_login;
use core\webapi\query_resolver;
use core\webapi\resolver\has_middleware;

final class programs implements query_resolver, has_middleware
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
        $programs = builder::table('prog', 'p')
            ->select(['p.id', 'p.fullname AS name', 'cc.name AS category'])
            ->join(['course_categories', 'cc'], 'p.category', '=', 'cc.id')
            ->left_join(['local_credly_badges', 'b'], 'p.id', '=', 'b.programid')
            ->where_null('p.certifid')
            ->where_null('b.id')
            ->order_by_raw('lower(p.fullname)')
            ->fetch();

        return [
            'metadata' => [
                'total' => count($programs),
            ],
            'items' => $programs,
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