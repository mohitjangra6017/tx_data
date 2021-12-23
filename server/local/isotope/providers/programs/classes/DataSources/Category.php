<?php

namespace isotopeprovider_programs\DataSources;

use dml_exception;
use isotopeprovider_programs\Contracts\DataSource;

abstract class Category implements DataSource
{

    protected $db;
    protected $filters;

    /**
     * @param array $dataSet
     * @return array|mixed
     * @throws dml_exception
     */
    public function categoryCheck($dataSet = [])
    {
        global $DB;

        if (empty($dataSet)) {
            return $dataSet;
        }
        if (empty($this->filters['category-path'])) {
            return $dataSet;
        }
        $path = $this->filters['category-path'];

        $categoryIds = array_filter(explode(',', $path));
        $includedCategories = [];
        $excludedCategories = [];
        foreach ($categoryIds as $categoryId) {
            // Add validation check - will be an empty value if dialog saved with no items.
            if (empty($categoryId)) {
                continue;
            };

            if ($this->filters['category-path_op']) {
                if ($this->filters['category-path_rec']) {
                    $categoryChildren = $DB->get_records_sql(
                        "SELECT cc.id
                        FROM {course_categories} AS cc
                        WHERE id = ?
                        OR path LIKE ?",
                        [$categoryId, "%/$categoryId/%"]
                    );
                    $includedCategories = array_merge(array_keys($categoryChildren), $includedCategories);
                } else {
                    $includedCategories[] = $categoryId;
                }
            } else {
                if ($this->filters['category-path_rec']) {
                    $categoryChildren = $DB->get_records_sql(
                        "SELECT cc.id
                        FROM {course_categories} AS cc
                        WHERE id = ?
                        OR path LIKE ?",
                        [$categoryId, "%/$categoryId/%"]
                    );
                } else {
                    $categoryChildren = $DB->get_records_sql(
                        "SELECT cc.id
                        FROM {course_categories} AS cc
                        WHERE id = ?",
                        [$categoryId]
                    );

                }
                $excludedCategories = array_merge(array_keys($categoryChildren), $excludedCategories);
            }
        }

        if (!$this->filters['category-path_op']) {
            $allCategories = array_keys($DB->get_records_menu('course_categories', [], '', 'id'));
            $includedCategories = array_diff($allCategories, $excludedCategories);
        }

        foreach ($dataSet as $key => $data) {
            if (array_search($data->category, $includedCategories) === false) {
                unset($dataSet[$key]);
            }
        }

        return $dataSet;
    }
}
