<?php
/**
 * Certification Status Decorator
 *
 * @package   isotopeprovider_required_learning
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace isotopeprovider_required_learning\Decorator;

use isotopeprovider_required_learning\Provider;

defined('MOODLE_INTERNAL') || die;

global $CFG;

require_once($CFG->dirroot . '/totara/certification/lib.php');

class CertificationStatusDecorator implements DecoratorInterface
{
    protected $statusMap = [
        CERTIFSTATUS_UNSET => Provider::STATUS_UNSET,
        CERTIFSTATUS_ASSIGNED => Provider::STATUS_NOTSTARTED,
        CERTIFSTATUS_INPROGRESS => Provider::STATUS_STARTED,
        CERTIFSTATUS_COMPLETED => Provider::STATUS_COMPLETED,
        CERTIFSTATUS_EXPIRED => Provider::STATUS_EXPIRED,
    ];

    /**
     * {@inheritdoc}
     */
    public function decorate($item): object
    {
        $item->status =
            isset($this->statusMap[$item->status]) ? $this->statusMap[$item->status] : Provider::STATUS_UNKNOWN;

        return $item;
    }
}