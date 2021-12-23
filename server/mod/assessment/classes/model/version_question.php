<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

namespace mod_assessment\model;

use dml_exception;
use mod_assessment\entity\SimpleDBO;
use moodle_database;

defined('MOODLE_INTERNAL') || die();

class version_question extends SimpleDBO
{

    public const TABLE = 'assessment_version_question';

    /** @var int $questionid */
    public int $questionid;

    /** @var int|null */
    public ?int $parentid;

    /** @var int $sortorder */
    public int $sortorder;

    /** @var int $stageid */
    public int $stageid;

    /** @var int $sortorder */
    public int $versionid;

    /**
     * @param array $conditions
     * @return self[]
     * @throws dml_exception
     */
    public static function instances(array $conditions): array
    {
        global $DB;
        $instances = [];
        $records = $DB->get_records(self::TABLE, $conditions);
        foreach ($records as $record) {
            $instance = new self();
            $instance->set_data($record);
            $instances[$instance->id] = $instance;
        }
        return $instances;
    }

    /**
     * Sets sortorder to earliest available slot
     *
     * @return self
     * @global moodle_database $DB
     */
    public function calculate_sortorder(): version_question
    {
        global $DB;

        $params = ['versionid' => $this->versionid, 'stageid' => $this->stageid];
        $whereparent = "parentid IS NULL";
        if ($this->get_parentid()) {
            $whereparent = "parentid = :parentid";
            $params['parentid'] = $this->get_parentid();
        }

        $sql = "SELECT sortorder+1
                  FROM (SELECT 0 AS sortorder UNION SELECT sortorder FROM {" . self::TABLE . "}) avq
                 WHERE NOT EXISTS ( SELECT NULL
                                      FROM ( SELECT sortorder FROM {" . self::TABLE . "} WHERE stageid = :stageid AND versionid = :versionid AND {$whereparent} ) avq2
                                     WHERE avq2.sortorder = avq.sortorder+1 )
              ORDER BY sortorder
                 LIMIT 1";

        $sortorder = $DB->get_field_sql($sql, $params);
        $this->sortorder = $sortorder ? $sortorder : 1;
        return $this;
    }

    public function get_parentid(): ?int
    {
        return $this->parentid;
    }

    public function get_stageid(): int
    {
        return $this->stageid;
    }

    public function set_parentid(?int $parentid): self
    {
        $this->parentid = $parentid;
        return $this;
    }

    /**
     * @param int $questionid
     * @return self
     */
    public function set_questionid(int $questionid): version_question
    {
        $this->questionid = $questionid;
        return $this;
    }

    /**
     * @param int $stageid
     * @return self
     */
    public function set_stageid(int $stageid): version_question
    {
        $this->stageid = $stageid;
        return $this;
    }

    /**
     * @param int $versionid
     * @return self
     */
    public function set_versionid(int $versionid): version_question
    {
        $this->versionid = $versionid;
        return $this;
    }

    /**
     * @return bool
     */
    public function can_moveup(): bool
    {
        return $this->sortorder > 1;
    }

    /**
     * @return bool
     * @global moodle_database $DB
     */
    public function can_movedown(): bool
    {
        global $DB;

        $params = ['stageid' => $this->stageid, 'versionid' => $this->versionid];
        $whereparent = "parentid IS NULL";
        if ($this->get_parentid()) {
            $whereparent = "parentid = :parentid";
            $params['parentid'] = $this->get_parentid();
        }
        $sql = "SELECT MAX(sortorder)
                  FROM {" . self::TABLE . "}
                 WHERE stageid = :stageid
                   AND versionid = :versionid
                   AND {$whereparent}";
        $maxorder = $DB->get_field_sql($sql, $params);
        return $this->sortorder < $maxorder;
    }

    /**
     * Updates the sort order of the question in the current version and rearranges associated questions as needed
     *
     * @param int $newso Desired new sort order
     * @global moodle_database $DB
     */
    public function update_sortorder(int $newso)
    {
        global $DB;
        $params = [
            'stageid' => $this->stageid,
            'versionid' => $this->versionid,
            'currentso' => $this->sortorder,
            'newso' => $newso
        ];

        $parentwhere = "parentid IS NULL";
        if ($this->get_parentid()) {
            $params['parentid'] = $this->get_parentid();
            $parentwhere = "parentid = :parentid";
        }

        if ($newso < $this->sortorder) {
            $sql = "UPDATE {" . self::TABLE . "} SET sortorder = sortorder + 1
                     WHERE stageid = :stageid
                           AND versionid = :versionid
                           AND sortorder < :currentso
                           AND sortorder >= :newso
                           AND {$parentwhere}";
            $DB->execute($sql, $params);
        } elseif ($newso > $this->sortorder) {
            $sql = "UPDATE {" . self::TABLE . "} SET sortorder = sortorder - 1
                     WHERE stageid = :stageid
                           AND versionid = :versionid
                           AND sortorder > :currentso
                           AND sortorder <= :newso
                           AND {$parentwhere}";
            $DB->execute($sql, $params);
        }

        // Fill in the hole that was made!
        $this->calculate_sortorder();
        $this->save();
    }
}
