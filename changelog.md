Release 14.0 (19th May 2021):
=============================

Important
---------

TL-28035 In order to upgrade to Totara 14 or later releases sites must upgrade through Totara 13

    Sites moving to Totara 14 or any later major releases must upgrade through
    Totara 13.
    Upgrade code has been cleaned up and optimised in Totara 14, leading to this
    requirement.

    For those who are moving from Totara 12 or below, to Totara 14 or above, you
    will need to upgrade the site to the latest Totara 13, and then to the latest
    Totara 14 or above.
    You do not need to run the site at Totara 13, you can simply put the code in
    place, run the upgrade, and then put the code you are aiming to upgrade in place
    and run the upgrade again.

    If you need further advice or information about this please reach out to us via
    our help desk, http://totara.support

TL-28140 The MNet integration has been removed

    The MNet integration in Totara was deprecated in Totara 13, and has now been
    removed.
    The user.mnethostid column has been removed from the database. Any custom
    plugins still referencing the field will need to be updated.

TL-28276 Added support for PHP 8.0

    Totara 14 is the first version to support PHP 8.0.
    Please be aware that several core changes were required in order to add support.
    If you have customisations or use third party plugins you will need to ensure
    they also support PHP 8.0 before choosing to use PHP 8.0.

TL-28280 PHP 7.3.4 is the minimum required version

    In order to run Totara 14 you will need to be running PHP 7.3.4, 7.4.x, or
    8.0.x.
    Information on our recommended system environments can be found on the help
    site:
    https://help.totaralearning.com/display/TPD/Recommended+hosting+environments

TL-28773 All remaining code to support Flash has been removed from the product

    Support for Flash has been removed from all supported browsers as of December
    2020.
    In light of this all code within the platform and products that supported Flash
    has now been removed.
    This includes the SWF media plugin, and all settings related to playing Flash
    content.

New features
------------

TL-25434 Implemented new question element to aggregate responses

    tbd

TL-28970 Implemented new question element to redisplay question responses from previous instances

    This feature adds a new question element plugin to redisplay responses of other
    questions in previous subject instances of the same activity or other
    activities. The configuration of this element includes the source question to
    redisplay responses from. Previous responses from all participants are shown,
    independent from the participants configured for the section the redisplay
    question is in.

    If an activity references another activity by using a redisplay question element
    the referenced activity, section or element cannot be deleted while this
    connection exists.

TL-29163 New performance activity question type "Review items"

    Configuration
    --------------

    With this new question type you can include content from other areas of Totara
    into a performance activity. With this release we only support competencies but
    support for Learning, Goals, and Evidence content types will follow in the
    future.

    Admins can set up a "Review items" question and choose which role can select the
    content for the individual subject instances.  All the perform element types
    are available as sub questions that can be added to the review question.  E.g.
    to elicit comments and ratings. 

    A new 'Rating scale: Competency' element has also been added.  The rating uses
    the selected competency's rating scale.  This rating does not impact on the
    competency, this only generates performance activity response data  

    The feature supports rating competencies via the performance activity. This can
    be toggled on or off by the admin.  This element uses the selected competency's
    rating scale and on submission passes the selected scale value to the competency

    Note; for this rating to be used in the competency's achievement calculation
    admins also need to add the new "Performance activities" achievement pathway to
    the competencies.

    The configured set of sub questions is displayed for each competency selected.

    User Experience
    ----------------

    After subject instances have been created users in the relationships configured
    in the review question will be able to select competencies to show up in their
    activity.   Once this is complete participants are able answer the sub
    questions for each selected competency and complete the activity.

    The final 'Competency Rating' can be given at any time in the activity workflow
    by participants in the relationship configured in the question settings.

    Developer information
    ----------------------

    More information about this new feature can be found in the help docs under
    [paste help docs link here].

    To enable sub questions for performance activities the structure of the VueJS
    components had to be changed quite significantly. For further details please see
    https://help.totaralearning.com/pages/viewpage.action?spaceKey=~kevinh&title=Performance+activity+content+element+plugins+v14
    [TBD].

TL-29311 Implemented Centralised Notifications subsystem

    What was done
    Scope of change

    Currently, centralised notifications are only used in Programs and
    Certifications but plans are gradually migrate all messages to this subsystem
    during next releases. Documentation for users and administrators can be found at
    https://help.totaralearning.com/display/THM/.Program+and+certification+notifications+v14

    To get more technical details on how centralised notifications work and how to
    implement new notifiable events, built-in notifications, delivery channels,
    placeholders, receivers, or schedulers please visit our developers
    documentation:
    https://help.totaralearning.com/display/TDDM/.Centralised+notifications+v1

TL-29343 Implemented theme based branding for email notifications

    It is now possible to set an HTML header, HTML footer, and plain text footer for
    emails within Ventura, as well as any other themes using the same theme settings
    engine.

TL-30212 Added support for multi lang filter in weka editor

    Enabled only in the centralised notifications. Multi lang isn't part of any of
    the existing variants so it has to be enabled manually by a developer. Details
    on how to enable it can be found in
    https://help.totaralearning.com/display/DEV/Weka+editor

    Once enabled, the multi_lang extension in filters /admin/filters.php needs to be
    enabled as well.

