<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace block_rate_course\Migration;

use core\orm\query\builder;
use core\orm\query\raw_field;
use core\orm\query\sql\query;
use local_core\Migration\BlockInstanceMigration;
use stdClass;
use xmldb_table;

class CourseRatingMigration extends BlockInstanceMigration
{
    /**
     * @var string The internal name of the block instance being replaced.
     */
    protected string $oldBlockName = 'courserating';

    /**
     * @var string The internal name of the block instance to replace the old one.
     */
    protected string $newBlockName = 'rate_course';
    
    private array $reviews;
    
    private array $likes;
    
    private array $recommendations;

    /**
     * @inheritDoc
     */
    protected function translateBlockConfig(array $oldConfig): array
    {
        $this->log('Course Ratings has no useful config, removing all config and starting fresh');
        return [];
    }

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
        // Make sure the BlockInstanceMigration also has a chance to check.
        $tableExists = $DB->get_manager()->table_exists('block_course_rating');
        $this->log(
            $tableExists
                ? 'Course Rating table(s) exist, migration possible'
                : 'Course Rating table(s) not found, migration not possible'
        );
        return parent::canMigrate() || $tableExists;
    }

    /**
     * This is called before the Totara upgrade is run. This gives your Migration a chance to collect any
     * data it needs, such as from the database or the Moodle data directory.
     */
    public function prepare(): void
    {
        global $DB;

        // Make sure the BlockInstanceMigration can also prepare itself.
        parent::prepare();

        $this->reviews = query::create()
            ->select(
                [
                    'crr.id',
                    'cr.courseid',
                    'cr.userid',
                    'cr.rating',
                    'crr.review',
                    'crr.timecreated',
                    'crr.timemodified',
                    new raw_field('COALESCE(crr.reviewliked, 0) AS reviewliked'),
                    new raw_field('COALESCE(crr.hidden, 0) AS reviewhidden'),
                ]
            )
            ->from('block_course_rating', 'cr')
            ->left_join(
                ['block_course_rating_reviews', 'crr'],
                function (builder $builder) {
                    $builder->where_field('cr.courseid', '=', 'crr.courseid')
                        ->where_field('cr.userid', '=', 'crr.userid');
                }
            )
            ->fetch(true);

        if ($DB->get_manager()->table_exists('block_course_rating_review_likes')) {
            $this->likes = query::create()
                ->select('*')
                ->from('block_course_rating_review_likes')
                ->fetch();
        }

        if ($DB->get_manager()->table_exists('block_course_rating_recommendations')) {
            $this->recommendations = query::create()
                ->select('*')
                ->from('block_course_rating_recommendations')
                ->fetch();
        }

        $this->log('Found ' . count($this->reviews) . ' review(s) to import');
        $this->log('Found ' . count($this->likes) . ' like(s) to import');
        $this->log('Found ' . count($this->recommendations) . ' recommendation(s) to import');
    }

    /**
     * Do all your actual Migration work here.
     * Any uncaught exceptions here will cause a full rollback of the DB.
     * Make sure that _canMigrate_ returns false by the time this function completes, otherwise your Migration
     * will cause an error and will fail the entire upgrade.
     */
    public function execute(): void
    {
        global $DB;
        
        // Let the BlockInstanceMigration go first.
        parent::execute();

        // We need to map out our review IDs here, as the likes use the old IDs, but they will be changed.
        $reviewIdMap = [];

        $this->log(sprintf('Migrating %d review(s)', count($this->reviews)));
        foreach ($this->reviews as $review) {
            $new = new stdClass();
            $new->course = $review->courseid;
            $new->userid = $review->userid;
            $new->rating = $review->rating ?? 0;
            $new->review = $review->review ?? '';
            $new->reviewlikes = $review->reviewliked;
            $new->hidden = $review->reviewhidden;
            $new->timecreated = $review->timecreated ?? time();
            $new->timemodified = $review->timemodified ?? time();

            $new->id = builder::table('rate_course_review')->insert($new);
            $reviewIdMap[$review->id] = $new->id;
        }

        $this->log(sprintf('Migrating %d like(s)', count($this->likes)));
        foreach ($this->likes as $like) {
            $new = new stdClass();
            $new->userid = $like->userid;
            $new->reviewid = $reviewIdMap[$like->reviewid];
            $new->timecreated = $like->timecreated;
            $new->timemodified = $like->timecreated;

            builder::table('rate_course_review_likes')->insert($new);
        }

        $this->log(sprintf('Migrating %d recommendation(s)', count($this->recommendations)));
        foreach ($this->recommendations as $recommendation) {
            $new = new stdClass();
            $new->userid = $recommendation->userid;
            $new->course = $recommendation->course;
            $new->useridto = $recommendation->useridto;
            $new->source = $recommendation->source;
            $new->status = $recommendation->status;
            $new->timecreated = $recommendation->timecreated;
            $new->timemodified = $recommendation->timemodified;

            builder::table('rate_course_recommendations')->insert($new);
        }

        $this->log('Removing all Course Rating DB tables');
        foreach (['block_course_rating', 'block_course_rating_reviews', 'block_course_rating_review_likes', 'block_course_rating_recommendations'] as $tableName) {
            if ($DB->get_manager()->table_exists($tableName)) {
                $DB->get_manager()->drop_table(new xmldb_table($tableName));
                $this->log('Dropped database table ' . $tableName);
            }
        }
        
        $this->log('Course Rating Migration has finished! See ya!');
    }
}