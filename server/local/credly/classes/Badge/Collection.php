<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_credly\Badge;

class Collection extends \core\collection
{
    protected int $total;

    public function __construct(array $items = [], int $total = 0)
    {
        parent::__construct($items);
        $this->total = $total;
    }

    public function total(): int
    {
        return $this->total;
    }

    /**
     * Pass items in the collection through a given callback and return a fresh copy
     * Reimplemented so we can pass on the total to the new Collection.
     *
     * @param callable $callable Callback
     * @return static
     */
    public function map(callable $callable): Collection
    {
        return new static(array_map($callable, $this->items), $this->total);
    }

    /**
     * Filter items in the collection matching a giving column value or a callback
     * Reimplemented so we can pass on the total to the new Collection.
     *
     * @param string|callable $column Column name to compare value with or a custom callback to find the desired item
     * @param mixed|null $value Value to compare to
     * @param bool $strict_comparison Strict comparison in find method
     * @return static
     */
    public function filter($column, $value = null, $strict_comparison = false): Collection
    {
        return new static(parent::filter($column, $value, $strict_comparison)->to_array(), $this->total);
    }
}


