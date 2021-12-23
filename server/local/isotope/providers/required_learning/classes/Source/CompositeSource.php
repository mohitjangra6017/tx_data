<?php
/**
 * Composite Source
 *
 * @package   isotopeprovider_required_learning
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace isotopeprovider_required_learning\Source;

defined('MOODLE_INTERNAL') || die;

class CompositeSource implements SourceInterface
{
    /**
     * @var SourceInterface[]
     */
    protected $sources;

    /**
     * CompositeSource constructor.
     * @param SourceInterface[] $sources
     */
    public function __construct(array $sources)
    {
        $this->sources = $sources;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        return array_reduce(
            $this->sources,
            function (array $carry, SourceInterface $source) {
                return array_merge($carry, $source->getData());
            },
            []
        );
    }
}