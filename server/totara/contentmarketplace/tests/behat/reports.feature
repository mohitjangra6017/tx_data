@totara @totara_contentmarketplace @mod_contentmarketplace @contentmarketplace_linkedin @totara_reportbuilder @javascript
Feature: Check that the course provider column and filter in reports work as expected

  Background:
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | c1        |
      | Course 2 | c2        |
    And the following "content marketplace" exist in "mod_contentmarketplace" plugin:
      | name     | course | marketplace_component       |
      | Learning | c2     | contentmarketplace_linkedin |

  Scenario: Course provider column and filter test
    Given the "linkedin" content marketplace plugin is disabled
    When I log in as "admin"
    And I navigate to "Manage embedded reports" node in "Site administration > Reports"
    And I set the field "Report Name value" to "course"
    And I click on "Search" "button" in the ".fitem_actionbuttons" "css_element"
    And I click on "Settings" "link" in the "Find Courses" "table_row"
    And I switch to "Columns" tab
    Then I should not see "Course Provider" in the "Column" "select"
    When I switch to "Filters" tab
    Then I should not see "Course Provider" in the "field" "select"

    # Enable the linkedin content marketplace and add the columns and filters
    Given the "linkedin" content marketplace plugin is enabled
    When I switch to "Columns" tab
    And I add the "Course Provider" column to the report
    And I switch to "Filters" tab
    And I set the field "newstandardfilter" to "Course Provider"
    And I click on "Save changes" "button"
    And I follow "View This Report"

    # Check the values of the course provider column.
    Then I should see "Internal" in the "course_provider" report column for "Course 1"
    And I should see "LinkedIn Learning" in the "course_provider" report column for "Course 2"

    # Check the results of the course provider filter.
    When I click on "Internal" "checkbox"
    And I click on "Search" "button" in the ".fitem_actionbuttons" "css_element"
    Then I should see "Course 1"
    And I should not see "Course 2"
    When I click on "LinkedIn Learning" "checkbox"
    And I click on "Internal" "checkbox"
    And I click on "Search" "button" in the ".fitem_actionbuttons" "css_element"
    Then I should not see "Course 1"
    And I should see "Course 2"

    # Disabling the plugin should hide the provider columns and filters
    When the "linkedin" content marketplace plugin is disabled
    And I reload the page
    Then I should not see "Course Provider"
