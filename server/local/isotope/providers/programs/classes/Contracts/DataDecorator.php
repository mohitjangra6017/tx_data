<?php

namespace isotopeprovider_programs\Contracts;

interface DataDecorator
{
    /**
     * Do whatever needs doing to the collection of data items.
     *
     * @param $data
     * @param array $context
     * @return array
     */
    public function decorate($data, $context = array());

    /**
     * Config options valid for the Decorator.
     * @param array $config
     */
    public function setConfig($config);
}
