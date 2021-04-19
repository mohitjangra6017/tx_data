@totara @totara_contentmarketplace @contentmarketplace_linkedin @javascript
Feature: Configure linkedin settings
  Scenario: Configure linkedin learning client id and secret
    Given I am on a totara site
    And I log in as "admin"
    # This steps needs to be update once we move the content marketplace navigation node.
    When I navigate to "Content Marketplace > Setup Content Marketplaces" in site administration
    Then I should see "LinkedIn Learning"
    And I should not see "LinkedIn Learning settings"
    When I follow "Enable LinkedIn Learning"
    And I click on "Enable" "button"
    Then I should see "LinkedIn Learning settings"
    And I navigate to "Content Marketplace > LinkedIn Learning settings" in site administration
    And I should see "Client ID"
    And I should see "Client secret"
    And I set the field "Client ID" to "clientid"
    And I set the field "Client secret" to "clientsecret"
    When I click on "Save changes" "button"
    Then I should see "Changes saved"
    And the field "Client ID" matches value "clientid"
    And the field "Client secret" matches value "clientsecret"
