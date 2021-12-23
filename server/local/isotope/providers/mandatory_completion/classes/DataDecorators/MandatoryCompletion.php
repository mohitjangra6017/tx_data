<?php
/**
 * @package isotopeprovider_mandatory_completion
 * @author Ben Lobo <ben.lobo@kineo.com>
 * @copyright City & Guilds Kineo 2019
 * @license http://www.kineo.com
 */

namespace isotopeprovider_mandatory_completion\DataDecorators;

use local_isotope\Data\DecoratorInterface;

class MandatoryCompletion implements DecoratorInterface
{
    const COMPONENT = 'isotopeprovider_mandatory_completion';

    private $config;

    /**
     * Config options valid for the Decorator.
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * Do whatever needs doing to the collection of data items.
     *
     * @param array $data The data to decorate
     * @param array $context Extra information used to decorate items
     * @return array
     */
    public function decorate(array $data, $context = [])
    {
        return $data;
    }

}