Improvements
------------

TL-27446 Removed language references to 'Program' when managing a Certification

    Prior to this improvement there were still a couple of interfaces that didn't
    use separate language for programs and certifications.
    This has been rectified and language is now consistent and correct.

TL-27551 Improved display of search results on workspace discussions

    Searching for a phrase in workspace discussions now shows all elements
    (discussion posts, comments and replies) that contain the searched phrase
    instead of showing only the discussion.

TL-28139 Add Seminar "Declare interest" to "Seminar direct enrolment" method.

    The *Declare interest* button is now available when using the *Seminar Direct
    Enrolment* plugin. This means that learners can declare their interest in a
    seminar event without already being enrolled on a course, so a manager can get a
    better idea of the interest level across organisation site's users, instead of
    just enrolled users.

TL-28197 Multiple improvements of OAuth 2 authentication plugin

    OAuth 2 authentication plugins was refactored and improved in several areas, the
    internal APIs are not fully backwards compatible.

    The major changes are:
    * user accounts are created after email confirmation instead of creation of
      unconfirmed accounts
    * there is a new Report builder source for linked logins with option
    * there is a new capability for deleting of linked logins of other users
    * two new plugin settings were added that control account creation and automatic
      account linking
    * linked logins are not deleted when user account is deleted any more, this
      prevent recreation of these accounts during next login
    * email confirmation was redesigned to improve security and user experience

    See /server/auth/oauth2/upgrade.txt for more information.

TL-28539 Added a report source for competencies

    This new report source gives an overview of all the competencies in the system.
    It can be filtered by competency framework and has configurable columns for
    related data like scale, type, assignment availability and parent competency. It
    is intended to be a user-friendly complement to the existing CSV export on the
    competency framework administration, which was more focused on
    machine-readability.

TL-28839 Added warning messages when a competency does not have a valid achievement path

    Whenever there are errors in achievement paths linked to a competency (e.g. no
    achievement path defined that will result in the user being considered
    proficient, or user is required to complete a course that no longer exists,
    etc.), users assigned to the competency will never be able to become proficient
    in the competency.

    In order to assist administrators in identifying such competencies, additional
    warning icons and messages were added to the following pages to highlight
    existing problems:
    * List of competency framework - identifies frameworks containing competencies
      with errors
    * List competencies within a specific framework - identifies actual competencies
      with errors
    * Assigning user groups to competencies - identifies competencies with errors in
      the list of competencies being assigned

TL-29071 Added a copy link feature to copy a seminar's virtual meeting join URL to the clipboard

    A 'Copy room link' feature has been added to the virtual meeting room card so
    that the 'Join room' URL can be easily copied and pasted to other applications.

TL-29162 Improved ad-hoc task that manages seminar virtual meetings

    The seminar virtual meetings are now managed with the state management system,
    which provides better error handling for robustness. For more information,
    please refer to the documentation.

TL-29203 Improved display of seminar virtual rooms that have failed to be created

TL-29253 Added a report source for competency ratings

    This new report source provides information on all manual competency ratings in
    the system. Competency user assignments that have been manually rated or
    potentially can be manually rated are reported on, including archived
    assignments. By default this shows one row per rating with columns for the
    user's name, competency, rating value, rating role and time rated. Configurable
    columns and filters are available for related data like competency framework,
    scale, type and all the user data fields.

TL-29432 Reworked performance activities participant view structure to support element grouping

    Reworked the structure of the participant view page components reducing
    duplication and allowing sub-plugins to support child elements. The sub-plugin
    elements are now responsible for handling their form and read only displays
    making them a lot more customisable. Any existing custom participant
    sub-plugins will need to be restructured to work with the new components as
    described in client/component/mod_perform/src/upgrade.txt

TL-29645 Migrated all program and certification messages to new notification

    TODO Ask Nathan - we need to provide important upgrade info here.

TL-29666 Improved the force new attempts setting in the scorm activity

    Added a new option to the force new attempts setting.

    Now the options are:
    - No
    - When completed/passed/failed (this is how the existing option works)
    - Always (new option) - It does not comply with SCORM Spec but allows to always
    force a new attempt. Useful for the case when a student is in the middle of
    viewing the SCORM and has not completed, passed or failed.


TL-29769 Added minimum proficiency override on individual competency assignments

    TODO

TL-30043 Brought default product styling inline with our branding

    The default accent theme colour, default mobile theme colour, and default
    learning item images have been brought inline with our branding colours.

Performance improvements
------------------------

TL-29730 Updated the admin menu to now load content on demand

    The admin menu under the cog in the top right requires the administration tree
    to be initialised in order to generate the HTML to display the menu. This was
    happening on every page and slowing every page down.
    The menu is now loaded via AJAX, and a 10 minute cache is used to optimise the
    performance of the admin menu cog.
    Because of the TTL on the cache the user may not see the correct menu items in
    situations where their permissions change, giving them access to more admin
    configuration, or removing items that are no longer accessible. This is
    rectified when the menu TTL expires, or if the user logs out and logs back in
    again.

