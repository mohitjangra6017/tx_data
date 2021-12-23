<?php

namespace isotopeprovider_record_of_learning\DataSources;

use core_tag_tag;
use dml_exception;

class Program extends Category
{
    public function __construct()
    {
        $this->filters = [];
    }

    /**
     * @param string $filterName
     * @param mixed $value
     * @return $this
     */
    public function setFilter($filterName, $value): self
    {
        $this->filters[$filterName] = $value;
        return $this;
    }

    /**
     * @return array
     * @throws dml_exception
     */
    public function getData(): array
    {
        if (!isset($this->filters['userid'])) {
            throw new \Exception("You must set a userid filter.");
        }

        $tagsToFilter = $this->filters['tags'];

        $programs =
            prog_get_all_programs($this->filters['userid'], 'ORDER BY duedate ASC', '', '', '', false, true, false);

        if (empty($tagsToFilter)) {
            return $this->categoryCheck($programs);
        }

        $filteredPrograms = array_filter(
            $programs,
            function ($prog) use ($tagsToFilter) {
                $progTags = core_tag_tag::get_item_tags_array('totara_program', 'prog', $prog->id);
                if (array_intersect($tagsToFilter, $progTags)) {
                    return $prog;
                }
            }
        );

        return $this->categoryCheck($filteredPrograms);
    }
}
