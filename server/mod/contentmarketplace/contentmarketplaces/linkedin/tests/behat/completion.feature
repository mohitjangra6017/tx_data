@mod @mod_contentmarketplace @contentmarketplace_linkedin @totara @totara_contentmarketplace @javascript
Feature: Content marketplace activity completion feature
  Background:
    Given the following "learning objects" exist in "contentmarketplace_linkedin" plugin:
      | urn | title    | level    | asset_type | locale_language | locale_country |
      | A   | Course A | BEGINNER | COURSE     | en              | US             |
    And the following "classifications" exist in "contentmarketplace_linkedin" plugin:
      | urn        | name  | type    | locale_language | locale_country |
      | category:1 | J2EE  | LIBRARY | en              | US             |
      | category:2 | JDBC  | SUBJECT | en              | US             |
    And the following "classification relationships" exist in "contentmarketplace_linkedin" plugin:
      | parent_urn | child_urn  |
      | category:1 | category:2 |
    And the following "learning object classifications" exist in "contentmarketplace_linkedin" plugin:
      | learning_object_urn | classification_urn |
      | A                   | category:2         |
    And the following "categories" exist:
      | name       | category | idnumber |
      | Category A | 0        | A        |
    And I set up the LinkedIn Learning content marketplace plugin

  Scenario: Should not see the completion when completion is disabled
    Given I am on a totara site
    And I log in as "admin"
    And I navigate to the catalog import page for the the "linkedin" content marketplace
    And I toggle the selection of row "1" of the tui select table
    And I set the field "Select category" to "Category A"
    And I click on "Next: Review" "button"
    When I click on "Create course(s)" "button"
    Then I should see "Course A"
    And I am on "Course A" course homepage
    And I should see "Not started"
    And I click on "Administration" "button"
    And I click on "Edit settings" "link"
    And I follow "Activity completion"
    And I set the field "Completion tracking" to "0"
    When I click on "Save and display" "button"
    Then I should not see "Not started"

  Scenario: Self completion is not enabled by default
    Given I am on a totara site
    And I log in as "admin"
    And I navigate to the catalog import page for the the "linkedin" content marketplace
    And I toggle the selection of row "1" of the tui select table
    And I set the field "Select category" to "Category A"
    And I click on "Next: Review" "button"
    When I click on "Create course(s)" "button"
    Then I should see "Course A"
    And I am on "Course A" course homepage
    And I should not see "I have completed this activity"
    And I click on "Administration" "button"
    And I click on "Edit settings" "link"
    And I follow "Activity completion"
    And I set the field "Completion tracking" to "1"
    When I click on "Save and display" "button"
    Then I should see "I have completed this activity"
    And I should see "Not started"
    And I should not see "Completed"
    When I click on "I have completed this activity" "button"
    Then I should see "Completed"
    And I should not see "Not started"