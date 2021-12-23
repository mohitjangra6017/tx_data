<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

use local_credly\Exception\InvalidCredlyEventTypeException;
use local_credly\Helper;
use local_credly\WebhookEndpoint;
use totara_webapi\local\util;

require_once(__DIR__ . '/../../config.php');
try {
    (new WebhookEndpoint(Helper::createCredlyEndpoint()))->execute(
        optional_param('token', '', PARAM_ALPHANUM),
        util::parse_http_request()
    );
} catch (InvalidArgumentException $e) {
    util::send_response([], 400);
} catch (InvalidCredlyEventTypeException $e) {
    util::send_error($e->getMessage(), $e->getCode());
} catch (Throwable $e) {
    util::send_response([], 503);
}

// This only gets hit if we somehow didn't send a response in execute, nor did we catch an exception.
util::send_response([], 400);