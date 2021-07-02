<?php
/*
 * This file is part of Totara Learn
 *
 * Copyright (C) 2018 onwards Totara Learning Solutions LTD
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
 * @author David Curry <david.curry@totaralearning.com>
 * @package mobile_findlearning
 */

namespace mobile_findlearning;

use totara_catalog\catalog_retrieval as core_retrieval;

use totara_catalog\datasearch\datasearch;
use totara_catalog\hook\exclude_item;

use totara_catalog\local\filter_handler;
use totara_catalog\local\feature_handler;

use mobile_findlearning\provider_handler;

defined('MOODLE_INTERNAL') || die();

/**
 * The catalog.
 */
class catalog_retrieval extends core_retrieval {

    /**
     * Get a page of objects. Assumes that all datasearch filters have been set up with whatever the current
     * parameters are, and that featured learning has been configured.
     *
     * Each 'object' contains:
     * - int id (from catalog table)
     * - int objectid
     * - string objecttype
     * - int contextid
     * - bool featured (optional, depending on configuration)
     *
     * @param int $pagesize
     * @param int $limitfrom
     * @param int $maxcount
     * @param string $orderbykey
     * @return \stdClass containing array 'objects', int 'limitfrom', int 'maxcount' and bool 'endofrecords'
     */
    public function get_page_of_objects(
        int $pagesize,
        int $limitfrom,
        int $maxcount = -1,
        string $orderbykey = 'featured'
    ): \stdClass {
        global $DB;

        list($selectsql, $countsql, $params) = $this->get_sql($orderbykey);

        $objects = [];
        $endofrecords = false;
        $querypagesize = $pagesize; // Doesn't need to be the same as page size, but shouldn't be smaller.
        $skipped = 0;

        $providerhandler = \mobile_findlearning\provider_handler::instance();
        $providers = $providerhandler->get_active_providers();

        foreach ($providers as $provider) {
            $provider->prime_provider_cache(); // Fetch appropriate visibility records in bulk.
        }

        while (!$endofrecords && count($objects) < $pagesize) {
            // Get some records.
            $records = $DB->get_records_sql($selectsql, $params, $limitfrom, $querypagesize);

            // Stop if there are no more records to be retrieved from the db.
            if (empty($records)) {
                $endofrecords = true;
                break;
            }

            foreach ($records as $record) {
                $limitfrom++; // Whether or not we return this record, we don't want to process it again.

                // Skip records for providers that aren't enabled (or maybe aren't even real!).
                if (!$providerhandler->is_active($record->objecttype)) {
                    $skipped++;
                    continue;
                }

                $provider = $providerhandler->get_provider($record->objecttype);

                // Check if the object can be included in the catalog for the given user.
                $cansees = $provider->can_see([$record]);
                if (!$cansees[$record->objectid]) {
                    $skipped++;
                    continue;
                }

                // A hook here to exclude/include the course/program/certificate based on the
                // third parties setting.
                $hook = new exclude_item($record);
                $hook->execute();

                if ($hook->is_excluded()) {
                    $skipped++;
                    continue;
                }

                // If we want to modify any record of a catalog, probably here is a good place to
                // have another seperate hook for it.

                // Unfortunately, there should not have a hook to add new record(s) into the list
                // of the result, because adding new record(s) will break the core functionality of
                // the catalog's pagination. Furthermore, we should not encourage the third party to
                // do so, because any record(s) added on the fly will not have any sorting supports

                // Not excluded, so add it to the results;
                $objects[] = $record;

                // Stop if we've got enough objects to fill the page.
                if (count($objects) == $pagesize) {
                    break 2;
                }
            }

            $querypagesize *= 2; // Exponential growth, so that we will do about O(log n) steps at most.
        }

        // Figure out if there are any more records to load, if we didn't reach the end while calculating the results.
        if ($endofrecords) {
            $totaluncheckedrecords = $limitfrom;
        } else {
            $totaluncheckedrecords = $DB->count_records_sql($countsql, $params);
            $endofrecords = $limitfrom == $totaluncheckedrecords;
        }

        // Figure out the maximum possible number of records that MIGHT be visible, according to the calculations we've done so far.
        if ($maxcount < 0) {
            $maxcount = $totaluncheckedrecords;
        }
        $maxcount -= $skipped;

        $page = new \stdClass();
        $page->objects = $objects;
        $page->limitfrom = $limitfrom;
        $page->maxcount = $maxcount;
        $page->endofrecords = $endofrecords;

        return $page;
    }
}
