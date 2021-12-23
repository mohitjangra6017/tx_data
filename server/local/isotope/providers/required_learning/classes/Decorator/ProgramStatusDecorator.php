<?php
/**
 * Program Status Decorator
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

require_once($CFG->dirroot . '/totara/program/lib.php');

class ProgramStatusDecorator implements DecoratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function decorate(object $item): object
    {
        if ($item->status == STATUS_PROGRAM_COMPLETE) {
            $item->status = Provider::STATUS_COMPLETED;
        } else if ($item->status == STATUS_PROGRAM_INCOMPLETE) {
            $item->status = $item->completion->timestarted > 0 ? Provider::STATUS_STARTED : Provider::STATUS_NOTSTARTED;
        } else {
            $item->status = Provider::STATUS_UNKNOWN;
        }

        return $item;
    }
}