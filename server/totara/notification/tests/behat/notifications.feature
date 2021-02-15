@totara @totara_notification @javascript @vuejs
Feature: Notifications page
  As an notifications administrator
  I need to be able to view notifications and manage notifications
  so they can be available to users.

  Scenario Admin is able to view notifications page
    Given I log in as "admin"
    And I navigate to system notifications page
    Then I should see "Notifications" in the ".tui-notificationHeader" "css_element"
    And I should see " Totara comment"
    When I click on "Totara comment details" "button"
    Then I should see "New comment create"
    When I click on "New comment created details" "button"
    Then I should see "Comment created"

  Scenario: Admin is able to create/update custom notification
    Given I log in as "admin"
    And I navigate to system notifications page
    And I should not see "comment created"
    When I click on "Totara comment details" "button"
    Then I should see "New comment created"
    When I click on "Create notification" "button"
    Then I should see "New comment created: Create notification" in the ".tui-modalContent__header-title" "css_element"
    And I click on "Close" "button"
    When I click on "more" "button"
    Then I should see "Create notification"
    When I click on "Create notification" "link"
    Then I should see "New comment created: Create notification" in the ".tui-modalContent__header-title" "css_element"
    And I set the field "Name" to "Test custom notification name"
    And I set the field "Notification subject" to "Test custom notification subject"
    And I set the field with xpath "//textarea[@class='tui-formTextarea tui-editorTextarea__textarea']" to "Test custom notification body"
    And I click on "Save" "button"
    And I navigate to system notifications page
    And I click on "Totara comment details" "button"
    When I click on "New comment created details" "button"
    Then I should see "Test custom notification name"

  Scenario: Admin is able to create custom notification in context notification page
    Given I log in as "admin"
    And I navigate to context notifications page
    And I click on "Totara comment details" "button"
    And I click on "Create notification" "button"
    And I set the field "Name" to "Test context notification name"
    And I set the field "Notification subject" to "Test context notification subject"
    And I set the field with xpath "//textarea[@class='tui-formTextarea tui-editorTextarea__textarea']" to "Test context notification body"
    And I click on "Save" "button"
    When I click on "New comment created details" "button"
    Then I should see "Test context notification name"

    And I navigate to system notifications page with context id
    And I click on "Totara comment details" "button"
    When I click on "New comment created details" "button"
    Then I should not see "Test context notification name"