<?php

namespace local_credly\Task;

use core\task\scheduled_task;
use local_credly\Endpoint;
use local_credly\entity\BadgeIssue;
use local_credly\Helper;

class Issue extends scheduled_task
{
    private ?Endpoint $endpoint = null;

    public function __construct()
    {
        try {
            $this->endpoint = Helper::createCredlyEndpoint();
        } catch (\Throwable $e) {
            // Brand new plugin installs don't have config so handle this here
        }
    }

    public function get_name()
    {
        return get_string('task:badge', 'local_credly');
    }

    public function execute(): void
    {
        if (!$this->endpoint || !$this->endpoint->isEnabled()) {
            mtrace(get_string('err:credly_not_enabled', 'local_credly'));
            return;
        }

        $this->reIssueFailedBadges();
        $this->issueProgramBadges();
        $this->issueCertificationBadges();
        $this->issueCourseBadges();
    }

    private function reIssueFailedBadges(): void
    {
        $issues = BadgeIssue::repository()->findUnsuccessfulBadgeIssues();
        $this->endpoint->batchBadgeIssues($issues);
    }

    private function issueProgramBadges(): void
    {
        $issues = BadgeIssue::repository()->findBadgesToBeIssuedByLearningType('program');
        $this->endpoint->batchBadgeIssues($issues);
    }

    private function issueCertificationBadges(): void
    {
        $issues = BadgeIssue::repository()->findBadgesToBeIssuedByLearningType('certification');
        $this->endpoint->batchBadgeIssues($issues);
        $replacements = BadgeIssue::repository()->findCertificationBadgeIssuesToBeReplaced();
        foreach ($replacements as $replacement) {
            $this->endpoint->issueBadge($replacement);
        }
    }

    private function issueCourseBadges()
    {
        $issues = BadgeIssue::repository()->findBadgesToBeIssuedByLearningType('course');
        $this->endpoint->batchBadgeIssues($issues);
    }
}