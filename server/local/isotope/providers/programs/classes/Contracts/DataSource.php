<?php

namespace isotopeprovider_programs\Contracts;

interface DataSource
{
    /**
     * Get the data provided by this data source.
     *
     * @return mixed
     */
    public function getData();

    /**
     * Set the value of the specified filter.
     *
     * @param $filterName
     * @param $value
     * @return mixed
     */
    public function setFilter($filterName, $value);
}
