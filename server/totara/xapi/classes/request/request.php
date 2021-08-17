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
 * @author  Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package totara_xapi
 */
namespace totara_xapi\request;

use coding_exception;
use totara_core\http\util;

class request {
    /**
     * The hashmap header of the request. Where key is the header name, and value
     * is the value associated with the header name.
     *
     * @var array<string, string>
     */
    private $headerParameters;

    /**
     * The hashmap of global $_POST.
     * @var array<string, mixed>
     */
    private $postParameters;

    /**
     * The hashmap of global $_GET.
     * @var array<string, mixed>
     */
    private $getParameters;

    /**
     * The hashmap of global $_SERVER
     * @var array<string, mixed>
     */
    private $serverParameters;

    /**
     * The http content.
     * @var string|null
     */
    private $content;

    /**
     * @param array<string, mixed> $postParameters
     * @param array<string, mixed> $getParameters
     * @param array<string, mixed> $serverParameters
     * @param array<string, string> $headerParameters
     */
    public function __construct(
        array $postParameters,
        array $getParameters,
        array $serverParameters,
        array $headerParameters
    ) {
        $this->postParameters = $postParameters;
        $this->getParameters = $getParameters;
        $this->headerParameters = $headerParameters;
        $this->serverParameters = $serverParameters;

        $this->content = null;
    }

    /**
     * @param string $content
     * @return void
     */
    public function set_content(string $content): void {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function get_content(): string {
        if (null === $this->content) {
            $this->content = file_get_contents("php://input");
        }

        return $this->content;
    }

    /**
     * @return array
     */
    public function get_content_as_decoded_json(): array {
        $content = $this->get_content();
        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Get the parameter, which is not a cleaned parameter by default.
     * Ideally the cleaning process should be done separately from
     * fetching the value. However, the parameter $param is in place to
     * allow us cleaning the value if desire to.
     * Only when value of parameter $param presents.
     *
     * @param string $field
     * @param null|mixed $default
     * @param string|null $param
     *
     * @return mixed|null
     */
    public function get_parameter(string $field, $default = null, ?string $param = null) {
        $value = $default;

        if (array_key_exists($field, $this->getParameters)) {
            $value = $this->getParameters[$field];
        } else if (array_key_exists($field, $this->postParameters)) {
            $value = $this->postParameters[$field];
        }

        if (null !== $param) {
            $value = clean_param($value, $param);
        }

        return $value;
    }

    /**
     * Get the parameter as required parameter.
     *
     * @param string $field
     * @param string|null $param
     *
     * @return mixed
     */
    public function get_required_parameter(string $field, ?string $param = null) {
        $this->required_parameter($field);
        return $this->get_parameter($field, null, $param);
    }

    /**
     * @param string $field
     * @return bool
     */
    public function has_parameter(string $field): bool {
        return array_key_exists($field, $this->getParameters) || array_key_exists($field, $this->postParameters);
    }

    /**
     * Throw an exception, if the given $field is not provided within the
     * parameter list.
     *
     * @param string $field
     * @return void
     */
    public function required_parameter(string $field): void {
        if ($this->has_parameter($field)) {
            return;
        }

        throw new coding_exception("The field {$field} is required, but missing from the request");
    }

    /**
     * Returns the value of the request's header. Null is returned when
     *
     * @param string $field
     * @return string|null
     */
    public function header(string $field): ?string {
        return $this->headerParameters[$field] ?? null;
    }

    /**
     * @param string $field
     * @return mixed|null
     */
    public function server(string $field) {
        return $this->serverParameters[$field] ?? null;
    }

    /**
     * @param array<string, mixed> $getParameters   Global $_GET - passing the value to this argument, in order
     *                                              to override the global $_GET
     *
     * @param array<string, mixed> $postParameters  Global $_POST - passing the value to this argument, in order
     *                                              to override the global $_POST.
     *
     * @param array<string, mixed> $servers         Global $_SERVER - passing the value to this argument, in order to
     *                                              to override the global $_SERVER
     *
     * @param array<string, string> $headers        The array of headers, which is mostly taken from global $_SERVER.
     *
     * @return request
     */
    public static function create_from_global(
        array $getParameters = [],
        array $postParameters = [],
        array $headers = [],
        array $servers = []
    ): request {
        global $_GET, $_POST, $_SERVER;
        if (empty($postParameters)) {
            $postParameters = $_POST;
        }

        if (empty($getParameters)) {
            $getParameters = $_GET;
        }

        if (empty($servers)) {
            $servers = $_SERVER;
        }

        if (empty($headers)) {
            $headers = util::get_request_headers();
            $headers = $headers ?: [];
        }

        return new self($postParameters, $getParameters, $servers, $headers);
    }
}