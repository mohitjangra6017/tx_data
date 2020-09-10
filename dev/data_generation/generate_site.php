<?php
/**
 * This file is part of Totara Learn
 *
 * Copyright (C) 2019 onwards Totara Learning Solutions LTD
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
 * @author Aleksandr Baishev <aleksandr.baishev@totaralearning.com>
 * @author Kian Nguyen <kian.nguyen@totaralearning.com>
 */
define('CLI_SCRIPT', 1);
use degeneration\App;
use degeneration\performance_testing;

if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "In order to execute this script you need to execute: 'composer install' from this directory!" . PHP_EOL;
    return 1;
}

require_once __DIR__ . '/vendor/autoload.php';
require_once(__DIR__ . '/../../server/config.php');
require_once(App::config()->libdir . '/phpunit/classes/util.php');
require_once(App::config()->dirroot . "/lib/clilib.php");



[$options, $params] = cli_get_params(
    [
        'help' => false,
        'size' => 's',
        'transaction' => 0
    ],
    [
        'h' => 'help',
        's' => 'size',
        't' => 'transaction'
    ]
);

if ($options['help']) {
    echo "
This script is designed to generate a vast amount of testing data for perform features of the site.
The script meant to be executed on a clean (freshly installed) site.

Usage:
    php dev/data_generation/generate_site.php --t=\"full_performance_testing\" --s=\"l\" -t=1
    
Options:
    -h, --help          Print out this help
    -s, --size          XS - A tiny amount of data
                        S - A little data
                        M - A little more data
                        L - Large amount of data
                        XL - Extra large amount of data
                        XXL - Extra-extra large amount of data
                        GOLIATH - Unnecessary large amount of data
    -t, --transaction   Specify if you want to run data generator in transaction.
    ";

    return 0;
}

if (!isset($options['size']) || !in_array(strtolower($options['size']), ['xs', 's', 'm', 'l', 'xl', 'xxl', 'goliath'])) {
    echo 'You must specify size of data to create to run the script';
    return 1;
}

// Ok we need to do something.
// Need to instantiate the main app and create things

$time = time();
$size = $options['size'];
$USER = get_admin();

$generate = function() use ($size) {
    $pt = new performance_testing();

    $pt->set_size($size)
        ->generate();

    // These commented out as basic examples:

    //
    // $audience = new audience();
    // $audience->save();
    //
    // $user = new user();
    // $user->save();
    //
    // $audience->add_member($user);
    //
    // $course = new course();
    // $course->save();
    //
    // $user->enrol($course);
    //
    // $org = new organisation();
    // $org->save();
    //
    // $pos = new position();
    // $pos->save();
    //
    // $comp = new competency();
    // $comp->save();
    //
    // $completion = new \degeneration\items\course_completion();
    // $completion->by($user)
    //     ->for($course)
    //     ->save();
    //
    // $ass = new assignment();
    //
    // $ass->for($org)
    //     ->set_competency($comp)
    //     ->save();
    //
    // $cc = new course_completion();
    // $cc->add_course($course);
    // $cc->save();
    //
    // $c_comp = new child_competency();
    // $c_comp->for($comp);
    // $c_comp->save();
    //
    // $on_activate = new on_activate();
    // $on_activate->for($comp);
    // $on_activate->save();
    //
    // $lc = new linked_courses();
    // $lc->for($comp);
    // $lc->save();
    //
    // $cg_pathway = new criteria_group();
    //
    // $cg_pathway->for($comp)
    //     ->add_criterion($c_comp)
    //     ->add_criterion($cc)
    //     ->save();
    //
    // $sv = $comp->get_data()->scale->sorted_values_high_to_low()->first();
    //
    // $mr = new manual_rating();
    //
    // $mr->for($comp)
    //     ->rate($user)
    //     ->rate_as($user)
    //     ->set_value($sv)
    //     ->save();
    //
    // $lpp = new learning_plan();
    //
    // $lpp->for($comp);
    // $lpp->save();
};

if ($options['transaction']) {
    echo 'Running data generation inside a transaction it might take a long time...' . PHP_EOL;
    App::transaction($generate);
} else {
    echo 'Running data generation without a transaction it might still take a long time...' . PHP_EOL;
    $generate();
}

echo PHP_EOL . PHP_EOL . "Time elapsed: " . (time() - $time) . PHP_EOL;
