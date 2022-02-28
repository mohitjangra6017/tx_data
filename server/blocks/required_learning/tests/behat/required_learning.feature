@block @block_required_learning
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

    And the following "course enrolments" exist:
      | user         | course | role    |
      | teststudent1 | C1     | student |

  @javascript @Kineo_RQL_01
  Scenario: Verify that Admin can add required Learning block on dashboard.
    Given I log in as "admin"
    And I am on homepage
    When I press "Customise this page"
    And I add the "Required Learning" block
    Then I should see "Required Learning"

  @javascript @Kineo_RQL_02 @Kineo_RQL_03
  Scenario: Verify that Admin can configure the Required Learning block
    Given I log in as "admin"
    And I am on homepage
    When I press "Customise this page"
    And I add the "Required Learning" block
    Then I should see "Required Learning"
    And I configure general block settings under required learning block


  @javascript @Kineo_RQL_04
  Scenario: Verify Learner can clicking on "Go To Required Learning" link to the bottom of the page redirects the user to correct destination.
    Given I log in as "admin"
    And I am on homepage
    When I press "Customise this page"
    And I add the "Required Learning" block
    Then I should see "Required Learning"
#    And I log out
#    Then I log in as "teststudent1"
    And I follow "Go to Required Learning"
    Then I should see "Required Learning"


  @javascript @Kineo_RQL_05
  Scenario: Verify that Learner can access the Program under required Learning block on dashboard.
    Given I log in as "admin"
    And I am on homepage
    When I press "Customise this page"
    And I add the "Required Learning" block
    Then I should see "Required Learning"
    And I log out
    Then I log in as "teststudent1"
    And I follow "Go to Required Learning"
    Then I should see "Required Learning"
    And I follow "Course Program"
