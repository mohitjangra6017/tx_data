<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace local_email_log\rb\display;

defined('MOODLE_INTERNAL') || die();

use html_writer;
use totara_reportbuilder\rb\display\base;

class html_to_text extends base
{
    private static bool $jsCalled = false;

    public static function display($value, $format, \stdClass $row, \rb_column $column, \reportbuilder $report): string
    {
        global $CFG, $PAGE;

        if (!self::$jsCalled) {
            $PAGE->requires->js_call_amd('local_email_log/modal', 'init_dialog_link');
            self::$jsCalled = true;
        }

        $text = html_to_text($value);
        $text = str_replace('&nbsp;', '', htmlentities($text));
        $text = preg_replace('/\s+/', ' ', $text);
        if (strlen($text) > 250) {
            $text = substr($text, 0, 250) . '...';
        }

        if ($format == 'html') {
            return html_writer::link(
                'javascript:void(0)',
                $text,
                [
                    'class' => 'modal-dialogbox-link',
                    'data-url' => "{$CFG->wwwroot}/local/email_log/view.php?id={$row->id}",
                ]
            );
        } else {
            return $text;
        }
    }
}
