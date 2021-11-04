Release 15.0 (5th November 2021):
=================================

New features
------------

  TL-30479 LinkedIn Learning content marketplace

    The LinkedIn Learning content marketplace plugin is a new integration in the
    Totara content marketplace. To make use of this integration your organisation
    will need a LinkedIn Learning account. The integration supports:
    1.  Setting up the integration in Totara and LinkedIn Learning by an
        administrator
    2.  Search and discovery of LinkedIn Learning courses in Totara by an
        administrator or course creator and the ability to import them into Totara
    3.  Enrolling in and launching imported LinkedIn Learning courses from
        Totara’s Find Learning catalog by a learner
    4.  Recording and reporting on course completions in Totara in real time by a
        learner or administrator

    The LinkedIn Learning content marketplace plugin was released as part of an open
    beta programme for the Totara Partner Network in November 2021. Open beta
    participants will have access to implementation documentation, priority support
    and will be able to provide direct feedback on how to improve the plugin. To
    participate in this programme sign-up here:

    https://help.totaralearning.com/linkedin-learning-beta

  TL-31507 Added a new review type for 'Evidence' to the 'Linked review' question element

    We have implemented a new type to review 'Evidence' within the new 'Linked
    review' question (originally introduced in 14.0).

    Admins can now set up a performance activity to include reviewing evidence as
    part of the activity. They can configure who (which participant relationship)
    can select the evidence for the individual activity instance.

    It is possible to add sub questions to the evidence review question.

  TL-31508 Added a new review type for 'Learning' to the 'Linked review' question element

    We have implemented a new type to review 'Learning' (which can be courses,
    programs or certifications) within the 'Linked review' question.

    Admins can now set up a performance activity to include reviewing learning items
    as part of the activity. They can configure who (which participant relationship)
    can select the learning items for the individual activity instance.

    It is possible to add sub questions to the learning review question.

  TL-32007 Added a new Machine Learning Service to replace and serve the recommenders system

    In previous versions, the recommendations system was run as a scheduled job once
    a day (or as configured) to generate a number of recommendations for the entire
    site.

    With this release we have introduced a Machine Learning service to produce
    real-time recommendations on demand. This service will be expanded upon in
    future versions for additional Machine Learning services beyond recommendations.
    The new service may be run independently of the Totara hosting server (e.g. on
    its own server, or in a Docker container). See extensions/ml_service/README.md
    for further details.

  TL-32396 Find learning catalogue and course enrolments are now supported within the Totara Mobile app

    This feature adds backend support for the mobile app to allow new find learning
    functionality. If your app is up to date and detects this code in the server it
    connects to, it will allow a user to view and search the learning items
    catalogue much like the webs grid catalogue. Along with viewing course details,
    enrolment options, and self enrolment / guest access to previously unenrolled
    courses.

    This is the first mobile plugin to take advantage of the change to sub-plugins
    (TL-31067) and as such the majority of code and endpoints can be found under the
    mobile_findlearning sub-plugin at ./totara/mobile/plugins/findlearning. However
    this does tie heavily into the grid catalogue code, along with some other core
    graphql apis. This feature consists of the following:

    New mobile persisted endpoints:
    * mobile_findlearning_view_catalog
    * mobile_findlearning_search_catalog
    * mobile_findlearning_enrolment_info
    * mobile_findlearning_attempt_self_enrolment
    * mobile_findlearning_validate_guest_password

    Mutations:
    * TL-31723 - added a new core mutation to attempt to enrol users into a course
      via self enrolment, the new mobile_findlearning_attempt_self_enrolment endpoint
      ties into this.

    Queries:
    * TL-31720 - added a new core query to retrieve information on a courses
      enrolment options, the new mobile_findlearning_enrolment_info endpoint ties into
      this.

    mobile_findlearning_view_catalog is a new query added to load a page of
    unfiltered catalogue items.

    mobile_findlearning_search_catalog is a new query to load a page of catalogue
    items filtered using the same full text search you find on the web grid
    catalogue.

    mobile_findlearning_validate_guest_password is a new query to validate guest
    access when it requires an enrolment key, which along with some small changes to
    the mobile_course query allow access for guests when the course enrolment
    options allow such.

