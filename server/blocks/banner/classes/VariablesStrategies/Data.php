<?php

namespace block_banner\VariablesStrategies;

use coding_exception;
use dml_exception;
use stdClass;

defined('MOODLE_INTERNAL') || die;

class Data
{
    /** @var stdClass */
    protected $user = null;

    /** @var null */
    protected $assignment = null;

    /**
     * data constructor.
     * @param int $userid
     * @throws dml_exception
     */
    public function __construct(int $userid)
    {
        global $DB;
        $this->user = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);
    }

    /**
     * @param string $name
     * @return string
     * @throws coding_exception
     */
    public function __get(string $name): string
    {
        if (!isloggedin() || isguestuser()) {
            return '';
        }

        $function = 'get' . ucfirst($name);
        if (!method_exists($this, $function)) {
            return '';
        }
        return $this->{$function}();
    }

    /**
     * @return string
     */
    protected function getLastName(): string
    {
        return $this->user->lastname;
    }

    /**
     * @return string
     */
    protected function getFirstName(): string
    {
        return $this->user->firstname;
    }

    /**
     * @return string
     */
    protected function getFullName(): string
    {
        return fullname($this->user);
    }

    /**
     * @return string
     */
    protected function getUserEmail(): string
    {
        return $this->user->email;
    }

    /**
     * @return int
     */
    protected function getUserId(): int
    {
        return $this->user->id;
    }
}
