@totara @totara_contentmarketplace @contentmarketplace_linkedin @javascript
Feature: Manage the LinkedIn Learning content marketplace plugin

  Background:
    Given I am on a totara site
    And I log in as "admin"

  Scenario: Enable and disable the LinkedIn Learning content marketplace plugin
    When I navigate to "Plugins > Content marketplace > Manage content marketplaces" in site administration
    Then I should not see "Enabled" in the "LinkedIn Learning" "table_row"
    And I should not see "Settings" in the "LinkedIn Learning" "table_row"
    And I should see "Online training courses for creative, technology, and business skills." in the "LinkedIn Learning" "table_row"
    And I should not see "Browse LinkedIn Learning content" in the "LinkedIn Learning" "table_row"
    When I click on "Set up" "link" in the "LinkedIn Learning" "table_row"

    # Remove the following in 16.0
    Then I should see "Enable LinkedIn Learning content marketplace" in the tui modal
    And I should see "LinkedIn Learning content will be available for course creators to include in new courses." in the tui modal
    And I should see "This feature is a beta with access limited to registered participants. You must register for the beta to receive an access code." in the tui modal
    And I should see "LinkedIn Learning access code" in the tui modal
    When I confirm the tui confirmation modal
    Then I should see "LinkedIn Learning access code" form field has the tui validation error "Required"
    When I set the field "LinkedIn Learning access code" to "invalid code"
    And I confirm the tui confirmation modal
    Then I should see "LinkedIn Learning access code" form field has the tui validation error "Invalid access code"
    When I close the tui modal
    Then I should not see "Enabled" in the "LinkedIn Learning" "table_row"
    When I click on "Set up" "link" in the "LinkedIn Learning" "table_row"
    When I set the field "LinkedIn Learning access code" to "linkedin2905"
    And I confirm the tui confirmation modal
    And I wait for pending js
    # Remove the above in 16.0

    # Uncomment the following in 16.0:
#    Then I should see "Enable LinkedIn Learning content marketplace" in the ".modal" "css_element"
#    And I should see "LinkedIn Learning content will be available for course creators to include in new courses." in the ".modal-body" "css_element"
#    When I click on "Cancel" "button" in the ".modal" "css_element"
#    Then I should not see "Enabled" in the "LinkedIn Learning" "table_row"
#    When I click on "Enable" "link" in the "LinkedIn Learning" "table_row"
#    And I click on "Enable" "button" in the ".modal" "css_element"

    Then I should see "Enabled" in the "LinkedIn Learning" "table_row"
    And I should not see "Disabled" in the "LinkedIn Learning" "table_row"
    And I should see "Settings" in the "LinkedIn Learning" "table_row"
    When I click on "Browse LinkedIn Learning content" "link" in the "LinkedIn Learning" "table_row"
    Then I should see "LinkedIn Learning catalogue"
    When I click on "Manage content marketplaces" "link"
    And I click on "Disable" "link" in the "LinkedIn Learning" "table_row"
    Then I should see "Disable LinkedIn Learning content marketplace" in the ".modal" "css_element"
    And I should see "LinkedIn Learning content will no longer be available for course creators to include in new courses." in the ".modal-body" "css_element"
    When I click on "Cancel" "button" in the ".modal" "css_element"
    Then I should see "Enabled" in the "LinkedIn Learning" "table_row"
    When I click on "Disable" "link" in the "LinkedIn Learning" "table_row"
    And I click on "Disable" "button" in the ".modal" "css_element"
    Then I should see "Disabled" in the "LinkedIn Learning" "table_row"
    When I click on "Enable" "link" in the "LinkedIn Learning" "table_row"
    Then I should see "Enable LinkedIn Learning content marketplace" in the ".modal" "css_element"
    And I should see "LinkedIn Learning content will be available for course creators to include in new courses." in the ".modal-body" "css_element"
    When I click on "Enable" "button" in the ".modal" "css_element"
    Then I should see "Enabled" in the "LinkedIn Learning" "table_row"