<?php

/**
 * Web service for recommending a course
 *
 * @package    block
 * @subpackage rate_course
 * @copyright  &copy; 2015 Nine Lanterns Pty Ltd  {@link http://www.ninelanterns.com.au}
 * @author     tri.le
 * @version    1.0
 */

$services = array(
    'block_rate_course_service' => array(
        'functions' => array('block_rate_course_suggest_course'),
        'requiredcapability' => 'block/rate_course:rate',
        'restrictedusers' => 0,
        'enabled'=> 1,
    )
);

$functions = array(

    // === enrol related functions ===
    'block_rate_course_suggest_course' => array(
        'classname'   => 'block_rate_course_external',
        'methodname'  => 'suggest_course',
        'classpath'   => 'blocks/rate_course/externallib.php',
        'description' => 'A web service to suggest a course',
        'capabilities'=> 'block/rate_course:rate',  // if a user can rate a course, they can recommend the course
        'type'        => 'write',
    ),

);
