<?php
/**
 * This file is part of Totara Learn
 *
 * Copyright (C) 2020 onwards Totara Learning Solutions LTD
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
 * @package totara_webapi
 */

namespace totara_webapi;

use coding_exception;
use core\performance_statistics\collector;
use core\webapi\execution_context;
use GraphQL\Executor\ExecutionResult;
use GraphQL\Server\OperationParams;
use GraphQL\Type\Schema;
use Throwable;
use totara_webapi\local\util;

/**
 * This class handles GraphQL requests originating via HTTP API endpoint.
 *
 * @package totara_webapi
 */
class server {

    /**
     * @var bool|int
     */
    protected $debug = false;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var execution_context
     */
    protected $execution_context;

    /**
     * @param execution_context $execution_context
     * @param mixed|null $debug
     */
    public function __construct(execution_context $execution_context, $debug = null) {
        $this->type = $execution_context->get_type();
        $this->execution_context = $execution_context;
        $this->debug = $debug;
    }

    /**
     * Set debug, check graphql library for available options
     *
     * @param bool|int $debug
     * @return $this
     */
    public function set_debug($debug): self {
        $this->debug = $debug;
        return $this;
    }

    /**
     * This can be used to override the execution context
     *
     * @param execution_context $ec
     * @return server
     */
    public function set_execution_context(execution_context $ec): self {
        $this->execution_context = $ec;
        return $this;
    }

    /**
     * Prepares, validates the request and executes it returning the result.
     * If a batched operation got requested it will return an array of results.
     *
     * @param request|null $request if not passed, the request will be taken from the http post data
     * @return ExecutionResult|ExecutionResult[]
     */
    public function handle_request(request $request = null) {
        global $CFG;

        try {
            if (!$request) {
                $request = new request($this->type);
            }

            $this->ensure_session_initiated();
            $result = processor::instance($this->execution_context, $this->debug)->process_request($request);
        } catch (Throwable $e) {
            $result = new ExecutionResult(null, [$e]);
            $result->setErrorsHandler([util::class, 'graphql_error_handler']);
            $result->setErrorFormatter([util::class, 'graphql_error_formatter']);
        }

        if ((defined('MDL_PERF') && MDL_PERF === true)
            || (!empty($CFG->perfdebug) && $CFG->perfdebug > 7)
        ) {
            $this->add_performance_data_to_result($result);
        }

        return $result;
    }

    /**
     * Ensures the session is initiated.
     *
     * @return void
    */
    private function ensure_session_initiated() {
        if ($this->type !== graphql::TYPE_DEV
            && !NO_MOODLE_COOKIES
            && !confirm_sesskey($_SERVER['HTTP_X_TOTARA_SESSKEY'] ?? null)
        ) {
            $exception = new webapi_request_exception('Invalid sesskey, page reload required');
            $category = isloggedin()
                ? 'require_refresh'
                : 'require_login';

            throw new client_aware_exception($exception, ['category' => $category]);
        }
    }

    /**
     * Build and validate the schema (on developer mode)
     *
     * @deprecated since Totara 15.0 TL-30804
     *
     * @param string|null $type One of graphql::TYPE_*
     * @return Schema
     */
    protected static function prepare_schema(string $type = null): Schema {
        debugging('server::prepare been deprecated since Totara 15.0', DEBUG_DEVELOPER);
        $schema_file_loader = new schema_file_loader();
        $schema_builder = new schema_builder($schema_file_loader);
        $schema = $schema_builder->build();

        if ($type === graphql::TYPE_DEV) {
            $schema->assertValid();
        }

        return $schema;
    }

    /**
     * Convert the request into OperationParams instances which the GraphQL library
     * needs for executing the request
     *
     * @deprecated since Totara 15.0 TL-30804
     *
     * @param request $request
     * @return OperationParams|OperationParams[]
     */
    protected function prepare_operations(request $request) {
        debugging('server::prepare_operations been deprecated since Totara 15.0', DEBUG_DEVELOPER);
        if ($request->is_batched()) {
            // Operation name in the execution context should be null
            // as the execution context is used for all queries
            if ($this->execution_context->get_operationname() !== null) {
                throw new coding_exception('Expected operation name in execution context to be null for batched queries');
            }
            return array_map(function ($operation) {
                return $this->create_operation($operation);
            }, $request->get_params());
        } else {
            $params = $request->get_params();
            // We want to be sure that the operation name in the execution context matches the one in the request
            if ($this->execution_context->get_operationname() !== null
                && $this->execution_context->get_operationname() !== $params['operationName']
            ) {
                throw new coding_exception('Operation name mismatch, request has different value as the execution_context.');
            }
            $this->execution_context->set_operationname($params['operationName']);
            return $this->create_operation($params);
        }
    }

    /**
     * @deprecated since Totara 15.0 TL-30804
     *
     * @param array $params
     * @return OperationParams
     */
    protected function create_operation(array $params) {
        debugging('server::create_operation been deprecated since Totara 15.0', DEBUG_DEVELOPER);
        // To be able to use the persistent query support built into
        // the GraphQL library we use the operation name for the queryId
        if ($this->type !== graphql::TYPE_DEV) {
            $params['queryId'] = $params['operationName'];
        }

        $params['webapi_type'] = $this->type;
        $params = fix_utf8($params);
        return OperationParams::create($params);
    }

    /**
     * If site is configured to capture performance metrics append it in the results.
     * This also works for batched queries.
     *
     * @param ExecutionResult|ExecutionResult[] $results
     */
    private function add_performance_data_to_result($results) {
        // We only want to query the performance metrics once per request
        // so do it once and pass it on if we have multiple results
        $collector = new collector();
        $performance_data = $collector->all();

        // If this is a batched queries we will have multiple results
        // so go through them and add the performance metrics for them
        if (is_array($results)) {
            foreach ($results as $result) {
                if ($result instanceof ExecutionResult) {
                    $result->extensions['performance_data'] = $performance_data;
                }
            }
        } else if ($results instanceof ExecutionResult) {
            $results->extensions['performance_data'] = $performance_data;
        }
    }

    /**
     * Convert the result or the array of results and send the back via the appropriate headers
     *
     * @param ExecutionResult|ExecutionResult[] $result
     * @param bool $stop_execution
     */
    public function send_response($result, bool $stop_execution = true) {
        if (is_array($result)) {
            $result = array_map(
                function ($execution_result) {
                    if (!$execution_result instanceof ExecutionResult) {
                        util::send_error('Invalid result', 500);
                    }
                    return $execution_result->toArray($this->debug);
                }, $result
            );
        } else {
            $result = $result->toArray($this->debug);
        }

        util::send_response($result, 200, $stop_execution);
    }
}
