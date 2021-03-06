@block @block_calendar_upcoming @javascript
Feature: View a site event on the frontpage
  In order to view a site event
  As a teacher
  I can view the event in the upcoming events block

  Scenario: View a global event in the upcoming events block on the frontpage
    Given the following "users" exist:
      | username | firstname | lastname | email | idnumber |
      | teacher1 | Teacher | 1 | teacher1@example.com | T1 |
    And I log in as "admin"
    And I am on "Dashboard" page
    And I click on "Go to calendar" "link"
    And I create a calendar event:
      | id_eventtype | Site |
      | id_name | My Site Event |
    And I am on site homepage
    And I navigate to "Turn editing on" node in "Front page settings"
    And I add the "Upcoming events" block
    And I log out
    When I log in as "teacher1"
    And I am on site homepage
    Then I should see "My Site Event" in the "Upcoming events" "block"
