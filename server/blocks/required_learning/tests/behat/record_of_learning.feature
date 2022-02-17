@block @block_record_of_learning
Feature: User can add record of learning block
  Background:
    Given I log in as "admin"
    And I am on homepage
    When I press "Customise this page"
    And I add the "Required Learning" block

  @javascript @Kineo_RQL_01
  Scenario: Verify that user can add required Learning block on dashboard.
  Given I am on homepage
  Then I should see "Required Learning"

  @javascript @Kineo_RQL_02
  Scenario: Verify that user can configure the Required Learning block
    Given I am on homepage
    Then I should see "Required Learning"
    And I open the "Required Learning" blocks action menu
    And I configure rate course common block
#    And I click on "Configure Required Learning block" "link" in the "Required Learning" "block"
#    Then I should see "Block Configuration"
