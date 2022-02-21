@block @block_isotope-block
Feature: User can add and configure required isotope block
  User can configure required isotope block settings

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

  @javascript @Kineo_ISB_01
  Scenario: Verify User can add Isotope Block and Login As a Admin
    Given I log in as "admin"
    And I am on homepage
    When I press "Customise this page"
    And I add the "Isotope" block
    Then I should see "Isotope"

  @javascript @Kineo_ISB_02
  Scenario: Verify that Admin can configure the Required Learning block
    Given I log in as "admin"
    And I am on homepage
    When I press "Customise this page"
    And I add the "Isotope" block
    Then I should see "Isotope"
    And I configure general block settings under isotope block
#    Issue with requirejs.php line 53

  @javascript @Kineo_ISB_06
  Scenario: Verify that user can change the course images according to the requirement
    Given I log in as "admin"
    And I am on homepage
    When I press "Customise this page"
    And I add the "Isotope" block
    Then I should see "Isotope"
