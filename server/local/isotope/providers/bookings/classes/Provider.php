<?php
/**
 * @copyright City & Guilds Kineo 2018
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace isotopeprovider_bookings;

use coding_exception;
use context_course;
use context_system;
use core_course\theme\file\course_image;
use dml_exception;
use lang_string;
use local_isotope\Form\Checkbox;
use local_isotope\Form\Option;
use local_isotope\Form\Select;
use local_isotope\IsotopeProvider;
use moodle_exception;
use moodle_url;
use Twig_Environment;

class Provider extends IsotopeProvider
{
    const COMPONENT = 'isotopeprovider_bookings';

    /** @var string */
    private string $providerDefaultImageUrl = '';

    public function __construct()
    {
        $this->setConfig(
            [
                'display_finish_date' => 0,
                'display_times' => 0,
                'url_dest' => 'facetoface',
            ]
        );
    }

    /**
     * Return the human-friendly name of the provider.
     * @return lang_string|string
     * @throws coding_exception
     */
    public function getDisplayName()
    {
        return get_string('title', self::COMPONENT);
    }

    /**
     * Return the short name of the plugin, used in config settings, and as a unique key.
     * @return string
     */
    public function getShortName()
    {
        return 'bookings';
    }

    /**
     * Return all the items that will be displayed in the current block.
     * @return array
     * @throws dml_exception
     * @throws moodle_exception
     */
    public function load()
    {
        global $CFG, $USER, $DB;

        require_once $CFG->dirroot . '/mod/facetoface/lib.php';

        [$visibilitySql, $params] =
            totara_visibility_where($USER->id, 'c.id', 'c.visible', 'c.audiencevisible', 'c', 'course');

        $params['signupuser'] = $USER->id;
        $params['signupstatus'] = MDL_F2F_STATUS_REQUESTED;

        $signups = $DB->get_records_sql(
            "SELECT d.id, c.id as courseid, c.fullname AS coursename, c.cacherev, f.name,
                    f.id as facetofaceid, s.id as sessionid,
                    d.timestart, d.timefinish, d.sessiontimezone, su.userid, ss.statuscode as status
               FROM {facetoface_sessions_dates} d
               JOIN {facetoface_sessions} s ON s.id = d.sessionid AND s.cancelledstatus != 1
               JOIN {facetoface} f ON f.id = s.facetoface
               JOIN {facetoface_signups} su ON su.sessionid = s.id
               JOIN {facetoface_signups_status} ss ON su.id = ss.signupid AND ss.superceded = 0
               JOIN {course} c ON f.course = c.id
              WHERE su.userid = :signupuser AND ss.statuscode >= :signupstatus
              AND {$visibilitySql}
              ORDER BY d.timestart ASC",
            $params
        );

        $groupedDates = $signups && count($signups) > 0 ? $this->groupSessionDates($signups) : [];
        foreach ($groupedDates as $groupedDate) {
            switch ($this->config['url_dest']) {
                case 'course':
                    $groupedDate->url =
                        (new moodle_url('/course/view.php', ['id' => $groupedDate->courseid]))->out(false);
                    break;
                case 'session':
                    $groupedDate->url =
                        (new moodle_url('/mod/facetoface/signup.php', ['s' => $groupedDate->sessionid]))->out(false);
                    break;
                case 'facetoface':
                default:
                    $groupedDate->url =
                        (new moodle_url('/mod/facetoface/view.php', ['f' => $groupedDate->facetofaceid]))->out(false);
                    break;
            }
            $groupedDate->image = $this->getCourseImageUrl($groupedDate);
        }

        return [
            'items' => $this->futureSessionDates($groupedDates),
            'plugin' => self::COMPONENT,
            'config' => $this->getConfig(),
        ];
    }

    /**
     * Order of preference is course image -> core course default
     * @param $groupedDate
     * @return string|null
     */
    private function getCourseImageUrl($groupedDate): ?string
    {
        global $PAGE;

        if (empty($this->config['display_course_image']) || $this->config['display_course_image'] === 'disabled') {
            return null;
        }


        $courseContext = context_course::instance($groupedDate->courseid);
        $fs = get_file_storage();
        $imageFiles = $fs->get_area_files($courseContext->id, 'course', 'images', 0, "timemodified DESC", false);
        if ($imageFiles) {
            return moodle_url::make_pluginfile_url(
                $courseContext->id,
                'course',
                'images',
                $groupedDate->cacherev,
                '/',
                'image',
                false
            )->out(false);
        }

        $course_image = new course_image($PAGE->theme);
        $url = $course_image->get_current_or_default_url();

        return $url ?? null;
    }

    /**
     * Returns the path to the main template to be loaded.
     * @return string
     */
    public function getTemplateFilename()
    {
        return 'bookings.twig';
    }

    /**
     * Options that allow this Provider to be configured.
     * Use this in conjunction with {@see IsotopeProvider::extendForm()} to change the behaviour of the form object.
     *
     * @return Option[]
     * @throws coding_exception
     */
    public function getSettings()
    {
        $settings = parent::getSettings();

        $settings[] = new Select(
            $this->getShortName() . '_url_dest',
            get_string('config:url_dest', self::COMPONENT),
            [
                'facetoface' => get_string('facetoface', 'mod_facetoface'),
                'session' => get_string('facetofacesession', 'mod_facetoface'),
                'course' => get_string('course'),
            ]
        );
        $settings[] = new Select(
            $this->getShortName() . '_display_course_image',
            get_string('config:display_course_image', self::COMPONENT),
            [
                'disabled' => get_string('config:display_course_image:disabled', self::COMPONENT),
                'background' => get_string('config:display_course_image:background', self::COMPONENT),
            ]
        );
        $settings[] = new Checkbox(
            $this->getShortName() . '_display_finish_date',
            get_string('config:display_finish_date', self::COMPONENT)
        );
        $settings[] = new Checkbox(
            $this->getShortName() . '_display_times',
            get_string('config:display_times', self::COMPONENT)
        );

        return $settings;
    }


    /**
     * Include and init any required JavaScript.
     * @throws moodle_exception
     */
    public function initJavaScript()
    {
        global $PAGE, $CFG;

        $selector = !empty($this->blockInstanceId) ? '#inst' . $this->blockInstanceId : '';
        $PAGE->requires->jquery();
        $PAGE->requires->js_init_call(
            'M.isotope_provider_bookings.init',
            ['selector' => "{$selector} .bookings"],
            false,
            [
                'name' => 'isotope_provider_bookings',
                'fullpath' => new moodle_url(substr(dirname(__DIR__), strlen($CFG->dirroot)) . '/js/base.js'),
            ]
        );
    }

    /**
     * This function was ported from blocks/facetoface/lib.php from Totara 2.9 as it's missing in Totara 9.
     * Group the Session dates together instead of having separate sessions when it spans multiple days
     *
     * @param array $sessions
     * @return array
     */
    protected function groupSessionDates(array $sessions)
    {
        $groupedSessions = [];

        foreach ($sessions as $session) {
            if (!array_key_exists($session->sessionid, $groupedSessions)) {
                $allDates = [];

                // Clone the session object so we don't override the existing object.
                $newSession = clone($session);
                $groupedSessions[$newSession->sessionid] = $newSession;
            } else {
                if ($session->timestart < $groupedSessions[$session->sessionid]->timestart) {
                    $groupedSessions[$session->sessionid]->timestart = $session->timestart;
                }

                if ($session->timefinish > $groupedSessions[$session->sessionid]->timefinish) {
                    $groupedSessions[$session->sessionid]->timefinish = $session->timefinish;
                }
                $groupedSessions[$session->sessionid]->sessiontimezone = $session->sessiontimezone;
            }

            // Ensure that we have the correct status (enrolled, cancelled) for the submission.
            if (isset($session->status) and $session->status == 0) {
                $groupedSessions[$session->sessionid]->status = $session->status;
            }

            $allDates[$session->id] = new \stdClass();
            $allDates[$session->id]->timestart = $session->timestart;
            $allDates[$session->id]->timefinish = $session->timefinish;
            $allDates[$session->id]->sessiontimezone = $session->sessiontimezone;
            $groupedSessions[$session->sessionid]->alldates = $allDates;
        }
        return $groupedSessions;
    }

    /**
     * This function was ported from blocks/facetoface/lib.php from Totara 2.9 as it's missing in Totara 9.
     * Separate out the dates from $sessions that finish after the current time
     *
     * @param array $sessions
     * @return array
     */
    protected function futureSessionDates(array $sessions)
    {
        $futureSessions = [];
        $now = time();

        if (!empty($sessions)) {
            foreach ($sessions as $session) {
                // Check if the finish time is after the current time.
                if ($session->timefinish >= $now) {
                    $futureSessions[$session->sessionid] = clone($session);
                }
            }
        }
        return $futureSessions;
    }

    /**
     * @param Twig_Environment $twig
     */
    public function twigExtensions(Twig_Environment $twig)
    {
        $twig->addFilter(
            new \Twig_SimpleFilter(
                'moodle_date',
                function ($date) {
                    $format = !empty($this->config['display_times']) ? 'strftimedatetime' : 'strftimedate';
                    return userdate($date, get_string($format, 'langconfig'));
                }
            )
        );
    }
}