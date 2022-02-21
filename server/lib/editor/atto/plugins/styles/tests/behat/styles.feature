@editor @editor_atto @atto @atto_styles
Feature: Atto styles
  To add and use block and inline styles with one and two classes each

  Background:
    Given the following "users" exist:
      | username | firstname | lastname |
      | user1    | Username  | 1        |
    And the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "course enrolments" exist:
      | user  | course | role    |
      | user1 | C1     | student |
    And the following "activities" exist:
      | activity   | name            | course  | idnumber   | section | assignsubmission_onlinetext_enabled |
      | assign     | Text Edit       | C1      | assign1    | 0       | 1                                   |


  @javascript @Kineo_AS_01
  Scenario: Verify that user can access Atto style Settings under Atto HTML Editor
    Given I log in as "admin"
    And I navigate to "Plugins > Text editors > Atto HTML editor > Styles settings" in site administration
    Then I should see "Styles settings"

  @javascript @Kineo_AS_02
  Scenario: Verify that User can add Atto styles option
    Given I log in as "admin"
    And I navigate to "Plugins > Text editors > Atto HTML editor > Styles settings" in site administration
    Then I should see "Styles settings"
    And I set the field "Styles configuration" to "other = html, fullscreen, bold, charmap"
    And I press "Save changes"


  @javascript @Kineo_AS_04
  Scenario: Verify that user can remove the Atto styles if its already added
    Given I log in as "admin"
    And I navigate to "Plugins > Text editors > Atto HTML editor > Styles settings" in site administration
    Then I should see "Styles settings"
    And I set the field "Styles configuration" to "bold, charmap"
    And I press "Save changes"


  @javascript @Kineo_AS_05
  Scenario: Verify that after deleted Atto styles, it is removed from the editor toolbar
    Given I log in as "admin"
    And I navigate to "Plugins > Text editors > Atto HTML editor > Styles settings" in site administration
    Then I should see "Styles settings"
    And I set the field "Styles configuration" to "other = html, fullscreen, bold, charmap"
    And I press "Save changes"
    And I open my profile in edit mode
    And I set the field "Description" to "Elephant"
    When I click on "Toggle full screen" "button"
    Then "button.atto_fullscreen_button.highlight" "css_element" should exist
    When I log out
    And I log in as "user1"
    And I am on "Course 1" course homepage
    And I follow "Text Edit"
    And I click on "Add submission" "button"
    Then "Toggle full screen" "button" should exist



























