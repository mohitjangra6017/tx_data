<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_credly\entity;

use core\orm\entity\entity;

/**
 * @property string $credlyid
 * @property string $name
 * @property int|null $programid
 * @property int|null $certificationid
 * @property int|null $courseid
 * @property string|null $state
 *
 * @property-read string $linktype
 * @property-read string|null $linkedlearningname
 *
 * @method static BadgeRepository repository()
 */
class Badge extends entity
{
    public const TABLE = 'local_credly_badges';

    public static function repository_class_name(): string
    {
        return BadgeRepository::class;
    }

    public function get_linktype_attribute(): string
    {
        if ($this->programid === null && $this->certificationid === null && $this->courseid === null) {
            return 'unlinked';
        }

        if ($this->programid !== null && $this->certificationid === null && $this->courseid === null) {
            return 'program';
        }

        if ($this->programid === null && $this->certificationid !== null && $this->courseid === null) {
            return 'certification';
        }

        if ($this->programid === null && $this->certificationid === null && $this->courseid !== null) {
            return 'course';
        }
    }

    public function get_linkedlearningname_attribute(): ?string
    {
        global $DB;

        if ($this->programid === null && $this->certificationid === null && $this->courseid === null) {
            return null;
        }

        if ($this->programid !== null && $this->certificationid === null && $this->courseid === null) {
            return $DB->get_field('prog', 'fullname', ['id' => $this->programid]);
        }

        if ($this->programid === null && $this->certificationid !== null && $this->courseid === null) {
            return $DB->get_field('prog', 'fullname', ['certifid' => $this->certificationid]);
        }

        if ($this->programid === null && $this->certificationid === null && $this->courseid !== null) {
            return $DB->get_field('course', 'fullname', ['id' => $this->courseid]);
        }
    }
}
