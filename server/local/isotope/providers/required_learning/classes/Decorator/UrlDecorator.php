<?php
/**
 * UrlDecorator.php
 *
 * @package
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace isotopeprovider_required_learning\Decorator;

defined('MOODLE_INTERNAL') || die;

class UrlDecorator implements DecoratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function decorate(object $item): object
    {
        $item->url = '/totara/program/required.php?id=' . $item->id;

        return $item;
    }
}