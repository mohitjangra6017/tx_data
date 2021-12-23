<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon Thornett <simon.thornett@kineo.com>
 */

namespace local_core\Hook;

use totara_core\hook\base;

class AfterRequireLogin extends base
{
    public $courseOrId, $autoLoginGuest, $cm, $setWantsUrlToMe, $preventRedirect;

    public function __construct(
        $courseOrId = null,
        $autoLoginGuest = null,
        $cm = null,
        $setWantsUrlToMe = null,
        $preventRedirect = null
    )
    {
        $this->courseOrId = $courseOrId;
        $this->autoLoginGuest = $autoLoginGuest;
        $this->cm = $cm;
        $this->setWantsUrlToMe = $setWantsUrlToMe;
        $this->preventRedirect = $preventRedirect;
    }
}