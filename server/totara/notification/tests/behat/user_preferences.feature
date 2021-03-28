@totara @totara_notification @javascript @vuejs
Feature: User notifications preferences
  As a user
  I need to be able to set notifications preferences
  so I receive my desired notifications.

  Background:
    Given the following "users" exist:
      | firstname | lastname | username | email           |
      | User      | One      | user1    | one@example.com |

  Scenario: User can access notification preferences
    When I log in as "user1"
    And I follow "Preferences" in the user menu
    And I follow "Notification preferences"
    Then I should see "Notification preferences"
    And I should see "Totara comment"
    When I click on "Totara comment details" "button"
    Then I should see "Comment deleted"
    # Check enabled toggle switch
#    When I click on the "Is enabled" tui toggle button
