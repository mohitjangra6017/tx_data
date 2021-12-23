<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace local_core\Hook\Email;


use moodle_phpmailer;
use stdClass;
use totara_core\hook\base;

class PreSend extends base
{
    private stdClass $userTo;
    private stdClass $userFrom;
    private moodle_phpmailer $mailer;

    public function __construct(stdClass $userTo, stdClass $userFrom, moodle_phpmailer $mailer)
    {
        $this->userTo = $userTo;
        $this->userFrom = $userFrom;
        $this->mailer = $mailer;
    }

    /**
     * @return stdClass
     */
    public function getUserTo(): stdClass
    {
        return $this->userTo;
    }

    /**
     * @param stdClass $userTo
     * @return PreSend
     */
    public function setUserTo(stdClass $userTo): PreSend
    {
        $this->userTo = $userTo;
        return $this;
    }

    /**
     * @return stdClass
     */
    public function getUserFrom(): stdClass
    {
        return $this->userFrom;
    }

    /**
     * @param stdClass $userFrom
     * @return PreSend
     */
    public function setUserFrom(stdClass $userFrom): PreSend
    {
        $this->userFrom = $userFrom;
        return $this;
    }

    /**
     * @return moodle_phpmailer
     */
    public function getMailer(): moodle_phpmailer
    {
        return $this->mailer;
    }

    /**
     * @param moodle_phpmailer $mailer
     * @return PreSend
     */
    public function setMailer(moodle_phpmailer $mailer): PreSend
    {
        $this->mailer = $mailer;
        return $this;
    }
}