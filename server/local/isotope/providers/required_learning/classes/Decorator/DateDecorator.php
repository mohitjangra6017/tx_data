<?php
/**
 * Date Decorator
 *
 * @package   isotopeprovider_required_learning
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace isotopeprovider_required_learning\Decorator;

use coding_exception;
use isotopeprovider_required_learning\Provider;

defined('MOODLE_INTERNAL') || die;

class DateDecorator implements DecoratorInterface
{
    /**
     * @var object
     */
    protected $cfg;

    /**
     * @var string
     */
    protected $format;

    /**
     * DateDecorator constructor.
     * @param object $cfg
     * @param string $format
     */
    public function __construct(object $cfg, string $format)
    {
        $this->cfg = $cfg;
        $this->format = $format;
    }

    /**
     * @param object $item
     * @return object
     * @throws coding_exception
     */
    public function decorate(object $item): object
    {
        if ($item->status == Provider::STATUS_COMPLETED) {
            $item->date = userdate($item->completion->timecompleted, $this->format, $this->cfg->timezone, false);
            $item->date = get_string('datecomplete', Provider::COMPONENT, $item->date);
        } else if ($item->status == Provider::STATUS_NOTSTARTED || $item->status == Provider::STATUS_STARTED) {
            if (empty($item->duedate) || $item->duedate == COMPLETION_TIME_NOT_SET) {
                $item->date = get_string('nodatedue', Provider::COMPONENT);
            } else {
                $item->date = userdate($item->duedate, $this->format, $this->cfg->timezone, false);
                $item->date = get_string('datedue', Provider::COMPONENT, $item->date);
            }
        } else {
            $item->date = '';
        }

        return $item;
    }
}