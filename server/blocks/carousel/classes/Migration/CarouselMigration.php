<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace block_carousel\Migration;

use core\orm\query\builder;
use local_core\Migration\BlockInstanceMigration;
use stdClass;
use totara_core\totara\menu\build;

class CarouselMigration extends BlockInstanceMigration
{
    /**
     * @var string The internal name of the block instance being replaced.
     */
    protected string $oldBlockName = 'block_kineo_carousel';

    /**
     * @var string The internal name of the block instance to replace the old one.
     */
    protected string $newBlockName = 'block_carousel';

    /** @var stdClass[] */
    private array $cohorts;

    /** @var stdClass[] */
    private array $curated;

    /** @var stdClass[] */
    private array $curatedCourses;

    /** @var stdClass[] */
    private array $curatedTags;

    /** @var stdClass[] */
    private array $files;

    private array $config;

    /**
     * Defines if this Migration should be run.
     *
     * Returning true will cause both _prepare_ and _execute_ to be called.
     * It is required that when _execute_ runs, you ensure that _canMigrate_ can no longer return true.
     * If _canMigrate_ returns true on the existence of a DB table, then DROP that table in _execute_.
     * This is an error and will cause your migration to fail if _canMigrate_ returns true after _execute_.
     *
     * @return bool
     */
    public function canMigrate(): bool
    {
        // We only need to check if 1 DB table exists, as they were all created at the same time.
        return parent::canMigrate() && builder::get_db()->get_manager()->table_exists('block_kineo_carousel_cohorts');
    }


    /**
     * This is called before the Totara upgrade is run. This gives your Migration a chance to collect any
     * data it needs, such as from the database or the Moodle data directory.
     */
    public function prepare(): void
    {
        parent::prepare();
        $this->cohorts = builder::table('block_kineo_carousel_cohorts')->fetch();
        $this->curated = builder::table('block_kineo_carousel_curated')->fetch();
        $this->curatedCourses = builder::table('block_kineo_carousel_curated_course')->fetch();
        $this->curatedTags = builder::table('block_kineo_carousel_curated_tags')->fetch();
        $this->config = get_config('block_kineo_carousel');

        $fs = get_file_storage();
        $files = builder::table('files')->where('component', 'block_kineo_carousel')->fetch();
        foreach ($files as $fr) {
            $file = $fs->get_file($fr->contextid, $fr->component, $fr->filearea, $fr->itemid, '/', $fr->filename);
            if (!$file || $file->is_directory()) {
                continue;
            }
            $this->files[] = (object) [
                'contextid' => $fr->contextid,
                'component' => $fr->component,
                'filearea' => $fr->filearea,
                'itemid' => $fr->itemid,
                'filename' => $fr->filename,
                'content' => $file->get_content(),
            ];
        }
        builder::table('files')->where_in('id', array_column($files, 'id'))->delete();
    }

    /**
     * Do all your actual Migration work here.
     * Any uncaught exceptions here will cause a full rollback of the DB.
     * Make sure that _canMigrate_ returns false by the time this function completes, otherwise your Migration
     * will cause an error and will fail the entire upgrade.
     */
    public function execute(): void
    {
        $this->log('Migrating Block Carousel data...');
        $fs = get_file_storage();

        foreach ($this->cohorts as $cohort) {
            builder::table('block_carousel_cohorts')->insert($cohort);
        }

        foreach ($this->curated as $curatedItem) {
            $oldId = $curatedItem->id;
            $newId = builder::table('block_carousel_curated')->insert($curatedItem);

            $curatedCourses = array_filter($this->curatedCourses, fn($course) => $course->curatedid == $oldId);
            foreach ($curatedCourses as $course) {
                $course->curatedid = $newId;
                builder::table('block_carousel_curated_course')->insert($course);
            }

            $curatedTags = array_filter($this->curatedTags, fn($tag) => $tag->curatedid == $oldId);
            foreach ($curatedTags as $tag) {
                $tag->curatedid = $newId;
                builder::table('block_carousel_curated_tags')->insert($tag);
            }

            $files = array_filter($this->files, fn($file) => $file->itemid == $oldId);
            foreach ($files as $file) {
                $record = clone $file;
                unset($record->content);
                $record->component = 'block_carousel';
                $record->itemid = $newId;
                $fs->create_file_from_string($record, $file->content);
            }
        }

        foreach ($this->config as $item => $value) {
            if ($item === 'version') {
                continue;
            }
            set_config($item, $value, 'block_carousel');
            unset_config($item, 'block_kineo_carousel');
        }

        $manager = builder::get_db()->get_manager();
        $manager->drop_table(new \xmldb_table('block_kineo_carousel_curated_tags'));
        $manager->drop_table(new \xmldb_table('block_kineo_carousel_curated_course'));
        $manager->drop_table(new \xmldb_table('block_kineo_carousel_curated'));
        $manager->drop_table(new \xmldb_table('block_kineo_carousel_cohorts'));

        $this->log('Block Carousel data migrated successfully');

        parent::execute();
    }

    /**
     * Translate the old block instance configuration into the new block instance configuration.
     *
     * @param array $oldConfig
     * @return array
     */
    protected function translateBlockConfig(array $oldConfig): array
    {
        // No translation necessary for this block; all the config is the same.
        return $oldConfig;
    }

}