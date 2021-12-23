<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_credly\webapi\resolver\query;

use core\webapi\execution_context;
use core\webapi\middleware;
use core\webapi\middleware\require_login;
use core\webapi\query_resolver;
use core\webapi\resolver\has_middleware;
use local_credly\Badge\Collection;
use local_credly\entity\Badge;

final class badges implements query_resolver, has_middleware
{

    private const PAGINATION_SIZE = 50;

    /**
     * Query resolver.
     *
     * @param array $args
     * @param execution_context $ec
     * @return mixed
     */
    public static function resolve(array $args, execution_context $ec): array
    {
        $page = $args['page'] ?? 1;
        $filters = [
            'type' => null,
            'search' => '',
        ];
        foreach ($args['filters'] as $filter) {
            // Crunch the arrays here as filters is an array of arrays.
            $filters[$filter['name']] = $filter['value'];
        }

        if (!get_config('local_credly', 'enabled')) {
            return [
                'metadata' => ['total' => 0, 'next' => null],
                'items' => [],
            ];
        }

        $offset = self::PAGINATION_SIZE * ($page - 1);
        $collection = new Collection(
            Badge::repository()->fetchAllBadges(
                $offset,
                self::PAGINATION_SIZE,
                $filters['type'],
                $filters['search'],
                true
            ),
            Badge::repository()->getCountOfBadges(
                $filters['type'],
                $filters['search'],
                true
            )
        );
        $nextPage = $collection->total() > $page * self::PAGINATION_SIZE ? $page + 1 : null;

        return [
            'metadata' => [
                'total' => $collection->total(),
                'next' => $nextPage,
            ],
            'items' => $collection->map(
                function (Badge $item) {
                    return [
                        'id' => $item->id,
                        'credlyId' => $item->credlyid,
                        'name' => $item->name,
                        'programId' => $item->programid,
                        'certificationId' => $item->certificationid,
                        'courseId' => $item->courseid,
                        'linkedLearningName' => $item->linkedlearningname,
                        'linkType' => $item->linktype,
                    ];
                }
            )->to_array(),
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