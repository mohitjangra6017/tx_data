@totara @totara_notification @javascript @vuejs
Feature: Notifications delivery channels
  As a notifications administrator
  I can change the default delivery channels set on a notifiable event
  so they can be available to be overridden by users.

  Scenario: Delivery channels are visible and configurable at the admin level
    Given I log in as "admin"
    And I navigate to system notifications page
    Then I should see "Totara comment"

    When I click on "Totara comment details" "button"
    Then I should see "New comment create"
    And I should see "Delivery channels"
    And I should see "Site notifications; Mobile app notifications; Tasks; Alerts; Email; Microsoft Teams"

    When I click on "more" "button"
    And I click on "Delivery preferences" "link"
    Then I should see "Delivery preferences" in the ".tui-modalContent__header-title" "css_element"
    And I should see "Notification trigger: New comment created" in the ".tui-modalContent__content" "css_element"
    And the field "Site notifications" matches value "1"
    And the field "Mobile app notifications" matches value "1"
    And the field "Tasks" matches value "1"
    And the field "Alerts" matches value "1"
    And the field "Email" matches value "1"
    And the field "Microsoft Teams" matches value "1"

    # Check that the parent/child toggle works
    When I click on the "default_popup" tui checkbox
    Then the field "Site notifications" matches value "0"
    And "Mobile app notifications" "checkbox" should not exist
    And "Tasks" "checkbox" should not exist
    And "Alerts" "checkbox" should not exist
    And the field "Microsoft Teams" matches value "1"

    # Save the changes
    When I click on "Save" "button"
    And I wait for the next second
    Then I should see "Email; Microsoft Teams"

  Scenario: Delivery channels are visible but not configurable at the course level
    Given I log in as "admin"
    And the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And I am on "Course 1" course homepage
    When I navigate to "Notifications" node in "Course administration"
    Then I should see "Notifications"

    When I click on "Totara comment details" "button"
    Then I should see "New comment create"
    And I should see "Delivery channels"
    And I should see "Site notifications; Mobile app notifications; Tasks; Alerts; Email; Microsoft Teams"

    When I click on "more" "button"
    Then I should not see "Delivery preferences"