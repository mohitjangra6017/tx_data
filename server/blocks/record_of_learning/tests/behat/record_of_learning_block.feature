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

  @javascript @Kineo_RCL_04
  Scenario: Verify Learner Can Check the availability and functionality of the search button.
    Given I follow "My Dashboard"
    And I add the "Record of Learning" block
    And I should see "Record of Learning"
    And I log out
    When I log in as "teststudent1"
    And I follow "My Dashboard"
    And I follow "Go to Record of Learning"
    And I should see "Record of Learning : Active Courses"
    When I search "Course 2" Course
    And I should not see "Course 2"
    When I search "Course 1" Course
    Then I should see "Course 1"


  @javascript @Kineo_RCL_05
  Scenario: Verify Learner Can Check the availability and functionality of Export button under Courses tab.
    Given I follow "My Dashboard"
    And I add the "Record of Learning" block
    And I should see "Record of Learning"
    And I log out
    When I log in as "teststudent1"
    And I follow "My Dashboard"
    And I follow "Go to Record of Learning"
    And I should see "Export as"
    Then I download format file
    And I Verify exist file then delete


