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
 * @author Mark Metcalfe <mark.metcalfe@totaralearning.com>
 * @package contentmarketplace_linkedin
 */

namespace contentmarketplace_linkedin\data_provider;

use core\orm\pagination\cursor_paginator;
use core\pagination\base_paginator;
use core\pagination\cursor;

/**
 * Common logic for filtering, fetching and getting paginated data for use in queries etc.
 *
 * @package mod_perform\data_providers
 */
abstract class paginated_provider extends provider {

    /**
     * Move the paginator to the next set of results and return it
     * NOTE: The caller is expected to call the applicable paginator methods to obtain the items, next_cursor, etc.
     *
     * @param cursor $cursor Caller should initialize
     * @param bool $include_total Whether to include the total count of records in the output.
     * @return cursor_paginator
     */
    protected function get_next(cursor $cursor, bool $include_total = true): cursor_paginator {
        $query = $this->build_query();
        $this->apply_query_filters($query);
        $this->apply_query_sorting($query);
    
        $paginator = new cursor_paginator($query, $cursor, $include_total);
        $paginator->get();

        return $paginator;
    }

    /**
     * Get the paginated items.
     *
     * @param array $pagination_params core_pagination_input input params from query, has keys: 'cursor', 'limit', 'page'.
     * @param bool $include_total Whether to include the total count of records in the output.
     * @return array Returns a set of ['items' => (same as what get() does), 'total' => (int), 'next_cursor' => (cursor)]
     */
    public function get_paginated(array $pagination_params, bool $include_total = true): array {
        $limit = $pagination_params['limit'] ?? null;
        if ($limit === null) {
            $limit = base_paginator::DEFAULT_ITEMS_PER_PAGE;
        }

        $cursor = $pagination_params['cursor'] ?? null;
        $page_cursor = cursor::create()->set_limit($limit);
        if ($cursor !== null) {
            $page_cursor = cursor::decode($cursor);
        }

        $paginated_set = $this->get_next($page_cursor, $include_total);
        $this->items = $paginated_set->get_items();

        $return_data = $paginated_set->get();
        $return_data['items'] = $this->process_fetched_items();
        return $return_data;
    }

}
