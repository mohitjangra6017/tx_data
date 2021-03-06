@totara @totara_plan @javascript
Feature: Verify the columns of the Record of Learning objectives report source.

  Background:
    Given I am on a totara site
    And the following "users" exist:
      | username | firstname | lastname | email                |
      | learner1 | Bob1      | Learner1 | learner1@example.com |
    And the following "plans" exist in "totara_plan" plugin:
      | user     | name            |
      | learner1 | Learning Plan 1 |
    And the following "objectives" exist in "totara_plan" plugin:
      | user     | plan            | name        |
      | learner1 | Learning Plan 1 | Objective 1 |
    And the following "standard_report" exist in "totara_reportbuilder" plugin:
      | fullname       | shortname             | source       |
      | RoL Objectives | report_rol_objectives | dp_objective |

    When I log in as "admin"
    And I navigate to "Manage user reports" node in "Site administration > Reports"
    And I follow "RoL Objectives"
    Then I should see "Edit Report 'RoL Objectives'"

    When I switch to "Columns" tab
    And I set the field "newcolumns" to "Date Created"
    And I press "Add"
    And I set the field "newcolumns" to "Date Updated"
    And I press "Add"
    And I press "Save changes"

  Scenario: Verify the objective date created column is present and correct.

    Given I follow "View This Report"
    Then I should see "1 record shown" in the ".rb-record-count" "css_element"
    # Check the created date. There won't be an updated date.
    And I should see date "today" formatted "%d %b %Y" in the "Objective 1" "table_row"

  Scenario: Verify the objective date updated column is present and correct.

    Given the "mylearning" user profile block exists
    When I navigate to "Manage users" node in "Site administration > Users"
    And I follow "Bob1 Learner1"
    And I click on "Learning Plans" "link" in the ".block_totara_user_profile_category_mylearning" "css_element"
    And I follow "Objectives (1)"
    And I set the field "menuproficiencies1" to "In Progress"
    And I navigate to "Manage user reports" node in "Site administration > Reports"
    And I follow "RoL Objectives"
    And I follow "View This Report"
    Then I should see "1 record shown" in the ".rb-record-count" "css_element"
    # Check the updated date.
    And I should see date "today" formatted "%d %b %Y" in the "//table[@id='report_rol_objectives']/tbody/tr[1]/td[9]" "xpath_element"
