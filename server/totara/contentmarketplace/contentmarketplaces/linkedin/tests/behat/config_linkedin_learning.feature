@totara @totara_contentmarketplace @contentmarketplace_linkedin @javascript
Feature: Configure linkedin settings
  Scenario: Configure linkedin learning client id and secret
    Given I am on a totara site
    And I log in as "admin"
    # This steps needs to be update once we move the content marketplace navigation node.
    When I navigate to "Plugins > Content Marketplace > Manage Content Marketplaces" in site administration
    Then I should see "LinkedIn Learning"
    And I should not see "LinkedIn Learning settings"
    When I follow "Enable LinkedIn Learning"
    And I click on "Enable" "button"
    Then I should see "LinkedIn Learning settings"
    And I navigate to "Plugins > Content Marketplace > LinkedIn Learning settings" in site administration
    And I should see "Client ID"
    And I should see "Client secret"
    And I should see "Guest access"
    And I set the field "Client ID" to "clientid"
    And I set the field "Client secret" to "clientsecret"
    And I click on "Guest access" "checkbox"
    When I click on "Save changes" "button"
    Then I should see "Changes saved"
    And the field "Client ID" matches value "clientid"
    And the field "Client secret" matches value "clientsecret"

  Scenario: Guest access config needs to be consistent with global guest access setting
    Given I am on a totara site
    And I log in as "admin"
    And I navigate to "Plugins > Content Marketplace > Manage Content Marketplaces" in site administration
    And I follow "Enable LinkedIn Learning"
    And I click on "Enable" "button"
    When I navigate to "Plugins > Content Marketplace > LinkedIn Learning settings" in site administration
    Then I should see "Guest access"
    And I navigate to "Manage enrol plugins" node in "Site administration > Plugins > Enrolments"
    And I click on "Disable" "link" in the "Guest access" "table_row"
    When I navigate to "Plugins > Content Marketplace > LinkedIn Learning settings" in site administration
    Then I should not see "Guest access"

  Scenario: System user assigned on site manager can manage plugin
    Given I am on a totara site
    And the following "users" exist:
      | username | firstname | lastname | email             |
      | user1    | user      | one      | user1@example.com |
    And the following "system role assigns" exist:
      | user     | role    |
      | user1    | manager |
    And I log in as "user1"
    And I navigate to "Plugins > Content Marketplace > Manage Content Marketplaces" in site administration
    And I follow "Enable LinkedIn Learning"
    When I click on "Enable" "button"
    Then I should see "LinkedIn Learning settings"