Performance improvements
------------------------

  TL-30416 Improved the performance of IE 11 devtools by moving CSS Variable rules onto a dedicated root DOM Node

    Also available in 14.4 and later releases.

  TL-30630 Fixed counting likes/comments in a workspace discussion multiple times in one request

    Previously when loading a list of discussions in a workspace, the number of
    likes and number of comments for each discussion were independently queried,
    resulting in a large number of queries run for a single request.

    With this patch in place when loading a list of workspace discussions, the
    counts are performed once with the initial query, decreasing the number of
    queries needed to return the results.

    Also available in 14.1 and later releases.

  TL-30652 Improved the performance of course completion aggregations for the completion_regular_task

    On large sites, especially those containing courses with multiple activities,
    enrolling large numbers of users to these courses can result in the catch-all
    task 'core\task\completion_regular_task' taking a very long time to complete.

    The purpose of this task is to ensure that all completion information for all
    users enrolled in courses is correct and up to date. When users are enrolled in
    bulk, or changes are made to courses with a large number of enrolled users, the
    task may need to check and process thousands of completion records.

    To improve performance and ensure that the task completes in a reasonable time,
    this patch not only streamlines the underlying check and processing steps, but
    also introduces the processing of completion records in batches. Only a single
    batch of completion records that needs to be re-checked and re-aggregated is
    processed in a single cron run. The following batch will be processed during the
    next cron run, etc.

    Also available in 14.2 and later releases.

  TL-30973 Improved the performance of the Record of Learning: Course report source when used with a very large number of course completion history records

    Prior to this patch on older databases a large number of course completion
    history records (in the millions) would slow down the Record of Learning: Course
    report when the Past Completions columns were added. With this patch the report
    should now behave faster when used with a large dataset.

    Important: This change introduces a new index on the course_completion_history
    table, which can take several minutes to run on a large Totara instance.

    Also available in 14.1 and later releases.

  TL-31089 Improved the performance of the user content restriction query

    Mainly on MySQL databases the user content restrictions were scaling poorly with
    larger amounts of job assignment records. This has been fixed and performance on
    MySQL 5.7 and MySQL 8 is now significantly improved if user content restrictions
    are used in reports.

    Also available in 14.1 and later releases.

  TL-31095 Improved performance of reports based on the user source when certain count columns are used

    The query part for the following columns changed to improve performance:
    * User's Achieved Competency Count
    * User's Courses Started Count
    * User's Courses Completed Count
    * Course Completions as Evidence
    * Extensions

    Also available in 14.1 and later releases.

  TL-31156 Improved performance of displaying seminars with many events

    Prior to this patch, the performance of the page showing all upcoming and past
    events for one seminar did not scale well with increasing number of events when
    the enrolment plugin 'Seminar direct enrolment' was activated. With this patch
    the performance of this page is significantly improved.

    Also available in 14.2 and later releases.

  TL-31210 Improved performance of the \totara_program\task\recurrence_history_task scheduled task

    Also available in 14.2 and later releases.

  TL-31570 Delegated the re-grading of final grades of enrolled users within courses when adding a new course module

    Prior to this patch, when a course had a large number of enrolled users and
    associated grade records, it would take a long time to add a course module that
    has grading enabled. This was due to the re-grading of final grades occurring
    immediately when a new course module was added.

    With this patch, a new adhoc task is introduced which defers the re-grading
    functionality into cron. The re-grading task will be deferred to cron, every
    time when a new course module (activity) that enable grading functionality was
    added to the course. A Course Creator, or Site Administrator will be able to see
    the notification banner when viewing the course that has the re-grading cron
    task pending. When the cron runs and all the grade records get processed, the
    notification banner will not appear again.

    Also available in 14.4 and later releases.

  TL-31762 Improved the performance of the totara_icon_url_and_alt function

    This function is used, amongst other places, for the display of 'Multi select'
    course custom fields. This change will improve the performance of report builder
    where these fields have been used.
    Contributed by Stewart Fulton at Kineo Pacific

    Also available in 14.4 and later releases.

  TL-31863 Improved the performance of the room selection dialog for a session

    Also available in 14.4 and later releases.

  TL-32547 Improved the performance of the 'certification completion date' dynamic audience rule

    Also available in 14.5 and later releases.

