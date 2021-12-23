<?php
/**
 * Completion Decorator
 *
 * @package   isotopeprovider_required_learning
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace isotopeprovider_required_learning\Decorator;

use dml_exception;
use moodle_database;

defined('MOODLE_INTERNAL') || die;

class CompletionDecorator implements DecoratorInterface
{
    /**
     * @var moodle_database
     */
    protected $db;

    /**
     * @var int
     */
    protected $userId;

    /**
     * CompletionDecorator constructor.
     * @param moodle_database $db
     * @param int $userId
     */
    public function __construct(moodle_database $db, int $userId)
    {
        $this->db = $db;
        $this->userId = $userId;
    }

    /**
     * @param object $item
     * @return object
     * @throws dml_exception
     */
    public function decorate(object $item): object
    {
        $item->completion = $this->db->get_record(
            'prog_completion',
            [
                'programid' => $item->id,
                'userid' => $this->userId,
                'coursesetid' => 0,
            ]
        );

        return $item;
    }
}