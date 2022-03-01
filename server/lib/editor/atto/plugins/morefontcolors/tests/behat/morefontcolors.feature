@editor @editor_atto @atto @atto_morefontcolors
Feature: Atto more font colours button
  To format text in Atto, I need to spray random colours all over my text like some maniacal Monet.

  Background:
    Given I log in as "admin"

  @javascript @Kineo_MFC_01 @DEVTXAUTO-55
  Scenario: Verify that Admin can access more font colors under Atto HTML Editor
    Given I navigate to "Plugins > Text editors > Atto HTML editor > More font colors" in site administration
    Then I should see "More font colors"


  @javascript @Kineo_MFC_02 @DEVTXAUTO-56
  Scenario: Verify that Admin can add new more font colors to the list
    Given the following config values are set as admin:
      | toolbar         | style1 = bold, morefontcolors | editor_atto         |
      | availablecolors | #123456 #654321               | atto_morefontcolors |

    And I follow "Profile" in the user menu
    And I follow "Edit profile"
    And I set the field "Description" to "Water lillies"
    And I select the text in the "Description" Atto editor
    When I click on ".atto_morefontcolors_button" "css_element"
    And ".atto_morefontcolors_button.atto_menu" "css_element" should be visible
    And "//div[@data-color='#123456']" "xpath_element" should exist in the ".atto_morefontcolors_button.atto_menu" "css_element"
    And "//div[@data-color='#654321']" "xpath_element" should exist in the ".atto_morefontcolors_button.atto_menu" "css_element"
    When I click on "//div[@data-color='#123456']" "xpath_element"
    And I press "Update profile"
    Then "//span[normalize-space(.)='Water lillies' and contains(@style, '18,52,86')]" "xpath_element" should exist

