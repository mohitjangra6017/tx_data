<?php
/**
 * @package   local_email_log
 * @author    Jo Jones <jo.jones@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

defined('MOODLE_INTERNAL') || die();

class rb_email_log_embedded extends rb_base_embedded
{
    public $defaultsortcolumn;
    protected $programid;

    public function __construct($data)
    {
        $this->url = '/local/email_log/index.php';
        $this->source = 'email_log';
        $this->shortname = 'email_log';
        $this->fullname = get_string('pluginname', 'local_email_log');
        $this->embeddedparams = $data;

        $this->columns = $this->define_columns();
        $this->filters = $this->define_filters();

        parent::__construct();
    }

    public function define_columns(): array
    {
        return [
            [
                'type' => 'base',
                'value' => 'timesent',
                'heading' => get_string('timesent', 'local_email_log'),
            ],
            [
                'type' => 'user',
                'value' => 'fullname',
                'heading' => get_string('fullname', 'local_email_log'),
            ],
            [
                'type' => 'base',
                'value' => 'usertoemail',
                'heading' => get_string('usertoemail', 'local_email_log'),
            ],
            [
                'type' => 'base',
                'value' => 'subject',
                'heading' => get_string('subject', 'local_email_log'),
            ],
            [
                'type' => 'base',
                'value' => 'message',
                'heading' => get_string('message', 'local_email_log'),
            ],
            [
                'type' => 'base',
                'value' => 'status',
                'heading' => get_string('status', 'local_email_log'),
            ],
            [
                'type' => 'base',
                'value' => 'actions',
                'heading' => get_string('actions', 'local_email_log'),
            ],
        ];
    }

    public function define_filters(): array
    {
        return [];
    }


    public function is_capable(): bool
    {
        return has_capability('local/email_log:access', context_system::instance());
    }
}