Improvements
------------

  TL-29566 Added evidence type custom fields to evidence reports

    It is now possible to view and export custom field data on evidence reports.

    Also available in 14.4 and later releases.

  TL-29734 Updated 'help command' into 'hero card' that contains 'sign-in', 'sign-out' and 'help' button in Microsoft Teams

    Also available in 14.1 and later releases.

  TL-29897 Improved the 'Multiple choice: Multi-select' element in performance activities to support a single checkbox
  TL-30025 Created a new 'help tab' to display 'help' information in Microsoft Teams

    Also available in 14.1 and later releases.

  TL-30027 Added sign-out link for manual login settings on each static tab in Microsoft Teams

    Also available in 14.1 and later releases.

  TL-30099 Improved accessibility of block show/hide buttons
  TL-30101 Improved accessibility of mobile view main navigation
  TL-30102 Improved accessibility of the action menus when expanded/collapsed
  TL-30103 Improved accessibility of close button in YUI dialogs
  TL-30173 Improved accessibility of managing selected tiles in content marketplace explorer
  TL-30177 Improved accessibility of close button in content marketplace cards
  TL-30221 Added 'Achievement Status' column and filter to the Competency Status report

    It is now possible to filter records shown in the Competency Status report on
    the Achievement status column thus allowing users to view only active (current)
    achieved values.

    Also available in 14.1 and later releases.

  TL-30279 Added the 'aria-sort' attribute to table headers created via tablelib

    Also available in 14.4 and later releases.

  TL-30285 Allow the uploading of custom evidence data while uploading course or certification completion records

    It is now possible to include custom field data when importing course and
    certification completion evidence records.

    The format for specifying custom field data is similar to what was used in
    earlier versions of Totara. The only difference being that fields available for
    import are no longer the same for all evidence types; these are now determined
    by the fields defined for the evidence type selected when starting the upload
    process.

    Only evidence types marked as 'Available for completion import' can be used
    during the import process.

    Also available in 14.2 and later releases.

  TL-30291 Changed inline help for the 'force new attempt' setting in SCORM

    Also available in 14.6 and later releases.

  TL-30486 Modified audience adder to always load the most recent audience data
  TL-30562 Improved the display of Atto autosave messages
  TL-30730 Added a user-defined report feature setting for refinement of the Learn Professional flavour

    A switch was added to the 'Shared services settings' section in the feature
    configuration that allows turning off the ability to create reports. By default
    this will always be switched on, except for installations of the Learn
    Professional flavour where it is forced to be off.

    To access this feature, the site flavour must be upgraded from Learn
    Professional to a Learn Flavour.

    Also available in 14.2 and later releases.

  TL-30732 Added a completion import feature setting for refinement of the Learn Professional flavour

    A switch was added to the 'Learn settings' section in the feature configuration
    that allows completion import to be turned on or off. By default this will
    always be on except for installations of the Learn Professional flavour where it
    is forced to be off.

    To access this feature the site flavour must be upgraded from Learn Professional
    to a Learn Flavour.

    Also available in 14.2 and later releases.

  TL-30791 Added a logo button to use by default for Microsoft OAuth2 issuers on the site login page

    A new option has been added to Microsoft issuers for the OAuth2 plugin, named
    'Show default Microsoft branding'. This ensures that any new issuers of the
    Microsoft type meet Microsoft's corporate branding requirements. Existing
    issuers of the Microsoft type are not affected by this change.

    Also available in 14.1 and later releases.

  TL-30794 Added a configuration option for the report builder graph to specify max value for x and y axes

    Also available in 14.1 and later releases.

  TL-30797 A users competency page now uses up to 2 lines when viewing the competency graph

    Also available in 14.1 and later releases.

  TL-30852 Renamed the 'Open in new window' button in Microsoft Teams to 'Open in browser'

    Also available in 14.1 and later releases.

  TL-30896 Disabled organisation hierarchies in Learn Professional flavour

    A switch was added to the 'Shared services settings' section in the feature
    configuration that allows turning off the access to organisation hierarchies. By
    default this will always be switched on except for installations of the Learn
    Professional flavour where it is forced to be off.

    To access this feature, the site flavour must be upgraded from Learn
    Professional to a Learn Flavour.

    Also available in 14.2 and later releases.

  TL-30934 Added a warning to the environment page if the charset of the current database does not support four-byte characters

    Also available in 14.1 and later releases.

  TL-31009 Adjusted the default scheduled 'send_registration_data_task' task to randomise the time it is run to distribute the load

    The change also uses an upgrade script to reset the task to ensure the new
    'randomised' time is set.

    Also available in 14.1 and later releases.

  TL-31021 Ensured that the CSS in the Microsoft Teams theme is compatible with older browsers

    Also available in 14.1 and later releases.

  TL-31038 Added a configuration option to Microsoft Teams tabs to allow the modification of existing tabs

    Also available in 14.1 and later releases.

  TL-31040 Added user custom field notification placeholders

    User custom/profile fields are now available to use as placeholders in
    notifications that use the 'user' placeholder group, such as 'Subject',
    'Manager' and 'Recipient'. Checkbox and non-public fields are excluded. Also, ID
    Number, Language and Description placeholders have been added to the 'user'
    placeholder group.

    Also available in 14.1 and later releases.

  TL-31057 Updated Microsoft Teams settings to support Totara's Microsoft Teams gateway

    Added a new 'hidden' setting option - $CFG->msteams_gateway_url - that can be
    used to define the Microsoft Teams gateway to be used. Once configured, the
    gateway setting will be available.

    Also available in 14.2 and later releases.

  TL-31066 Added an optional description configuration option to the 'Custom rating scale' element in performance activities
  TL-31162 Added a signature to verify that the Microsoft Teams gateway registration request is genuinely from a Totara site

    Also available in 14.4 and later releases.

  TL-31259 Added a search filter to the filter bar on the performance activities page.

    The search operates over the Activity Name and User Name fields.

  TL-31260 Added a filter on the performance activities page to filter out completed activities
  TL-31261 Changed the order and some strings of the activity list columns on the performance activities page
  TL-31262 Changed the pagination of performance activity subject instances from cursor to offset based
  TL-31264 Added a priority sorted card view to the performance activities page
  TL-31276 Updated the URL to the product documentation and improved the wording of the 'Help' tab in the Microsoft Teams application

    Also available in 14.2 and later releases.

  TL-31356 Added a function to let notification resolvers know notifications have been sent

    Notifiable event resolvers are now able to implement a function
    'notification_sent' which will be called when a notification based on the
    resolver is sent. The resolver can then execute arbitrary code, such as
    recording the information in a custom log table.

    Also available in 14.2 and later releases.

  TL-31378 Added the ability to use data series configuration on report builder graphs

    This allows more advanced configuration of data series within a graph, for
    example, fill under lines in line graphs, line styles or colours of data sets.

    Also available in 14.2 and later releases.

  TL-31379 Hide collapsible components when there is only one notification resolver

    When there is only one item to show/hide, the collapsible component does not
    increase the quality of the user's experience. The show/hide elements of the UI
    are now hidden in these cases.

    Also available in 14.2 and later releases.

  TL-31402 Increased the maximum length of course category names to 1333 characters

    Also available in 14.2 and later releases.

  TL-31403 Created a 'program assigned' column for the deprecated 'mandatory' column in the learning plans (program) report source

    Investigation showed that the 'Mandatory' column is only an indication whether
    the user is still assigned to a program that they previously completed. This
    column has now been deprecated and a replacement column 'program assigned'
    created to reflect the correct meaning.

    Also available in 14.4 and later releases.

  TL-31438 Created an accessible HTML version of the certificate activity PDF

    In the certificate course module a user can now access a HTML version of the
    certificate alongside the existing PDF version. This is to provide an accessible
    version of the certificate as the PDF is not currently accessible. If the
    certificate is sent via email to the user the user will be able to access the
    HTML version from the email via a link.

  TL-31447 Added a CLI script for changing program start and completion times

    The course completion editor allows administrators to change the date and time
    when an enrolled user completed a course to a date in the future. If this course
    is then later included in a program and the user assigned to the program, the
    resulting program completion record may indicate that the user started and
    completed the program in the future.

    As the program completion editor does not allow changes to the program start
    datetime administrators have no easy way to fully correct their data.

    The provided new script
    (totara/program/cli/update_program_completion_start_end.php) allows
    administrators to manually change program start and completion datetimes for
    assigned users.

    Note: this script allows administrators to update multiple program completion
    records in a single run. It should therefore be used with care.

    Also available in 14.4 and later releases.

  TL-31552 Goal target date changes are now recorded for posterity

    This patch starts recording changes to goal target dates, both for personal and
    company goals. For performance activities, this enables the two goal review
    question types to display the historic target date setting at the time the goal
    was selected within the activity.

    Also available in 14.5 and later releases.

  TL-31626 Added a hook to allow the overriding of component capability checks

    The totara\core\hook\component_access_check hook can now be used to override
    capability restrictions.

    This hook is not triggered automatically but needs to be executed wherever
    overriding of capability checks are allowed, e.g. in performance activity
    elements.

    Also available in 14.4 and later releases.

  TL-31653 Added basic support for mailto links in the WEKA editor

    Also available in 14.5 and later releases.

  TL-31713 Updated the welcome message that is sent when adding the Totara app to Microsoft Teams for the first time

    Also available in 14.2 and later releases.

  TL-31763 The information shown when viewing a goal on a performance activity now includes the type

    Also available in 14.3 and later releases.

  TL-31764 Added the review type to the "Element type" column in the performance activity report source for "Linked review" questions

    Also available in 14.4 and later releases.

  TL-31784 Conditionally show the filters 'Exclude completed activities' and 'Overdue activities only' in the activity list

    The filters are only shown if the user has any completed or overdue activities
    in their list.

  TL-31786 Added a sort by selection to the performance activities page to sort the list of activities
  TL-31793 Improved the capability checks when selecting competencies in "Linked review" question elements in a performance activity

    When linking competency review elements to a performance element, the selecting
    participant was not allowed to view and select relevant competencies of the
    subject user without being granted additional capabilities. With this patch the
    selecting participant in a performance activity will be allowed to select
    competencies assigned to the subject user regardless of granted capabilities.

    Also available in 14.4 and later releases.

  TL-31797 Added a description text field to the "Numeric rating scale" question element in performance activities
  TL-31805 Improved accessibility of Ventura theme settings
  TL-31868 Improved the element spacing in Totara Engage playlists and resources on mobile
  TL-31872 Added an option to show rating scale descriptions on the "Competency rating scale" question element in performance activities
  TL-31930 Removed the admin buttons and headings on pages when viewed within Microsoft Teams

    Also available in 14.4 and later releases.

  TL-31934 Added logout to configuration tab in Microsoft Teams and hid unwanted headings and configuration options

    Also available in 14.4 and later releases.

  TL-31937 Added a configuration setting to prevent resending legacy program and certification messages on schedule change

    Introduced a new configuration setting
    '$CFG->program_message_prevent_resend_on_schedule_change'. This is only relevant
    for rare cases where new message types based on the legacy
    'prog_eventbased_message' class had been programmed and were carried over from
    Totara versions prior to Totara 14. When set to true, it switches off the
    default behaviour of resending program and certification messages on change of
    the message scheduling.

    Also available in 14.5 and later releases.

  TL-31960 Made improvements to the collapsible side panel in Totara Engage on mobile

    Changed the side panel in resources and playlists to not initially open by
    default on mobile viewports. Modified the position of the side panel
    expand/collapse handle on mobile to always show in the middle of the vertical
    viewport.

  TL-31990 Added tabs for each of the users roles on the performance activities page
  TL-32023 Improved the capability checks when selecting evidence  in "Linked review" question elements in a performance activity

    When linking evidence review elements to a performance element, the selecting
    participant is now allowed to view and select relevant evidence of the subject
    user without the need to grant additional capabilities to the selecting
    participant.

  TL-32024 Removed the required attribute from playlist description as it was not required

    Also available in 14.4 and later releases.

  TL-32078 Added a body class to every page when viewed within a Mobile webview

    This patch added a hook to page setup, allowing plugins to watch for the hook
    and inject classes into the body of the page. Mobile watches for said hook,
    checks whether the request headers match expectations for a mobile webview, and
    conditionally adds a webview class to the body of the page.

  TL-32079 Added the ability in the side panel comment box component to hide the like (thumbs up) button

    Also available in 14.4 and later releases.

  TL-32081 Removed the attendee date conflict check when copying seminar events

    Also available in 14.4 and later releases.

  TL-32082 Courses now displays a banner when being viewed as a guest

    Courses now display a prominent banner so users can see that they are not fully
    enrolled (and therefore course progress will not be tracked). The banner shows
    different messages for 'guests' and also 'administrators' who are accessing the
    course without being enrolled.

    If the user is able to enrol in the course the banner will include a button
    prompting them to enrol. In most cases this takes the user to the enrolment
    options page, but if there is only one enrolment method and that method supports
    'non interactive enrolment' then the user can be directly enrolled when they
    press the Enrol button.

  TL-32117 Added information about seminar specific requirements to the 'Completion progress details' page

    Also available in 14.4 and later releases.

  TL-32146 Added Totara Comment placeholder group

    A group of placeholders has been implemented which can be used to add Totara
    Comment content to notifications. This is not yet in use, but is available to
    third-party developers.

    Also available in 14.4 and later releases.

  TL-32216 Added a 'year range' configuration option to the 'Date picker' question element in performance activities
  TL-32241 Added a 'select all' feature on the content marketplace page
  TL-32284 Reworded seminar notifications help text

    Previously the session booking and session date changed reminder recipients had
    the wording 'All events(past, ...)'.

    However, these notifications never go out for past events. This ticket removed
    the word 'past'; the new wording makes it clear that notifications only go out
    for current and future events.

    Also available in 14.4 and later releases.

  TL-32400 Added support for detecting missing primary keys in the database schema check CLI script
  TL-32463 Increased the length of the 'title' column in the 'notification_preference' table to fix an issue with the migration of legacy messages

    Also available in 14.5 and later releases.

  TL-32474 Implemented a hook for core roles to assign users with a different context

    Currently `core_role_get_potential_user_selector()` has some logic to determine
    whether a context is 'above' a course or 'below' it, and chooses a user selector
    for assigning roles based on that.

    The CONTEXT_MODULE is considered 'below' a course, and so the users you can
    select have to be enrolled on the course.

    For some cases, there is nobody enrolled on the course; the admin should be free
    to choose any user in the system (subject to multi-tenancy rules).

    Also available in 14.5 and later releases.

  TL-32517 Added a new key binding that adds multilanguage blocks to the weka editor

    Also available in 14.6 and later releases.

  TL-32595 Creating a workspace while viewing a workspace discussion takes the user to the newly created workspace

    Also available in 14.6 and later releases.

  TL-32598 Improved accessibility of 'Add to admin menu' dropdown that appears on admin pages by adding a label
  TL-32603 Improved the accessibility of progress bars by adding an aria-label
  TL-32637 Removed references to recommended workspaces when a user has none

