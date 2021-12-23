<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_credly\Controller;

use context;
use core\notification;
use JsonSerializable;
use local_credly\entity\BadgeIssue;
use local_credly\Helper;
use reportbuilder;
use stdClass;
use totara_mvc\controller;
use totara_mvc\viewable;

class BadgeResend extends controller
{
    protected function setup_context(): context
    {
        return \context_system::instance();
    }

    /**
     * This is the default action and it can be overridden by the children.
     * If no action is passed to the render method this default action is called.
     * In this case it has to be defined in child classes.
     *
     * @return viewable|string|array|stdClass|JsonSerializable if it cannot be cast to a string the result will be json encoded
     */
    public function action()
    {
        global $CFG;
        require_once $CFG->dirroot . '/totara/reportbuilder/lib.php';

        // Construct the report, this will check if we have access to it.
        $reportId = $this->get_required_param('reportid', PARAM_INT);
        $report = reportbuilder::create($reportId);

        // Make sure this is the correct report, no funny business here.
        if (get_class($report->src) !== \rb_source_credly_badges::class) {
            print_error('reportwithidnotfound', 'totara_reportbuilder', '', $report->get_id());
        }

        $endpoint = Helper::createCredlyEndpoint();
        if (!$endpoint->isEnabled()) {
            print_error('err:credly_not_enabled', 'local_credly');
        }

        $badgeIssueId = $this->get_required_param('badgeid', PARAM_INT);
        $badgeIssue = BadgeIssue::repository()->find_or_fail($badgeIssueId);

        if ($badgeIssue->isSuccessful()) {
            notification::error(get_string('err:cannot_reissue_badge', 'local_credly'));
            $this->doRedirect($report);
        }

        $endpoint->issueBadge($badgeIssue);

        if ($badgeIssue->isSuccessful()) {
            notification::success(get_string('page:badges:successfully_sent', 'local_credly'));
        } else {
            notification::error(get_string('err:badge_issue_failed', 'local_credly'));
        }
        $this->doRedirect($report);
        // No return, as redirect will kill the script here.
    }

    protected function doRedirect(reportbuilder $report): void
    {
        $returnUrl = $this->get_optional_param('return_url', null, PARAM_URL);
        if (!empty($returnUrl)) {
            redirect($returnUrl);
        }
        // Attempt to build the report URL if for some reason we don't have a return URL already.
        redirect($report->report_url($report->get_current_url_params()));
    }
}