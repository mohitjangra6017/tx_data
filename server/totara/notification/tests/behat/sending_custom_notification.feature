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
    When I log in as "admin"
    And I navigate to system notifications page
    And I click on "Totara comment" "button"
    And I click on "New comment created details" "button"
    When I click on "Actions for New comment created event" "button"
    And I click on "Create notification" "link"
    And I set the field with xpath "//select[@class='tui-select__input']" to "Owner"
    And I set the field "Name" to "Custom notification one"
    And I set the weka editor with css ".tui-notificationPreferenceForm__subjectEditor" to "Test custom notification subject"
    And I set the weka editor with css ".tui-notificationPreferenceForm__bodyEditor" to "Test custom notification body"
    # The status field that handled by TUI form. At this point it does not understand the label associated with it.
    # Hence we are going to have to use the checkbox's field name.
    And I click on the "enabled[value]" tui checkbox
    And I click on "Save" "button"
    Then I should see "Custom notification one"

    When I log out
    And I log in as "one"
    And I view article "Test Article 1"
    And I follow "Comments"
    And I should not see "This is comment"
    And I set the weka editor with css ".tui-commentForm__editor" to "This is comment"
    And I click on "Post" "button"
    Then I should see "This is comment"

    When I log out
    And I log in as "admin"
    And I reset the email sink
    And I trigger cron
    Then the message "Test custom notification subject" contains "Test custom notification body" for "admin" user
    And the following emails should have been sent:
      | To                 | Subject                          | Body                          |
      | moodle@example.com | Test custom notification subject | Test custom notification body |
    # Note that moodle@example.com is the default email for admin. We will be sure that this
    # value will stay forever.

    # Comment author will not receive the notification
    When I am on site homepage
    And I log out
    And I log in as "one"
    And I click on ".popover-region-notifications" "css_element"
    Then I should not see "Comment created"
    And I should not see "Test custom notification subject"

  Scenario: Sending notification to user on created comment should not use the overridden value at lower context
    Given I log in as "admin"
    And I navigate to system notifications page
    And I click on "Totara comment" "button"
    And I click on "New comment created details" "button"
    When I click on "Actions for New comment created event" "button"
    And I click on "Create notification" "link"
    And I set the field with xpath "//select[@class='tui-select__input']" to "Owner"
    And I set the field "Name" to "Custom notification one"
    And I set the weka editor with css ".tui-notificationPreferenceForm__subjectEditor" to "Custom notification subject"
    And I set the weka editor with css ".tui-notificationPreferenceForm__bodyEditor" to "Custom notification body"
    # The status field that handled by TUI form. At this point it does not understand the label associated with it.
    # Hence we are going to have to use the checkbox's field name.
    And I click on the "enabled[value]" tui checkbox
    And I click on "Save" "button"
    And the following "courses" exist:
      | fullname | shortname | format |
      | Course 1 | c101      | topics |
    And I navigate to notifications page of "course" "c101"
    And I click on "Totara comment" "button"

    When I click on "New comment created details" "button"
    Then I should see "Custom notification one"
    When I click on "Actions for Custom notification one" "button"
    Then I should see "Edit"
    And I click on "Edit notification Custom notification one" "link"
    And "override_recipient" "checkbox" should exist

    When I click on the "override_recipient" tui checkbox
    Then the "Recipient" "field" should be enabled

    When I click on the "override_subject" tui checkbox
    And I set the weka editor with css ".tui-notificationPreferenceForm__subjectEditor" to "Custom notification at course context"
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
      | To                 | Subject                               | Body                     |
      | moodle@example.com | Custom notification at course context | Custom notification body |
    And the message "Custom notification subject" contains "Custom notification body" for "admin" user
    And the following emails should have been sent:
      | To                 | Subject                     | Body                     |
      | moodle@example.com | Custom notification subject | Custom notification body |

  Scenario: Sending notifications to comment author
    Given I log in as "admin"
    And I navigate to system notifications page
    And I click on "Totara comment" "button"
    And I click on "New comment created details" "button"
    When I click on "Actions for New comment created event" "button"
    And I click on "Create notification" "link"
    And I set the field with xpath "//select[@class='tui-select__input']" to "Comment author"
    And I set the field "Name" to "Custom notification one"
    And I set the weka editor with css ".tui-notificationPreferenceForm__subjectEditor" to "Test custom notification subject"
    And I set the weka editor with css ".tui-notificationPreferenceForm__bodyEditor" to "Test custom notification body"
    # The status field that handled by TUI form. At this point it does not understand the label associated with it.
    # Hence we are going to have to use the checkbox's field name.
    And I click on the "enabled[value]" tui checkbox
    And I click on "Save" "button"
    And I log out

    Given I log in as "one"
    And I view article "Test Article 1"
    And I follow "Comments"
    And I set the weka editor with css ".tui-commentForm__editor" to "This is comment"
    And I click on "Post" "button"
    And I log out

    When I log in as "admin"
    And I reset the email sink
    And I trigger cron
    Then the following emails should not have been sent:
      | To                 | Subject                          | Body                          |
      | moodle@example.com | Test custom notification subject | Test custom notification body |
    And the following emails should have been sent:
      | To                 | Subject                          | Body                               |
      | moodle@example.com | Comment created                  | A new comment created on your item |
      | one@example.com    | Test custom notification subject | Test custom notification body      |

    # Resource owner should received the built-in notification but not the custom one
    When I am on site homepage
    And I click on ".popover-region-notifications" "css_element"
    And I click on "View full notification" "link" in the ".popover-region-notifications" "css_element"
    Then I should see "Comment created"
    And I should not see "Test custom notification subject"

    # Comment author will receive the custom notification
    When I log out
    And I log in as "one"
    And I click on ".popover-region-notifications" "css_element"
    And I click on "View full notification" "link" in the ".popover-region-notifications" "css_element"
    Then I should see "Test custom notification subject"
    And I should not see "Comment created"