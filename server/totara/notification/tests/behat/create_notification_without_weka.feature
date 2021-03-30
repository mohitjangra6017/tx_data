@totara @totara_notification @javascript @vuejs @editor
Feature: Produce notification's content without weka editor
  Background:
    Given I log in as "admin"
    And I navigate to "Plugins > Text editors > Manage editors" in site administration
    And I click on "Disable" "link" in the "Weka editor" "table_row"

  Scenario: Create a notification preference record with disabled weka editor
    Given I navigate to system notifications page
    And I click on "Totara comment" "button"
    And I click on "Actions for New comment created event" "button"
    When I click on "Create notification" "link"
    Then "div.tui-notificationPreferenceForm__subjectEditor textarea" "css_element" should exist
    And "div.tui-notificationPreferenceForm__bodyEditor textarea" "css_element" should exist
    And I set the field "Recipient" to "Comment author"
    And I set the field "Name" to "Custom notification"
    And I set the field with css "div.tui-notificationPreferenceForm__subjectEditor textarea" to "Custom notification subject"
    And I set the field with css "div.tui-notificationPreferenceForm__bodyEditor textarea" to "Custom notification body"
    And I click on the "Days after" tui radio
    And I set the field "Number" to "55"
    And I click on "Save" "button"
    And I click on "New comment created details" "button"
    Then I should see "Custom notification"
    And I click on "Actions for Custom notification" "button"
    When I click on "Edit notification Custom notification" "link"
    Then I should see "55"
    And I should see "Comment author"
    And the field with xpath "//div[contains(@class, 'tui-notificationPreferenceForm__subjectEditor')]/textarea" matches value "Custom notification subject"
    And the field with xpath "//div[contains(@class, 'tui-notificationPreferenceForm__bodyEditor')]/textarea" matches value "Custom notification body"

  Scenario: Update a built in notification preference with disabled weka editor
    Given I navigate to system notifications page
    And I click on "Totara comment" "button"
    And I click on "Actions for New comment created event" "button"
    And I click on "New comment created details" "button"
    And I click on "Actions for Comment created" "button"
    When I click on "Edit notification Comment created" "link"
    Then "div.tui-notificationPreferenceForm__subjectEditor textarea" "css_element" should exist
    And "div.tui-notificationPreferenceForm__bodyEditor textarea" "css_element" should exist
    And I click on the "override_subject" tui checkbox
    And I click on the "override_body" tui checkbox
    And I set the field with css "div.tui-notificationPreferenceForm__subjectEditor textarea" to "Override notification subject"
    And I set the field with css "div.tui-notificationPreferenceForm__bodyEditor textarea" to "Override notification body"
    And I click on "Save" "button"
    And I click on "Actions for Comment created" "button"
    When I click on "Edit notification Comment created" "link"
    And the field with xpath "//div[contains(@class, 'tui-notificationPreferenceForm__subjectEditor')]/textarea" matches value "Override notification subject"
    And the field with xpath "//div[contains(@class, 'tui-notificationPreferenceForm__bodyEditor')]/textarea" matches value "Override notification body"