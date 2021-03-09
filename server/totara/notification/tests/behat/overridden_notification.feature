@totara @totara_notification @javascript @vuejs
Feature: Overridden notifications at lower context

  Scenario: Admin edit inherited notification at the system context
    Given I log in as "admin"
    And I navigate to system notifications page

    When I click on "Totara comment" "button"
    Then I should see "New comment created"
    And "New comment created details" "button" should exist
    And "Actions for New comment created event" "button" should exist

    When I click on "Actions for New comment created event" "button"
    Then I should see "Create notification"
    And "Create notification" "link" should exist

    When I click on "New comment created details" "button"
    Then I should see "Comment created"

    When I click on "Actions for Comment created" "button"
    Then I should see "Edit"
    And "Edit" "link" should exist

    When I click on "Edit" "link"
    Then I should not see "Overridden subject at system"
    And I should not see "Overridden body at system"
    And "Enable customising field recipient" "button" should exist
    And "Enable customising field subject" "button" should exist
    And "Enable customising field schedule" "button" should exist
    And "Enable customising field body" "button" should exist
    And "Enable customising notification status" "button" should exist
    And the "Recipient" "field" should be disabled
    And the "Name" "field" should be disabled
    And the "On notification trigger event" "field" should be disabled
    And the "Days after" "field" should be disabled
    And the "Enabled" "field" should be disabled

    When I click on the "Enable customising field recipient" tui toggle button
    And I click on the "Enable customising field subject" tui toggle button
    And I click on the "Enable customising field schedule" tui toggle button
    And I click on the "Enable customising field body" tui toggle button
    And I click on the "Enable customising notification status" tui toggle button
    Then the "Recipient" "field" should be enabled
    And the "On notification trigger event" "field" should be enabled
    And the "Days after" "field" should be enabled
    And the "Enabled" "field" should be enabled

    When I set the field with xpath "//select[@class='tui-select__input']" to "Owner"
    And I set the weka editor with css ".tui-notificationPreferenceForm__subjectEditor" to "Overridden subject at system"
    And I set the weka editor with css ".tui-notificationPreferenceForm__bodyEditor" to "Overridden body at system"
    And I click on the "Days after" tui radio
    And I set the field "Number" to "3"
    # The status field that handled by TUI form. At this point it does not understand the label associated with it.
    # Hence we are going to have to use the checkbox's field name.
    And I click on the "enabled[value]" tui checkbox
    And I click on "Save" "button"
    And I click on "Actions for Comment created" "button"
    Then I click on "Edit" "link"
    Then the "Recipient" "field" should be enabled
    And the "On notification trigger event" "field" should be enabled
    And the "Days after" "field" should be enabled
    And the "Enabled" "field" should be enabled
    And the field "Recipient" matches value "Owner"
    And I should see "Overridden subject at system"
    And I should see "Overridden body at system"
    And the field "Number" matches value "3"
    # By default the notification preference is enabled, hence after click should result it to be disabled.
    And the field "Enabled" matches value "0"

    When I click on the "Enable customising field recipient" tui toggle button
    And I click on the "Enable customising field subject" tui toggle button
    And I click on the "Enable customising field body" tui toggle button
    And I click on the "Enable customising field schedule" tui toggle button
    And I click on the "Enable customising notification status" tui toggle button
    Then I should not see "Overridden subject at system"
    And I should not see "Overridden body at system"

    When I click on "Save" "button"
    And I click on "Actions for Comment created" "button"
    And I click on "Edit" "link"
    Then I should not see "Overridden subject at system"
    And I should not see "Overridden body at system"
    And the field "Number" does not match value "3"
    # Default of status is enabled.
    And the field "Enabled" matches value "1"