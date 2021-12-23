<?php
/**
 * Composite Decorator
 *
 * @package   isotopeprovider_required_learning
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace isotopeprovider_required_learning\Decorator;

defined('MOODLE_INTERNAL') || die;

class CompositeDecorator implements DecoratorInterface
{
    /**
     * @var DecoratorInterface[]
     */
    protected $decorators;

    /**
     * CompositeDecorator constructor.
     * @param DecoratorInterface[] $decorators
     */
    public function __construct(array $decorators)
    {
        $this->decorators = $decorators;
    }

    /**
     * Return the given item decorated.
     *
     * @param object $item
     * @return object
     */
    public function decorate(object $item): object
    {
        foreach ($this->decorators as $decorator) {
            $item = $decorator->decorate($item);
        }

        return $item;
    }
}