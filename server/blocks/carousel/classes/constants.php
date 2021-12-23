<?php
/**
 * Class to define constants
 *
 * @package    block_carousel
 * @copyright  &copy; 2021 Kineo Pty Ltd  {@link http://kineo.com/au}
 * @author     tri.le
 * @version    1.0
*/

namespace block_carousel;

class constants {
    // Card size
    const BKC_CARD_SMALL = 'small';
    const BKC_CARD_LARGE = 'large';

    // Number defines how many cards to show
    // Thus defining the card size
    const BKC_CARD_SMALL_COUNT = 5;
    const BKC_CARD_LARGE_COUNT = 3;

    // carousel templates
    const BKC_TEMPLATE_RED = '_red';
    const BKC_TEMPLATE_BLUE = '_blue';

    // Course progress thresholds
    const BKC_LOWER_PROGRESS_THRESHOLD = 25;
    const BKC_UPPER_PROGRESS_THRESHOLD = 75;

    // Default number of records
    // Where applicable
    const BKC_DEFAULT_RECORD_LIMIT = 12;

    // Tags join condition for curated contents
    const BKC_TAG_JOIN_AND = 'AND';
    const BKC_TAG_JOIN_OR = 'OR';

    // Course count
    const BKC_SHOW_COURSE_COUNT = 1;
    const BKC_SHOW_PERCENTAGE_COMP = 2;

    // Carousel types
    const BKC_JUMPBACKIN = 'jumpbackin';
    const BKC_RECOMMENDED = 'recommended';
    const BKC_EVENTS = 'events';
    const BKC_SHOUTOUTS = 'shoutouts';
    const BKC_FACETOFACE = 'facetoface';
    const BKC_TODOLIST = 'todolist';
    const BKC_FILTERED_ENROLLED_LIST = 'filteredenrolledlist';
    const BKC_WHATS_HOT = 'whatshot';
    const BKC_WHATS_NEW = 'whatsnew';
    const BKC_WHATS_HAPPENING = 'whatshappening';
    const BKC_CURATED = 'curated';
    const BKC_CLUSTER = 'cluster';
    const BKC_COURSELETS = 'courselets';
    const BKC_PROGRAMS = 'programs';

    // Strip length
    // TODO: We are doing this via JS now
    // Refactor the code to make full use of JS
    // At the moment it doesn't probably work best with Smaller 
    // Card size so this has been kept intact as a fallback
    const BKC_TITLE_LENGHT_SMALL_CARD = 40;
    const BKC_TITLE_LENGHT_LARGE_CARD = 55;
    const BKC_SUMMARY_LENGHT_SMALL_CARD = 100;
    const BKC_SUMMARY_LENGHT_LARGE_CARD = 200;

    private static $BKC_CAROUSELTYPE = array(
        '',
        self::BKC_CLUSTER,
        self::BKC_CURATED,
        self::BKC_FILTERED_ENROLLED_LIST,
        self::BKC_EVENTS,
        self::BKC_FACETOFACE,
        self::BKC_JUMPBACKIN,
        self::BKC_RECOMMENDED,
        self::BKC_SHOUTOUTS,
        self::BKC_TODOLIST,
        self::BKC_WHATS_HAPPENING ,
        self::BKC_WHATS_HOT ,
        self::BKC_WHATS_NEW ,
        self::BKC_COURSELETS,
        self::BKC_PROGRAMS
    );

    private static $BKC_SHOW_COUNTTYPE = array(
        '',
        self::BKC_SHOW_COURSE_COUNT,
        self::BKC_SHOW_PERCENTAGE_COMP
    );

    private static $BKC_TEMPLATES = array(
        self::BKC_TEMPLATE_RED,
        self::BKC_TEMPLATE_BLUE
    );

    private static $BKC_CARDSIZE = array(
        '',
        self::BKC_CARD_SMALL,
        self::BKC_CARD_LARGE
    );

    public static function get_carouseltypes() {
        $return = array();

        foreach (self::$BKC_CAROUSELTYPE as $item) {
            if (!empty($item)) {
                $return[$item] = get_string('type:'.$item, 'block_carousel');
            } else {
                $return[''] = '';
            }
        }

        return $return;
    }

    public static function get_counttypes() {
        $return = array();

        foreach (self::$BKC_SHOW_COUNTTYPE as $item) {
            if (!empty($item)) {
                $return[$item] = get_string('show:'.$item, 'block_carousel');
            } else {
                $return[''] = '';
            }
        }

        return $return;
    }

    public static function get_templates() {
        $return = array();

        foreach (self::$BKC_TEMPLATES as $item) {
            $return[$item] = get_string('template:'.$item, 'block_carousel');
        }

        return $return;
    }

    public static function get_cardsizes() {
        $return = array();

        foreach (self::$BKC_CARDSIZE as $item) {
            if (!empty($item)) {
                $return[$item] = get_string('size:'.$item, 'block_carousel');
            } else {
                $return[''] = '';
            }
        }

        return $return;
    }
}
