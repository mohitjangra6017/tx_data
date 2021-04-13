@totara @totara_notification @javascript @vuejs
Feature: Notifications delivery channels
  As a notifications administrator
  I can change the default delivery channels set on a notifiable event
  so they can be available to be overridden by users.

  Scenario: Delivery channels are visible and configurable at the admin level
    Given I log in as "admin"
    And I navigate to system notifications page
    Then I should see "Totara comment"

    When I click on "Totara comment" "button"
    Then I should see "New comment create"
    And I should see "Default delivery channels"

    When I click on "more" "button"
    And I click on "Edit delivery channels" "link"
    Then I should see "Edit delivery channels" in the ".tui-modalContent__header-title" "css_element"
    And I should see "Notification trigger: New comment created" in the ".tui-modalContent__content" "css_element"
    And the field "Site notifications" matches value "1"
    And the field "Mobile app notifications" matches value "0"
    And the field "Tasks" matches value "0"
    And the field "Alerts" matches value "0"
    And the field "Email" matches value "1"
    And the field "Microsoft Teams" matches value "0"

    # Check that the parent/child toggle works
    When I click on the "default_popup" tui checkbox
    Then the field "Site notifications" matches value "0"
    And "Mobile app notifications" "checkbox" should not exist
    And "Tasks" "checkbox" should not exist
    And "Alerts" "checkbox" should not exist
    And the field "Microsoft Teams" matches value "0"

    # Save the changes
    When I click on "Save" "button"
    And I wait for the next second
    And I click on "more" "button"
    And I click on "Edit delivery channels" "link"
    Then the field "Site notifications" matches value "0"
    And "Mobile app notifications" "checkbox" should not exist
    And "Tasks" "checkbox" should not exist
    And "Alerts" "checkbox" should not exist
    And the field "Microsoft Teams" matches value "0"

  Scenario: Delivery channels are visible but not configurable at the course level
    Given I log in as "admin"
    And the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And I am on "Course 1" course homepage
    When I navigate to "Notifications" node in "Course administration"
    Then I should see "Notifications"

    When I click on "Totara comment" "button"
    Then I should see "New comment create"
    And I should see "Default delivery channels"
    When I click on "more" "button"
    Then I should not see "Delivery preferences"

  Scenario: Delivery channels are visible and overridable at the user preference level
    Given I log in as "admin"
    And I follow "Preferences" in the user menu
    And I follow "Notification preferences"
    Then I should see "Notification preferences"
    And I should see "Totara comment"

    When I click on "Totara comment" "button"
    Then I should see "New comment create"
    And I should see "Default delivery channels"
    And I should see "Site notifications; Email"

    When I click on "more" "button"
    And I click on "Edit delivery channels" "link"
    Then I should see "Delivery preferences" in the ".tui-modalContent__header-title" "css_element"
    And I should see "Notification trigger: New comment created" in the ".tui-modalContent__content" "css_element"
    And the field "Override" matches value "0"
    And the "Site notifications" "checkbox" should be disabled
    And the "Mobile app notifications" "checkbox" should be disabled
    And the "Tasks" "checkbox" should be disabled
    And the "Alerts" "checkbox" should be disabled
    And the "Email" "checkbox" should be disabled
    And the "Microsoft Teams" "checkbox" should be disabled

    When I click on the "override_delivery_preferences" tui checkbox
    Then the "Site notifications" "checkbox" should be enabled
    And the "Mobile app notifications" "checkbox" should be enabled
    And the "Tasks" "checkbox" should be enabled
    And the "Alerts" "checkbox" should be enabled
    And the "Email" "checkbox" should be enabled
    And the "Microsoft Teams" "checkbox" should be enabled

    # Turn the override back off
    When I click on the "override_delivery_preferences" tui checkbox
    Then the "Site notifications" "checkbox" should be disabled
    And the "Email" "checkbox" should be disabled
    And the "Microsoft Teams" "checkbox" should be disabled