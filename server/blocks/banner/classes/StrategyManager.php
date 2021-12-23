<?php
/**
 * @file
 */

namespace block_banner;

use block_banner\Contracts\ImageStrategyInterface;
use block_banner\VariablesStrategies\Variables;
use Exception;

defined('MOODLE_INTERNAL') || die;

/**
 * Class strategy_manager
 *
 * This class helps manage different generic strategies.
 */
class StrategyManager
{
    /** @var array */
    private $strategies;

    /**
     * StrategyManager constructor.
     * @param array $strategies
     */
    public function __construct(array $strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * Get all of the strategies that are stored in this manager.
     *
     * @return array
     */
    public function getStrategies(): array
    {
        return $this->strategies;
    }

    /**
     * Get a specific strategy by name.
     *
     * @param $name
     * @return ImageStrategyInterface|Variables
     * @throws Exception If the strategy does not exist.
     */
    public function getStrategy($name)
    {
        if (!array_key_exists($name, $this->strategies)) {
            throw new Exception("No strategy called $name exists.");
        }
        return $this->strategies[$name];
    }
}
