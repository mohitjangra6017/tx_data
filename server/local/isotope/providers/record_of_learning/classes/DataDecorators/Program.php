<?php

namespace isotopeprovider_record_of_learning\DataDecorators;

use coding_exception;
use dml_exception;
use isotopeprovider_record_of_learning\Repositories\ProgramCompletion;
use local_isotope\Data\DecoratorInterface;
use moodle_exception;

class Program implements DecoratorInterface
{
    /** @var ProgramCompletion */
    private $repo;

    /** @var array */
    private $config;

    public const COMPONENT = 'isotopeprovider_record_of_learning';
    private const DATE_FORMAT = '%e %b %Y';
    public const STATUS_UNSET = 'unset';
    public const STATUS_NOTSTARTED = 'notstarted';
    public const STATUS_STARTED = 'started';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_UNKNOWN = 'unknown';
    public const STATUS_EXPIRED = 'expired';

    /** @var string[] */
    protected $certstatusmap = [
        CERTIFSTATUS_UNSET => self::STATUS_UNSET,
        CERTIFSTATUS_ASSIGNED => self::STATUS_NOTSTARTED,
        CERTIFSTATUS_INPROGRESS => self::STATUS_STARTED,
        CERTIFSTATUS_COMPLETED => self::STATUS_COMPLETED,
        CERTIFSTATUS_EXPIRED => self::STATUS_EXPIRED,
    ];

    public function __construct(ProgramCompletion $repo)
    {
        global $CFG;
        require_once($CFG->dirroot . '/totara/certification/lib.php');
        $this->repo = $repo;
    }