Recommendations engine
----------------------

TL-29237 Additional user profile fields to user data export

    Additional user-related data is being exported to the recommendation system.  No
    names, family names, or any contact details are exported.

    User database id
    Language (language code)
    City (plain text)
    Country (country code)
    Interests (ids)
    Aspirational position (id)
    Positions (ids)
    Organisations (ids)
    Competence proficiencies (ids and level)
    Badges (ids)
    User description (plain text)

    This update also changes some of the recommender system's default settings.
    These changes serve to optimise the machine learning uploads and to ensure that
    the additional user profile fields will be utilised when recommendations are
    being computed.

TL-29271 Implemented lemmatization support in the recommender engine before transforming the raw text into TF-IDF matrix

    This implementation will help better match texts on the basis of their context
    (or lemmas) instead of raw words.

API changes
-----------

TL-23343 Updated LDAP API functions to server controls in PHP 7.3 and above

    Functions ldap_control_paged_result_response and ldap_control_paged_result have
    been deprecated in PHP 7.4. This change updates functionality that used these
    functions to use server controls instead. PHP 7.3 and below still uses these
    functions.

TL-26250 PHP warnings will now be detected by PHPUnit and will cause it report a failure

    Prior to this change warnings triggered during PHPUnit runs were simply being
    ignored.
    Warnings now cause the test scenario to be marked as a failure. This makes it
    easier to identify deprecations across PHP versions.

TL-27939 PHPUnit initialisation no longer depends on shifting the current working directory

TL-28144 Plugin and core versioning is now fully independent from Moodle

    In past releases we have kept the main version number, and plugin version
    numbers in sync with Moodle.
    Given sites moving from Moodle to Totara must migrate using the provided tool we
    have broken the dependence on Moodle version numbers and can now move versions
    freely as required.
    This enables us to simplify instructions for our core developers, to shift
    essential install.xml changes from totara_core to core, write upgrades into
    lib/db/upgrade.php, and makes it easier to both backport and make changes in
    Moodle plugins.

TL-28405 Added support in the DML for composed unique indexes with NULL values

TL-28407 Persistent abstraction consistency was improved to fetch data after insert and to automatically cast all data to strings

    The core\persistent abstract class now ensures that after every insertion the
    object is reset using the data from the database.
    This ensures proper defaults are loaded into object after it has been written to
    the database.

TL-28432 PHPUnit has been upgraded to version 9.5.1

TL-29085 MDL-36941: Create new tables for messages and notifications and convert existing API to use these

    Previously, messages and notifications were both stored together, in the
    "message" and "message_read" tables. This patch separates them into tables
    "messages" and "notifications". Several APIs were updated, and site with
    customisations should consult the upgrade.txt files for details.

TL-29306 Replace MDN es6 polyfills withe core-js polyfills

TL-29337 All deprecated trusttext related features and APIs have been removed

TL-29437 Deprecated the creation of some mod_facetoface\xxx_list classes with no parameters passed

    Creating one of the following list classes with no parameters is deprecated. If
    it is absolutely necessary, please pass an empty string/array to its condition
    parameter:
    The following classes have been affected:
    * mod_facetoface\interest_list
    * mod_facetoface\role_list
    * mod_facetoface\room_list
    * mod_facetoface\seminar_list
    * mod_facetoface\signup_list
    * mod_facetoface\signup_status_list

TL-29564 Testing data generators were migrated to standardised \testing\ namespace

TL-29611 Added theme_config to the properties available in the tenant_customizable_theme_settings hook

    Contributed by Michael Geering, Kineo UK

TL-29695 PHPUnit support classes were refactored to use core_phpunit namespace

TL-29729 Improved cursor paginator to support queries sorted by joined columns

TL-30377 Any plugin within Totara can now define report builder sources

    Previously, report builder rb_sources directories were only allowed for eight
    plugin types: 'auth', 'mod', 'block', 'tool', 'totara', 'local', 'enrol',
    and 'repository'. This meant that other types of plugins and sub-plugins could
    not provide their own report sources.

    This has been changed so that any type of plugin can now provide report sources.

    This may lead to unexpected report sources being detected in custom plugins, and
    installed on upgrade.

Tui front end framework
-----------------------

TL-29757 Added a paging component

TL-29758 Added an OverflowContainer component

TL-29962 Transparency and alignment can now be set for the CollapsibleGroupToggle component

TL-29963 Added indented and stealth props to the DataTable component

TL-30018 It is now possible to expand multiple roles within the Table component

TL-30078 The responsive component can now have its internal ResizeObserver paused and resumed

TL-30138 Allowed the contents of the Collapsible component to be indented

TL-30139 Update indented styles for the Table component and its children

TL-30197 Replaced the success icon with a tick

    The success icon has been replaced by a tick. The previous success icon has been
    renamed to SuccessSolid for those who still require that icon.

TL-30697 Improved the display of SurveyCard components when lots of text was used in the content

Contributions
-------------

* Michael Geering, Kineo UK - TL-29611

