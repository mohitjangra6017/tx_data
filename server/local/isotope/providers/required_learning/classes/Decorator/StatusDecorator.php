<?php
/**
 * Status Decorator
 *
 * @package   isotopeprovider_required_learning
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace isotopeprovider_required_learning\Decorator;

defined('MOODLE_INTERNAL') || die;

class StatusDecorator implements DecoratorInterface
{
    /**
     * @var DecoratorInterface
     */
    protected $programStatusDecorator;

    /**
     * @var DecoratorInterface
     */
    protected $certificationStatusDecorator;

    /**
     * StatusDecorator constructor.
     * @param DecoratorInterface $programStatusDecorator
     * @param DecoratorInterface $certificationStatusDecorator
     */
    public function __construct(
        DecoratorInterface $programStatusDecorator,
        DecoratorInterface $certificationStatusDecorator
    ) {
        $this->programStatusDecorator = $programStatusDecorator;
        $this->certificationStatusDecorator = $certificationStatusDecorator;
    }

    /**
     * {@inheritdoc}
     */
    public function decorate(object $item): object
    {
        return $item->certifid > 0 ? $this->certificationStatusDecorator->decorate($item) :
            $this->programStatusDecorator->decorate($item);
    }
}