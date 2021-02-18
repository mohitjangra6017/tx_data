@totara @totara_notification @javascript @vuejs
Feature: Overridden notifications at lower context

  Scenario: Admin edit inherrited notification at the system context
    Given I log in as "admin"
    And I navigate to system notifications page
    When I click on "Totara comment details" "button"
    Then I should see "New comment created"
    And "New comment created details" "button" should exist
    And "Edit notification Comment created" "button" should not exist
    And I click on "New comment created details" "button"
    Then I should see "Comment created"
    And "Edit notification Comment created" "button" should exist
    And "Enable customizing field subject" "button" should not exist
    And "Enable customizing field body" "button" should not exist
    When I click on "Edit notification Comment created" "button"
    Then I should not see "Overridden subject at system"
    And I should not see "Overridden body at system"
    And "Enable customizing field subject" "button" should exist
    And the "Subject" "field" should be disabled
    And the "Name" "field" should be disabled
    And "Enable customizing field body" "button" should exist
    When I click on the "Enable customizing field subject" tui toggle button
    Then the "Subject" "field" should be enabled
    And I set the field "Subject" to "Overridden subject at system"
    When I click on the "Enable customizing field body" tui toggle button
    And I set the field with css ".tui-notificationPreferenceForm__editor textarea" to "Overridden body at system"
    And I click on "Save" "button"
    When I click on "Edit notification Comment created" "button"
    And the field "Subject" matches value "Overridden subject at system"
    And the field with xpath "//textarea[@class='tui-formTextarea tui-editorTextarea__textarea']" matches value "Overridden body at system"

    And the "Subject" "field" should be enabled
    When I click on the "Enable customizing field subject" tui toggle button
    And I click on the "Enable customizing field body" tui toggle button
    And the field "Subject" does not match value "Overridden subject at system"
    And the field with xpath "//textarea[@class='tui-formTextarea tui-editorTextarea__textarea']" does not match value "Overridden body at system"

    And I click on "Save" "button"
    When I click on "Edit notification Comment created" "button"
    And the field "Subject" does not match value "Overridden subject at system"
    And the field with xpath "//textarea[@class='tui-formTextarea tui-editorTextarea__textarea']" does not match value "Overridden body at system"