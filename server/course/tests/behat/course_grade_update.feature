@core @core_course @totara @javascript
Feature: Adding course's activity should require cron to run.
  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email           |
      | user_one | User      | One      | one@example.com |
      | user_two | User      | Two      | two@example.com |
    And the following "courses" exist:
      | shortname | fullname   | idnumber | enablecompletion |
      | c101      | Course 101 | c101     | 1                |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | user_two | c101   | editingteacher |
      | user_one | c101   | student        |

  Scenario: Adding course activity should notify the regrade for admin user.
    Given I am on a totara site
    And I log in as "admin"
    And I am on "Course 101" course homepage
    And I turn editing mode on
    And I should not see "Grades are now being re-aggregated due to the additional activity."
    When I add a "Assignment" to section "1" and I fill the form with:
      | Assignment name     | Ass 1                                             |
      | Completion tracking | Show activity as complete when conditions are met |
      | completionusegrade  | 1                                                 |
    Then I should see "Grades are now being re-aggregated due to the additional activity."
    And I turn editing mode off
    And I log out
    And I log in as "user_one"
    When I am on "Course 101" course homepage
    Then I should not see "Grades are now being re-aggregated due to the additional activity."
    And I log out
    And I log in as "user_two"
    When I am on "Course 101" course homepage
    Then I should see "Grades are now being re-aggregated due to the additional activity."