Bug fixes
---------

  TL-29363 Moved the user profile content region to a similar location of the main region on the user profile page
  TL-30367 Fixed CSS that was unexpectedly affecting tables inside a block
  TL-30457 The add alt text dialog within Weka now shifts focus to itself when opening
  TL-30467 Clicking outside of a dropdown when managing a competency now closes the dropdown
  TL-30609 Fixed the 'fullnamedisplay' setting not being applied to the user menu
  TL-30877 Fixed z-index stacking order issues when displaying the session error modal
  TL-31412 Changed the 'unassign' option in the program exception report action to 'dismiss'

    This reflects the actual behaviour of the code. When the exception is
    'dismissed', it is just marked as 'dismissed', the users are still assigned to
    the program and they will appear in the completion report.

    A new column 'exception status' has been added to the program membership report
    source. Using this new column and the existing program name column, users can
    zoom into a user in the program whose exceptions were dismissed.

  TL-31533 Improved the error handling in GraphQL if the user does not have the capability to view a category name with raw format

Technical changes
-----------------

  TL-28526 Added GraphQL mutations to mark courses, programs, certifications as viewed

    Also available in 14.2 and later releases.

  TL-30395 Set the loglevel to 'warn' in .npmrc to suppresses 'notice' level outputs, such as the core-js advertising on npm ci and npm install

    Also available in 14.1 and later releases.

  TL-30635 Made grade feedback format an optional value for get_grade_items webservice return values

    Also available in 14.1 and later releases.

  TL-30756 Added support for JSON validation

    Implemented a JSON validator to validate the JSON against the JSON schema which
    is defined by https://json-schema.org/. At the very heart of the validator API,
    it uses Opis JSON Validator to do the validation of the JSON data.

  TL-30804 Added the ability to execute GraphQL operations from page controllers

    A new method execute_graphql_operation() has been added
    to totara_mvc/controller to allow execution of GraphQL operations from a page
    controller.

    Also available in 14.1 and later releases.

  TL-30956 Deprecated the function get_module_types_names() and added course_helper::get_all_modules()
  TL-30974 Removed obsolete reCAPTCHA v1 file

    Version one of reCAPTCHA has not been supported since 2018, and we have long
    since moved to version two or three. Cleaning up left over files and references
    to version one to avoid any confusion.

  TL-31067 Changed the architecture of the mobile plugin to allow sub-plugins

    This will allow for greater extensibility of the back-end of the mobile app,
    along with increased ease of customisation for clients interested in doing so.
    The current learning queries have been moved to the first sub-plugin, class
    stubs have been left in place in case anything has been extended and persisted
    queries remain in the same place pointing to the moved revolvers to avoid any
    conflicts with existing customisations.

    The totara_mobile_me query has also been updated to return version information
    on any enabled mobile sub-plugins, for now this is limited to the current
    learning plugin but it allows further flexibility going forwards.

    Also available in 14.3 and later releases.

  TL-31207 The report link on Totara comments can now be hidden by the component which displays them

    We have improved the 'Report' link in Totara comments by making it customisable
    per comment component.

    Individual components can now describe specific rules on when it shows the
    Report link for comments and replies.

    Also available in 14.2 and later releases.

  TL-31244 Allow notification resolvers to specify additional criteria

    The notification API has been extended to allow individual notification
    resolvers to specify additional criteria which must be met before a notification
    will be sent. See notification technical documentation for details.

    Also available in 14.2 and later releases.

  TL-31372 Added a new to_record() function for entities to have greater compatibility with legacy APIs
  TL-31480 Removed superfluous trace messages when there are no notifications to send out

    Also available in 14.6 and later releases.

  TL-31635 Updated the totara_userdata component to use the ORM builder

    Added two new functions
    * totara_userdata/userdata/item::get_activities_context_builder_join() same as
      totara_userdata/userdata/item::get_activities_context_join() but using the ORM
      framework
    * totara_userdata/userdata/item::get_activities_builder_join() same as
      totara_userdata/userdata/item::get_activities_join() but using the ORM framework

    As Totara introduced the ORM framework, we are converting SQL queries from DML
    to ORM.

    Please refer to the full documentation available here:
    https://help.totaralearning.com/x/TKbgB

    Also available in 14.2 and later releases.

  TL-31645 Added behat steps to check toolbar options in the Weka editor

    Also available in 14.2 and later releases.

  TL-31661 \page_requirements_manager::js_call_amd() can now be called without specifying the 'function' parameter

    Also available in 14.2 and later releases.

  TL-31682 Updated the ALLOWED_VALUES constraint in install.xml files to allow uppercase letters

    Also available in 14.4 and later releases.

  TL-31720 Added a new GraphQL query to retrieve information on a course's enrolment options
  TL-31723 Added a new GraphQL mutation to allow users to self enrol in courses

    This mutation takes the ID of a course and enrolment instance, along with an
    optional password, and then attempts to enrol the current user in the course
    (assuming everything is configured correctly).

  TL-31782 Refactored the due date format in the 'mod_perform_subject_instance' GraphQL type and introduced a 'days to due date' attribute
  TL-31800 Added more user fields for web service create and update functions

    \core_user_external::create_users() and \core_user_external::update_users() can
    now accept more user profile fields so user creation/update via web service can
    now be very similar to the edit profile page's functionality. The new fields
    that have been added are:
    * maildisplay
    * interests
    * url
    * skype
    * institution
    * department
    * phone1
    * phone2
    * address

    Also available in 14.4 and later releases.

  TL-31979 Removed SidePanelCommentBox.interactor

    The SidePanelCommentBox.interactor was introduced in the previous release but
    breaks backward compatibility. Any code that has depended on the prop since
    13.11 or 14.3 will be required to use :showComment="interactor.can_comment"
    instead.

    Also available in 14.4 and later releases.

