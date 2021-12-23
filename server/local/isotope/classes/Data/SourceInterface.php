<?php
/**
 * @copyright City & Guilds Kineo 2019
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_isotope\Data;

interface SourceInterface
{
    /**
     * Get the data provided by this data source.
     *
     * @return array
     */
    public function getData();

    /**
     * Set the value of the specified filter.
     *
     * @param string $filterName
     * @param mixed $value
     * @return $this
     */
    public function setFilter($filterName, $value);
}