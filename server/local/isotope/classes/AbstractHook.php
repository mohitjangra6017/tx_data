<?php
/**
 * @copyright City & Guilds Kineo 2019
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_isotope;

abstract class AbstractHook
{
    /**
     * Set to false to stop processing other hooks after this one.
     * @var bool
     */
    public static $canContinue = true;

    /**
     * When false, there is no return value from the run function.
     * When true, the value returned is passed to the next Hook as the data.
     * @var bool
     */
    public static $hasReturn = false;

    /**
     * Set the priority for this Hook to run. Higher priorities run first.
     * If 2 Hooks share a priority they will be run in a undefined order.
     * @var int
     */
    public static $priority = 0;

    /**
     * Run this Hook, modifying the data - or creating new data - as needed.
     * Avoid throwing Exceptions where possible, simply do not call the returnFn if you want to fail.
     *
     * @param mixed $data Data for the hook to process
     * @param callable $returnFn Called when the hook finishes processing the data
     * @return mixed|void If self::$hasReturn is true, this value is used as the data for the next Hook
     */
    abstract public function run($data, $returnFn);
}