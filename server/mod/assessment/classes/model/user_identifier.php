<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\model;

use Exception;

defined('MOODLE_INTERNAL') || die();

class user_identifier
{
    public const ID = 'id';
    public const IDNUMBER = 'idnumber';
    public const USERNAME = 'username';
    public const EMAIL = 'email';

    /** @var string */
    protected string $identifier;

    public function __construct(string $identifier)
    {
        if (!array_key_exists($identifier, $this->get_identifiers())) {
            throw new Exception("Invalid role: {$identifier}");
        }

        $this->identifier = $identifier;
    }

    public static function get_identifiers(): array
    {
        return [
            self::ID => get_string('userid', 'totara_userdata'),
            self::USERNAME => get_string('username'),
            self::IDNUMBER => get_string('idnumber'),
            self::EMAIL => get_string('email'),
        ];
    }

    public function get_string(): array
    {
        return self::get_identifiers()[$this->identifier];
    }

    public function value(): string
    {
        return $this->identifier;
    }
}