    /**
     * Config options valid for the Decorator.
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * Do whatever needs doing to the collection of data items.
     *
     * @param array $data The data to decorate
     * @param array $context Extra information used to decorate items
     * @return array
     * @throws coding_exception
     * @throws dml_exception
     * @throws moodle_exception
     */
    public function decorate(array $data, $context = []): array
    {
        global $DB;

        $repo = $this->repo;

        return array_map(
            function ($item) use ($repo, $context, $DB) {
                global $CFG;
                $item->completion = $repo->get($item->id, $context['userid']);
                // Set status.
                if ($item->certifid > 0) {
                    if ($this->config['display_statewindowopen_status'] && certif_iswindowopen($item->certifid, $context['userid'])) {
                        global $CERTIFCOMPLETIONSTATE;
                        $item->windowopen = 1;
                        $item->status = $CERTIFCOMPLETIONSTATE[CERTIFCOMPLETIONSTATE_WINDOWOPEN];
                    } else if (isset($this->certstatusmap[$item->status])) {
                        $item->status = $this->certstatusmap[$item->status];
                    } else {
                        $item->status = self::STATUS_UNKNOWN;
                    }
                    $type = 'certification';
                } else {
                    if ($item->status == STATUS_PROGRAM_COMPLETE) {
                        $item->status = self::STATUS_COMPLETED;
                    } else if ($item->status == STATUS_PROGRAM_INCOMPLETE) {
                        if ($this->hasCoursesetStarted($item->id, $context['userid'])) {
                            $item->status = self::STATUS_STARTED;
                        } else {
                            $item->status = self::STATUS_NOTSTARTED;
                        }
                    } else {
                        $item->status = self::STATUS_UNKNOWN;
                    }
                    $type = 'program';
                }
                // Set url and date.
                $item->url = $CFG->wwwroot . '/totara/program/required.php?id=' . $item->id;

                if (isset($this->config['link_single_prog_courses']) && $this->config['link_single_prog_courses']) {
                    $sql = <<<SQL
SELECT DISTINCT pcc.courseid FROM {prog_courseset_course} pcc
LEFT JOIN {prog_courseset} pc ON pc.id = pcc.coursesetid
WHERE pc.programid = ?
SQL;
                    $courses = $DB->get_records_sql($sql, [$item->id]);
                    if (count($courses) == 1) {
                        // Send user to the program required.php which will trigger the enrolment.
                        // Going to course/view.php when the user is not enrolled will not trigger the enrolment
                        // and they will see a "course unavilable" message.
                        $url = new \moodle_url(
                            '/totara/program/required.php',
                            [
                                'id' => $item->id,
                                'cid' => reset($courses)->courseid,
                                'userid' => $context['userid'],
                                'sesskey' => sesskey(),
                            ]
                        );
                        $item->url = $url->out(false);
                    }
                }

                if ($item->status == self::STATUS_COMPLETED) {
                    if ($item->completion->timecompleted == 0 && $type == 'certification') {
                        $item->date = get_string('renewalstatus_dueforrenewal', 'totara_certification');
                    } else {
                        $item->date = userdate($item->completion->timecompleted, self::DATE_FORMAT, $CFG->timezone, false);
                        $item->date = get_string('datecomplete', self::COMPONENT, $item->date);
                    }
                } else if ($item->status == self::STATUS_NOTSTARTED || $item->status == self::STATUS_STARTED) {
                    if (empty($item->duedate) || $item->duedate == COMPLETION_TIME_NOT_SET) {
                        $item->date = get_string('nodatedue', self::COMPONENT);
                    } else {
                        $item->date = userdate($item->duedate, self::DATE_FORMAT, $CFG->timezone, false);
                        $item->date = get_string('datedue', self::COMPONENT, $item->date);
                    }
                } else {
                    $item->date = '';
                }
                $item->status = $this->is_overdue($item) ? 'overdue' : $item->status;
                $item->type = $type;
                $item->displaytype = get_string('type:' . $type, self::COMPONENT);
                $item->categoryname = $this->get_category($item->category);

                if ($this->config['display_status_colours_with_image']) {
                    $item->imgstatus = 'show';
                } else {
                    $item->imgstatus = 'hide';
                }

                // Get the program image.
                $fs = get_file_storage();
                $item->image = null;
                $files = $fs->get_area_files(\context_program::instance($item->id)->id, 'totara_program', 'images');
                $files += $fs->get_area_files(\context_program::instance($item->id)->id, 'totara_program', 'overviewfiles');

                foreach ($files as $file) {
                    if ($file->is_directory()) {
                        continue;
                    }
                    try {
                        if (!$file->is_valid_image()) {
                            continue;
                        }
                    } catch (moodle_exception $e) {
                        // So is_valid_image likes to throw an exception instead of returning false if the file doesn't exist.
                        continue;
                    }
                    // Set the image then drop out. We only want 1 of them.
                    $item->image = \moodle_url::make_pluginfile_url(
                        $file->get_contextid(),
                        $file->get_component(),
                        $file->get_filearea(),
                        $file->get_itemid(),
                        $file->get_filepath(),
                        $file->get_filename()
                    )
                    ->out(false);
                    break;
                }

                return $item;
            },
            $data
        );
    }

    /**
     * @param $item
     * @return bool
     */
    protected function is_overdue($item): bool
    {
        if ($item->status == self::STATUS_COMPLETED || $item->duedate > time() || $item->duedate <= 0) {
            return false;
        }
        return true;
    }

    /**
     * @param int $categoryid
     * @return string
     * @throws dml_exception
     */
    protected function get_category(int $categoryid): string
    {
        global $DB;
        $categoryName = $DB->get_field('course_categories', 'name', ['id' => $categoryid]);
        return $categoryName ?: '';
    }

    /**
     * @param int $programId
     * @param int $userId
     * @return bool
     * @throws dml_exception
     */
    protected function hasCoursesetStarted(int $programId, int $userId): bool
    {
        global $DB;

        $sql = "SELECT cc.status
                 FROM {prog} p
                 JOIN {prog_courseset} pc
                     ON p.id = pc.programid
                 JOIN {prog_courseset_course} pcc
                     ON pc.id = pcc.coursesetid
                 JOIN {course_completions} cc
                     ON cc.course = pcc.courseid
                 WHERE p.id = ? 
                 AND cc.userid = ?
                 AND cc.status >= ?
                ";

        return $DB->record_exists_sql($sql, [$programId, $userId, COMPLETION_STATUS_INPROGRESS]);
    }
}