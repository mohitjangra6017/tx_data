<?php
/**
 * This file is part of Totara Learn
 *
 * Copyright (C) 2021 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author  Matthias Bonk <matthias.bonk@totaralearning.com>
 * @package totara_notification
 */
namespace totara_program\totara_notification\placeholder;

use coding_exception;
use core\orm\query\builder;
use prog_assignment_category;
use stdClass;
use totara_notification\placeholder\abstraction\single_emptiable_placeholder;
use totara_notification\placeholder\option;
use totara_program\utils;

class assignment extends single_emptiable_placeholder {
    /**
     * @var ?stdClass
     */
    private $record;

    /**
     * @var int
     */
    private $user_id;

    /**
     * @param stdClass|null $record
     * @param int $user_id
     */
    public function __construct(?stdClass $record, int $user_id) {
        $this->record = $record;
        $this->user_id = $user_id;
    }

    /**
     * @param int $program_id
     * @param int $user_id
     * @return assignment
     */
    public static function from_program_id_and_user_id(int $program_id, int $user_id): assignment {
        global $DB;

        // Use the most recently assigned.
        $user_assignments = $DB->get_records(
            'prog_user_assignment',
            ['programid' => $program_id, 'userid' => $user_id],
            'timeassigned DESC, id ASC'
        );
        $record = null;
        if (count($user_assignments) > 0) {
            $user_assignment = reset($user_assignments);
            $record = $DB->get_record('prog_assignment', ['id' => $user_assignment->assignmentid]);
            $record = $record ?: null;
        }
        return new static($record, $user_id);
    }

    /**
     * @return option[]
     */
    public static function get_options(): array {
        return [
            option::create('due_date_criteria', get_string('due_date_criteria', 'totara_program')),
            option::create('due_date', get_string('duedate', 'totara_program')),
        ];
    }

    /**
     * @param string $key
     * @return bool
     */
    protected function is_available(string $key): bool {
        return null !== $this->record;
    }

    /**
     * @param string $key
     * @return string
     */
    public function do_get(string $key): string {
        if (null === $this->record) {
            throw new coding_exception("The program assignment record is empty");
        }

        switch ($key) {
            case 'due_date_criteria':
                return $this->get_due_date_criteria();
            case 'due_date':
                return $this->get_due_date();
        }

        throw new coding_exception("Invalid key '{$key}'");
    }

    /**
     * This is mostly taken from the legacy program messages, see prog_message::set_replacementvars().
     *
     * @return string
     */
    private function get_due_date(): string {
        // Get completion date.
        $record = builder::table('prog_completion')
            ->select('timedue')
            ->where('programid', $this->record->programid)
            ->where('userid', $this->user_id)
            ->where('coursesetid', 0)
            ->order_by('timedue')
            ->first();
        $completion_time = $record->timedue ?? null;
        if ($completion_time && (int)$completion_time !== COMPLETION_TIME_NOT_SET) {
            $date_time_format = get_string("strftimedatefulllong", "langconfig");
            $due_date = userdate(
                $completion_time,
                $date_time_format,
                99, // Use current user's timezone which should be the notification recipient's one.
                false
            );
        } else {
            $due_date = get_string('duedatenotset', 'totara_program');
        }
        return $due_date;
    }

    /**
     * This is mostly taken from the legacy program messages, see prog_message::set_replacementvars().
     *
     * @return string
     */
    private function get_due_date_criteria(): string {
        require_once(__DIR__ . '../../../../program_assignments.class.php');

        if (null === $this->record) {
            return get_string('due_date_criteria_not_defined', 'totara_program');
        }

        $time = $this->record->completiontime;
        $event = $this->record->completionevent;
        $instance = $this->record->completioninstance;

        // Get completion criteria.
        if ((int)$this->record->completionevent === COMPLETION_EVENT_NONE) {
            $criteria = get_string('due_date_criteria_not_defined', 'totara_program');
            if ((int)$time !== COMPLETION_TIME_NOT_SET) {
                $formatted_time = trim(userdate(
                    $time,
                    get_string('strftimedatefulllong', 'langconfig'),
                    99, // Use current user's timezone which should be the notification recipient's one.
                    false
                ));
                $criteria = prog_assignment_category::build_completion_string($formatted_time, $event, $instance);
            }
        } else {
            $parts = utils::duration_explode($time);
            $formatted_time = $parts->num . ' ' . $parts->period;
            $criteria = prog_assignment_category::build_completion_string($formatted_time, $event, $instance);
        }
        return $criteria;
    }
}