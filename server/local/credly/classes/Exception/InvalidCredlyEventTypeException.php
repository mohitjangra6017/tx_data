<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace local_credly\Exception;

use Throwable;

class InvalidCredlyEventTypeException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Credly Event Type not implemented', 200);
    }
}