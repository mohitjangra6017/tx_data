<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace local_credly;

use local_credly\entity\Badge;
use local_credly\entity\WebhookLog;
use local_credly\Exception\CoreUnavailableException;
use local_credly\Exception\InvalidCredlyEventTypeException;
use stdClass;
use Throwable;
use totara_webapi\local\util;

class WebhookEndpoint
{
    private stdClass $event;

    private int $badgeId;

    public const CREDLY_BADGE_TEMPLATE_CREATED = 'badge_template.created';

    public const CREDLY_BADGE_TEMPLATE_CHANGED = 'badge_template.changed';

    public const CREDLY_BADGE_TEMPLATE_DELETED = 'badge_template.deleted';

    public const VALID_WEBHOOKS = [
        self::CREDLY_BADGE_TEMPLATE_CREATED => self::CREDLY_BADGE_TEMPLATE_CREATED,
        self::CREDLY_BADGE_TEMPLATE_CHANGED => self::CREDLY_BADGE_TEMPLATE_CHANGED,
        self::CREDLY_BADGE_TEMPLATE_DELETED => self::CREDLY_BADGE_TEMPLATE_DELETED,
    ];

    private ?Endpoint $credlyEndpoint;

    public function __construct(Endpoint $endpoint)
    {
        $this->credlyEndpoint = $endpoint;
    }

    /**
     * @param string|null $token
     * @param array|null $data
     */
    public function execute(?string $token, ?array $data): void
    {
        $this->validateRequest($token, $data);

        switch ($this->event->data->event_type) {
            case self::CREDLY_BADGE_TEMPLATE_CREATED:
                $this->badgeTemplateCreated();
                break;
            case self::CREDLY_BADGE_TEMPLATE_CHANGED:
                $this->badgeTemplateUpdated();
                break;
            case self::CREDLY_BADGE_TEMPLATE_DELETED:
                $this->badgeTemplateDeleted();
                break;
        }

        $this->logRequest();

        if (!PHPUNIT_TEST) {
            util::send_response([], 200);
        }
    }


    /**
     * Credly will keep sending the request on anything other than a 200 response
     * so only send a 200 if it came from Credly and it is valid
     */
    private function validateRequest(?string $token, ?array $data): void
    {
        global $CFG;

        if (!empty($CFG->upgraderunning) || !empty($CFG->maintenance_enabled)) {
            throw new CoreUnavailableException();
        }

        $config = get_config('local_credly');

        if (empty($config->webhooktoken) || $token != $config->webhooktoken) {
            throw new \InvalidArgumentException();
        }

        if (empty($data) || empty($config->organisation_id) || $data['organization_id'] != $config->organisation_id) {
            throw new \InvalidArgumentException();
        }

        if (empty(self::VALID_WEBHOOKS[$data['event_type']])) {
            throw new InvalidCredlyEventTypeException();
        }

        try {
            $this->event = $this->credlyEndpoint->getEvent($data['id']);
        } catch (Throwable $t) {
            // The request did not come from our trusted Credly instance
            throw new \InvalidArgumentException();
        }
    }

    private function logRequest(): void
    {
        if (array_key_exists('HTTP_ORIGIN', $_SERVER)) {
            $origin = $_SERVER['HTTP_ORIGIN'];
        } else if (array_key_exists('HTTP_REFERER', $_SERVER)) {
            $origin = $_SERVER['HTTP_REFERER'];
        } else if (!empty($_SERVER['REMOTE_ADDR'])){
            $origin = $_SERVER['REMOTE_ADDR'];
        } else {
            $origin = 'unknown';
        }

        $webHookLog = new WebhookLog();
        $webHookLog->eventid = $this->event->data->id;
        $webHookLog->badgeid = $this->badgeId;
        $webHookLog->eventtype = $this->event->data->event_type;
        $webHookLog->referer = $origin;
        $webHookLog->occurredat = strtotime($this->event->data->occurred_at);
        $webHookLog->save();
    }

    private function badgeTemplateCreated(): void
    {
        $badge = new Badge();
        $badge->credlyid = $this->event->data->badge_template->id;
        $badge->name = $this->event->data->badge_template->name;
        $badge->state = $this->event->data->badge_template->state;
        $badge->save();

        $this->badgeId = $badge->id;
    }

    private function badgeTemplateUpdated(): void
    {
        if ($badge = Badge::repository()->findByCredlyId($this->event->data->badge_template->id)) {
            $badge->name = $this->event->data->badge_template->name;
            $badge->state = $this->event->data->badge_template->state;
        } else {
            $badge = new Badge();
            $badge->credlyid = $this->event->data->badge_template->id;
            $badge->name = $this->event->data->badge_template->name;
            $badge->state = $this->event->data->badge_template->state;
        }
        $badge->save();

        $this->badgeId = $badge->id;
    }

    private function badgeTemplateDeleted(): void
    {
        if ($badge = Badge::repository()->findByCredlyId($this->event->data->badge_template->id)) {
            $badge->state = $this->event->data->badge_template->state;
            $badge->courseid = null;
            $badge->programid = null;
            $badge->certificationid = null;
            $badge->state = 'archived';
            $badge->save();
            $this->badgeId = $badge->id;
        }
    }
}