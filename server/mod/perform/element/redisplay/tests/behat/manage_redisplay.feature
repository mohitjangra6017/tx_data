@totara @perform @mod_perform @perform_element @javascript @vuejs
Feature: Manage performance activity redisplay element.

  Background:
    Given the following "activities" exist in "mod_perform" plugin:
      | activity_name      | description           | activity_type | create_track | create_section | activity_status | anonymous_responses |
      | First Activity     | My First description  | check-in      | true         | false          | Draft           | true                |
      | Second Activity    | My Second description | check-in      | true         | false          | Draft           | false               |
      | Redisplay Activity | My Second description | check-in      | true         | true           | Draft           | false               |
    And the following "activity settings" exist in "mod_perform" plugin:
      | activity_name   | multisection |
      | First Activity  | yes          |
      | Second Activity | yes          |
    And the following "activity sections" exist in "mod_perform" plugin:
      | activity_name      | section_name |
      | First Activity     | section 1-1  |
      | First Activity     | section 1-2  |
      | Second Activity    | section 2-1  |
      | Second Activity    | section 2-2  |
    And the following "section relationships" exist in "mod_perform" plugin:
      | section_name | relationship | can_view | can_answer |
      | section 1-1  | subject      | yes      | no         |
      | section 1-1  | manager      | yes      | yes        |
      | section 1-2  | subject      | yes      | yes        |
      | section 1-2  | manager      | yes      | yes        |
      | section 2-1  | peer         | yes      | no         |
      | section 2-1  | appraiser    | yes      | no         |
      | section 2-2  | peer         | yes      | yes        |
      | section 2-2  | mentor       | yes      | yes        |
    And the following "section elements" exist in "mod_perform" plugin:
      | section_name | element_name | title                   |
      | section 1-1  | short_text   | 1-1 Favourite position? |
      | section 1-1  | long_text    | 1-1 Describe position?  |
      | section 1-2  | short_text   | 1-2 Best position?      |
      | section 1-2  | long_text    | 1-2 Explain position?   |
      | section 2-1  | short_text   | 2-1 Favourite job?      |
      | section 2-1  | long_text    | 2-1 Describe job?       |
      | section 2-2  | short_text   | 2-2 Best job?           |
      | section 2-2  | long_text    | 2-2 Explain job?        |

  Scenario: I can create & update a redisplay perform element.
    Given I log in as "admin"
    When I navigate to the edit perform activities page for activity "Redisplay Activity"
    And I click on "Edit content elements" "link_or_button"
    And I add a "Response redisplay" activity content element
    When I set the following fields to these values:
      | rawTitle   | Review what you did in the past |
      | activityId | First Activity (Draft)          |
    And I wait for pending js
    And I set the following fields to these values:
      | sectionElementId   | 1-1 Favourite position? (Text: Short response) |
    And I save the activity content element
    Then I should see "Element saved."
    And I wait for pending js
    And I should see "Review what you did in the past"
    And I should see "First Activity"
    And I should see "1-1 Favourite position?"
    And I should see "{Anonymous responses}"

    And I add a "Response redisplay" activity content element
    When I set the following fields to these values:
      | rawTitle   | Discussing previous job duties   |
      | activityId | Second Activity (Draft)          |
    And I wait for pending js
    And I set the following fields to these values:
      | sectionElementId | 2-1 Favourite job? (Text: Short response) |
    And I save the activity content element
    Then I should see "Element saved."
    And I wait for pending js
    And I should see "Discussing previous job duties"
    And I should see "Second Activity"
    And I should see "2-1 Favourite job?"
    And I should see "{No responding relationships added yet}"

    And I add a "Response redisplay" activity content element
    When I set the following fields to these values:
      | rawTitle   | Discussing best job duties |
      | activityId | Second Activity (Draft)    |
    And I wait for pending js
    And I set the following fields to these values:
      | sectionElementId | 2-2 Best job? (Text: Short response) |
    And I save the activity content element
    Then I should see "Element saved."
    And I wait for pending js
    And I should see "Discussing best job duties"
    And I should see "Second Activity"
    And I should see "2-2 Best job?"
    And I should see "{Responses from: Peer, Mentor}"
