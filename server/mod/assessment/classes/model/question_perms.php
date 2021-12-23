<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2019 Kineo
 */

namespace mod_assessment\model;

class question_perms
{

    public const CAN_VIEW_OTHER = 1;
    public const CAN_VIEW_SUBMITTED = 2;
    public const CAN_ANSWER = 4;
    public const IS_REQUIRED = 8;

    /** @var bool */
    protected bool $cananswer;

    /** @var bool */
    protected bool $required;

    /** @var bool */
    protected bool $viewother;

    /** @var bool */
    protected bool $viewsubmitted;

    public static function get_types(): array
    {
        return [
            self::CAN_ANSWER => 'cananswer',
            self::IS_REQUIRED => 'requireanswer',
            self::CAN_VIEW_OTHER => 'canviewother',
            self::CAN_VIEW_SUBMITTED => 'canviewsubmitted'
        ];
    }

    public function __construct(bool $cananswer, bool $required, bool $viewother, bool $viewsubmitted)
    {
        $this->viewsubmitted = $viewsubmitted;
        if ($this->viewsubmitted) {
            $this->cananswer = false;
            $this->required = false;
            $this->viewother = false;
            return;
        }

        $this->cananswer = $cananswer;
        $this->required = $this->cananswer && $required;
        $this->viewother = $viewother;
    }

    public function can_answer(): bool
    {
        return $this->cananswer;
    }

    public function can_viewother(): bool
    {
        return $this->viewother;
    }

    public function can_viewsubmitted(): bool
    {
        return $this->viewsubmitted;
    }

    public function is_required(): bool
    {
        return $this->required;
    }

    public function get_perms(): array
    {
        return [
            'cananswer' => $this->cananswer ? self::CAN_ANSWER : 0,
            'requireanswer' => $this->required ? self::IS_REQUIRED : 0,
            'canviewother' => $this->viewother ? self::CAN_VIEW_OTHER : 0,
            'canviewsubmitted' => $this->viewsubmitted ? self::CAN_VIEW_SUBMITTED : 0,
        ];
    }

    public function value(): int
    {
        return ($this->cananswer ? self::CAN_ANSWER : 0)
            | ($this->required ? self::IS_REQUIRED : 0)
            | ($this->viewother ? self::CAN_VIEW_OTHER : 0)
            | ($this->viewsubmitted ? self::CAN_VIEW_SUBMITTED : 0);
    }

}