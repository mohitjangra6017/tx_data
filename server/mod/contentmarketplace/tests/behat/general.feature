@mod @mod_contentmarketplace @totara @javascript
Feature: General behaviour with mod contentmarketplace

  Background:
    Given the following "courses" exist:
      | fullname   | shortname | format |
      | Course 101 | c101      | topics |
    And the following "content marketplace" exist in "mod_contentmarketplace" plugin:
      | name       | course | marketplace_component       |
      | Learning 1 | c101   | contentmarketplace_linkedin |

  Scenario: View the content marketplace within and without listing
    Given I am on a totara site
    And I log in as "admin"
    When I am on "Course 101" course homepage
    Then I should see "Learning 1"
    When I am on content marketplace index page of course "c101"
    Then I should see "Learning 1"

  Scenario: View the content marketplace from multi activities course
    Given I am on a totara site
    And I log in as "admin"
    When I am on "Course 101" course homepage
    Then I should see "Learning 1"
    And I follow "Learning 1"
    Then I should see "language: en"
    And I should see "Learning 1"