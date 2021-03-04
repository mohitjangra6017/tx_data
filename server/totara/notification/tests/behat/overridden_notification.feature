@totara @totara_notification @javascript @vuejs
Feature: Overridden notifications at lower context

  Scenario: Admin edit inherited notification at the system context
    Given I log in as "admin"
    And I navigate to system notifications page

    When I click on "Totara comment details" "button"
    Then I should see "New comment created"
    And "New comment created details" "button" should exist
    And "Create notification" "button" should exist

    When I click on "New comment created details" "button"
    Then I should see "Comment created"
    And "Edit notification Comment created" "button" should exist

    When I click on "Edit notification Comment created" "button"
    Then I should not see "Overridden subject at system"
    And I should not see "Overridden body at system"
    And "Enable customising field recipient" "button" should exist
    And "Enable customising field subject" "button" should exist
    And "Enable customising field schedule" "button" should exist
    And "Enable customising field body" "button" should exist
    And the "Recipient" "field" should be disabled
    And the "Name" "field" should be disabled
    And the "On notification trigger event" "field" should be disabled
    And the "Days after" "field" should be disabled

    When I click on the "Enable customising field recipient" tui toggle button
    And I click on the "Enable customising field subject" tui toggle button
    And I click on the "Enable customising field schedule" tui toggle button
    And I click on the "Enable customising field body" tui toggle button
    Then the "Recipient" "field" should be enabled
    And the "On notification trigger event" "field" should be enabled
    And the "Days after" "field" should be enabled

    When I set the field with xpath "//select[@class='tui-select__input']" to "Owner"
    And I set the weka editor with css ".tui-notificationPreferenceForm__subjectEditor" to "Overridden subject at system"
    And I set the weka editor with css ".tui-notificationPreferenceForm__bodyEditor" to "Overridden body at system"
    And I click on the "Days after" tui radio
    And I set the field "Number" to "3"
    And I click on "Save" "button"
    And I click on "Edit notification Comment created" "button"
    Then the "Recipient" "field" should be enabled
    And the "On notification trigger event" "field" should be enabled
    And the "Days after" "field" should be enabled
    And the field "Recipient" matches value "Owner"
    And I should see "Overridden subject at system"
    And I should see "Overridden body at system"
    And the field "Number" matches value "3"

    When I click on the "Enable customising field recipient" tui toggle button
    And I click on the "Enable customising field subject" tui toggle button
    And I click on the "Enable customising field body" tui toggle button
    And I click on the "Enable customising field schedule" tui toggle button
    Then I should not see "Overridden subject at system"
    And I should not see "Overridden body at system"

    When I click on "Save" "button"
    And I click on "Edit notification Comment created" "button"
    Then I should not see "Overridden subject at system"
    And I should not see "Overridden body at system"
    And the field "Number" does not match value "3"