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
 * Unit tests for lib/outputcomponents.php.
 *
 * @package   core
 * @category  phpunit
 * @copyright 2011 David Mudrak <david@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/outputcomponents.php');

/**
 * Unit tests for the user_picture class.
 */
class core_outputcomponents_testcase extends advanced_testcase {

    public function test_fields_aliasing() {
        $fields = user_picture::fields();
        $fields = array_map('trim', explode(',', $fields));
        $this->assertTrue(in_array('id', $fields));

        $aliased = array();
        foreach ($fields as $field) {
            if ($field === 'id') {
                $aliased['id'] = 'aliasedid';
            } else {
                $aliased[$field] = 'prefix'.$field;
            }
        }

        $returned = user_picture::fields('', array('custom1', 'id'), 'aliasedid', 'prefix');
        $returned = array_map('trim', explode(',', $returned));
        $this->assertEquals(count($returned), count($fields) + 1); // Only one extra field added.

        foreach ($fields as $field) {
            if ($field === 'id') {
                $expected = "id AS aliasedid";
            } else {
                $expected = "$field AS prefix$field";
            }
            $this->assertContains($expected, $returned, "Expected pattern '$expected' not returned");
        }
        $this->assertContains("custom1 AS prefixcustom1", $returned, "Expected pattern 'custom1 AS prefixcustom1' not returned");
    }

    public function test_fields_unaliasing() {
        $fields = user_picture::fields();
        $fields = array_map('trim', explode(',', $fields));

        $fakerecord = new stdClass();
        $fakerecord->aliasedid = 42;
        foreach ($fields as $field) {
            if ($field !== 'id') {
                $fakerecord->{'prefix'.$field} = "Value of $field";
            }
        }
        $fakerecord->prefixcustom1 = 'Value of custom1';

        $returned = user_picture::unalias($fakerecord, array('custom1'), 'aliasedid', 'prefix');

        $this->assertEquals(42, $returned->id);
        foreach ($fields as $field) {
            if ($field !== 'id') {
                $this->assertSame("Value of $field", $returned->{$field});
            }
        }
        $this->assertSame('Value of custom1', $returned->custom1);
    }

    public function test_fields_unaliasing_null() {
        $fields = user_picture::fields();
        $fields = array_map('trim', explode(',', $fields));

        $fakerecord = new stdClass();
        $fakerecord->aliasedid = 42;
        foreach ($fields as $field) {
            if ($field !== 'id') {
                $fakerecord->{'prefix'.$field} = "Value of $field";
            }
        }
        $fakerecord->prefixcustom1 = 'Value of custom1';
        $fakerecord->prefiximagealt = null;

        $returned = user_picture::unalias($fakerecord, array('custom1'), 'aliasedid', 'prefix');

        $this->assertEquals(42, $returned->id);
        $this->assertNull($returned->imagealt);
        foreach ($fields as $field) {
            if ($field !== 'id' and $field !== 'imagealt') {
                $this->assertSame("Value of $field", $returned->{$field});
            }
        }
        $this->assertSame('Value of custom1', $returned->custom1);
    }

    public function test_create_pix_icon() {

        $icon = new pix_icon('delete', 'hello');
        $this->assertEquals('hello', $icon->attributes['alt']);
        $this->assertEquals('hello', $icon->attributes['title']);

        $attributes = array('class' => 'activityicon otherclass', 'title' => 'Title text');
        $icon = new pix_icon('icon', 'Alt text', 'forum', $attributes);
        $this->assertEquals('Alt text', $icon->attributes['alt']);
        $this->assertEquals('Title text', $icon->attributes['title']);

        $icon = new pix_icon('delete', '{"alt":"hello"}');
        $this->assertEquals('hello', $icon->attributes['alt']);
        $this->assertArrayNotHasKey('title', $icon->attributes);

        $icon = new pix_icon('delete', '{"alt":"hello", "data-id":"x1"}', 'moodle', array('alt' => 'none', 'classes' => 'muppet', 'data-blah' => 'nah'));
        $this->assertEquals('hello', $icon->attributes['alt']);
        $this->assertEquals('x1', $icon->attributes['data-id']);
        $this->assertEquals('muppet', $icon->attributes['classes']);
        $this->assertEquals('nah', $icon->attributes['data-blah']);
        $this->assertArrayNotHasKey('title', $icon->attributes);
    }

