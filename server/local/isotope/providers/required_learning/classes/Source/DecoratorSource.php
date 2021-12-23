<?php
/**
 * DecoratorSource.php
 *
 * @package
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace isotopeprovider_required_learning\Source;

use isotopeprovider_required_learning\Decorator\DecoratorInterface;

defined('MOODLE_INTERNAL') || die;

class DecoratorSource implements SourceInterface
{
    /**
     * @var DecoratorInterface
     */
    protected $decorator;

    /**
     * @var SourceInterface
     */
    protected $source;

    /**
     * DecoratorSource constructor.
     * @param DecoratorInterface $decorator
     * @param SourceInterface $source
     */
    public function __construct(DecoratorInterface $decorator, SourceInterface $source)
    {
        $this->decorator = $decorator;
        $this->source = $source;
    }

    /**
     * Get the data provided by this data source.
     *
     * @return array
     */
    public function getData(): array
    {
        return array_map([$this->decorator, 'decorate'], $this->source->getData());
    }
}