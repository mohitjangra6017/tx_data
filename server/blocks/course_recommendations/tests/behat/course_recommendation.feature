@block_course_recommendations
Feature: User can add course recommendations block on dashboard and rate course on course
  User (learner & admin) can recommend the course to learner
  User can navigate to recommended course
  User can view records are available under Course Recommendation block
  User can dismiss recommendation of the course and ignore future recommendation of the course
  User can see recommended details on Course Recommendations report source

  Background:
    Given the following "courses" exist:
      | fullname | shortname | category | groupmode |
      | Course 1 | C1 | 0 | 0 |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | teststudent1 | Student | First  | student1@mail.com |
    And the following "course enrolments" exist:
      | user     | course | role |
      | teststudent1 | C1     | student |
    And I log in as "admin"
    And I follow "Course 1"
    And I follow "Turn editing on"
    And I add the "Course Rating" block


  @javascript @Kineo_CR_019
  Scenario: Verify Admin can add course recommendation block on dashboard
#    Given I add the"Course recommendations"
#    And I should see"Course recommendations"

#
#  @javascript @Kineo_CR_02
#  Scenario: Learner can see recommended course via rate course
#    Then Switch User
#    Then I should see recommended course in course recommendation block
#
#  @javascript @Kineo_CR_03
#  Scenario: Learner can navigate to recommended course on dashboard
#    Then Switch User
#    And I click on recommended course in course recommendation block
#    Then I should navigate to Recommended Course page

