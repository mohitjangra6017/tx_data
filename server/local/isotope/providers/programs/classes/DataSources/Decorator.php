<?php

namespace isotopeprovider_programs\DataSources;

use isotopeprovider_programs\Contracts\DataDecorator;
use isotopeprovider_programs\Contracts\DataSource;

class Decorator implements DataSource
{

    private $decorator;
    private $dataSource;
    private $context = [];

    public function __construct(DataSource $dataSource, DataDecorator $decorator)
    {
        $this->decorator = $decorator;
        $this->dataSource = $dataSource;
    }

    /**
     * Get the data provided by this data source.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->decorator->decorate($this->dataSource->getData(), $this->context);
    }

    /**
     * Set the value of the specified filter.
     *
     * @param $filterName
     * @param $value
     * @return mixed
     */
    public function setFilter($filterName, $value)
    {
        $this->dataSource->setFilter($filterName, $value);
        $this->context[$filterName] = $value;
        return $this;
    }
}