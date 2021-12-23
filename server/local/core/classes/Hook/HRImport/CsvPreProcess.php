<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon.Thornett
 */

namespace local_core\Hook\HRImport;

use totara_core\hook\base;
use totara_sync_source;

class CsvPreProcess extends base
{
    private totara_sync_source $source;

    private string $file;

    public function __construct(totara_sync_source $source, string $file)
    {
        $this->source = $source;
        $this->file = $file;
    }

    public function getSource(): totara_sync_source
    {
        return $this->source;
    }

    public function getFile(): string
    {
        return $this->file;
    }
}