@totara @totara_notification @javascript @vuejs
Feature: Course notifications node
  As an notifications administrator
  I need to be able to view notifications and manage notifications

  Scenario: Admin is able to create/update custom notification
    Given I log in as "admin"
    And the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And I am on "Course 1" course homepage
    When I navigate to "Notifications" node in "Course administration"
    Then I should see "Notifications"

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
