<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2017 Kineo
 * @package totara
 * @subpackage assquestion_html
 */

namespace mod_assessment\question\html\model;

use context_module;
use mod_assessment\question\html;
use rb_column;
use reportbuilder;
use stdClass;
use totara_form\element;
use totara_form\form;

defined('MOODLE_INTERNAL') || die();

class question extends \mod_assessment\model\question
{

    // TODO: Make non-questions extend nonquestion, which doesn't require this unneeded stuff?
    public function encode_value($value, form $form): string
    {
        return '';
    }

    public function get_default(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function get_displayname(): string
    {
        return get_string('pluginname', 'assquestion_html');
    }

    public function get_element(): html\form\element\html
    {
        return new html\form\element\html("q_{$this->id}", $this->question);
    }

    public function get_html(context_module $context): string
    {
        // Make sure to format the text based on the given format, so we can handle editors that aren't Atto.
        $html = file_rewrite_pluginfile_urls($this->get_config()->html->text, 'pluginfile.php', $context->id, 'mod_assessment', 'html', $this->id);
        return format_text($html, $this->get_config()->html->format);
    }

    /**
     * @return string
     */
    public function get_type(): string
    {
        return 'html';
    }

    /**
     * @return bool
     */
    public function is_gradable(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function is_question(): bool
    {
        return false;
    }

    public function report_display($value, $format, stdClass $row, rb_column $column, reportbuilder $report): string
    {
        debugging('HTML question should not be included in report displays');
        return '';
    }
}
