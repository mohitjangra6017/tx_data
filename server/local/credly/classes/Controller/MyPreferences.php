<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_credly\Controller;

use context;
use context_user;
use core\notification;
use JsonSerializable;
use local_credly\Form\MyPreferencesForm;
use stdClass;
use totara_mvc\controller;
use totara_mvc\view;
use totara_mvc\viewable;

class MyPreferences extends controller
{
    protected $require_login = true;

    protected $layout = 'standard';

    public function __construct()
    {
        parent::__construct();
        $this->set_url(new \moodle_url('/local/credly/mypreferences.php'));
        $this->get_page()->set_title(get_string('user:preferences', 'local_credly'));
    }

    /**
     * @inheritDoc
     */
    protected function setup_context(): context
    {
        return context_user::instance($this->currently_logged_in_user()->id);
    }

    /**
     * This is the default action and it can be overridden by the children.
     * If no action is passed to the render method this default action is called.
     * In this case it has to be defined in child classes.
     *
     * @return viewable|string|array|stdClass|JsonSerializable if it cannot be cast to a string the result will be json encoded
     */
    public function action()
    {
        $optOut = get_user_preferences('local_credly_opt_out', '0');

        $form = new MyPreferencesForm(['opt_out' => $optOut]);
        if ($data = $form->get_data()) {
            set_user_preference('local_credly_opt_out', $data->opt_out);
            notification::success(get_string('user_preference:updated', 'local_credly'));
            redirect($this->url);
        }

        return view::create('local_credly/my_preferences', ['form' => $form->render()]);
    }
}