Tui front end framework
-----------------------

  TL-29630 Replaced the Tui component used for progress tracking

    To support a wider range of use cases for 'showing step-based progress', for
    example showing current step in a workflow, and to support flexible type of
    content per progress step, we have created a new set of progressTrackerNav and
    coupled child components.

    The original progressTracker and coupled child components that were made
    available in T13 are now deprecated, we have updated our usages of those
    components to point to our new ones introduced by this change.

  TL-29716 Allow Uniform/Reform form state to be controlled by an embedding component

    Also available in 14.1 and later releases.

  TL-29719 Updated the collapsible component to match the latest design
  TL-30893 Introduced a produce() utility in `tui/immutable` with a similar API to the open-source immer library

    This allows developers to express modifications to JS data structures as
    imperative updates inside a callback, and have the modifications automatically
    translated to an immutable update - the library will clone the parts of the
    structure you modify, but leave the parts you don't pointing to the same object.

  TL-31171 Allowed the returning of a new value from produce() in tui/immutable

    Also available in 14.4 and later releases.

  TL-31432 Added a core file card component
  TL-31443 Converted the AttachmentNode Weka component to a thin wrapper over the new FileCard component
  TL-31560 Improved the handling for invalid date formats within the Tui date selector component

    Also available in 14.3 and later releases.

  TL-31581 Uniform propagates the 'validation-changed' event

    The 'validation-changed' event fires on change events in Uniform controlled
    forms and is now exposed at Uniform component level.
    The event supplies a `validationResults` object with validation details for each
    input field that has been touched.

    Also available in 14.2 and later releases.

  TL-31616 Extracted the skeleton content component from the content marketplace into TUI core
  TL-31646 Improved the alignment of the lozenge component with other inline elements

    The lozenge component will now visually align better with other inline elements.

  TL-31647 Extended notification banner component to allow for custom body content

    Extended notification banner component to allow for custom body content, custom
    inner layouts can now be added to a slot.

  TL-31650 Added hasShadow and noBorder properties to the ActionCard.vue component to align it with the Card.vue component for consistency
  TL-31789 Added closeOnClick prop to TagList component

    Also available in 14.3 and later releases.

  TL-31812 Changed table components to stack based on their own width
  TL-31973 Included a loading display within the Vue datatable components

    Included a loading display within the Vue datatable components using the
    skeleton content UI for a more polished look and feel.

  TL-31977 TagList component emits open event

    Also available in 14.4 and later releases.

  TL-32112 Updated styling of loading button

    Also available in 14.4 and later releases.

  TL-32192 TagList component has optional "accessible-name" prop

    The TagList component now accepts an optional "accessible-name" property that
    can be used to override the default name used in the dropdown's aria-label.

    Also available in 14.4 and later releases.

  TL-32444 Fixed issue where button text was covered by an additional HTML element

    Also available in 14.5 and later releases.

Library updates
---------------

  TL-28211 Moved library AdoDB to composer and upgraded to version 5.21.2
  TL-28217 Upgraded library minify to version 1.3.63
  TL-28219 Removed unused library Pear_Auth_Radius
  TL-28220 Removed unused library Pear_Crypt_CHAP
  TL-28223 Upgraded library TCPDF to version 6.4.2
  TL-28226 Moved markdown library to required libraries managed via composer
  TL-28241 Upgraded library ES promise polyfill for IE11 to 4.2.8
  TL-28244 Upgraded library box/spout to version 3.3.0
  TL-28246 Upgraded PHPMailer library to version 6.5.1

    Also available in 14.5 and later releases.

  TL-28271 Upgraded the ChartJS library used in Tui to version 2.9.4

    Also available in 14.5 and later releases.

  TL-30743 Upgraded library jQuery to version 3.6.0
  TL-31875 Upgraded postcss library to version 7.0.36

    Also available in 14.4 and later releases.

  TL-32257 Updated indirect dependencies being used by composer libraries
  TL-32258 Updated indirect dependencies being used by npm libraries
  TL-32262 Removed unused pear Console_Getopt library

Contributions
-------------

  * Stewart Fulton at Kineo Pacific - TL-31762