    public function test_get_url() {
        global $DB, $CFG;


        // Force SVG on so that we have predictable URL's.
        $CFG->svgicons = true;
        // Totara: Login as real user to see all profile images.
        $this->setAdminUser();
        $CFG->forcelogin = '1';
        $CFG->forceloginforprofileimage = '1';

        // Verify new install contains expected defaults.
        $this->assertSame(theme_config::DEFAULT_THEME, $CFG->theme);
        $this->assertEquals(1, $CFG->slasharguments);
        $this->assertEquals(1, $CFG->themerev);
        $this->assertEquals(0, $CFG->themedesignermode);
        $this->assertSame('https://www.example.com/moodle', $CFG->wwwroot);
        $this->assertEquals(0, $CFG->enablegravatar);
        $this->assertSame('mm', $CFG->gravatardefaulturl);

        // Create some users.
        $page = new moodle_page();
        $page->set_url('/user/profile.php');
        $page->set_context(context_system::instance());
        $renderer = $page->get_renderer('core');

        $user1 = $this->getDataGenerator()->create_user(array('picture'=>11, 'email'=>'user1@example.com'));
        $context1 = context_user::instance($user1->id);
        $user2 = $this->getDataGenerator()->create_user(array('picture'=>0, 'email'=>'user2@example.com'));
        $context2 = context_user::instance($user2->id);

        $user3 = $this->getDataGenerator()->create_user(array('picture'=>1, 'deleted'=>1, 'email'=>'user3@example.com'));
        $context3 = context_user::instance($user3->id, IGNORE_MISSING);
        $this->assertEquals(0, $user3->picture);
        $this->assertNotEquals('user3@example.com', $user3->email);
        $this->assertFalse($context3);

        // Try legacy picture == 1.
        $user1->picture = 1;
        $up1 = new user_picture($user1);
        $this->assertSame($CFG->wwwroot.'/pluginfile.php/'.$context1->id.'/user/icon/' . $CFG->theme . '/f2?rev=1', $up1->get_url($page, $renderer)->out(false));
        $user1->picture = 11;

        // Try valid user with picture when user context is not cached - 1 query expected.
        context_helper::reset_caches();
        $reads = $DB->perf_get_reads();
        $up1 = new user_picture($user1);
        $this->assertEquals($reads, $DB->perf_get_reads());
        $this->assertSame($CFG->wwwroot.'/pluginfile.php/'.$context1->id.'/user/icon/' . $CFG->theme . '/f2?rev=11', $up1->get_url($page, $renderer)->out(false));
        $this->assertEquals($reads+1, $DB->perf_get_reads());

        // Try valid user with contextid hint - no queries expected.
        $user1->contextid = $context1->id;
        context_helper::reset_caches();
        $reads = $DB->perf_get_reads();
        $up1 = new user_picture($user1);
        $this->assertEquals($reads, $DB->perf_get_reads());
        $this->assertSame($CFG->wwwroot.'/pluginfile.php/'.$context1->id.'/user/icon/' . $CFG->theme . '/f2?rev=11', $up1->get_url($page, $renderer)->out(false));
        $this->assertEquals($reads, $DB->perf_get_reads());

        // Try valid user without image - no queries expected.
        context_helper::reset_caches();
        $reads = $DB->perf_get_reads();
        $up2 = new user_picture($user2);
        $this->assertEquals($reads, $DB->perf_get_reads());
        $this->assertSame($CFG->wwwroot.'/theme/image.php/' . $CFG->theme . '/core/1/u/f2', $up2->get_url($page, $renderer)->out(false));
        $this->assertEquals($reads, $DB->perf_get_reads());

        // Try guessing of deleted users - no queries expected.
        unset($user3->deleted);
        context_helper::reset_caches();
        $reads = $DB->perf_get_reads();
        $up3 = new user_picture($user3);
        $this->assertEquals($reads, $DB->perf_get_reads());
        $this->assertSame($CFG->wwwroot.'/theme/image.php/' . $CFG->theme . '/core/1/u/f2', $up3->get_url($page, $renderer)->out(false));
        $this->assertEquals($reads, $DB->perf_get_reads());

        // Try incorrectly deleted users (with valid email and pciture flag) - some DB reads expected.
        $user3->email = 'user3@example.com';
        $user3->picture = 1;
        $reads = $DB->perf_get_reads();
        $up3 = new user_picture($user3);
        $this->assertEquals($reads, $DB->perf_get_reads());
        $this->assertEquals($CFG->wwwroot.'/theme/image.php/' . $CFG->theme . '/core/1/u/f2', $up3->get_url($page, $renderer)->out(false));
        $this->assertGreaterThan($reads, $DB->perf_get_reads());

        // Totara: test privacy.
        $this->setUser(null);
        $up1 = new user_picture($user1);
        $this->assertSame($CFG->wwwroot.'/theme/image.php/' . $CFG->theme . '/core/1/u/f2', $up1->get_url($page, $renderer)->out(false));
        $CFG->forcelogin = '0';
        $CFG->forceloginforprofileimage = '0';
        $up1 = new user_picture($user1);
        $this->assertSame($CFG->wwwroot.'/pluginfile.php/'.$context1->id.'/user/icon/' . $CFG->theme . '/f2?rev=11', $up1->get_url($page, $renderer)->out(false));
        $CFG->forcelogin = '1';
        $CFG->forceloginforprofileimage = '1';
        $this->setAdminUser();

        // Test gravatar.
        set_config('enablegravatar', 1);

        // Deleted user can not have gravatar.
        $user3->email = 'deleted';
        $user3->picture = 0;
        $up3 = new user_picture($user3);
        $this->assertSame($CFG->wwwroot.'/theme/image.php/' . $CFG->theme . '/core/1/u/f2', $up3->get_url($page, $renderer)->out(false));

        // Http version.
        $CFG->wwwroot = str_replace('https:', 'http:', $CFG->wwwroot);

        // Http version.
        $CFG->wwwroot = str_replace('https:', 'http:', $CFG->wwwroot);
        $CFG->httpswwwroot = str_replace('https:', 'http:', $CFG->wwwroot);

        // Verify defaults to misteryman (mm).
        $up2 = new user_picture($user2);
        $this->assertSame('http://www.gravatar.com/avatar/ab53a2911ddf9b4817ac01ddcd3d975f?s=35&d=mm', $up2->get_url($page, $renderer)->out(false));

        // Without gravatardefaulturl, verify we pick own file.
        set_config('gravatardefaulturl', '');
        $up2 = new user_picture($user2);
        $this->assertSame('http://www.gravatar.com/avatar/ab53a2911ddf9b4817ac01ddcd3d975f?s=35&d=http%3A%2F%2Fwww.example.com%2Fmoodle%2Fpix%2Fu%2Ff2.png', $up2->get_url($page, $renderer)->out(false));
        // uploaded image takes precedence before gravatar
        $up1 = new user_picture($user1);
        $this->assertSame($CFG->wwwroot.'/pluginfile.php/'.$context1->id.'/user/icon/' . $CFG->theme . '/f2?rev=11', $up1->get_url($page, $renderer)->out(false));

        // Https version.
        $CFG->wwwroot = str_replace('http:', 'https:', $CFG->wwwroot);

        $up1 = new user_picture($user1);
        $this->assertSame($CFG->wwwroot.'/pluginfile.php/'.$context1->id.'/user/icon/' . $CFG->theme . '/f2?rev=11', $up1->get_url($page, $renderer)->out(false));

        $up3 = new user_picture($user3);
        $this->assertSame($CFG->wwwroot.'/theme/image.php/' . $CFG->theme . '/core/1/u/f2', $up3->get_url($page, $renderer)->out(false));

        $up2 = new user_picture($user2);
        $this->assertSame('https://secure.gravatar.com/avatar/ab53a2911ddf9b4817ac01ddcd3d975f?s=35&d=https%3A%2F%2Fwww.example.com%2Fmoodle%2Fpix%2Fu%2Ff2.png', $up2->get_url($page, $renderer)->out(false));

        // TODO MDL-44792 Rewrite those tests to use a fixture.
        // Now test gravatar with one theme having own images (afterburner).
        // $CFG->httpswwwroot = $CFG->wwwroot;
        // $this->assertFileExists("$CFG->dirroot/theme/afterburner/config.php");
        // set_config('theme', 'afterburner');
        // $page = new moodle_page();
        // $page->set_url('/user/profile.php');
        // $page->set_context(context_system::instance());
        // $renderer = $page->get_renderer('core');

        // $up2 = new user_picture($user2);
        // $this->assertEquals('http://www.gravatar.com/avatar/ab53a2911ddf9b4817ac01ddcd3d975f?s=35&d=http%3A%2F%2Fwww.example.com%2Fmoodle%2Ftheme%2Fafterburner%2Fpix_core%2Fu%2Ff2.png', $up2->get_url($page, $renderer)->out(false));

        // // Https version.
        // $CFG->httpswwwroot = str_replace('http:', 'https:', $CFG->wwwroot);

        // $up2 = new user_picture($user2);
        // $this->assertSame('https://secure.gravatar.com/avatar/ab53a2911ddf9b4817ac01ddcd3d975f?s=35&d=https%3A%2F%2Fwww.example.com%2Fmoodle%2Ftheme%2Fafterburner%2Fpix_core%2Fu%2Ff2.png', $up2->get_url($page, $renderer)->out(false));
        // End of gravatar tests.

        // Test themed images.
        // set_config('enablegravatar', 0);
        // $this->assertFileExists("$CFG->dirroot/theme/formal_white/config.php"); // Use any other theme.
        // set_config('theme', 'formal_white');
        // $CFG->httpswwwroot = $CFG->wwwroot;
        // $page = new moodle_page();
        // $page->set_url('/user/profile.php');
        // $page->set_context(context_system::instance());
        // $renderer = $page->get_renderer('core');

        // $up1 = new user_picture($user1);
        // $this->assertSame($CFG->wwwroot.'/pluginfile.php/'.$context1->id.'/user/icon/formal_white/f2?rev=11', $up1->get_url($page, $renderer)->out(false));

        // $up2 = new user_picture($user2);
        // $this->assertSame($CFG->wwwroot.'/theme/image.php/formal_white/core/1/u/f2', $up2->get_url($page, $renderer)->out(false));

        // Test non-slashargument images.
        set_config('theme', 'ventura');
        $CFG->wwwroot = str_replace('https:', 'http:', $CFG->wwwroot);
        $CFG->slasharguments = 0;
        $page = new moodle_page();
        $page->set_url('/user/profile.php');
        $page->set_context(context_system::instance());
        $renderer = $page->get_renderer('core');

        $up3 = new user_picture($user3);
        $this->assertSame($CFG->wwwroot.'/theme/image.php?theme=' . $CFG->theme . '&component=core&rev=1&image=u%2Ff2', $up3->get_url($page, $renderer)->out(false));
    }

