<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

namespace mod_assessment\question;

use mod_assessment\model\answer;

trait gradable
{

    abstract public function get_max_score();

    abstract public function get_score(answer $answer);

    public function is_gradable(): bool
    {
        return true;
    }
}
