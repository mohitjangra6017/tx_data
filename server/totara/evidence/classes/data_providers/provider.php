<?php
/**
 * This file is part of Totara Learn
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
 * @author Marco Song <marco.song@totaralearning.com>
 * @package totara_evidence
 */

namespace totara_evidence\data_providers;

use coding_exception;
use core\collection;
use core\orm\entity\repository;
use core\orm\pagination\cursor_paginator;
use core\pagination\cursor;

/**
 * Common logic for filtering, fetching and getting data for use in queries etc.
 *
 * @package mod_perform\data_providers
 */
abstract class provider {

    /**
     * Array of filters to apply when fetching the data
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Return whether data has been fetched
     *
     * @var bool
     */
    protected $fetched = false;

    /**
     * @var collection
     */
    protected $items;

    /**
     * Page number
     *
     * @var int
     */
    protected $page = 0;

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
     * @return $this
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
     * @param repository $repository
     * @return $this
     * @throws coding_exception
     */
    protected function apply_filters(repository $repository): self {
        foreach ($this->filters as $key => $value) {
            if ($this->fetched) {
                throw new \coding_exception('Must call apply_filters() before fetching.');
            }

            if (!method_exists($this, 'filter_by_' . $key)) {
                throw new \coding_exception("Filtering by '{$key}' is not supported");
            }

            $this->{'filter_by_' . $key}($repository, $value);
        }

        return $this;
    }

    /**
     * (Optionally) augment the fetched items before returning them with get().
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
        $this->apply_filters($query);

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

    /**
     * Set page number
     *
     * @param int $page
     * @return $this
     */
    public function set_page(int $page) {
        $this->page = $page;

        return $this;
    }

    /**
     * Fetch a paginated list of evidence.
     *
     * @param string|null $cursor
     * @param int|null $limit
     * @return cursor_paginator
     * @throws coding_exception
     */
    public function fetch_paginated(?string $cursor, ?int $limit): cursor_paginator {
        $page_cursor = cursor::create()->set_limit($limit ?? cursor_paginator::DEFAULT_ITEMS_PER_PAGE);
        if ($cursor) {
            $page_cursor = cursor::decode($cursor);
        }

        $query = $this->build_query();
        $this->apply_filters($query);

        $paginator = new cursor_paginator($query, $page_cursor, true);
        $paginator->get();

        return $paginator;
    }
}
