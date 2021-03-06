@mod @mod_assign
Feature: Switch role does not cause an error message in assignsubmission_comments

  Background:
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "users" exist:
      | username |
      | teacher1 |
    And the following "course enrolments" exist:
      | course | user     | role           |
      | C1     | teacher1 | editingteacher |
    And the following "permission overrides" exist:
      | capability              | permission | role           | contextlevel | reference |
      | moodle/role:switchroles | Allow      | editingteacher | System       |           |
    And I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    And I add a "Assignment" to section "1" and I fill the form with:
      | Assignment name           | Test assignment              |
      | Description               | This is the description text |
      | Learners submit in groups | Yes                          |

  Scenario: I switch role to student and an error doesn't occur
    When I navigate to "Switch role to..." node in "Course administration"
    And I set the following fields to these values:
      | Role | Student |
    And I press "Save changes"
    And I follow "Test assignment"
    Then I should see "This is the description text"
