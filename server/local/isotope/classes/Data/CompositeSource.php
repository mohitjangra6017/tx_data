<?php
/**
 * @copyright City & Guilds Kineo 2019
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_isotope\Data;

final class CompositeSource implements SourceInterface
{
    /**
     * @var SourceInterface[]
     */
    private $subSources = [];

    /**
     * CompositeSource constructor.
     * @param SourceInterface[] $subSources
     */
    public function __construct(array $subSources = [])
    {
        foreach ($subSources as $source) {
            if (!$source instanceof SourceInterface) {
                throw new \InvalidArgumentException(
                    sprintf('%s does not implement %s', get_class($source), SourceInterface::class)
                );
            }
        }
        $this->subSources = $subSources;
    }

    /**
     * Get the data provided by this data source.
     *
     * @return array
     */
    public function getData()
    {
        $data = [];
        foreach ($this->subSources as $source) {
            $data = array_merge($data, $source->getData());
        }
        return $data;
    }

    /**
     * Set the value of the specified filter.
     *
     * @param string $filterName
     * @param mixed $value
     * @return $this
     */
    public function setFilter($filterName, $value)
    {
        foreach ($this->subSources as $source) {
            $source->setFilter($filterName, $value);
        }
        return $this;
    }

    /**
     * @return SourceInterface[]
     */
    public function getSubSources()
    {
        return $this->subSources;
    }
}