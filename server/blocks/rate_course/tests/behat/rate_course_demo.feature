@block @block_rate_course
Feature: User can add rate course block on dashboard and rate a course
  User can delete the rating of the course and recommend to another user
  User can create and view the report for course rating and view average rating for the course
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
      | txuser1 | C1     | learner |
      | txuser2 | C1     | learner |


  @javascript @Kineo_RC_01 @Kineo_RC_03
  Scenario: Verify that Admin can delete the rating of course
    Given I log in as "admin"
    And I am on homepage
    And I am on "Your Dashboard"
    And I follow "Turn editing on"
    And I add the "Course Rating" block
    When I should see "Rate Course Block"
    And I delete the course rating
    Then I log out

  @javascript @Kineo_RC_07
  Scenario: Verify that admin can disable the show button under General block settings
    Given I log in as "admin"
    And I am on homepage
    And I am on "Your Dashboard"
    And I follow "Turn editing on"
    When I should see "Rate Course Block"
    And I configure show button as disabled under general block setting
    Then I log out

  @javascript @Kineo_RC_06
  Scenario: Verify that admin user can configure the rate course block
    Given I log in as "admin"
    And I am on homepage
    And I am on "Your Dashboard"
    And I follow "Turn editing on"
    When I should see "Rate Course Block"
    And I configure rate course block
    Then I log out

  @javascript @Kineo_RC_02
  Scenario: Verify that Learner can give the rating of course
    Given I log in as "learner"
    And I am on "Your Dashboard"
    When I should see "Rate Course Block"
    And I click button "Rate this course" in rate course block
    And I set the course rating to "5" stars
    Then I log out

  @javascript @Kineo_RC_04
  Scenario: Verify that Learner can recommend the course
    Given I log in as "learner"
    And I am on "Your Dashboard"
    When I should see "Rate Course Block"
    And I recommended the course to another User
    Then I log out

  @javascript @Kineo_RC_08
  Scenario: Verify that learner can view average ratings for the course with a count of ratings,and View number of course completions on course rate block
    Given I log in as "learner"
    And I am on "Your Dashboard"
    When I should see "Rate Course Block"
    And I see average rating with count and number of course
    Then I log out


