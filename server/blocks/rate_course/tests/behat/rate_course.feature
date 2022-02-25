@block @block_rate_course
Feature: User can rate a course and see how well the course is rated with the block rate course
  In order to know the most useful course from the user (students & teachers) perspective
  As a user enrolled into the course
  I could rate and recommend a course to another user and see the rating and receive recommendation

  Background:
    Given the following "courses" exist:
      | fullname | shortname | category | groupmode |
      | Course 1 | C1        | 0        | 0         |

    And the following "users" exist:
      | username     | firstname | lastname | email             |
      | teststudent1 | Student   | First    | student1@mail.com |
      | testrecom2   | Student   | Second   | fhdsgf@gmail.com  |

    And the following "course enrolments" exist:
      | user         | course | role    |
      | teststudent1 | C1     | student |


  @javascript @Kineo_RC_01 @DEVTXAUTO-10
  Scenario: Verify that admin can add rate course block
    Given I log in as "admin"
    And I am on homepage
    And I am on course index
    When I follow "Course 1"
    And I follow "Turn editing on"
    And I add the "Rate Course" block
    Then I should see "Rate Course"

  @javascript @Kineo_RC_02
  Scenario: Verify that Learner can give the rating of course
    Given I log in as "admin"
    And I am on homepage
    And I am on course index
    Given I follow "Course 1"
    And I follow "Turn editing on"
    And I add the "Rate Course" block
    Then I should see "Rate Course"
    And I log out
    Given I log in as "teststudent1"
    And I am on homepage
    And I follow "Course 1"
    Then I should see "Rate Course"
    Then I set the course rating to "5" stars and review

  @javascript @Kineo_RC_03
  Scenario: Verify that Admin can delete the rating of course
    Given I log in as "admin"
    And I am on homepage
    And I am on course index
    Given I follow "Course 1"
    And I follow "Turn editing on"
    And I add the "Rate Course" block
    Then I should see "Rate Course"
    And I log out
    Given I log in as "teststudent1"
    And I am on homepage
    And I follow "Course 1"
    Then I should see "Rate Course"
    Then I set the course rating to "5" stars and review
    And I log out
    Given I log in as "admin"
    And I am on homepage
    And I follow "Course 1"
    Then I should see "Rate Course"
    And I delete the rating of course

  @javascript @Kineo_RC_04
  Scenario: Verify that Learner can recommend the course
    Given I log in as "admin"
    And I am on homepage
    And I am on course index
    Given I follow "Course 1"
    And I follow "Turn editing on"
    And I add the "Rate Course" block
    Then I should see "Rate Course"
    And I log out
    Given I log in as "teststudent1"
    And I am on homepage
    And I follow "Course 1"
    Then I should see "Rate Course"
    And I recommend the course to another User

  @javascript @Kineo_RC_06
  Scenario: Verify that admin user can configure the rate course block under Common block setting
    Given I log in as "admin"
    And I am on homepage
    And I am on course index
    Given I follow "Course 1"
    And I follow "Turn editing on"
    And I add the "Rate Course" block
    Then I should see "Rate Course"
    And I configure rate course Allow block hiding and docking

  @javascript @Kineo_RC_07
  Scenario: Verify that Admin can disable the show button under General block settings
    Given I log in as "admin"
    And I am on homepage
    And I am on course index
    Given I follow "Course 1"
    And I follow "Turn editing on"
    And I add the "Rate Course" block
    Then I should see "Rate Course"
    And I configure rate course disable the show button under General block

  @javascript @Kineo_RC_08
  Scenario: Verify that Learner can view average ratings for the course with a count of ratings
    Given I log in as "admin"
    And I am on homepage
    And I am on course index
    Given I follow "Course 1"
    And I follow "Turn editing on"
    And I add the "Rate Course" block
    Then I should see "Rate Course"
    And I log out
    Given I log in as "teststudent1"
    And I am on homepage
    And I follow "Course 1"
    Then I should see "Rate Course"
    Then I set the course rating to "5" stars and review
    Then I should see "Rate Course"
    And I view average rating count
