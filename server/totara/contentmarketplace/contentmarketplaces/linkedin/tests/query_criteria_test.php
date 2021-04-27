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
 * @author  Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package contentmarketplace_linkedin
 */

use contentmarketplace_linkedin\api\v2\service\learning_asset\constant;
use contentmarketplace_linkedin\api\v2\service\learning_asset\query\criteria;
use core_phpunit\testcase;

class contentmarketplace_linkedin_query_criteria_testcase extends testcase {
    /**
     * @return void
     */
    public function test_set_parameters_from_uri(): void {
        $uri = implode(
            '&',
            [
                '/v2/learningAssets?assetFilteringCriteria.assetTypes[0]=COURSE',
                'assetFilteringCriteria.licensedOnly=true',
                'assetFilteringCriteria.locales[0].country=US',
                'assetFilteringCriteria.locales[0].language=en',
                'assetRetrievalCriteria.expandDepth=1',
                'assetRetrievalCriteria.includeRetired=true',
                'count=2',
                'q=criteria',
                'start=3398',
            ]
        );

        $criteria = new criteria();
        $criteria->set_parameters_from_paging_url($uri);

        $moodle_url = new moodle_url("/totara/contentmarketplace/marketplaces.php");
        self::assertEmpty($moodle_url->params());

        $criteria->apply_to_url($moodle_url);
        self::assertNotEmpty($moodle_url->params());

        $applied_parameters = $moodle_url->params();
        ksort($applied_parameters);

        $expected_parameters = [
            'assetFilteringCriteria.assetTypes[0]' => constant::ASSET_TYPE_COURSE,
            'assetFilteringCriteria.licensedOnly' => 'true',
            'assetFilteringCriteria.locales[0].country' => 'US',
            'assetFilteringCriteria.locales[0].language' => 'en',
            'assetRetrievalCriteria.expandDepth' => '1',
            'assetRetrievalCriteria.includeRetired' => 'true',
            'count' => '2',
            'q' => 'criteria',
            'start' => '3398',
        ];
        ksort($expected_parameters);

        // Moodle URL parameters get cast to string eventually.
        self::assertSame($expected_parameters, $applied_parameters);
    }
}