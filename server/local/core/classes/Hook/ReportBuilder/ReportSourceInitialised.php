<?php

namespace local_core\Hook\ReportBuilder;

use rb_base_source;
use totara_core\hook\base;

class ReportSourceInitialised extends base
{
    /** @var rb_base_source */
    protected rb_base_source $source;

    public function __construct(rb_base_source $source)
    {
        $this->source = $source;
    }

    /**
     * @return rb_base_source
     */
    public function getSource(): rb_base_source
    {
        return $this->source;
    }
}