@block @block_record_of_learning
Feature: User can add and configure record of learning block
  User can configure record of learning block settings
  User can access the Program under record of Learning block on dashboard

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

      # Set up the dashboard.
    When I log in as "admin"
    And I navigate to "Dashboards" node in "Site administration > Navigation"
    And I press "Create dashboard"
    And I set the field "Name" to "My Dashboard"
    And I press "Create dashboard"
    And I should see "Dashboard saved"


  @javascript @Kineo_RCL_01
  Scenario: Verify that Admin can add record of Learning block on dashboard.
    Given I follow "My Dashboard"
    And I add the "Record of Learning" block
    And I should see "Record of Learning"

  @javascript @Kineo_RCL_02
  Scenario: Verify Admin Can Access Record Of Learning Link Is Available and Clickable
    Given I follow "My Dashboard"
    And I add the "Record of Learning" block
    When I should see "Record of Learning"
    And I follow "Go to Record of Learning"
    Then I should see "Record of Learning : Active Courses"


  @javascript @Kineo_RCL_03
  Scenario: Verify Admin Can check the functionality of the default course tab.
    Given I follow "My Dashboard"
    And I add the "Record of Learning" block
    And I should see "Record of Learning"
    And I log out
    When I log in as "teststudent1"
    And I follow "My Dashboard"
    And I follow "Go to Record of Learning"
    And I should see "Record of Learning : Active Courses"
    Then I Check Active Course Tab Functionality

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
