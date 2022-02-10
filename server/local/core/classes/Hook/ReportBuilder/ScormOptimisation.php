<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_core\Hook\ReportBuilder;

class ScormOptimisation
{
    /**
     * Creates the database triggers for the SCORM optimisation report.
     * Inserts all the data into the table.
     * This can only run during an install or upgrade.
     *
     * IMPORTANT
     * We make use of _change_database_structure_ here, which TLS specifically says not to use, however we have no other safe way
     * of triggering these queries as we can't use _execute_ at all.
     */
    public static function onInstall()
    {
        global $CFG, $DB;
            return;
        if (empty($CFG->upgraderunning)) {
            return;
        }

        $saveFunction = <<<SQL
CREATE OR REPLACE FUNCTION local_core_scorm_optimisation_track_save() RETURNS TRIGGER AS \$local_core_scorm_optimisation_track_save\$
    DECLARE
        existing_id INT;
    BEGIN
        SELECT id INTO existing_id
        FROM "ttr_local_core_optimised_scorm_sco_track"
        WHERE scormid = NEW.scormid AND scoid = NEW.scoid AND userid = NEW.userid AND attempt = NEW.attempt;

        IF existing_id IS NULL THEN
            INSERT INTO "ttr_local_core_optimised_scorm_sco_track" (scormid, scoid, userid, attempt)
            VALUES (NEW.scormid, NEW.scoid, NEW.userid, NEW.attempt);
        END IF;

        RETURN NEW;
    END;
\$local_core_scorm_optimisation_track_save\$ LANGUAGE plpgsql;
SQL;
        $saveTrigger = <<<SQL
DROP TRIGGER IF EXISTS save_scorm_track ON "ttr_scorm_scoes_track"; 
CREATE TRIGGER save_scorm_track
AFTER INSERT OR UPDATE ON "ttr_scorm_scoes_track"
FOR EACH ROW EXECUTE PROCEDURE local_core_scorm_optimisation_track_save();
SQL;
        $DB->change_database_structure($DB->fix_sql_params($saveFunction)[0]);
        $DB->change_database_structure($DB->fix_sql_params($saveTrigger)[0]);

        $deleteFunction = <<<SQL
CREATE OR REPLACE FUNCTION local_core_scorm_optimisation_track_delete() RETURNS TRIGGER AS \$local_core_scorm_optimisation_track_delete\$
    DECLARE
    BEGIN
        DELETE FROM "ttr_local_core_optimised_scorm_sco_track"
        WHERE scormid = OLD.scormid AND scoid = OLD.scoid AND userid = OLD.userid AND attempt = OLD.attempt;
        RETURN OLD;
    END;
\$local_core_scorm_optimisation_track_delete\$ LANGUAGE plpgsql;
SQL;
        $deleteTrigger = <<<SQL
DROP TRIGGER IF EXISTS delete_scorm_track ON "ttr_scorm_scoes_track";
CREATE TRIGGER delete_scorm_track
AFTER DELETE ON "ttr_scorm_scoes_track"
FOR EACH ROW EXECUTE PROCEDURE local_core_scorm_optimisation_track_delete();
SQL;
        $DB->change_database_structure($DB->fix_sql_params($deleteFunction)[0]);
        $DB->change_database_structure($DB->fix_sql_params($deleteTrigger)[0]);

        $truncateFunction = <<<SQL
CREATE OR REPLACE FUNCTION local_core_scorm_optimisation_track_truncate() RETURNS TRIGGER AS \$local_core_scorm_optimisation_track_truncate\$
    DECLARE
    BEGIN
        TRUNCATE "ttr_local_core_optimised_scorm_sco_track";
        RETURN OLD;
    END;
\$local_core_scorm_optimisation_track_truncate\$ LANGUAGE plpgsql;
SQL;
        $truncateTrigger = <<<SQL
DROP TRIGGER IF EXISTS truncate_scorm_track ON "ttr_scorm_scoes_track";
CREATE TRIGGER truncate_scorm_track
AFTER TRUNCATE ON "ttr_scorm_scoes_track"
FOR EACH STATEMENT EXECUTE PROCEDURE local_core_scorm_optimisation_track_truncate();
SQL;
        $DB->change_database_structure($DB->fix_sql_params($truncateFunction)[0]);
        $DB->change_database_structure($DB->fix_sql_params($truncateTrigger)[0]);

        $dataInsert = <<<SQL
INSERT INTO "ttr_local_core_optimised_scorm_sco_track" (scormid, scoid, userid, attempt)
(SELECT DISTINCT scormid, scoid, userid, attempt FROM "ttr_scorm_scoes_track")
SQL;

        $DB->execute($dataInsert);
    }

    public static function switchScormScoesTrackTable(string $currentTable): string
    {
        if (get_config('local_core', 'scorm_opt_enabled')) {
            return '{local_core_optimised_scorm_sco_track}';
        }
        return $currentTable;
    }
}