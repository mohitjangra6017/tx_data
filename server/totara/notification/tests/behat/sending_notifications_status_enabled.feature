@totara @totara_notification @javascript @vuejs @engage_article
Feature: Notifications are not sent when notifiable event status is disabled

  Background:
    Given I log in as "admin"
    And the following "users" exist:
      | firstname | lastname | username | email           |
      | User      | One      | one      | one@example.com |
      | User      | Two      | two      | two@example.com |
    And the following "topics" exist in "totara_topic" plugin:
      | name   |
      | Topic1 |

    And the following "articles" exist in "engage_article" plugin:
      | name           | username | content            | access | topics |
      | Test Article 1 | one      | My Test Article    | PUBLIC | Topic1 |
    And I log out

  Scenario: Notifications are sent when notifiable event status is enabled
    Given I log in as "admin"
    And I navigate to system notifications page
    And I click on "Totara comment" "button"
    Then ".tui-toggleSwitch__btn[aria-pressed][aria-label='New comment created notification status']" "css_element" should exist

    # Make the comment
    When I log out
    And I log in as "two"
    And I view article "Test Article 1"
    And I follow "Comments"
    Then I should not see "This is comment"
    When I set the weka editor with css ".tui-commentForm__editor" to "This is comment"
    And I click on "Post" "button"
    Then I should see "This is comment"
    And I log out

    # Check the notification
    And I log in as "admin"
    And I reset the email sink
    And I trigger cron
    Then the following emails should have been sent:
      | To              | Subject         | Body                               |
      | one@example.com | Comment created | A new comment created on your item |

  Scenario: Notifications are not sent when notifiable event status is disabled
    Given I log in as "admin"
    And I navigate to system notifications page
    And I click on "Totara comment" "button"
    When I click on the "New comment created notification status" tui toggle button
    And I log out

    When I log in as "two"
    And I view article "Test Article 1"
    And I follow "Comments"
    Then I should not see "This is comment"
    When I set the weka editor with css ".tui-commentForm__editor" to "This is comment"
    And I click on "Post" "button"
    Then I should see "This is comment"
    And I log out

    # Check the notification
    And I log in as "admin"
    And I reset the email sink
    And I trigger cron
    Then the following emails should not have been sent:
      | To              | Subject         | Body                               |
      | one@example.com | Comment created | A new comment created on your item |

  Scenario: Notifications are sent when notifiable event status is enabled for a user
    Given I log in as "admin"
    And I navigate to system notifications page
    And I click on "Totara comment" "button"
    Then ".tui-toggleSwitch__btn[aria-pressed][aria-label='New comment created notification status']" "css_element" should exist

    # Check the user has the preference enabled
    When I log out
    And I log in as "one"
    And I follow "Preferences" in the user menu
    And I follow "Notification preferences"
    And I click on "Totara comment" "button"
    Then ".tui-toggleSwitch__btn[aria-pressed][aria-label='New comment created notification status']" "css_element" should exist

    # Make the comment
    When I log out
    And I log in as "two"
    And I view article "Test Article 1"
    And I follow "Comments"
    Then I should not see "This is comment"
    When I set the weka editor with css ".tui-commentForm__editor" to "This is comment"
    And I click on "Post" "button"
    Then I should see "This is comment"
    And I log out

    # Check the notification
    And I log in as "admin"
    And I reset the email sink
    And I trigger cron
    Then the following emails should have been sent:
      | To              | Subject         | Body                               |
      | one@example.com | Comment created | A new comment created on your item |

  Scenario: Notifications are not sent when notifiable event status is disabled for a user
    Given I log in as "admin"
    And I navigate to system notifications page
    And I click on "Totara comment" "button"
    Then ".tui-toggleSwitch__btn[aria-pressed][aria-label='New comment created notification status']" "css_element" should exist

    When I log out
    And I log in as "one"
    And I follow "Preferences" in the user menu
    And I follow "Notification preferences"
    And I click on "Totara comment" "button"
    And I click on the "New comment created notification status" tui toggle button
    And I log out
    And I log in as "two"
    And I view article "Test Article 1"
    And I follow "Comments"
    Then I should not see "This is comment"

    When I set the weka editor with css ".tui-commentForm__editor" to "This is comment"
    And I click on "Post" "button"
    Then I should see "This is comment"

    # Check the notification
    When I log out
    And I log in as "admin"
    And I reset the email sink
    And I trigger cron
    Then the following emails should not have been sent:
      | To              | Subject         | Body                               |
      | one@example.com | Comment created | A new comment created on your item |