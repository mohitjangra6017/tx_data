@totara @totara_notification @javascript @vuejs
Feature: Notifications page
  As a notifications administrator
  I need to be able to view notifications and manage notifications
  so they can be available to users.

  Scenario: Admin is able to view notifications page
    Given I log in as "admin"
    And I navigate to system notifications page
    Then I should see " Totara comment"

    When I click on "Totara comment details" "button"
    Then I should see "New comment create"

    When I click on "New comment created details" "button"
    Then I should see "Comment created"

  Scenario: Admin is able to create/update/delete custom notification
    Given I log in as "admin"
    And I navigate to system notifications page
    Then I should not see "comment created"

    When I click on "Totara comment details" "button"
    Then I should see "New comment created"

    When I click on "Create notification" "button"
    Then I should see "Create notification" in the ".tui-modalContent__header-title" "css_element"

    When I click on "Close" "button"
    And I click on "more" "button"
    Then I should see "Create notification"

    When I click on "Create notification" "link"
    Then I should see "Create notification" in the ".tui-modalContent__header-title" "css_element"

    When I set the field with xpath "//select[@class='tui-select__input']" to "Comment author"
    And I set the field "Name" to "Test custom notification name"
    And I set the weka editor with css ".tui-notificationPreferenceForm__subjectEditor" to "Test custom notification subject"
    And I set the weka editor with css ".tui-notificationPreferenceForm__bodyEditor" to "Test custom notification body"
    And I click on the "Days after" tui radio
    And I set the field "Number" to "7"
    And I click on "Save" "button"
    And I navigate to system notifications page
    And I click on "Totara comment details" "button"
    And I click on "New comment created details" "button"
    Then I should see "Test custom notification name"
    And I should see "Comment author"

    #Update custom notification
    When I click on "Edit notification Test custom notification name" "button"
    Then I should see "Edit notification"

    When I set the field "Name" to "New notification name"
    And I set the field "Number" to "12"
    And I set the field with xpath "//select[@class='tui-select__input']" to "Owner"
    And I click on "Save" "button"
    Then I should see "New notification name"
    And I should see "12 days after"
    And I should see "Owner"

    #Delete custom notification
    When I click on "More actions for New notification name" "button"
    And I click on "Delete" "link"
    And I should see "Delete notification: New notification name"
    And I should see "Are you sure? Deleting this notification will remove its instances in other contexts, such as categories and courses. This action cannot be undone."
    And I click on "Delete" "button"
    And I should see "Successfully deleted notification"

  Scenario: Admin is able to create custom notification in context notification page
    Given I log in as "admin"
    And the following "courses" exist:
      | fullname   | shortname | format |
      | Course 101 | c101      | topics |
    And I navigate to notifications page of "course" "c101"
    And I click on "Totara comment details" "button"
    And I click on "Create notification" "button"
    And I set the field with xpath "//select[@class='tui-select__input']" to "Comment author"
    And I set the field "Name" to "Test context notification name"
    And I set the weka editor with css ".tui-notificationPreferenceForm__subjectEditor" to "Test context notification subject"
    And I set the weka editor with css ".tui-notificationPreferenceForm__bodyEditor" to "Test context notification body"
    And I click on the "Days after" tui radio
    And I set the field "Number" to "55"
    And I click on "Save" "button"
    And I click on "New comment created details" "button"
    Then I should see "Test context notification name"
    And I should see "55"
    And I should see "Comment author"

    When I navigate to system notifications page
    And I click on "Totara comment details" "button"
    And I click on "New comment created details" "button"
    Then I should not see "Test context notification name"
    And I should not see "55"

  Scenario: Admin is able to view notifications page through admin menu
    Given I log in as "admin"
    And I click on "Show admin menu window" "button"
    When I click on "Notifications" "link" in the "#quickaccess-popover-content" "css_element"
    Then I should see "Notifications"