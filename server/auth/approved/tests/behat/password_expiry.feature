@totara @auth @auth_approved @javascript
Feature: Password expiration of approved user accounts

  Scenario: Do not expire approved account password if expiration disabled
    Given I am on a totara site
    And the following "users" exist:
      | username  | firstname | lastname  | email                 | auth     |
      | user1     | First     | User      | user1@example.com     | approved |
    And I log in as "admin"
    And I navigate to "Manage authentication" node in "Site administration > Plugins > Authentication"
    And I click on "Enable" "link" in the "Self-registration with approval" "table_row"
    And I navigate to "Settings" node in "Site administration > Plugins > Authentication > Self-registration with approval"
    And I set the following fields to these values:
    | Enable password expiry | 0       |
    | Password duration      | 30 days |
    | Notification threshold | Never   |
    And I press "Save changes"
    And I log out

    When I use magic for auth approved to set last password change to "P31D" for user "user1"
    And I use magic for persistent login to open the login page
    And I set the field "Username" to "user1"
    And I set the field "Password" to "user1"
    And I press "Log in"
    Then I should see "You do not have any current learning."
    And I log out

  Scenario: Expire approved account password after thirty days without warning
    Given I am on a totara site
    And the following "users" exist:
      | username  | firstname | lastname  | email                 | auth     |
      | user1     | First     | User      | user1@example.com     | approved |
    And I log in as "admin"
    And I navigate to "Manage authentication" node in "Site administration > Plugins > Authentication"
    And I click on "Enable" "link" in the "Self-registration with approval" "table_row"
    And I navigate to "Settings" node in "Site administration > Plugins > Authentication > Self-registration with approval"
    And I set the following fields to these values:
      | Enable password expiry | 1       |
      | Password duration      | 30 days |
      | Notification threshold | Never   |
    And I press "Save changes"
    And I log out

    When I use magic for auth approved to set last password change to "P29D" for user "user1"
    And I use magic for persistent login to open the login page
    And I set the field "Username" to "user1"
    And I set the field "Password" to "user1"
    And I press "Log in"
    Then I should see "You do not have any current learning."
    And I log out

    When I use magic for auth approved to set last password change to "P31D" for user "user1"
    And I use magic for persistent login to open the login page
    And I set the field "Username" to "user1"
    And I set the field "Password" to "user1"
    And I press "Log in"
    Then I should see "Your password has expired. Please change it now."
    And I press "Continue"
    And I set the following fields to these values:
    | Current password    | user1       |
    | New password        | Xuseruser1! |
    | New password (again)| Xuseruser1! |
    And I press "Save changes"
    And I should see "Password has been changed"
    And I am on homepage
    And I should see "You do not have any current learning."
    And I log out
    And I use magic for persistent login to open the login page
    And I set the field "Username" to "user1"
    And I set the field "Password" to "Xuseruser1!"
    And I press "Log in"
    And I should see "You do not have any current learning."
    And I log out

  Scenario: Expire approved account password after thirty days with warning
    Given I am on a totara site
    And the following "users" exist:
      | username  | firstname | lastname  | email                 | auth     |
      | user1     | First     | User      | user1@example.com     | approved |
    And I log in as "admin"
    And I navigate to "Manage authentication" node in "Site administration > Plugins > Authentication"
    And I click on "Enable" "link" in the "Self-registration with approval" "table_row"
    And I navigate to "Settings" node in "Site administration > Plugins > Authentication > Self-registration with approval"
    And I set the following fields to these values:
      | Enable password expiry | 1       |
      | Password duration      | 30 days |
      | Notification threshold | 2 days  |
    And I press "Save changes"
    And I log out

    When I use magic for auth approved to set last password change to "P27D" for user "user1"
    And I use magic for persistent login to open the login page
    And I set the field "Username" to "user1"
    And I set the field "Password" to "user1"
    And I press "Log in"
    Then I should see "You do not have any current learning."
    And I log out

    When I use magic for auth approved to set last password change to "P29D" for user "user1"
    And I use magic for persistent login to open the login page
    And I set the field "Username" to "user1"
    And I set the field "Password" to "user1"
    And I press "Log in"
    And I should see "Your password will expire in 1 days. Do you want to change your password now?"
    And I press "Cancel"
    Then I should see "You do not have any current learning."
    And I log out

    When I use magic for persistent login to open the login page
    And I set the field "Username" to "user1"
    And I set the field "Password" to "user1"
    And I press "Log in"
    And I should see "Your password will expire in 1 days. Do you want to change your password now?"
    And I press "Continue"
    And I set the following fields to these values:
      | Current password    | user1       |
      | New password        | Xuseruser1! |
      | New password (again)| Xuseruser1! |
    And I press "Save changes"
    And I should see "Password has been changed"
    And I am on homepage
    And I should see "You do not have any current learning."
    And I log out
    And I use magic for persistent login to open the login page
    And I set the field "Username" to "user1"
    And I set the field "Password" to "Xuseruser1!"
    And I press "Log in"
    And I should see "You do not have any current learning."
    And I log out

    When I use magic for auth approved to set last password change to "P31D" for user "user1"
    And I use magic for persistent login to open the login page
    And I set the field "Username" to "user1"
    And I set the field "Password" to "Xuseruser1!"
    And I press "Log in"
    Then I should see "Your password has expired. Please change it now."
    And I press "Continue"
    And I set the following fields to these values:
      | Current password    | Xuseruser1! |
      | New password        | Xuseruser2! |
      | New password (again)| Xuseruser2! |
    And I press "Save changes"
    And I should see "Password has been changed"
    And I am on homepage
    And I should see "You do not have any current learning."
    And I log out
    And I use magic for persistent login to open the login page
    And I set the field "Username" to "user1"
    And I set the field "Password" to "Xuseruser2!"
    And I press "Log in"
    And I should see "You do not have any current learning."
    And I log out

    When I use magic for auth approved to set last password change to "P31D" for user "user1"
    And I use magic for persistent login to open the login page
    And I set the field "Username" to "user1"
    And I set the field "Password" to "Xuseruser2!"
    And I press "Log in"
    Then I should see "Your password has expired. Please change it now."
    And I press "Log out"
    And I should see "Cookies must be enabled in your browser"

