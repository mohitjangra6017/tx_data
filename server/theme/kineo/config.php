<?php

defined('MOODLE_INTERNAL') || die();

$THEME->doctype = 'html5';
$THEME->name = 'kineo';
$THEME->parents = ['ventura', 'legacy', 'base'];
$THEME->enable_dock = true;
$THEME->enable_hide = true;
$THEME->scss = 'main';
$THEME->minify_css = false;
$THEME->prescsscallback = 'theme_kineo_pre_scss_compile';
$THEME->extrascsscallback = 'theme_kineo_extra_scss_compile';
$THEME->csspostprocess = 'theme_kineo_css_post_process';
$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->layouts = array(
    // Most backwards compatible layout with blocks on the left - this is the layout used by default in Totara,
    // it is also the fallback when page layout is set too late when initialising page.
    // Standard Moodle themes have base layout without blocks.
    'base' => array(
        'file' => 'default.php',
        'regions' => array('side-pre', 'admin-region'),
        'defaultregion' => 'side-pre',
    ),
    // Standard layout with blocks, this is recommended for most pages with general information.
    'standard' => array(
        'file' => 'default.php',
        'regions' => array('top', 'bottom', 'side-pre', 'side-post', 'footer-region', 'admin-region'),
        'defaultregion' => 'side-pre',
    ),
    // Main course page.
    'course' => array(
        'file' => 'course.php',
        'regions' => array('top', 'bottom', 'side-pre', 'side-post', 'footer-region', 'admin-region', 'crs-pge-top', 'crs-pge-bottom'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu' => true),
    ),
    'coursecategory' => array(
        'file' => 'default.php',
        'regions' => array('top', 'bottom', 'side-pre', 'side-post', 'footer-region', 'admin-region'),
        'defaultregion' => 'side-pre',
    ),
    // Part of course, typical for modules - default page layout if $cm specified in require_login().
    'incourse' => array(
        'file' => 'default.php',
        'regions' => array('top', 'bottom', 'side-pre', 'side-post', 'footer-region', 'admin-region'),
        'defaultregion' => 'side-pre',
    ),
    // The site home page.
    'frontpage' => array(
        'file' => 'frontpage.php',
        'regions' => array('front-pge-top', 'top', 'side-pre', 'side-post', 'main','main-one','main-two', 'main-three', 'main-four', 'bottom', 'front-pge-bottom', 'footer-region', 'admin-region'),
        'defaultregion' => 'side-pre',
        'options' => array('nonavbar' => true),
    ),
    // A page with one column that allows top and bottom configurable blocks.
    'columnpage' => array(
        'file' => 'default.php',
        'regions' => array('top', 'bottom', 'footer-region', 'admin-region'),
        'defaultregion' => 'top',
    ),
    // Server administration scripts.
    'admin' => array(
        'file' => 'default.php',
        'regions' => array('side-pre', 'footer-region', 'admin-region'),
        'defaultregion' => 'side-pre',
        'options' => array('fluid' => true),
    ),
    // This would be better described as "user profile" but we've left it as mydashboard
    // for backward compatibilty for existing themes. This layout is NOT used by Totara
    // dashboards but is used by user related pages such as the user profile, private files
    // and badges.
    'mydashboard' => array(
        'file' => 'default.php',
        'regions' => array('top', 'bottom', 'side-pre', 'side-post', 'footer-region', 'admin-region'),
        'defaultregion' => 'side-pre',
    ),
    // The dashboard layout differs from the one above in that it includes a central block region.
    // It is used by Totara dashboards.

    'dashboard' => array(
        'file' => 'dashboard.php',
        'regions' => array('dash-pge-top', 'top', 'side-pre', 'side-post', 'main', 'dash-main-one', 'dash-main-two', 'dash-main-three', 'dash-main-four', 'bottom', 'dash-pge-bottom', 'footer-region', 'admin-region'),
        'defaultregion' => 'main',
        'options' => array('langmenu' => true),
    ),
    // My public page.
    'mypublic' => array(
        'file' => 'default.php',
        'regions' => array('top', 'bottom', 'main', 'side-pre', 'side-post', 'footer-region', 'admin-region'),
        'defaultregion' => 'main',
    ),
    'login' => array(
        'file' => 'default.php',
        'regions' => array('footer-region'),
        'options' => array('langmenu' => true, 'nototaramenu' => true, 'nonavbar' => true),
        'defaultregion' => 'footer-region',
    ),

    // Pages that appear in pop-up windows - no navigation, no blocks, no header.
    'popup' => array(
        'file' => 'popup.php',
        'regions' => array(),
        'options' => array('nofooter' => true, 'nonavbar' => true),
    ),
    // No blocks and minimal footer - used for legacy frame layouts only!
    'frametop' => array(
        'file' => 'default.php',
        'regions' => array(),
        'options' => array('nofooter' => true, 'nocoursefooter' => true),
    ),
    // Embeded pages, like iframe/object embeded in moodleform - it needs as much space as possible.
    'embedded' => array(
        'file' => 'embedded.php',
        'regions' => array()
    ),
    // Used during upgrade and install, and for the 'This site is undergoing maintenance' message.
    // This must not have any blocks, links, or API calls that would lead to database or cache interaction.
    // Please be extremely careful if you are modifying this layout.
    'maintenance' => array(
        'file' => 'maintenance.php',
        'regions' => array(),
    ),
    // Should display the content and basic headers only.
    'print' => array(
        'file' => 'default.php',
        'regions' => array(),
        'options' => array('nofooter' => true, 'nonavbar' => false),
    ),
    // The pagelayout used when a redirection is occuring.
    'redirect' => array(
        'file' => 'embedded.php',
        'regions' => array(),
    ),
    // The pagelayout used for reports.
    'report' => array(
        'file' => 'default.php',
        'regions' => array('top', 'bottom', 'side-pre', 'footer-region', 'admin-region'),
        'defaultregion' => 'side-pre',
    ),
    // The pagelayout used for safebrowser and securewindow.
    'secure' => array(
        'file' => 'secure.php',
        'regions' => array('top', 'bottom', 'side-pre', 'side-post', 'footer-region', 'admin-region'),
        'defaultregion' => 'side-pre'
    ),
    'noblocks' => array(
        'file' => 'default.php',
        'regions' => array(),
        'options' => array('noblocks' => true, 'langmenu' => true),
    ),
    // Totara: special layout for mobile app WebView.
    'webview' => array(
        'file' => 'webview.php',
        'regions' => array(),
    ),
    'legacynolayout' => array(
        'file' => 'default.php',
        'regions' => array(),
        'options' => array(
            'noblocks' => true,
            'langmenu' => true,
            'nonavbar' => true,
            'nosubnav' => true,
        ),
    ),
    // This layout can be used for external users accessing the page.
    // This should also be combined with setting no cookies so that
    // the user won't be logged in and wouldn't see the user menu or other
    // related information a normal logged in user sees
    'external' => array(
        'file' => 'default.php',
        'regions' => array(),
        'options' => array(
            'noblocks' => true,
            'langmenu' => true,
            'nonavbar' => true,
            'nosubnav' => true,
            'nofooter' => true,
            'nototaramenu' => true,
            'nologinbutton' => true,
        ),
    )
);
$THEME->javascripts_footer = array(
    'moodlebootstrap', 'kineo', 'dock'
);