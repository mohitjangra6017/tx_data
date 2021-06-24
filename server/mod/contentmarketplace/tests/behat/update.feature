@mod @javascript @mod_contentmarketplace @totara @contentmarketplace_linkedin @totara_contentmarketplace
Feature: Update content marketplace activity within course
  Background:
    Given I set up the LinkedIn Learning content marketplace plugin
    And the following "learning objects" exist in "contentmarketplace_linkedin" plugin:
      | urn          | title     |
      | urn:course:1 | Hibernate |
    And the following "categories" exist:
      | name       | category | idnumber |
      | Category A | 0        | A        |

  Scenario: Create course from learning object and update activity.
    Given I am on a totara site
    And I log in as "admin"
    And I navigate to the catalog import page for the the "linkedin" content marketplace
    And I should see "Hibernate"
    And I toggle the selection of row "1" of the tui select table
    And I set the field "Select category" to "Category A"
    And I click on "Next: Review" "button"
    And I click on "Create course(s)" "button"
    When I click on "Find Learning" in the totara menu
    Then I should see "Hibernate"
    And I am on "Hibernate" course homepage
    And I follow "Edit settings"
    Then I should see "Updating: External content marketplace"
    And the field "Name" matches value "Hibernate"
    And the "Name" "field" should be disabled
    And I follow "Common module settings"
    Then I should see "ID number"
    And the field "ID number" matches value ""
    And I set the field "ID number" to "lil_101"
    When I click on "Save and display" "button"
    Then I should see "Hibernate"
    And I follow "Edit settings"
    And I follow "Common module settings"
    And the field "ID number" matches value "lil_101"

  Scenario: Create course from learning object and update with completion.
    Given I am on a totara site
    And I log in as "admin"
    And I navigate to the catalog import page for the the "linkedin" content marketplace
    And I should see "Hibernate"
    And I toggle the selection of row "1" of the tui select table
    And I set the field "Select category" to "Category A"
    And I click on "Next: Review" "button"
    And I click on "Create course(s)" "button"
    When I click on "Find Learning" in the totara menu
    Then I should see "Hibernate"
    And I am on "Hibernate" course homepage
    And I follow "Edit settings"
    And I follow "Activity completion"
    And I should see "Completion condition"
    And I should see "Mark the activity completed on launch"
    And I should see "Show activity as complete when LinkedIn Learning conditions have been met"
    And the "Mark the activity completed on launch" "field" should be disabled
    And the "Show activity as complete when LinkedIn Learning conditions have been met" "field" should be disabled
    When I set the field "Completion tracking" to "Show activity as complete when conditions are met"
    Then the "Mark the activity completed on launch" "field" should be enabled
    And the "Show activity as complete when LinkedIn Learning conditions have been met" "field" should be enabled
    When I set the field "Show activity as complete when LinkedIn Learning conditions have been met" to "1"
    Then the field "Mark the activity completed on launch" matches value "0"
    When I set the field "Mark the activity completed on launch" to "1"
    Then the field "Show activity as complete when LinkedIn Learning conditions have been met" matches value "0"
    When I click on "Save and display" "button"
    Then I should see "Hibernate"
    And I follow "Edit settings"
    When I follow "Activity completion"
    Then the field "Mark the activity completed on launch" matches value "1"
    And the field "Show activity as complete when LinkedIn Learning conditions have been met" matches value "0"
    And the "Mark the activity completed on launch" "field" should be enabled
    And the "Show activity as complete when LinkedIn Learning conditions have been met" "field" should be enabled
    When I set the field "Completion tracking" to "Do not indicate activity completion"
    Then the "Mark the activity completed on launch" "field" should be disabled
    And the "Show activity as complete when LinkedIn Learning conditions have been met" "field" should be disabled
    And I click on "Save and display" "button"
    And I should see "Hibernate"
    And I follow "Edit settings"
    And I follow "Activity completion"
    Then the field "Mark the activity completed on launch" matches value "0"
    And the field "Show activity as complete when LinkedIn Learning conditions have been met" matches value "0"
    And the "Mark the activity completed on launch" "field" should be disabled
    And the "Show activity as complete when LinkedIn Learning conditions have been met" "field" should be disabled