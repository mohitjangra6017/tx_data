<?php
/**
 * @copyright City & Guilds Kineo 2019
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_isotope\Data;

interface DecoratorInterface
{
    /**
     * Do whatever needs doing to the collection of data items.
     * A @see DecoratorInterface MUST return the same number of items it is given.
     * A @see SourceInterface MUST be used to add/remove data.
     * A @see \LogicException WILL be thrown if the number of decorated items differs from the Source items.
     *
     * @param array $data The data to decorate
     * @param array $context Extra information used to decorate items
     * @return array
     */
    public function decorate(array $data, $context = []);

    /**
     * Config options valid for the Decorator.
     * @param array $config
     */
    public function setConfig(array $config);
}