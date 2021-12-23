<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package mod_assessment
 * @subpackage assquestion_multichoice
 */

namespace mod_assessment\question\multichoice\model;

defined('MOODLE_INTERNAL') || die();

class response
{

    public $default;
    public $penalty;
    public $text;
    public $value;
    /**
     * @var string[]
     */
    private array $raw;

    /**
     * @param string $text
     * @param string $value
     * @param string $penalty
     * @param bool $default
     */
    public function __construct(string $text, string $value, string $penalty, $default = false)
    {
        $this->raw = [
            'value' => $value,
            'penalty' => $penalty,
        ];

        $this->text = $text;
        $this->value = (int)$value;
        $this->penalty = (int)$penalty;
        $this->default = $default;
    }

    /**
     * @return int
     */
    public function get_penalty(): int
    {
        return (int)$this->penalty;
    }

    /**
     * @param int $penalty
     * @return response
     */
    public function set_penalty(int $penalty): response
    {
        $this->penalty = $penalty;
        return $this;
    }

    /**
     * @return string[]
     */
    public function get_raw(): array
    {
        return $this->raw;
    }

    /**
     * @param string $value
     * @param string $penalty
     * @return $this
     */
    public function set_raw(string $value, string $penalty): response
    {
        $this->raw = [
            'value' => $value,
            'penalty' => $penalty,
        ];
        return $this;
    }

    /**
     * @return string
     */
    public function get_text(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function set_text(string $text): response
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return int
     */
    public function get_value(): int
    {
        return (int)$this->value;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function set_value(int $value): response
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function is_default(): bool
    {
        return $this->default;
    }

    /**
     * @param bool $default
     * @return $this
     */
    public function make_default(bool $default): response
    {
        $this->default = $default;
        return $this;
    }

}
