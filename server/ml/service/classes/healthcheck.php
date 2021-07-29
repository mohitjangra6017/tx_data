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
 * @author Cody Finegan <cody.finegan@totaralearning.com>
 * @package ml_service
 */

namespace ml_service;

/**
 * Perform a healthcheck of the ml_service service.
 *
 * @package ml_service
 */
class healthcheck {

    /**
     * @var api
     */
    protected $api;

    /**
     * @var null|bool
     */
    protected $totara_to_service = null;

    /**
     * @var null|bool
     */
    protected $service_to_totara = null;

    /**
     * Collection of errors encountered during the test
     *
     * @var array
     */
    protected $error_messages = [];

    /**
     * @var array
     */
    protected $other_info = [];

    /**
     * @param api|null $api $api
     */
    public function __construct(?api $api = null) {
        $this->api = $api ?? new api();
    }

    /**
     * @param api|null $api
     * @return healthcheck
     */
    public static function make(?api $api = null): healthcheck {
        return new self($api);
    }

    public function reset(): void {
        $this->service_to_totara = null;
        $this->totara_to_service = null;
        $this->error_messages = [];
        $this->other_info = [];
    }

    /**
     * Fluent call to check the health of the service.
     *
     * @return $this
     */
    public function check_health(): healthcheck {
        $this->reset();

        if (!$this->is_service_configured()) {
            $this->totara_to_service = false;
            $this->error_messages[] = get_string('error_no_config_defined', 'ml_service');
            return $this;
        }

        $response = $this->api->get('/health-check');

        // Can Totara talk to the service?
        if (!$response->is_ok()) {
            $this->totara_to_service = false;

            // Why did it fail?
            if ($error = $response->try_get_error_message()) {
                $this->error_messages[] = $error;
            } else {
                $this->error_messages[] = $response->get_body();
            }
            return $this;
        }

        $this->totara_to_service = true;

        $data = $response->get_body_as_json(true);
        $this->service_to_totara = $data['success'] ?: false;

        $this->other_info = $data['totara'];

        if (!empty($data['errors'])) {
            $this->error_messages = array_merge($this->error_messages, $data['errors']);
        }

        return $this;
    }

    /**
     * @return bool|null
     */
    public function get_totara_to_service(): ?bool {
        return $this->totara_to_service;
    }

    /**
     * @return bool|null
     */
    public function get_service_to_totara(): ?bool {
        return $this->service_to_totara;
    }

    /**
     * @return array
     */
    public function get_error_messages(): array {
        return $this->error_messages;
    }

    /**
     * @return string|null
     */
    public function get_service_url(): ?string {
        global $CFG;
        return $CFG->ml_service_url ?? null;
    }

    /**
     * @param bool|null $status
     * @return string
     */
    public function as_label(?bool $status): string {
        if (null === $status) {
            return get_string('unknown', 'ml_service');
        }

        if (false === $status) {
            return get_string('unhealthy', 'ml_service');
        }

        return get_string('healthy', 'ml_service');
    }

    /**
     * @return bool
     */
    protected function is_service_configured(): bool {
        return null !== $this->get_service_url();
    }

    /**
     * @return array
     */
    public function get_other_info(): array {
        return $this->other_info;
    }
}