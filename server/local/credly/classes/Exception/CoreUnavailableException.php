<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace local_credly\Exception;

class CoreUnavailableException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Service Unavailable', 503);
    }
}