<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace local_credly;


use core\notification;
use Exception;
use totara_core\http\clients\curl_client;

class Helper
{
    private static bool $authTested = false;

    public static function createCredlyEndpoint($endpointUrl = '', $authToken = '', $organisationId = ''): Endpoint
    {
        if (!$endpointUrl && !$authToken && !$organisationId) {
            $config = get_config('local_credly');
            if (empty($config->endpoint_url) || empty($config->auth_token) || empty($config->organisation_id)) {
                throw new \InvalidArgumentException('Invalid Credly Endpoint Configuration');
            }
            $endpointUrl = $config->endpoint_url;
            $authToken = $config->auth_token;
            $organisationId = $config->organisation_id;
        }

        $client = new curl_client();
        return new Endpoint($endpointUrl, $authToken, $organisationId, $client);
    }

    public static function testCredlyAuthentication(): void
    {
        if (self::$authTested) {
            return;
        }

        $endpointUrl = optional_param('s_local_credly_endpoint_url', '', PARAM_URL);
        $authToken = optional_param('s_local_credly_auth_token', '', PARAM_ALPHANUM);
        $organisationId = optional_param('s_local_credly_organisation_id', '', PARAM_ALPHANUMEXT);

        if (empty($endpointUrl) || empty($authToken) || empty($organisationId)) {
            return;
        }

        try {
            static::createCredlyEndpoint($endpointUrl, $authToken, $organisationId)->authenticateCredentials();
            notification::success(get_string('endpoint:auth_success', 'local_credly'));
        } catch (Exception $e) {
            notification::error(get_string('endpoint:auth_fail', 'local_credly', $e->getMessage()));
        }

        self::$authTested = true;
    }

    public static function regenWebHookToken(): void
    {
        if (get_config('local_credly', 'regentoken')) {
            set_config('webhooktoken', uniqid('4kr1nnmzzbln0oo'), 'local_credly');
            set_config('regentoken', 0, 'local_credly');
        }
    }
}