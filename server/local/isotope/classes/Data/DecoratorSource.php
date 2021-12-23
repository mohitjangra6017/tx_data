<?php
/**
 * @copyright City & Guilds Kineo 2019
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_isotope\Data;

use LogicException;

final class DecoratorSource implements SourceInterface
{
    private $decorator;
    private $dataSource;
    private $context = [];

    public function __construct(SourceInterface $dataSource, DecoratorInterface $decorator)
    {
        $this->decorator = $decorator;
        $this->dataSource = $dataSource;
    }

    /**
     * Get the data provided by this data source.
     *
     * @return array
     */
    public function getData()
    {
        $items = $this->dataSource->getData();
        $decorated = $this->decorator->decorate($items, $this->context);

        if (count($items) != count($decorated)) {
            throw new LogicException(
                sprintf(
                    'Decorators must not reduce/expand the Source items: Changed from %d to %d',
                    count($items),
                    count($decorated)
                )
            );
        }

        return $decorated;
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
        $this->dataSource->setFilter($filterName, $value);
        $this->context[$filterName] = $value;
        return $this;
    }

    /**
     * @return DecoratorInterface
     */
    public function getDecorator()
    {
        return $this->decorator;
    }

    /**
     * @return SourceInterface
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }
}