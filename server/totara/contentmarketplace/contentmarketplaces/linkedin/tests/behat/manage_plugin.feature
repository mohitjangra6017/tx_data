@totara @totara_contentmarketplace @contentmarketplace_linkedin @javascript
Feature: Manage the LinkedIn Learning content marketplace plugin

  Background:
    Given I am on a totara site
    And I log in as "admin"

  Scenario: Enable and disable the LinkedIn Learning content marketplace plugin
    When I navigate to "Plugins > Content Marketplace > Manage Content Marketplaces" in site administration
    Then I should not see "Enabled" in the "LinkedIn Learning" "table_row"
    And I should not see "Settings" in the "LinkedIn Learning" "table_row"
    And I should see "Online training courses for creative, technology, and business skills." in the "LinkedIn Learning" "table_row"
    And I should not see "Browse LinkedIn Learning content" in the "LinkedIn Learning" "table_row"
    When I click on "Enable" "link" in the "LinkedIn Learning" "table_row"
    Then I should see "Enable LinkedIn Learning content" in the ".modal" "css_element"
    And I should see "Items from the marketplace will be available to course creators for inclusion in newly created courses." in the ".modal-body" "css_element"
    When I click on "Cancel" "button" in the ".modal" "css_element"
    Then I should not see "Enabled" in the "LinkedIn Learning" "table_row"
    When I click on "Enable" "link" in the "LinkedIn Learning" "table_row"
    And I click on "Enable" "button" in the ".modal" "css_element"
    Then I should see "Enabled" in the "LinkedIn Learning" "table_row"
    And I should not see "Disabled" in the "LinkedIn Learning" "table_row"
    And I should see "Settings" in the "LinkedIn Learning" "table_row"
    When I click on "Browse LinkedIn Learning content" "link" in the "LinkedIn Learning" "table_row"
    Then I should see "LinkedIn Learning catalogue"
    When I click on "Manage Content Marketplaces" "link"
    And I click on "Disable" "link" in the "LinkedIn Learning" "table_row"
    Then I should see "Disable LinkedIn Learning content" in the ".modal" "css_element"
    And I should see "Items from the marketplace will no longer be available to course creators for inclusion in newly created courses." in the ".modal-body" "css_element"
    When I click on "Cancel" "button" in the ".modal" "css_element"
    Then I should see "Enabled" in the "LinkedIn Learning" "table_row"
    When I click on "Disable" "link" in the "LinkedIn Learning" "table_row"
    And I click on "Disable" "button" in the ".modal" "css_element"
    Then I should see "Disabled" in the "LinkedIn Learning" "table_row"
    When I click on "Enable" "link" in the "LinkedIn Learning" "table_row"
    Then I should see "Enable LinkedIn Learning content" in the ".modal" "css_element"
    And I should see "Items from the marketplace will be available to course creators for inclusion in newly created courses." in the ".modal-body" "css_element"
    When I click on "Enable" "button" in the ".modal" "css_element"
    Then I should see "Enabled" in the "LinkedIn Learning" "table_row"