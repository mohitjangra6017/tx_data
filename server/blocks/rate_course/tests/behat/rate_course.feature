@block @block_rate_course
Feature: User can rate a course and see how well the course is rated with the block rate course
  In order to know the most useful course from the user (students & teachers) perspective
  As a user enrolled into the course
  I could rate and recommend a course to another user and see the rating and receive recommendation

  Background:
    Given the following "courses" exist:
      | fullname | shortname | category | groupmode |
      | Course 1 | C1 | 0 | 0 |
      | Course 2 | C2 | 0 | 0 |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | teststudent1 | Student | First  | student1@mail.com |
      | teststudent2 | Student | Second | student2@mail.com |
      | teststudent3 | Student | Third  | student3@mail.com |
    And the following "course enrolments" exist:
      | user     | course | role |
      | teststudent1 | C1     | student |
      | teststudent2 | C1     | student |
    And I log in as "admin"
    And I am on homepage
    And I follow "Course 1"
    And I follow "Turn editing on"
    And I add the "Course Rating" block
   Then I should see "Average rating"
    And I log out

  @javascript @123
  Scenario: Students can like a course
    Given I log in as "teststudent1"
      And I am on homepage
     When I follow "Course 1"
     Then I should see "Course Rating"
      And I click button "Like Course" in rate course block
     Then I should see the button "Like Course" selected
      And I reload the page
      And I should see the button "Like Course" selected
     Then I click button "Like Course" in rate course block
      And I should see the button "Like Course" deselected
      And I reload the page
      And I should see the button "Like Course" deselected

  @javascript
  Scenario: Students can enrol themselves to a course
     Given I log in as "admin"
       And I am on homepage
       And I follow "Course 1"
       And I click on "Edit settings" "link" in the "Administration" "block"
       And I set the following fields to these values:
            | Allow guest access | Yes |
       And I press "Save changes"
       And I log out
      Then I log in as "teststudent3"
       And I follow "Course 1"
       And I click button "Enrol course" in rate course block
       And I should see "Enrol"
      Then I set the field "Start" to "01/01/2015"
       And I click button "Enrol course" in rate course block
       And I press "Enrol"
      Then I expand "My courses" node
       And I should see "C1"
       And I should see the button "Enrol course" disabled

  @javascript
  Scenario: Students can rate a course
     Given I log in as "teststudent1"
       And I am on homepage
       And I follow "Course 1"
       And I click button "Rate course" in rate course block
      Then I should see "Submit my rating"
     Given I set the course rating to "5" stars
      Then I should see "5" stars highlighted
       And I should see "Five Stars"
       And I set the field "Review" to "Highly recommended for new staff"
       And I click button "Rate course" in rate course block
       And I press "Submit my rating"
       And I should see "Average rating" with "5" stars highlighted
       And I should see "Highly recommended for new staff"
      Then I log out
     Given I log in as "teststudent2"
       And I follow "Course 1"
      Then I should see "Average rating" with "5" stars highlighted
       And I click button "Rate course" in rate course block
       And I set the course rating to "3" stars
       And I set the field "Review" to "Easy to follow"
       And I click button "Rate course" in rate course block
       And I press "Submit my rating"
       And I should see "Average rating" with "4" stars highlighted
