@totara @totara_oauth2 @auth @oauth2 @auth_oauth2 @javascript
Feature: General behaviour with totara oauth2

  Scenario: View OAuth 2 provider details with one client provider
    Given I am on a totara site
    And the following "client provider" exist in "totara_oauth2" plugin:
      |        name          |        description            |         client_id          |
      | linked learning name | linked learning description   |  linked learning client id |
    And I log in as "admin"
    And I navigate to "Server > OAuth 2 > OAuth 2 provider details" in site administration
    Then I should see "OAuth 2 provider details"
    And I should see "linked learning name"
    And I should see "linked learning description"
    And I should see "linked learning client id"

  Scenario: View OAuth 2 provider details without one client provider
    Given I am on a totara site
    And I log in as "admin"
    And I navigate to "Server > OAuth 2 > OAuth 2 provider details" in site administration
    Then I should see "OAuth 2 provider details"
    And I should see "No OAuth 2 providers have been created. A provider for LinkedIn Learning reporting will be created by a scheduled task on the next cron run."
    And I should not see "linked learning name"
    And I should not see "linked learning description"