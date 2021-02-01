@totara @totara_notification
Feature: Notifications page
  As an notifications administrator
  I need to be able to view notifications and manage notifications
  so they can be available to users.

  @javascript @vuejs
  Scenario: I am able to vie notifications page
    Given I log in as "admin"
    And I view notifications page
    Then I should see "Notifications" in the ".tui-notificationHeader" "css_element"
    And I should see "Events and notifications"
    And I should see " Totara comment"
    When I click on "Totara comment details" "button"
    Then I should see "New comment create"
    When I click on "New comment created details" "button"
    Then I should see "Comment created"