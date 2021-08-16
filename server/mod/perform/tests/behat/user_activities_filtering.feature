@totara @perform @mod_perform @javascript @vuejs
Feature: Filtering user activities list
  Background:
    Given I am on a totara site
    And the following "users" exist:
      | username | firstname | lastname | email                   |
      | john     | John      | One      | john.one@example.com    |
      | david    | David     | Two      | david.two@example.com   |
      | harry    | Harry     | Three    | harry.three@example.com |
    And the following "subject instances" exist in "mod_perform" plugin:
      | activity_name                   | activity_type | subject_username | subject_is_participating | other_participant_username | number_repeated_instances |
      | johns example activity 1        | check-in      | john             | true                     | harry                      | 1                         |
      | johns example activity 2        | appraisal     | john             | true                     | harry                      | 1                         |
      | johns example activity 3        | appraisal     | john             | true                     | harry                      | 1                         |
      | johns example activity 4        | check-in      | john             | true                     | harry                      | 1                         |
      | johns example activity 5        | feedback      | john             | true                     | harry                      | 1                         |
      | johns example activity 6        | check-in      | john             | true                     | harry                      | 1                         |
      | johns example activity 7        | feedback      | john             | true                     | harry                      | 1                         |
      | johns annual review (repeating) | check-in      | john             | true                     | harry                      | 3                         |
      | davids example activity 1       | check-in      | david            | false                    | john                       | 1                         |
      | davids example activity 2       | appraisal     | david            | false                    | john                       | 1                         |
      | davids example activity 3       | check-in      | david            | false                    | john                       | 1                         |
      | davids example activity 4       | appraisal     | harry            | false                    | john                       | 1                         |
      | davids example activity 5       | check-in      | harry            | false                    | john                       | 1                         |
      | davids example activity 6       | appraisal     | harry            | false                    | john                       | 1                         |

  Scenario: Can view and filter activities I am a participant in that are about me
    Given I log in as "john"
    When I navigate to the outstanding perform activities list page
    And I set the field "Type" to "Appraisal"
    Then I should see "2" rows in the tui datatable

    When I set the field "Type" to "Check-in"
    Then I should see "6" rows in the tui datatable

    When I set the field "Type" to "Feedback"
    Then I should see "2" rows in the tui datatable

    When I set the field "Type" to "All"
    And I set the field "Your progress" to "Complete"
    Then I should see "No matching items found."

    When I set the field "Your progress" to "All"
    And I click on "johns example activity 1" "link"
    Then I should see "johns example activity 1" in the ".tui-pageHeading__title" "css_element"
    And I should see perform "short text" question "Question one" is unanswered
    And I should see perform "short text" question "Question two" is unanswered

    When I answer "short text" question "Question one" with "My first answer"
    And I answer "short text" question "Question two" with "My second answer"
    Then I should see "Question one" has no validation errors
    And I should see "Question two" has no validation errors

    When I click on "Submit" "button"
    And I confirm the tui confirmation modal
    Then I should see "Performance activities"
    And I should see "Section submitted" in the tui success notification toast
    And the "Your activities" tui tab should be active

    When I set the field "Your progress" to "Complete"
    Then I should see "1" rows in the tui datatable

    When I set the field "Your progress" to "All"
    And I click on "Exclude completed activities" tui "toggle_switch"
    Then I should see "9" rows in the tui datatable

    When I set the field "Search by activity" to "johns annual review"
    Then I should see "3" rows in the tui datatable

    When I set the field "Sort by" to "Activity"
    Then I should see "3" rows in the tui datatable

