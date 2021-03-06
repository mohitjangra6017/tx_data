<?php defined('MOODLE_INTERNAL') || die();
 $configuration = array (
  'siteidentifier' => '1a42772a51042653fb112be46c413461',
  'stores' => 
  array (
    'default_application' => 
    array (
      'name' => 'default_application',
      'plugin' => 'file',
      'configuration' => 
      array (
      ),
      'features' => 30,
      'modes' => 3,
      'default' => true,
      'class' => 'cachestore_file',
      'lock' => 'cachelock_file_default',
    ),
    'default_session' => 
    array (
      'name' => 'default_session',
      'plugin' => 'session',
      'configuration' => 
      array (
      ),
      'features' => 14,
      'modes' => 2,
      'default' => true,
      'class' => 'cachestore_session',
      'lock' => 'cachelock_file_default',
    ),
    'default_request' => 
    array (
      'name' => 'default_request',
      'plugin' => 'static',
      'configuration' => 
      array (
      ),
      'features' => 31,
      'modes' => 4,
      'default' => true,
      'class' => 'cachestore_static',
      'lock' => 'cachelock_file_default',
    ),
  ),
  'modemappings' => 
  array (
    0 => 
    array (
      'mode' => 1,
      'store' => 'default_application',
      'sort' => -1,
    ),
    1 => 
    array (
      'mode' => 2,
      'store' => 'default_session',
      'sort' => -1,
    ),
    2 => 
    array (
      'mode' => 4,
      'store' => 'default_request',
      'sort' => -1,
    ),
  ),
  'definitions' => 
  array (
    'core/string' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 30,
      'canuselocalstore' => true,
      'component' => 'core',
      'area' => 'string',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/langmenu' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'canuselocalstore' => true,
      'component' => 'core',
      'area' => 'langmenu',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/databasemeta' => 
    array (
      'mode' => 1,
      'requireidentifiers' => 
      array (
        0 => 'dbfamily',
      ),
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 15,
      'component' => 'core',
      'area' => 'databasemeta',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/eventinvalidation' => 
    array (
      'mode' => 1,
      'staticacceleration' => true,
      'requiredataguarantee' => true,
      'simpledata' => true,
      'component' => 'core',
      'area' => 'eventinvalidation',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/questiondata' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'requiredataguarantee' => false,
      'datasource' => 'question_finder',
      'datasourcefile' => 'question/engine/bank.php',
      'component' => 'core',
      'area' => 'questiondata',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/htmlpurifier' => 
    array (
      'mode' => 1,
      'canuselocalstore' => true,
      'component' => 'core',
      'area' => 'htmlpurifier',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/config' => 
    array (
      'mode' => 1,
      'staticacceleration' => true,
      'simpledata' => true,
      'component' => 'core',
      'area' => 'config',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/groupdata' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 2,
      'component' => 'core',
      'area' => 'groupdata',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/calendar_subscriptions' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'component' => 'core',
      'area' => 'calendar_subscriptions',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/capabilities' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 1,
      'ttl' => 3600,
      'component' => 'core',
      'area' => 'capabilities',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/yuimodules' => 
    array (
      'mode' => 1,
      'component' => 'core',
      'area' => 'yuimodules',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/observers' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 2,
      'component' => 'core',
      'area' => 'observers',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/plugin_manager' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'component' => 'core',
      'area' => 'plugin_manager',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/coursecattree' => 
    array (
      'mode' => 1,
      'staticacceleration' => true,
      'invalidationevents' => 
      array (
        0 => 'changesincoursecat',
      ),
      'component' => 'core',
      'area' => 'coursecattree',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/coursecat' => 
    array (
      'mode' => 2,
      'invalidationevents' => 
      array (
        0 => 'changesincoursecat',
        1 => 'changesincourse',
      ),
      'ttl' => 600,
      'component' => 'core',
      'area' => 'coursecat',
      'sharingoptions' => 2,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/coursecatrecords' => 
    array (
      'mode' => 4,
      'simplekeys' => true,
      'invalidationevents' => 
      array (
        0 => 'changesincoursecat',
      ),
      'component' => 'core',
      'area' => 'coursecatrecords',
      'sharingoptions' => 2,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/coursecontacts' => 
    array (
      'mode' => 1,
      'staticacceleration' => true,
      'simplekeys' => true,
      'ttl' => 3600,
      'component' => 'core',
      'area' => 'coursecontacts',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/repositories' => 
    array (
      'mode' => 4,
      'component' => 'core',
      'area' => 'repositories',
      'sharingoptions' => 2,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/externalbadges' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'ttl' => 3600,
      'component' => 'core',
      'area' => 'externalbadges',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/coursemodinfo' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'canuselocalstore' => true,
      'component' => 'core',
      'area' => 'coursemodinfo',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/userselections' => 
    array (
      'mode' => 2,
      'simplekeys' => true,
      'simpledata' => true,
      'component' => 'core',
      'area' => 'userselections',
      'sharingoptions' => 2,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/completion' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'ttl' => 3600,
      'staticacceleration' => true,
      'staticaccelerationsize' => 2,
      'component' => 'core',
      'area' => 'completion',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/coursecompletion' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => false,
      'ttl' => 3600,
      'staticacceleration' => true,
      'staticaccelerationsize' => 30,
      'component' => 'core',
      'area' => 'coursecompletion',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/navigation_expandcourse' => 
    array (
      'mode' => 2,
      'simplekeys' => true,
      'simpledata' => true,
      'component' => 'core',
      'area' => 'navigation_expandcourse',
      'sharingoptions' => 2,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/suspended_userids' => 
    array (
      'mode' => 4,
      'simplekeys' => true,
      'simpledata' => true,
      'component' => 'core',
      'area' => 'suspended_userids',
      'sharingoptions' => 2,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/roledefs' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 30,
      'component' => 'core',
      'area' => 'roledefs',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/plugin_functions' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 5,
      'component' => 'core',
      'area' => 'plugin_functions',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/tags' => 
    array (
      'mode' => 4,
      'simplekeys' => true,
      'staticacceleration' => true,
      'component' => 'core',
      'area' => 'tags',
      'sharingoptions' => 2,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/grade_categories' => 
    array (
      'mode' => 2,
      'simplekeys' => true,
      'invalidationevents' => 
      array (
        0 => 'changesingradecategories',
      ),
      'component' => 'core',
      'area' => 'grade_categories',
      'sharingoptions' => 2,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/temp_tables' => 
    array (
      'mode' => 4,
      'simplekeys' => true,
      'simpledata' => true,
      'component' => 'core',
      'area' => 'temp_tables',
      'sharingoptions' => 2,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/tagindexbuilder' => 
    array (
      'mode' => 2,
      'simplekeys' => true,
      'simplevalues' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 10,
      'ttl' => 900,
      'invalidationevents' => 
      array (
        0 => 'resettagindexbuilder',
      ),
      'component' => 'core',
      'area' => 'tagindexbuilder',
      'sharingoptions' => 2,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/message_processors_enabled' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 3,
      'component' => 'core',
      'area' => 'message_processors_enabled',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/message_time_last_message_between_users' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simplevalues' => true,
      'datasource' => '\\core_message\\time_last_message_between_users',
      'component' => 'core',
      'area' => 'message_time_last_message_between_users',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/postprocessedcss' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => false,
      'component' => 'core',
      'area' => 'postprocessedcss',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/user_group_groupings' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'component' => 'core',
      'area' => 'user_group_groupings',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/filter_active' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 10,
      'component' => 'core',
      'area' => 'filter_active',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/themedesigner' => 
    array (
      'mode' => 1,
      'component' => 'core',
      'area' => 'themedesigner',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/image_sizes' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'component' => 'core',
      'area' => 'image_sizes',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/themefileclasses' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'component' => 'core',
      'area' => 'themefileclasses',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/site_course' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'component' => 'core',
      'area' => 'site_course',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/theme_setting_categories' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'component' => 'core',
      'area' => 'theme_setting_categories',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'core/json_schema' => 
    array (
      'mode' => 4,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => false,
      'component' => 'core',
      'area' => 'json_schema',
      'sharingoptions' => 2,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'availability_grade/scores' => 
    array (
      'mode' => 1,
      'staticacceleration' => true,
      'staticaccelerationsize' => 2,
      'ttl' => 3600,
      'component' => 'availability_grade',
      'area' => 'scores',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'availability_grade/items' => 
    array (
      'mode' => 1,
      'staticacceleration' => true,
      'staticaccelerationsize' => 2,
      'ttl' => 3600,
      'component' => 'availability_grade',
      'area' => 'items',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'mod_glossary/concepts' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => false,
      'staticacceleration' => true,
      'staticaccelerationsize' => 30,
      'component' => 'mod_glossary',
      'area' => 'concepts',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'block_admin_related_pages/map' => 
    array (
      'mode' => 2,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => false,
      'ttl' => 600,
      'component' => 'block_admin_related_pages',
      'area' => 'map',
      'sharingoptions' => 2,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'block_anderspink/apdata' => 
    array (
      'mode' => 1,
      'component' => 'block_anderspink',
      'area' => 'apdata',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'block_carousel/block_carousel_custom_styles' => 
    array (
      'mode' => 1,
      'component' => 'block_carousel',
      'area' => 'block_carousel_custom_styles',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'block_totara_report_graph/graph' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => false,
      'staticacceleration' => false,
      'ttl' => 86400,
      'canuselocalstore' => true,
      'component' => 'block_totara_report_graph',
      'area' => 'graph',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'editor_weka/editorconfig' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => false,
      'canuselocalstore' => true,
      'component' => 'editor_weka',
      'area' => 'editorconfig',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'repository_skydrive/foldername' => 
    array (
      'mode' => 2,
      'component' => 'repository_skydrive',
      'area' => 'foldername',
      'sharingoptions' => 2,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_cloudfiledir/downloadurls' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'ttl' => 3600,
      'canuselocalstore' => true,
      'component' => 'totara_cloudfiledir',
      'area' => 'downloadurls',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_comment/author_access' => 
    array (
      'mode' => 4,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 50,
      'canuselocalstore' => true,
      'component' => 'totara_comment',
      'area' => 'author_access',
      'sharingoptions' => 2,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_core/hookwatchers' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 2,
      'component' => 'totara_core',
      'area' => 'hookwatchers',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_core/flex_icons' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 10,
      'component' => 'totara_core',
      'area' => 'flex_icons',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_core/completion_progressinfo' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 10,
      'component' => 'totara_core',
      'area' => 'completion_progressinfo',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_core/quickaccessmenu' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 10,
      'component' => 'totara_core',
      'area' => 'quickaccessmenu',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_core/quickaccessmenu_complete' => 
    array (
      'mode' => 1,
      'requireidentifiers' => 
      array (
        0 => 'userid',
        1 => 'lang',
      ),
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 2,
      'ttl' => 600,
      'component' => 'totara_core',
      'area' => 'quickaccessmenu_complete',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_core/totara_course_is_viewable' => 
    array (
      'mode' => 4,
      'simplekeys' => true,
      'simpledata' => true,
      'component' => 'totara_core',
      'area' => 'totara_course_is_viewable',
      'sharingoptions' => 2,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_core/visible_content' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 5,
      'ttl' => 600,
      'component' => 'totara_core',
      'area' => 'visible_content',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_customfield/areamap' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 2,
      'datasource' => '\\totara_customfield\\areamap_data_source',
      'canuselocalstore' => true,
      'component' => 'totara_customfield',
      'area' => 'areamap',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_engage/share_recipient_class' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 30,
      'canuselocalstore' => true,
      'component' => 'totara_engage',
      'area' => 'share_recipient_class',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_engage/share_recipient_areas' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 30,
      'canuselocalstore' => true,
      'component' => 'totara_engage',
      'area' => 'share_recipient_areas',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_engage/query_providers' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 30,
      'canuselocalstore' => true,
      'component' => 'totara_engage',
      'area' => 'query_providers',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_notification/notifiable_resolver_map' => 
    array (
      'mode' => 4,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => false,
      'component' => 'totara_notification',
      'area' => 'notifiable_resolver_map',
      'sharingoptions' => 2,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_notification/access' => 
    array (
      'mode' => 4,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => false,
      'component' => 'totara_notification',
      'area' => 'access',
      'sharingoptions' => 2,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_playlist/catalog_visibility' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'component' => 'totara_playlist',
      'area' => 'catalog_visibility',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_program/program_progressinfo' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 10,
      'component' => 'totara_program',
      'area' => 'program_progressinfo',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_program/program_users' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 10,
      'component' => 'totara_program',
      'area' => 'program_users',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_program/user_programs' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 10,
      'component' => 'totara_program',
      'area' => 'user_programs',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_program/course_order' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 100,
      'datasource' => '\\totara_program\\rb_course_sortorder_helper',
      'component' => 'totara_program',
      'area' => 'course_order',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_reportbuilder/rb_source_directories' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 2,
      'component' => 'totara_reportbuilder',
      'area' => 'rb_source_directories',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_reportbuilder/rb_ignored_embedded' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 2,
      'component' => 'totara_reportbuilder',
      'area' => 'rb_ignored_embedded',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_reportbuilder/rb_ignored_sources' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 2,
      'component' => 'totara_reportbuilder',
      'area' => 'rb_ignored_sources',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_webapi/persistedoperations' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'component' => 'totara_webapi',
      'area' => 'persistedoperations',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'totara_webapi/schema' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'component' => 'totara_webapi',
      'area' => 'schema',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'tool_monitor/eventsubscriptions' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 10,
      'component' => 'tool_monitor',
      'area' => 'eventsubscriptions',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'tool_uploadcourse/helper' => 
    array (
      'mode' => 4,
      'component' => 'tool_uploadcourse',
      'area' => 'helper',
      'sharingoptions' => 2,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'tool_usertours/tourdata' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 1,
      'component' => 'tool_usertours',
      'area' => 'tourdata',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'tool_usertours/stepdata' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 1,
      'component' => 'tool_usertours',
      'area' => 'stepdata',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'container_workspace/draft_id' => 
    array (
      'mode' => 4,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 50,
      'canuselocalstore' => true,
      'component' => 'container_workspace',
      'area' => 'draft_id',
      'sharingoptions' => 2,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'container_workspace/workspace' => 
    array (
      'mode' => 4,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => true,
      'staticaccelerationsize' => 50,
      'canuselocalstore' => true,
      'component' => 'container_workspace',
      'area' => 'workspace',
      'sharingoptions' => 2,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'ml_recommender/recommended_user_items' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'ttl' => 3600,
      'component' => 'ml_recommender',
      'area' => 'recommended_user_items',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'ml_recommender/recommended_related_items' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'ttl' => 21600,
      'component' => 'ml_recommender',
      'area' => 'recommended_related_items',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'theme_msteams/postprocessedcode' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'staticacceleration' => false,
      'component' => 'theme_msteams',
      'area' => 'postprocessedcode',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'contentmarketplace_goone/goonewslearningobject' => 
    array (
      'mode' => 1,
      'ttl' => 300,
      'component' => 'contentmarketplace_goone',
      'area' => 'goonewslearningobject',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'contentmarketplace_goone/goonewslearningobjectbulk' => 
    array (
      'mode' => 1,
      'ttl' => 300,
      'component' => 'contentmarketplace_goone',
      'area' => 'goonewslearningobjectbulk',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'contentmarketplace_goone/goonewscount' => 
    array (
      'mode' => 1,
      'ttl' => 300,
      'component' => 'contentmarketplace_goone',
      'area' => 'goonewscount',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'engage_article/catalog_visibility' => 
    array (
      'mode' => 1,
      'simplekeys' => true,
      'simpledata' => true,
      'component' => 'engage_article',
      'area' => 'catalog_visibility',
      'sharingoptions' => 15,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
    'local_credly/badges' => 
    array (
      'mode' => 2,
      'component' => 'local_credly',
      'area' => 'badges',
      'sharingoptions' => 2,
      'selectedsharingoption' => 2,
      'userinputsharingkey' => '',
    ),
  ),
  'definitionmappings' => 
  array (
  ),
  'locks' => 
  array (
    'cachelock_file_default' => 
    array (
      'name' => 'cachelock_file_default',
      'type' => 'cachelock_file',
      'dir' => 'filelocks',
      'default' => true,
    ),
  ),
);