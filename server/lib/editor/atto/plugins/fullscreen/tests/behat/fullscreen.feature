@editor @editor_atto @atto @atto_fullscreen
Feature: Atto fullscreen editor button
  In order to edit big text
  I need to use an editing tool to expand editor.

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


  @javascript @Kineo_AFS_01
  Scenario: Verify that Admin can access Atto Full Screen Settings under Atto HTML Editor
    Given I log in as "admin"
    And I navigate to "Plugins > Text editors > Atto HTML editor > Atto toolbar settings" in site administration
    Then I should see "Atto toolbar settings"

  @javascript @Kineo_AFS_02
  Scenario: Verify that Admin can add full Screen option.
    Given I log in as "admin"
    And I navigate to "Plugins > Text editors > Atto HTML editor > Atto toolbar settings" in site administration
    And I set the field "Toolbar config" to "other = html, fullscreen, bold, charmap"
    And I press "Save changes"

  @javascript @Kineo_AFS_03
  Scenario: Verify Admin after added fullscreen option it is showing under in a editor toolbar
    And I log in as "admin"
    And I navigate to "Plugins > Text editors > Atto HTML editor > Atto toolbar settings" in site administration
    And I set the field "Toolbar config" to "other = html, fullscreen, bold, charmap"
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

  @javascript @Kineo_AFS_04
  Scenario: Verify that Admin can remove the full screen option
    And I log in as "admin"
    And I navigate to "Plugins > Text editors > Atto HTML editor > Atto toolbar settings" in site administration
    And I set the field "Toolbar config" to "other = html, bold, charmap"
    And I press "Save changes"
    And I open my profile in edit mode
    And I set the field "Description" to "Elephant"
    Then I should not see "Toggle full screen"

  @javascript @Kineo_AFS_05
  Scenario: Verify Admin Can True or Enable Full Screen Setting Checkbox
    And I log in as "admin"
    And I navigate to "Plugins > Text editors > Atto HTML editor > Atto toolbar settings" in site administration
    And I set the field "Toolbar config" to "other = html, fullscreen, bold, charmap"
    And I press "Save changes"
    When I navigate to "Plugins > Text editors > Atto HTML editor > Full screen settings" in site administration
    And I set the field "Require editing" to "1"
    And I press "Save changes"
    And I log out
    And I log in as "user1"
    And I am on "Course 1" course homepage
    And I follow "Text Edit"
    And I click on "Add submission" "button"
    Then "Toggle full screen" "button" should not exist