    public function test_prepare() {
        $expecteda = array('<span class="current-page">1</span>',
                           '<a href="index.php?page=1">2</a>',
                           '<a href="index.php?page=2">3</a>',
                           '<a href="index.php?page=3">4</a>',
                           '<a href="index.php?page=4">5</a>',
                           '<a href="index.php?page=5">6</a>',
                           '<a href="index.php?page=6">7</a>',
                           '<a href="index.php?page=7">8</a>',
                           );
        $expectedb = array('<a href="page?page=3">4</a>',
                           '<a href="page?page=4">5</a>',
                           '<span class="current-page">6</span>',
                           '<a href="page?page=6">7</a>',
                           '<a href="page?page=7">8</a>',
                           );

        $mpage = new moodle_page();
        $rbase = new renderer_base($mpage, "/");
        $pbara = new paging_bar(40, 0, 5, 'index.php');
        $pbara->prepare($rbase, $mpage, "/");
        $pbarb = new paging_bar(100, 5, 5, 'page');
        $pbarb->maxdisplay = 5;
        $pbarb->prepare($rbase, $mpage, "/");

        $this->assertEquals($expecteda, $pbara->pagelinks);
        $this->assertEquals($expectedb, $pbarb->pagelinks);
    }

    public function test_add_js_strings() {
        $p = new page_requirements_manager();
        $p->strings_for_js(['info', 'error', 'confirm'], 'moodle');
        $end = $p->get_end_code(false);

        $this->assertStringContainsString('"info":"Information"', $end);
        $this->assertStringContainsString('"error":"Error"', $end);
        $this->assertStringContainsString('"confirm":"Confirm"', $end);
        $this->assertStringContainsString('M.util.add_strings', $end);
        $this->assertStringNotContainsString("M.str", $end);
    }
}
