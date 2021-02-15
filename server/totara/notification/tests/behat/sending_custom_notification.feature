@totara @totara_notification @javascript @vuejs @engage_article
Feature: Sending custom notifications to user
  Background:
    Given I log in as "admin"
    And the following "users" exist:
      | firstname | lastname | username | email           |
      | User      | One      | one      | one@example.com |
    And the following "topics" exist in "totara_topic" plugin:
      | name   |
      | Topic1 |

    And the following "articles" exist in "engage_article" plugin:
      | name           | username | content | access | topics |
      | Test Article 1 | admin    | blah    | PUBLIC | Topic1 |
    And I log out

  Scenario: Sending notifications to user on created comment should included the custom notification
    Given I log in as "admin"
    And I navigate to system notifications page
    And I click on "Totara comment details" "button"
    And I click on "New comment created details" "button"
    And I click on "Create notification" "button"
    And I set the field "Name" to "Custom notification one"
    And I set the field "Subject" to "Test custom notification subject"
    And I set the field with xpath "//textarea[@class='tui-formTextarea tui-editorTextarea__textarea']" to "Test custom notification body"
    When I click on "Save" "button"
    Then I should see "Custom notification one"
    And I log out
    And I log in as "one"
    And I view article "Test Article 1"
    And I follow "Comments"
    And I should not see "This is comment"
    And I set the weka editor with css ".tui-commentForm__editor" to "This is comment"
    When I click on "Post" "button"
    Then I should see "This is comment"
    And I log out
    And I log in as "admin"
    And I reset the email sink
    When I trigger cron
    Then the message "Test custom notification subject" contains "custom notification body" for "admin" user
    And the following emails should have been sent:
      | To                 | Subject                          | Body                          |
      | moodle@example.com | Test custom notification subject | Test custom notification body |
    # Note that moodle@example.com is the default email for admin. We will be sure that this
    # value will stay forever.

  Scenario: Sending notification to user on created comment should not use the overridden value at lower context
    Given I log in as "admin"
    And I navigate to system notifications page
    And I click on "Totara comment details" "button"
    And I click on "New comment created details" "button"
    And I click on "Create notification" "button"
    And I set the field "Name" to "Custom notification one"
    And I set the field "Subject" to "Custom notification subject"
    And I set the field with css ".tui-notificationPreferenceForm__editor textarea" to "Custom notification body"
    And I click on "Save" "button"
    And the following "courses" exist:
      | fullname | shortname | format |
      | Course 1 | c101      | topics |

    And I navigate to notifications page of "course" "c101"
    And I click on "Totara comment details" "button"
    When I click on "New comment created details" "button"
    Then I should see "Custom notification one"
    And I click on "Edit notification Custom notification one" "button"
    And the "Subject" "field" should be disabled
    When I click on the "Enable customizing field subject" tui toggle button
    And the "Subject" "field" should be enabled
    And I set the field "Subject" to "Custom notification at course context"
    And I click on "Save" "button"
    And I log out
    And I log in as "one"
    And I view article "Test Article 1"
    And I follow "Comments"
    And I should not see "This is comment"
    And I set the weka editor with css ".tui-commentForm__editor" to "This is comment"
    When I click on "Post" "button"
    Then I should see "This is comment"
    And I log out
    And I log in as "admin"
    And I reset the email sink
    When I trigger cron
    Then the following emails should not have been sent:
      | To                 | Subject                          | Body                          |
      | moodle@example.com | Custom notification at course context | Custom notification body |
    And the message "Custom notification subject" contains "Custom notification body" for "admin" user
    And the following emails should have been sent:
      | To                 | Subject                          | Body                          |
      | moodle@example.com | Custom notification subject | Custom notification body |
