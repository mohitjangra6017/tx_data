<?php
/**
 * Required Certification Source
 *
 * @package   isotopeprovider_required_learning
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace isotopeprovider_required_learning\Source;

defined('MOODLE_INTERNAL') || die;

global $CFG;

require_once($CFG->dirroot . '/totara/program/lib.php');

class RequiredCertificationSource implements SourceInterface
{
    /**
     * @var int
     */
    protected $userId;

    /**
     * RequiredCertificationSource constructor.
     * @param int $userId
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        return prog_get_certification_programs($this->userId, '', '', '', false, true, true);
    }
}