<?php

namespace block_banner\VariablesStrategies;

use dml_exception;

defined('MOODLE_INTERNAL') || die;

class Variables
{
    /** @var string */
    private const PREFIX = '{%';

    /** @var string */
    private const POSTFIX = '%}';

    /** @var array */
    protected $variables = [
        'firstname',
        'lastname',
        'fullname',
        'username',
        'useremail',
        'userid',
        'userpos',
        'userorg',
        'managername',
    ];

    /**
     * @param string $text
     * @param int $userId
     * @param bool $addSpan
     * @return mixed
     * @throws dml_exception
     */
    public function replaceVariables(string $text, int $userId, bool $addSpan)
    {
        global $CFG;

        if (empty($userId)) {
            // Fallback to guest.
            $userId = $CFG->siteguest;
        }

        $data = new DataJobs($userId);

        $variables = [];
        foreach ($this->variables as $variable) {
            $value = $data->{$variable};
            if ($addSpan && $variable != 'userid') {
                $value = \html_writer::span($value, 'banner-variable-' . $variable);
            }
            $variables[$this->formatVariable($variable)] = $value;
        }

        return str_ireplace(array_keys($variables), array_values($variables), $text);
    }

    /**
     * Get supported variables.
     * @param bool $justNames
     * @return array
     */
    public function getVariables(bool $justNames = false): array
    {
        if ($justNames) {
            return $this->variables;
        }

        return $this->formatVariables($this->variables);
    }

    /**
     * Format the variables with the prefix and postfix.
     * @param array $variables
     * @return array
     */
    public function formatVariables(array $variables): array
    {
        return array_map([$this, 'formatVariable'], $variables);
    }

    /**
     * @param $name
     * @return string
     */
    public function formatVariable($name): string
    {
        return self::PREFIX . $name . self::POSTFIX;
    }
}