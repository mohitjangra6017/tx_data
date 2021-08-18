<?php
/**
 * This file is part of Totara Core
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
 * @author  Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package contentmarketplace_linkedin
 */
namespace contentmarketplace_linkedin\dto\xapi;

use coding_exception;

class progress {
    /**
     * From Linkedin Learning help documentation:
     * sent when the learner/actor has completed a course.
     *
     * @see https://docs.microsoft.com/en-us/linkedin/learning/integrations/xapi
     * @var string
     */
    public const COMPLETED = "COMPLETED";

    /**
     * From Linkedin Learning help documentation:
     * sent when the learner/actor has completed a video in a course.
     *
     * @see https://docs.microsoft.com/en-us/linkedin/learning/integrations/xapi
     * @var string
     */
    public const PROGRESSED = "PROGRESSED";

    /**
     * @var bool
     */
    private $completed;

    /**
     * The value should be related to the constants which are
     * {@see progress::COMPLETED} or {@see progress::PROGRESSED}
     *
     * @var string|null
     */
    private $progress;

    /**
     * @param bool $completed
     * @param string $progress
     */
    private function __construct(bool $completed, string $progress) {
        $this->completed = $completed;
        $this->progress = $progress;
    }

    /**
     * Create an instance of progress dto with validation.
     *
     * @param bool $completed
     * @param string $progress
     *
     * @return progress
     */
    public static function create(bool $completed, string $progress): progress {
        if (!in_array($progress, [self::PROGRESSED, self::COMPLETED])) {
            throw new coding_exception("Invalid value for progress");
        }

        return new self($completed, $progress);
    }

    /**
     * @return bool
     */
    public function is_completed(): bool {
        return $this->completed;
    }

    /**
     * @return string
     */
    public function get_progress(): string {
        return $this->progress;
    }
}