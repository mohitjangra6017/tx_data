<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon.Thornett
 */


namespace local_core\Hook\HRImport;

use totara_core\hook\base;
use totara_sync_source;

class CsvPostProcess extends base
{
    private totara_sync_source $source;

    /** @var resource */
    private $file;

    public function __construct(totara_sync_source $source, $file)
    {
        $this->source = $source;
        $this->file = $file;
    }

    public function getSource(): totara_sync_source
    {
        return $this->source;
    }

    /**
     * @return resource
     */
    public function getFile()
    {
        return $this->file;
    }
}