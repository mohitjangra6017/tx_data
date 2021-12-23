<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_credly\entity;

use core\orm\entity\repository;
use core\orm\query\builder;
use core\orm\query\field;
use core\orm\query\raw_field;

class BadgeRepository extends repository
{
    public function findByCredlyId(string $credlyId, bool $strict = false): ?Badge
    {
        return builder::table($this->get_table())
            ->where('credlyid', $credlyId)
            ->map_to(Badge::class)
            ->one($strict);
    }

    public function findByProgramId(int $programId, bool $strict = false): ?Badge
    {
        return builder::table($this->get_table())
            ->where('programid', $programId)
            ->map_to(Badge::class)
            ->one($strict);
    }

    public function findByCertificationId(int $certificationId, bool $strict = false): ?Badge
    {
        return builder::table($this->get_table())
                      ->where('certificationid', $certificationId)
                      ->map_to(Badge::class)
                      ->one($strict);
    }

    public function findByCourseId(int $courseId, bool $strict = false): ?Badge
    {
        return builder::table($this->get_table())
            ->where('courseid', $courseId)
            ->map_to(Badge::class)
            ->one($strict);
    }

    /**
     * @param int[] $credlyIds
     * @return Badge[]
     */
    public function findManyByCredlyIds(array $credlyIds): array
    {
        return builder::table($this->get_table())
            ->select_raw('credlyid, *')
            ->where_in('credlyid', $credlyIds)
            ->map_to(Badge::class)
            ->fetch();
    }

    /**
     * @param int|null $offset
     * @param int|null $limit
     * @param bool $activeOnly
     * @return array
     */
    public function fetchAllBadges(int $offset = null, int $limit = null, string $type = null, string $search = null, bool $activeOnly = false): array
    {
        return $this->getBadgeQuery($type, $search, $activeOnly)
            ->offset($offset)
            ->limit($limit)
            ->order_by('id')
            ->map_to(Badge::class)
            ->fetch();
    }

    /**
     * @param string|null $type
     * @param string|null $search
     * @param bool $activeOnly
     * @return int
     */
    public function getCountOfBadges(string $type = null, string $search = null, bool $activeOnly = false): int
    {
        return $this->getBadgeQuery($type, $search, $activeOnly)->count();
    }

    /**
     * @param string|null $type
     * @param string|null $search
     * @param bool $activeOnly
     * @return builder
     */
    private function getBadgeQuery(string $type = null, string $search = null, bool $activeOnly = false): builder
    {
        return builder::table($this->get_table(), 'credly')
            ->select_raw('credlyid, credly.*')
            ->when($activeOnly, function (builder $builder) {
                $builder->where('state', 'active');
            })
            ->when($type === 'program', function (builder $builder) {
                $builder->where_not_null('programid');
            })
            ->when($type === 'course', function (builder $builder) {
                $builder->where_not_null('courseid');
            })
            ->when($type === 'certification', function (builder $builder) {
                $builder->where_not_null('certificationid');
            })
            ->when($type === 'unlinked', function (builder $builder) {
                $builder->where_null('programid')->where_null('courseid')->where_null('certificationid');
            })
            ->when(!empty($search), function (builder $builder) use ($search) {
                $builder
                    ->left_join('prog', 'programid', 'id')
                    ->left_join('course', 'courseid', 'id')
                    ->left_join(['prog', 'certif'], 'certificationid', 'certifid')
                    ->nested_where(function (builder $builder) use ($search) {
                        $builder->where_like(new raw_field('LOWER(name)'), mb_strtolower($search))
                            ->or_where_like('credlyid', mb_strtolower($search))
                            ->or_where_like(new raw_field('LOWER(prog.fullname)'), mb_strtolower($search))
                            ->or_where_like(new raw_field('LOWER(course.fullname)'), mb_strtolower($search))
                            ->or_where_like(new raw_field('LOWER(certif.fullname)'), mb_strtolower($search));
                    });
            });
    }
}