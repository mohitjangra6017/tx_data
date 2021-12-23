<?php
/**
 * Required Program Source
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

class RequiredProgramSource implements SourceInterface
{
    /**
     * @var int
     */
    protected $userId;

    /**
     * ProgramSource constructor.
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
        return prog_get_required_programs($this->userId, '', '', '', false, true);
    }
}