<?php
/**
 * DecoratorInterface.php
 *
 * @package
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace isotopeprovider_required_learning\Decorator;

defined('MOODLE_INTERNAL') || die;

interface DecoratorInterface
{
    /**
     * Return the given item decorated.
     *
     * @param object $item
     * @return object
     */
    public function decorate(object $item): object;
}