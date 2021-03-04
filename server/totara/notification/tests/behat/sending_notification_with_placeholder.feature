@totara @totara_notification @javascript @vuejs @engage_article
Feature: Sending notification with placeholders
  Background:
    Given I log in as "admin"
    And the following "users" exist:
      | firstname | lastname | username | email           |
      | One       | User     | one      | one@example.com |
      | Two       | User     | two      | two@example.com |
    And the following "topics" exist in "totara_topic" plugin:
      | name   |
      | Topic1 |

    And the following "articles" exist in "engage_article" plugin:
      | name           | username | content | access | topics |
      | Test Article 1 | one      | blah    | PUBLIC | Topic1 |
    And I log out

  Scenario: Sending overridden built-in notification to user on created comment with placeholder
    Given I log in as "admin"
    And I navigate to system notifications page
    And I click on "Totara comment details" "button"
    And I click on "New comment created details" "button"
    And I click on "Edit notification Comment created" "button"
    And I click on the "Enable customising field subject" tui toggle button
    And I set the weka editor with css ".tui-notificationPreferenceForm__subjectEditor" to ""
    And I activate the weka editor with css ".tui-notificationPreferenceForm__subjectEditor"
    And I type "New comment from " in the weka editor
    When I type "[commenter:" in the weka editor
    Then I should see "Commenter's First name"
    And I should see "Commenter's Surname"
    And I should see "Commenter's Full name"
    When I click on "Commenter's First name" "link"
    # They are concatenated string with <span\> for the placeholder - hence this is the only to
    # check for the value in weka editor.
    Then I should see "New comment from " in the ".tui-notificationPreferenceForm__subjectEditor" "css_element"
    And I should see "Commenter's First name" in the ".tui-notificationPreferenceForm__subjectEditor" "css_element"
    And I should not see "Commenter's Surname"
    And I should not see "Commenter's Full name"
    And I click on the "Enable customising field body" tui toggle button
    And I set the weka editor with css ".tui-notificationPreferenceForm__bodyEditor" to ""
    And I activate the weka editor with css ".tui-notificationPreferenceForm__bodyEditor"
    And I type "Hello user " in the weka editor
    When I type "[item_owner:" in the weka editor
    Then I should see "Item owner's First name"
    And I should see "Item owner's Surname"
    And I should see "Item owner's Full name"
    When I click on "Item owner's Full name" "link"
     # They are concatenated string with <span\> for the placeholder - hence this is the only to
    # check for the value in weka editor.
    Then I should see "Hello user " in the ".tui-notificationPreferenceForm__bodyEditor" "css_element"
    And I should see "Item owner's Full name" in the ".tui-notificationPreferenceForm__bodyEditor" "css_element"
    And I type " you have new comment" in the weka editor
    And I click on "Save" "button"
    And I log out
    And I log in as "two"
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
      | To              | Subject                                | Body                                                  |
      | one@example.com | New comment from [commenter:firstname] | Hello user [item_owner:fullname] you have new comment |
    And the following emails should have been sent:
      | To              | Subject              | Body                                     |
      | one@example.com | New comment from Two | Hello user One User you have new comment |
    And the message "New comment from Two" contains "Hello user One User you have new comment" for "one" user
