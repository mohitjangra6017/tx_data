<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_core\Migration;

use context_block;
use core\orm\query\builder;
use stdClass;

abstract class BlockInstanceMigration extends Migration
{
    /**
     * @var string The internal name of the block instance being replaced.
     */
    protected string $oldBlockName;

    /**
     * @var string The internal name of the block instance to replace the old one.
     */
    protected string $newBlockName;

    private array $blockInstances;

    /**
     * Translate the old block instance configuration into the new block instance configuration.
     *
     * @param array $oldConfig
     * @return array
     */
    abstract protected function translateBlockConfig(array $oldConfig): array;

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
        global $DB;

        if (empty($this->oldBlockName) || empty($this->newBlockName)) {
            return false;
        }

        $blockCount = $DB->count_records('block_instances', ['blockname' => $this->oldBlockName]);

        $this->log(
            $blockCount > 0
                ? 'Found ' . $blockCount . ' block instance(s) to migrate'
                : 'Found no block instances to migrate'
        );

        return $blockCount > 0;
    }

    /**
     * This is called before the Totara upgrade is run. This gives your Migration a chance to collect any
     * data it needs, such as from the database or the Moodle data directory.
     */
    public function prepare(): void
    {
        global $DB;

        $this->blockInstances = $DB->get_records('block_instances', ['blockname' => $this->oldBlockName]);
    }

    /**
     * Do all your actual Migration work here.
     * Any uncaught exceptions here will cause a full rollback of the DB.
     * Make sure that _canMigrate_ returns false by the time this function completes, otherwise your Migration
     * will cause an error and will fail the entire upgrade.
     */
    public function execute(): void
    {
        $this->log(
            sprintf(
                "Migrating %d old block instances from block '%s' to '%s'",
                count($this->blockInstances),
                $this->oldBlockName,
                $this->newBlockName
            )
        );

        foreach ($this->blockInstances as $instance) {
            $this->log('Migrating Block Instance ID ' . $instance->id);
            // Note: Technically the config is a stdClass object, but for consistency we convert it to an array for use, then back to an object for storage.
            $oldConfig = !empty($instance->configdata)
                ? (array) unserialize(base64_decode($instance->configdata))
                : []
            ;
            $newConfig = (object) $this->translateBlockConfig($oldConfig);
            $this->log('Translated Block Config', ['old' => $oldConfig, 'new' => $newConfig]);

            // Ideally we would use block_manager::add_block here, except that is always in the context of the current page,
            // so it won't ever have the right block regions loaded; especially if we are doing this from the CLI.
            // Instead, ignore the region checks, and just directly create the block and the context, then delete the old block.
            $newInstance = new stdClass();
            $newInstance->blockname = $this->newBlockName;
            $newInstance->parentcontextid = $instance->parentcontextid;
            $newInstance->showinsubcontexts = $instance->showinsubcontexts;
            $newInstance->pagetypepattern = $instance->pagetypepattern;
            $newInstance->subpagepattern = $instance->subpagepattern;
            $newInstance->defaultregion = $instance->defaultregion;
            $newInstance->defaultweight = $instance->defaultweight;
            $newInstance->configdata = base64_encode(serialize($newConfig));
            $newInstance->timecreated = time();
            $newInstance->timemodified = time();

            $newInstance->id = builder::table('block_instances')->insert($newInstance);
            context_block::instance($newInstance->id);
            $this->log('Block Instance ID ' . $instance->id . ' successfully migrated to new Instance ID ' . $newInstance->id);

            blocks_delete_instance($instance);
            $this->log('Deleted old block instance ID ' . $instance->id);
        }

        $this->log("Finished migrating '{$this->oldBlockName}' to '{$this->newBlockName}'");
    }
}