<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    block
 * @subpackage rate_course
 * @copyright  2009 Jenny Gray
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * Code was Rewritten for Moodle 2.X By Atar + Plus LTD for Comverse LTD.
 * @copyright &copy; 2011 Comverse LTD.
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

function xmldb_block_rate_course_upgrade($oldversion=0) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2009020307) {
        $oldblock = $DB->get_record('block', array('name'=>'rate_unit'));
        $newblock = $DB->get_record('block', array('name'=>'rate_course'));

        if ($oldblock) {
            // First migrate data from rate_unit.
            $ratings = $DB->get_recordset('rate_unit');
            if ($ratings) {
                while (!$ratings->EOF) {
                    $newrow = new stdClass();
                    $newrow->course = $ratings->fields['course'];
                    $newrow->userid = $ratings->fields['userid'];
                    $newrow->rating = $ratings->fields['course'];
                    $DB->insert_record('block_rate_course', $newrow);
                    $ratings->MoveNext();
                }
            }

            //  Swap the block instances over.
            $instances = $DB->get_records('block_instance',
                    array('blockid'=>$oldblock->id));
            if (!empty($instances)) {
                foreach ($instances as $instance) {
                    $instance->blockid = $newblock->id;
                    $DB->update_record('block_instance', $instance);
                }
            }
            $instances = $DB->get_records('block_pinned',
                    array('blockid'=>$oldblock->id));
            if (!empty($instances)) {
                foreach ($instances as $instance) {
                    $instance->blockid = $newblock->id;
                    $DB->update_record('block_pinned', $instance);
                }
            }

            // Delete the old block stuff.
            $DB->delete_records('block', array('id'=>$oldblock->id));
            $DB->drop_plugin_tables($oldblock->name,
                    "$CFG->dirroot/blocks/$oldblock->name/db/install.xml",
                    false); // Old obsoleted table names.
            $DB->drop_plugin_tables('block_'.$oldblock->name, "$CFG->dirroot/blocks/$oldblock->name/db/install.xml", false);
            capabilities_cleanup('block/'.$oldblock->name);
        }
    }

    // VHLU-23 Extend ratings to include comment reviews.
    if ($oldversion < 2014081200) {

        // Define field review to be added to block_rate_course.
        $table = new xmldb_table('block_rate_course');
        $field = new xmldb_field('review', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'rating');

        // Conditionally launch add field review.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field hidden to be added to block_rate_course.
        $table = new xmldb_table('block_rate_course');
        $field = new xmldb_field('reviewlikes', XMLDB_TYPE_BINARY, null, null, true, null, 0, 'review');

        // Conditionally launch add field hidden.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field hidden to be added to block_rate_course.
        $table = new xmldb_table('block_rate_course');
        $field = new xmldb_field('hidden', XMLDB_TYPE_BINARY, null, null, null, null, null, 'reviewlikes');

        // Conditionally launch add field hidden.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field timecreated to be added to block_rate_course.
        $table = new xmldb_table('block_rate_course');
        $field = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'hidden');

        // Conditionally launch add field timecreated.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field timemodified to be added to block_rate_course.
        $table = new xmldb_table('block_rate_course');
        $field = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'timecreated');

        // Conditionally launch add field timemodified.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Rate_course savepoint reached.
        upgrade_block_savepoint(true, 2014081200, 'rate_course');
    }

    if ($oldversion < 2014081201) {

        // Define table block_rate_course_likes to be created.
        $table = new xmldb_table('block_rate_course_likes');

        // Adding fields to table block_rate_course_likes.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('reviewid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table block_rate_course_likes.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Adding indexes to table block_rate_course_likes.
        $table->add_index('mdl_ix_review_user', XMLDB_INDEX_UNIQUE, array('reviewid', 'userid'));

        // Conditionally launch create table for block_rate_course_likes.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Rate_course savepoint reached.
        upgrade_block_savepoint(true, 2014081201, 'rate_course');
    }

    if ($oldversion < 2014081203) {

        // Define table block_rate_course to be renamed to rate_course_review.
        $table = new xmldb_table('block_rate_course');

        // Launch rename table for rate_course_review.
        $dbman->rename_table($table, 'rate_course_review');

        // Define table block_rate_course_likes to be renamed to rate_course_review_likes.
        $table = new xmldb_table('block_rate_course_likes');

        // Launch rename table for rate_course_review.
        $dbman->rename_table($table, 'rate_course_review_likes');

        // Define table block_rate_course_recommends to be created.
        $table = new xmldb_table('rate_course_recommendations');

        // Adding fields to table block_rate_course_recommends.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('course', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('useridto', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('status', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table block_rate_course_recommends.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('recommend_uq_ix', XMLDB_KEY_UNIQUE, array('userid', 'course', 'useridto'));

        // Adding indexes to table block_rate_course_recommends.
        $table->add_index('user_recommendations_ix', XMLDB_INDEX_NOTUNIQUE, array('useridto'));

        // Conditionally launch create table for block_rate_course_recommends.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table rate_course_course_likes to be created.
        $table = new xmldb_table('rate_course_course_likes');

        // Adding fields to table rate_course_course_likes.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('course', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table rate_course_course_likes.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('user_course_like_uq_ix', XMLDB_KEY_UNIQUE, array('course', 'userid'));

        // Conditionally launch create table for rate_course_course_likes.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Rate_course savepoint reached.
        upgrade_block_savepoint(true, 2014081203, 'rate_course');
    }

    if ($oldversion < 2014081204) {

        // Define field timecreated to be added to rate_course_review_likes.
        $table = new xmldb_table('rate_course_review_likes');
        $field = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'reviewid');

        // Conditionally launch add field timecreated.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field timemodified to be added to rate_course_review_likes.
        $table = new xmldb_table('rate_course_review_likes');
        $field = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'timecreated');

        // Conditionally launch add field timemodified.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field timecreated to be added to rate_course_recommendations.
        $table = new xmldb_table('rate_course_recommendations');
        $field = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'status');

        // Conditionally launch add field timecreated.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field timemodified to be added to rate_course_recommendations.
        $table = new xmldb_table('rate_course_recommendations');
        $field = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'timecreated');

        // Conditionally launch add field timemodified.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field timecreated to be added to rate_course_course_likes.
        $table = new xmldb_table('rate_course_course_likes');
        $field = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'course');

        // Conditionally launch add field timecreated.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field timemodified to be added to rate_course_course_likes.
        $table = new xmldb_table('rate_course_course_likes');
        $field = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'timecreated');

        // Conditionally launch add field timemodified.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Rate_course savepoint reached.
        upgrade_block_savepoint(true, 2014081204, 'rate_course');
    }

    if ($oldversion < 2014081206) {

        // Add the source field
        $table = new xmldb_table('rate_course_recommendations');
        $field = new xmldb_field('source', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, 'useridto');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // modify the unique key to include the source
        $key = new xmldb_key('recommend_uq_ix', XMLDB_KEY_UNIQUE, array('userid', 'course', 'useridto'));
        $dbman->drop_key($table, $key);

        $key = new xmldb_key('recommend_uq_ix', XMLDB_KEY_UNIQUE, array('userid', 'course', 'useridto', 'source'));
        $dbman->add_key($table, $key);

        // update the current records source field to 'block_rate_course'
        $DB->set_field('rate_course_recommendations', 'source', 'block_rate_course');

        // Rate_course savepoint reached.
        upgrade_block_savepoint(true, 2014081206, 'rate_course');
    }
    
    if ($oldversion < 2014081210) {

        // Define table rate_course_review_comments to be created.
        $table = new xmldb_table('rate_course_review_comments');

        // Adding fields to table rate_course_review_comments.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('user_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('review_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('comment', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table rate_course_review_comments.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('user_id_fk', XMLDB_KEY_FOREIGN, array('user_id'), 'user', array('id'));
        $table->add_key('review_id_fk', XMLDB_KEY_FOREIGN_UNIQUE, array('review_id'), 'rate_course_review', array('id'));

        // Conditionally launch create table for rate_course_review_comments.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Rate_course savepoint reached.
        upgrade_block_savepoint(true, 2014081210, 'rate_course');
    }


    return true;
}
