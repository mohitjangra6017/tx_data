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
 * @author Fabian Derschatta <fabian.derschatta@totaralearning.com>
 * @package core
 */

use core\link\url_final_destination;

defined('MOODLE_INTERNAL') || die();

class core_link_url_final_destination_testcase extends advanced_testcase {

    protected $base_url = 'http://test.totaralms.com:80/exttests';

    /**
     * @var string
     */
    protected $base_url_https = 'https://test.totaralms.com:443/exttests';

    /**
     * @dataProvider get_url_dataprovider
     */
    public function test_find_final_destination(string $request_url, string $final_url, ?int $max_redirects, bool $use_https) {
        // Test standard redirects.
        $testurl = $this->getExternalTestFileUrl($request_url, $use_https);

        $url = new moodle_url($testurl);

        $final_destination = new url_final_destination();
        if (!$use_https) {
            $final_destination->set_allow_http();
        }

        if ($max_redirects === null) {
            $this->assertEquals($final_url, (string) $final_destination($url));
        } else {
            $this->assertEquals($final_url, (string) $final_destination($url, $max_redirects));
        }
    }

    public function get_url_dataprovider(): array {
        return [
            'no redirect' => ['/test_redir.php?done=1', $this->getExternalTestFileUrl('/test_redir.php?done=1'), null, false],
            'one redirect' => ['/test_redir.php?redir=1', $this->base_url.'/test_redir.php?done=1', null, false],
            'max redirects reached' => ['/test_redir.php?redir=10', $this->base_url.'/test_redir.php?redir=7', null, false],
            'with increased max limit' => ['/test_redir.php?redir=10', $this->base_url.'/test_redir.php?redir=5', 5, false],
            'with other protocol' => ['/test_redir_proto.php?proto=ftp', 'ftp://nowhere/', null, false],
            'no redirect https' => ['/test_redir.php?done=1', $this->getExternalTestFileUrl('/test_redir.php?done=1', true), null, true],
            'one redirect https' => ['/test_redir.php?redir=1', $this->base_url_https.'/test_redir.php?done=1', null, true],
            'max redirects reached https' => ['/test_redir.php?redir=10', $this->base_url_https.'/test_redir.php?redir=7', null, true],
            'with increased max limit https' => ['/test_redir.php?redir=10', $this->base_url_https.'/test_redir.php?redir=5', 5, true],
        ];
    }

}