@editor @editor_atto @atto @atto_morebackcolors
Feature: Atto more font background colours button
  To format text in Atto, I need to spray random colours all over my text like some maniacal Monet.

  Background:
    Given I log in as "admin"

  @javascript @Kineo_MFBC_01
  Scenario: Verify that Admin can access more font background colors under Atto HTML Editor
    Given I navigate to "Plugins > Text editors > Atto HTML editor > More font background colors" in site administration
    Then I should see "More font background colors"


  @javascript @Kineo_MFBC_02
  Scenario: Verify that Admin can add new font background colors
    Given the following config values are set as admin:
      | toolbar         | style1 = bold, morebackcolors | editor_atto         |
      | availablecolors | #123456 #654321               | atto_morebackcolors |

    And I follow "Profile" in the user menu
    And I follow "Edit profile"
    And I set the field "Description" to "Water lillies"
    And I select the text in the "Description" Atto editor
    When I click on ".atto_morebackcolors_button" "css_element"
    Then ".atto_morebackcolors_button.atto_menu" "css_element" should be visible
    And "//div[@data-color='#123456']" "xpath_element" should exist in the ".atto_morebackcolors_button.atto_menu" "css_element"
    And "//div[@data-color='#654321']" "xpath_element" should exist in the ".atto_morebackcolors_button.atto_menu" "css_element"
    When I click on "//div[@data-color='#123456']" "xpath_element"
    And I press "Update profile"
    Then "//span[normalize-space(.)='Water lillies' and contains(@style, '18,52,86')]" "xpath_element" should exist

