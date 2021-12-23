<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace local_credly;

use local_credly\entity\BadgeIssue;
use local_credly\entity\BadgeIssueLog;
use stdClass;
use totara_core\http\client;
use totara_core\http\clients\curl_client;
use totara_core\http\request;
use totara_core\http\response;

class Endpoint
{
    protected client $client;
    protected string $endpointUrl;
    protected string $authToken;
    protected string $organisationId;

    private ?string $groupTag;
    private static ?string $oldTimezone = null;

    public function __construct(string $endpointUrl = '', string $authToken = '', string $organisationId = '', ?client $client = null)
    {
        $config = get_config('local_credly');
        $this->endpointUrl = $endpointUrl ?? $config->endpoint_url;
        $this->authToken = $authToken ?? $config->auth_token;
        $this->organisationId = $organisationId ?? $config->organisation_id;
        $this->client = $client ?? new curl_client();
        $this->groupTag = $config->group_tag ?? null;
    }

    /**
     * @param string $endpoint
     * @return string
     */
    private function getFullUrl(string $endpoint): string
    {
        return rtrim($this->endpointUrl, '/') . "/organizations/{$this->organisationId}/{$endpoint}";
    }

    /**
     * @return string[]
     */
    private function getDefaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($this->authToken . ':'),
        ];
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return get_config('local_credly', 'enabled') ?? false;
    }

    public function authenticateCredentials(): void
    {
        $request = request::get($this->getFullUrl(''), $this->getDefaultHeaders());
        $response = $this->client->execute($request);
        $response->throw_if_error();
    }

    /**
     * @param string $eventId
     * @return stdClass
     */
    public function getEvent(string $eventId): stdClass
    {
        $request = request::get($this->getFullUrl("events/{$eventId}"), $this->getDefaultHeaders());
        $response = $this->client->execute($request);
        $response->throw_if_error();

        return $response->get_body_as_json();
    }

    /**
     * @param string $filter
     * @param int $page
     * @return stdClass
     */
    public function getBadges(string $filter = '', int $page = 1): stdClass
    {
        $request = request::get(
            $this->getFullUrl(
                "badge_templates?sort=name&page={$page}&filter={$filter}"
            ),
            $this->getDefaultHeaders()
        );
        $response = $this->client->execute($request);
        $response->throw_if_error();

        return $response->get_body_as_json();
    }

    /**
     * @param BadgeIssue $badgeIssue
     */
    public function issueBadge(BadgeIssue $badgeIssue): void
    {
        if (!$this->isEnabled()) {
            return;
        }

        if (!$this->userIsOptedIn($badgeIssue->userid)) {
            return;
        }

        if (!$badgeIssue->exists()) {
            $badgeIssue->status = BadgeIssue::STATUS_NOT_SENT;
            $badgeIssue->save();
        }
        
        if ($badgeIssue->issueid && $badgeIssue->status == BadgeIssue::STATUS_REPLACE) {
            $request = $this->getBadgeReplaceRequest($badgeIssue);
        } else {
            $request = $this->getBadgeIssueRequest($badgeIssue);
        }

        $response = $this->client->execute($request);
        $httpCode = $response->get_http_code();
        $log = new BadgeIssueLog();

        if ($httpCode === 400 || $httpCode === 422) {
            $body = $response->get_body_as_json();
            $log->status = BadgeIssue::STATUS_UNRECOVERABLE_FAILURE;
            $log->response = json_encode($body->data);
        }

        if ($httpCode === 500) {
            $log->status = BadgeIssue::STATUS_RECOVERABLE_FAILURE;
            $log->response = $response->get_body();
        }

        if ($httpCode === 200 || $httpCode === 201) {
            $log->status = BadgeIssue::STATUS_SUCCESS;
            $responseBody = $response->get_body_as_json();
            $badgeIssue->issueid = $responseBody->data->id;
            $log->response = null;
        }

        $badgeIssue->addLog($log);
        $badgeIssue->save();
    }

    /**
     * @param BadgeIssue $badgeIssue
     * @return request
     */
    private function getBadgeReplaceRequest(BadgeIssue $badgeIssue): request
    {
        return request::post(
            $this->getFullUrl("badges/{$badgeIssue->issueid}/replace"),
            $this->mapIssueToRequest($badgeIssue),
            $this->getDefaultHeaders()
        );
    }

    /**
     * @param BadgeIssue $badgeIssue
     * @return request
     */
    private function getBadgeIssueRequest(BadgeIssue $badgeIssue): request
    {
        return request::post(
            $this->getFullUrl('badges'),
            $this->mapIssueToRequest($badgeIssue),
            $this->getDefaultHeaders()
        );
    }

    /**
     * @param BadgeIssue $issue
     * @return object
     */
    private function mapIssueToRequest(BadgeIssue $issue): object
    {
        self::setTimezone();
        $data = new stdClass();
        $data->recipient_email = $issue->user->email;
        $data->issuer_earner_id = $issue->user->id;
        $data->issued_to_first_name = $issue->user->firstname;
        $data->issued_to_last_name = $issue->user->lastname;
        $data->badge_template_id = $issue->badge->credlyid;
        $data->issued_at = date(DATE_ISO8601, $issue->issuetime);
        $data->expires_at = $issue->timeexpires ? date(DATE_ISO8601, $issue->timeexpires) : null;
        if (!empty($this->groupTag)) {
            $groupTag = new stdClass();
            $groupTag->name = $this->groupTag;
            $data->issuer_earner_group = $groupTag;
        }
        self::restoreTimezone();

        return $data;
    }

    /**
     * @param BadgeIssue[] $issues
     */
    public function batchBadgeIssues(array $issues): void
    {
        static $maxBatchSize = 50;

        $batchCount = ceil(count($issues) / $maxBatchSize);
        /** @var BadgeIssue[][] $batches */
        $batches = [];
        for ($i = 0; $i < $batchCount; $i++) {
            $batches[] = array_slice($issues, $i * $maxBatchSize, $maxBatchSize);
        }

        foreach ($batches as &$batch) {
            while (count($batch) > 0) {
                $response = $this->postBadgeBatch($batch);

                // Special case for a 422 response.
                // This means there is a problem with 1 specific item, so get rid of it and try the rest of the batch again.
                // For certifications they may already have a badge so for a 422 response we should mark them for replacement
                if ($response->get_http_code() === 422) {
                    $body = $response->get_body_as_json();
                    $errorIndex = $body->data->error_index;
                    $badgeIssue = $batch[$errorIndex];
                    $log = new BadgeIssueLog();
                    $log->status = $badgeIssue->certificationid ? BadgeIssue::STATUS_REPLACE : BadgeIssue::STATUS_UNRECOVERABLE_FAILURE;
                    $log->response = json_encode($body->data);
                    $badgeIssue->addLog($log);
                    $badgeIssue->save();
                    unset($batch[$errorIndex]);
                    $batch = array_values($batch);
                    continue;
                }

                $status = $response->get_http_code() > 299 ? BadgeIssue::STATUS_RECOVERABLE_FAILURE : BadgeIssue::STATUS_SUCCESS;
                $responseString = $response->get_http_code() > 299 ? $response->get_body() : null;
                $body = $response->get_body_as_json();
                foreach ($batch as $i => $item) {
                    $log = new BadgeIssueLog();
                    $log->status = $status;
                    $log->response = $responseString;
                    $item->addLog($log);
                    $item->issueid = $body->data[$i]->id;
                    $item->save();
                }
                break;
            }
        }
    }

    /**
     * @param BadgeIssue[] $batch
     * @return response
     */
    private function postBadgeBatch(array $batch): response
    {
        $toSend = [];
        foreach ($batch as $issue) {
            if (!$this->userIsOptedIn($issue->userid)) {
                continue;
            }
            if (!$issue->exists()) {
                $issue->status = BadgeIssue::STATUS_NOT_SENT;
                $issue->save();
            }
            $data = $this->mapIssueToRequest($issue);
            $toSend[] = $data;
        }
        $request = request::post(
            $this->getFullUrl('badges/batch'),
            json_encode(['badges' => $toSend]),
            $this->getDefaultHeaders()
        );
        return $this->client->execute($request);
    }

    private static function setTimezone()
    {
        if (static::$oldTimezone) {
            return;
        }

        static::$oldTimezone = date_default_timezone_get();
        date_default_timezone_set('UTC');
    }

    private static function restoreTimezone()
    {
        if (!static::$oldTimezone) {
            return;
        }

        date_default_timezone_set(static::$oldTimezone);
        static::$oldTimezone = null;
    }

    /**
     * If we allow users to opt out, and this user HAS opted out, then DO NOT send them a Badge under any circumstances.
     *  If we don't allow users to opt out, then ignore any previously set user preferences.
     * @param int $userId
     * @return bool
     */
    private function userIsOptedIn(int $userId): bool
    {
        return get_config('local_credly', 'allow_opt_out') == 0 || get_user_preferences('local_credly_opt_out', '0', $userId) == 0;
    }
}