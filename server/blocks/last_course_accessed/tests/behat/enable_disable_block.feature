@totara @block @block_last_course_accessed @javascript
Feature: Site Administrator can enable and disable the LCA block.

  Background:
    Given I am on a totara site
    And the following "courses" exist:
      | fullname | shortname | enablecompletion |
      | Course 1 | C1        | 1                |
    When I log in as "admin"
    And I am on "Course 1" course homepage with editing mode on
    And I add the "Last Course Accessed" block
    Then I should see "Last Course Accessed"
    And I log out

  Scenario: Verify Site Administrator can disable the LCA block.
    Given I log in as "admin"
    When I navigate to "Manage blocks" node in "Site administration > Plugins > Blocks"
    And I click on "Hide" "link" in the "Last Course Accessed" "table_row"
    And I am on "Course 1" course homepage
    Then I should not see "Last Course Accessed"
    And I log out

  Scenario: Verify Site Administrator can enable the LCA block.
    Given I log in as "admin"
    When I navigate to "Manage blocks" node in "Site administration > Plugins > Blocks"
    And I click on "Hide" "link" in the "Last Course Accessed" "table_row"
    # Use css_element to ensure exact match (as there is another link with the text "Show ...")
    And I click on "[title='Show']" "css_element" in the "Last Course Accessed" "table_row"
    And I am on "Course 1" course homepage
    Then I should see "Last Course Accessed"
    And I log out
