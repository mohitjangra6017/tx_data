@block @block_rate_course @create_and_view_course_rating_report
Feature: User can create and view the report for course rating
  As a user enrolled into the course (learner & admin) perspective

  Background:
    Given the following "courses" exist:
      | fullname | shortname | category | groupmode |
      | Course 1 | C1 | 0 | 0 |
      | Course 2 | C2 | 0 | 0 |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | txuser1 | Testingxperts1 | User1  | txuser1@mail.com |
      | txuser2 | Testingxperts2 | User2  | txuser2@mail.com |
      | txuser3 | Testingxperts3 | User3  | txuser3@mail.com |

    And the following "course enrolments" exist:
      | user     | course | role |
      | txuser3 | C1     | admin |
      | txuser2 | C1     | learner |


    And I log in as "admin"
    And I am on homepage
    And I see "course rating" is selected on report page

  @javascript @Kineo_RC_05
  Scenario: Verify that admin can Create and View the report for Course rating report source
    Given I am on report page
    And I click button "Create And View" on report page
    And I click button "Edit this report" on Manager User Report Page
    And I change the report title as "course rating updated"

  @javascript @Kineo_RC_09
  Scenario: Verify that admin can Create and View the report for Courses (with rating) report source
    Given I am on report page
    And I click button "Create And View" on report page
    And I click button "Edit this report" on Manager User Report Page
    And I change the report title as "course rating updated" with rating
