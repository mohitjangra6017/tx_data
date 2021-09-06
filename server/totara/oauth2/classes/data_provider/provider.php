<?php
/**
 * This file is part of Totara Core
 *
 * Copyright (C) 2021 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Qingyang Liu <qingyang.liu@totaralearning.com>
 * @package totara_oauth2
 */

namespace totara_oauth2\data_provider;

use core\collection;
use core\orm\entity\repository;
use coding_exception;

/**
 * Class provider
 * @package totara_oauth2\data_provider
 */
abstract class provider {
    /**
     * True when data has been fetched, otherwise False
     *
     * @var bool
     */
    protected $fetched;

    /**
     * @var collection
     */
    protected $items;

    /**
     * Array of filters to apply when fetching the data
     *
     * @var array
     */
    protected $filters = [];

    /**
     * provider constructor.
     */
    function __construct() {
        $this->fetched = false;
    }

    /**
     * Build the base ORM query using the relevant repository.
     *
     * @return repository
     */
    abstract protected function build_query(): repository;


    /**
     * Add filters for this provider.
     *
     * @param array $filters
     * @return provider
     */
    final public function add_filters(array $filters): self {
        $this->filters = array_merge(
            $this->filters,
            array_filter($filters, static function ($filter_value) {
                return isset($filter_value);
            })
        );

        return $this;
    }

    /**
     * Apply filters to a given repository before it is fetched from the database.
     *
     * To add a query filter, define a method like:
     * ```php
     *     protected function filter_query_by_FILTERNAME(repository $repository, mixed $filter_value): void { ... }
     * ```
     *
     * @param repository $repository Repository to apply filters
     * @return $this
     */
    protected function apply_query_filters(repository $repository): self {
        foreach ($this->filters as $key => $value) {
            if ($this->fetched) {
                throw new coding_exception('Must call apply_query_filters() before fetching.');
            }

            if (!method_exists($this, 'filter_query_by_' . $key)) {
                throw new coding_exception("Filtering by '{$key}' is not supported");
            }

            $this->{'filter_query_by_' . $key}($repository, $value);
        }

        return $this;
    }

    /**
     * (Optionally) Modify the fetched items before returning them with get().
     *
     * @return collection
     */
    protected function process_fetched_items(): collection {
        // Do nothing here, override in subclasses if needed.
        return $this->items;
    }

    /**
     * Run the ORM query and mark the data provider as already fetched.
     */
    public function fetch(): self {
        $this->fetched = false;

        $query = $this->build_query();
        $this->apply_query_filters($query);;

        $this->items = $query->get();
        $this->fetched = true;
        $this->items = $this->process_fetched_items();

        return $this;
    }

    /**
     * Get the queried items.
     *
     * @return collection
     */
    public function get(): collection {
        if (!$this->fetched) {
            $this->fetch();
        }

        return $this->items;
    }

}