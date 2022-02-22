@block @block_related_course
Feature: User can add and configure required learning block
  User can configure required learning block settings
  User can access the Program under required Learning block on dashboard

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


  @javascript @Kineo_RELC_01
  Scenario: Verify that user can add related course block on course
    Given I log in as "admin"
    And I am on homepage
    And I am on course index
    When I follow "Course 1"
    And I follow "Turn editing on"
    And I add the "Related courses" block
    Then I should see "Related courses"

  @javascript @Kineo_RELC_02
  Scenario: Verify that user can configure related courses block
    Given I log in as "admin"
    And I am on homepage
    And I am on course index
    When I follow "Course 1"
    And I follow "Turn editing on"
    And I add the "Related courses" block
    Then I should see "Related courses"
    And I configure related course block under General block

  @javascript @Kineo_RELC_03
  Scenario: Verify that learner user can see related courses under related courses block
    Given I log in as "admin"
    And I am on homepage
    And I am on course index
    When I follow "Course 1"
    And I follow "Turn editing on"
    And I add the "Related courses" block
    Then I should see "Related courses"
    And I configure related course block under General block