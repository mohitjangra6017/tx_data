@editor @editor_atto @atto @atto_fullscreen
Feature: Atto fullscreen editor button
  User can access Atto Full Screen Settings
  I need to use an editing tool to expand editor.

  Background:
    Given I log in as "admin"

  @javascript @Kineo_AFS_01 @DEVTXAUTO-28
  Scenario: Verify that Admin can access Atto Full Screen Settings under Atto HTML Editor
    Given I am on homepage
    And I navigate to "Plugins > Text editors > Atto HTML editor > Atto toolbar settings" in site administration
    Then I should see "Atto toolbar settings"

  @javascript @Kineo_AFS_02 @DEVTXAUTO-29
  Scenario: Verify that Admin can add full Screen option.
    Given I am on homepage
    And I navigate to "Plugins > Text editors > Atto HTML editor > Atto toolbar settings" in site administration
    And I set the field "Toolbar config" to "other = html, fullscreen, bold, charmap"
    Then I press "Save changes"

  @javascript @Kineo_AFS_03 @DEVTXAUTO-30
  Scenario: Verify Admin after added fullscreen option it is showing under in a editor toolbar
    Given I am on homepage
    And I navigate to "Plugins > Text editors > Atto HTML editor > Atto toolbar settings" in site administration
    And I set the field "Toolbar config" to "other = html, fullscreen, bold, charmap"
    And I press "Save changes"
    And I open my profile in edit mode
    And I set the field "Description" to "Elephant"
    When I click on "Toggle full screen" "button"
    Then "button.atto_fullscreen_button.highlight" "css_element" should exist


  @javascript @Kineo_AFS_04 @DEVTXAUTO-32
  Scenario: Verify that Admin can remove the full screen option
    Given I am on homepage
    And I navigate to "Plugins > Text editors > Atto HTML editor > Atto toolbar settings" in site administration
    And I set the field "Toolbar config" to "other = html, bold, charmap"
    And I press "Save changes"
    And I open my profile in edit mode
    And I set the field "Description" to "Elephant"
    Then I should not see "Toggle full screen"

  @javascript @Kineo_AFS_05 @DEVTXAUTO-33
  Scenario: Verify Admin Can True or Enable Full Screen Setting Checkbox
    Given I am on homepage
    And I navigate to "Plugins > Text editors > Atto HTML editor > Atto toolbar settings" in site administration
    And I set the field "Toolbar config" to "other = html, fullscreen, bold, charmap"
    And I press "Save changes"
    When I navigate to "Plugins > Text editors > Atto HTML editor > Full screen settings" in site administration
    And I set the field "Require editing" to "1"
    Then I press "Save changes"
