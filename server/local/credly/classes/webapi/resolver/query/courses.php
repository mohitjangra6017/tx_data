<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_credly\webapi\resolver\query;

use container_course\course;
use core\orm\query\builder;
use core\webapi\execution_context;
use core\webapi\middleware;
use core\webapi\middleware\require_login;
use core\webapi\query_resolver;
use core\webapi\resolver\has_middleware;

final class courses implements query_resolver, has_middleware
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
        $courses = builder::table('course', 'c')
            ->select(['c.id', 'c.fullname AS name', 'cc.name AS category'])
            ->join(['course_categories', 'cc'], 'c.category', '=', 'cc.id')
            ->left_join(['local_credly_badges', 'b'], 'c.id', '=', 'b.courseid')
            ->where_null('b.id')
            ->where('c.containertype', '=', course::get_type())
            ->order_by_raw('lower(c.fullname)')
            ->fetch();

        return [
            'metadata' => [
                'total' => count($courses),
            ],
            'items' => $courses,
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