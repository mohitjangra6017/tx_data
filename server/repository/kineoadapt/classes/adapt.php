<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Adapt API client.
 *
 * @since Moodle 3.0
 * @package    repository_kineoadapt
 * @copyright  2016 City & Guilds Kineo
 * @author     Ben Lobo <ben.lobo@kineo.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace repository_kineoadapt;

use moodle_exception;
use moodle_url;

defined('MOODLE_INTERNAL') || die();

/**
 * Adapt API.
 */
class adapt extends \curl {

    /** @var string client url */
    private $clienturl = '';

    /** @var string The client api key. */
    private $clientkey = '';

    /**
     * Create the Adapt API Client.
     *
     * @param string $apiurl The API URL
     * @param string $apikey The API key
     */
    public function __construct(string $apiurl, string $apikey) {
        parent::__construct();
        $this->clienturl = $apiurl;
        $this->clientkey = $apikey;
    }

    /**
     * Return the constructed API endpoint URL.
     *
     * @param string $endpoint The endpoint to be contacted
     * @return moodle_url The constructed API URL
     * @throws moodle_exception
     */
    protected function get_api_endpoint($endpoint) {
        $params = ['token' => $this->clientkey];
        return new \moodle_url($this->clienturl . '/totaraconnect/' . $endpoint, $params);
    }

    /**
     * Make an API call against the specified endpoint with supplied data.
     *
     * @param string $route The API route/method to be contacted
     * @param array $data Any data to pass to the endpoint
     * @param string $returntype
     * @return object Content decoded from the endpoint
     * @throws moodle_exception
     */
    protected function fetch_adapt_data($route, $data = [], $returntype = 'json') {
        $url = $this->get_api_endpoint($route);
        $this->cleanopt();
        $this->resetHeader();

        if ($data === null) {
            // Some API endpoints explicitly expect a data submission of 'null'.
            $options['CURLOPT_POSTFIELDS'] = 'null';
        } else {
            $options['CURLOPT_POSTFIELDS'] = json_encode($data);
        }
        $options['CURLOPT_POST'] = false;
        $options['CURLOPT_RETURNTRANSFER'] = true;

        $httpheaders = array(
            'Authorization: Basic ' . $this->clientkey
        );
        $options['CURLOPT_HTTPHEADER'] = $httpheaders;

        $response = $this->request($url->out(false), $options);

        if ($returntype == 'json') {
            $result = json_decode($response);
        } else {
            $result = $response;
        }

        $this->check_and_handle_api_errors($result);

        return $result;
    }

    /**
     * Check for and attempt to handle API errors.
     *
     * @param string $data The returned content.
     * @throws moodle_exception
     */
    protected function check_and_handle_api_errors($data) {

        // Success.
        if ($this->info['http_code'] == 200) {
            return;
        }

        // Non success codes.
        switch($this->info['http_code']) {
            case 400:
                // Bad input parameter. Error message should indicate which one and why.
                throw new \coding_exception('Invalid input parameter passed to Adapt API.');
                break;
            case 401:
                // Bad or expired token. This can happen if the access token is expired or if the access token has been
                // revoked by Adapt. To fix this, you should generate a new authentication token in Adapt
                // and save it in the Adapt repository global configuration settings in Totara.
                throw new authentication_exception('Authentication token expired');
                break;
            case 409:
                // Endpoint-specific error. Look to the response body for the specifics of the error.
                throw new \coding_exception('Endpoint specific error: ' . $data);
                break;
            default:
                break;
        }

        if ($this->info['http_code'] >= 500 && $this->info['http_code'] < 600) {
            throw new \invalid_response_exception($this->info['http_code'] . ": " . $data);
        }

        return;
    }

    /**
     * Get file listing from Adapt.
     *
     * @return object The returned course listing, or null on failure
     * @throws moodle_exception
     */
    public function get_listing() {
        $route = 'courses';
        $data = $this->fetch_adapt_data($route);
        return $data;
    }

    /**
     * Get scorm file from Adapt.
     *
     * @param $id
     * @param $filepath
     * @return object
     * @throws moodle_exception
     */
    public function get_scorm($id, $filepath) {

        $route = 'scorm/'.$id;
        $url = $this->get_api_endpoint($route);

        $this->cleanopt();
        $this->resetHeader();

        $options['CURLOPT_POST'] = false;
        $options['CURLOPT_RETURNTRANSFER'] = true;

        $httpheaders = array(
            'Authorization: Basic ' . $this->clientkey
        );

        $params = [
            'CURLOPT_HTTPHEADER' => $httpheaders,
            'filepath' => $filepath
        ];

        // Download the file.
        $result = $this->download_one($url->out(false), null, $params);

        // Check that a valid zip file was sent.
        if ($result) {
            $zip = zip_open($filepath);
            if (!is_resource($zip)) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * Test the Adapt API connection.
     *
     * @return boolean True if connection successful, false if not
     * @throws moodle_exception
     */
    public function test_connection() {
        $route = 'testconnection';
        $result = $this->fetch_adapt_data($route, null, 'json');
        if ($result->message == 'Connection successful') {
            return true;
        }
        return false;
    }

    /**
     * Get file search results from Adapt.
     *
     * @param string $query The search query
     * @return object The returned course listing, or null on failure
     * @throws moodle_exception
     */
    public function search($query = '') {
        $route = 'courses?search='.$query;
        $data = $this->fetch_adapt_data($route, null);
        return $data;
    }

}
