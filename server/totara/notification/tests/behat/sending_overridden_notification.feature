@totara @totara_notification @javascript @vuejs @engage_article
Feature: Sending overridden notification

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
      | name           | username | content | access | topics |
      | Test Article 1 | one      | blah    | PUBLIC | Topic1 |
    And I log out

  Scenario: Sending overridden built-in notification to user on created comment
    Given I log in as "admin"
    And I navigate to system notifications page
    And I click on "Totara comment details" "button"
    And I click on "New comment created details" "button"
    And I click on "Edit notification Comment created" "button"
    Then the "Recipient" "field" should be disabled

    When I click on the "Enable customising field recipient" tui toggle button
    Then the "Recipient" "field" should be enabled
    And  I set the field with xpath "//select[@class='tui-select__input']" to "Owner"
    And I click on the "Enable customising field subject" tui toggle button
    And I set the weka editor with css ".tui-notificationPreferenceForm__subjectEditor" to "Overridden subject at system"
    And I click on "Save" "button"
    And I log out

    # Make the comment
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
    Then the following emails should not have been sent:
      | To              | Subject         | Body                               |
      | one@example.com | Comment created | A new comment created on your item |
    And the following emails should have been sent:
      | To              | Subject                      | Body                               |
      | one@example.com | Overridden subject at system | A new comment created on your item |
    And the message "Overridden subject at system" contains "A new comment created on your item" for "one" user
