@totara @totara_cohort @totara_plan @javascript
Feature: Learning plans can be created for members of an audience

  Background:
    Given I am on a totara site
    And the following "users" exist:
      | username | firstname  | lastname  | email                | city      |
      | learner1 | learner1   | learner1  | learner1@example.com | Brighton  |
      | learner2 | learner2   | learner2  | learner2@example.com |           |
    And I log in as "admin"
    # Create some new learning plan templates.
    When I navigate to "Manage templates" node in "Site administration > Learning Plans"
    And I click on "Edit" "link" in the "Learning Plan (Default)" "table_row"
    And I set the field "Name" to "Learning plan template 1"
    And I press "Save changes"
    Then I should see "Learning plan template 1"
    When I navigate to "Manage templates" node in "Site administration > Learning Plans"
    And I set the field "Name" to "Learning plan template 2"
    And I press "Save changes"
    Then I should see "Learning plan template 2"
    And I log out

  Scenario: Learning plans can be manually created for users in an audience
    Given I log in as "admin"
    And the following "cohorts" exist:
      | name    | idnumber | cohorttype  |
      | setaud1 | setaud1  | 1           |
    And the following "cohort members" exist:
      | user     | cohort    |
      | learner1 | setaud1   |
      | learner2 | setaud1   |
    When I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "setaud1"
    And I switch to "Learning Plan" tab

    # Check default settings.
    Then the field "Plan template" matches value "Learning plan template 1"
    And the field "excludecreatedmanual" matches value "1"
    And the field "excludecreatedauto" matches value "1"
    And the field "excludecompleted" matches value "1"
    And the field "Create new plans as" matches value "Draft"
    And the field "autocreatenew" matches value "0"

    # Create plans.
    When I click on "Save and create plans" "button"
    Then I should see "Confirm creation of plans"
    And I should see "This will create new learning plans for 2 user(s)."
    When I click on "Save" "button" in the "Confirm creation of plans" "totaradialogue"
    Then the following should exist in the "cohortplancreatehistory" table:
      | Template                 | User       | Plan status | Number of affected users |
      | Learning plan template 1 | Admin User | Draft       | 2                        |
    And I log out

    # Check the plans have been created.
    When I log in as "learner1"
    And I click on "Dashboard" in the totara menu
    And I click on "Learning Plans" "link"
    Then I should see "Learning plan template 1"
    And I log out
    When I log in as "learner2"
    And I click on "Dashboard" in the totara menu
    And I click on "Learning Plans" "link"
    Then I should see "Learning plan template 1"

  Scenario: Learning plans can be automatically created for users in a set audience
    Given I log in as "admin"
    And the following "cohorts" exist:
      | name    | idnumber | cohorttype  |
      | setaud1 | setaud1  | 1           |
    And I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "setaud1"
    And I switch to "Learning Plan" tab

    # Turn on 'Dynamic creation'.
    And I set the field "autocreatenew" to "1"
    And I click on "Save and create plans" "button"
    Then I should see "Confirm creation of plans"
    And I should see "No users require having learning plans created."
    When I click on "Save" "button" in the "Confirm creation of plans" "totaradialogue"
    Then the following should exist in the "cohortplancreatehistory" table:
      | Template                 | User       | Plan status | Number of affected users |
      | Learning plan template 1 | Admin User | Draft       | 0                        |

    # Add a user to the audience.
    When I switch to "Edit members" tab
    And I set the field "Potential users" to "learner1 learner1 (learner1@example.com)"
    And I press "Add"
    And I set the field "Potential users" to "learner2 learner2 (learner2@example.com)"
    And I press "Add"
    And I switch to "Members" tab
    Then I should see "learner1 learner1"
    And I should see "learner2 learner2"

    # Check the plans have been created.
    When I switch to "Learning Plan" tab
    Then the following should exist in the "cohortplancreatehistory" table:
      | Template                 | User       | Plan status | Number of affected users |
      | Learning plan template 1 | Admin User | Draft       | 2                        |
    And I log out
    When I log in as "learner1"
    And I click on "Dashboard" in the totara menu
    And I click on "Learning Plans" "link"
    Then I should see "Learning plan template 1"
    And I log out
    When I log in as "learner2"
    And I click on "Dashboard" in the totara menu
    And I click on "Learning Plans" "link"
    Then I should see "Learning plan template 1"

  Scenario: Learning plans can be automatically created for users in a dynamic audience
    Given I log in as "admin"
    And the following "cohorts" exist:
      | name    | idnumber | cohorttype  |
      | setaud1 | setaud1  | 2           |
    And I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "setaud1"
    And I switch to "Learning Plan" tab

    # Turn on 'Dynamic creation'.
    And I set the field "autocreatenew" to "1"
    And I click on "Save and create plans" "button"
    Then I should see "Confirm creation of plans"
    And I should see "No users require having learning plans created."
    When I click on "Save" "button" in the "Confirm creation of plans" "totaradialogue"
    Then the following should exist in the "cohortplancreatehistory" table:
      | Template                 | User       | Plan status | Number of affected users |
      | Learning plan template 1 | Admin User | Draft       | 0                        |

    # Add a user to the audience.
    When I switch to "Rule sets" tab
    And I set the field "addrulesetmenu" to "City"
    And I set the field "listofvalues" to "Brighton"
    And I click on "Save" "button" in the "Add rule" "totaradialogue"
    Then I should see "Audience rules changed"
    When I press "Approve changes"
    And I switch to "Members" tab
    Then I should see "learner1 learner1"

    # Check the plans have been created.
    When I switch to "Learning Plan" tab
    Then the following should exist in the "cohortplancreatehistory" table:
      | Template                 | User       | Plan status | Number of affected users |
      | Learning plan template 1 | Admin User | Draft       | 1                        |
      | Learning plan template 1 | Admin User | Draft       | 0                        |
    And I log out
    When I log in as "learner1"
    And I click on "Dashboard" in the totara menu
    And I click on "Learning Plans" "link"
    Then I should see "Learning plan template 1"
    And I log out

    # Update another user so they are added to the audience
    When I log in as "admin"
    And I navigate to "Browse list of users" node in "Site administration > Users > Accounts"
    And I follow "learner2 learner2"
    And I follow "Edit profile"
    And I set the field "City/town" to "Brighton"
    And I press "Update profile"

    # Check the new plan has been created.
    And I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "setaud1"
    And I switch to "Learning Plan" tab
    Then the following should exist in the "cohortplancreatehistory" table:
      | Template                 | User       | Plan status | Number of affected users |
      | Learning plan template 1 | Admin User | Draft       | 1                        |
      | Learning plan template 1 | Admin User | Draft       | 0                        |
    And I log out
    When I log in as "learner1"
    And I click on "Dashboard" in the totara menu
    And I click on "Learning Plans" "link"
    Then I should see "Learning plan template 1"

  Scenario: Learning plans dynamic created is disabled in the UI when exclude users who 'have an existing, automatically created plan based on this template' is set
    Given I log in as "admin"
    And the following "cohorts" exist:
      | name    | idnumber | cohorttype  |
      | setaud1 | setaud1  | 1           |
    And I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "setaud1"
    When I switch to "Learning Plan" tab
    Then the "autocreatenew" "checkbox" should be enabled
    When I set the field "excludecreatedauto" to "0"
    Then the "autocreatenew" "checkbox" should be disabled

  Scenario: Learning plans status can be set on creation within an audience
    Given I log in as "admin"
    And the following "cohorts" exist:
      | name    | idnumber | cohorttype  |
      | setaud1 | setaud1  | 1           |
    And the following "cohort members" exist:
      | user     | cohort    |
      | learner1 | setaud1   |

    # Create plans with status as 'Draft'.
    And I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "setaud1"
    When I switch to "Learning Plan" tab
    And I set the field "Plan template" to "Learning plan template 1"
    And I set the field "Create new plans as" to "Draft"
    And I click on "Save and create plans" "button"
    Then I should see "Confirm creation of plans"
    And I should see "This will create new learning plans for 1 user(s)."
    When I click on "Save" "button" in the "Confirm creation of plans" "totaradialogue"
    Then the following should exist in the "cohortplancreatehistory" table:
      | Template                 | User       | Plan status | Number of affected users |
      | Learning plan template 1 | Admin User | Draft       | 1                        |
    And I log out

    # Check the plan has been created with 'Draft' status.
    When I log in as "learner1"
    And I click on "Dashboard" in the totara menu
    And I click on "Learning Plans" "link"
    Then I should see "Learning plan template 1"
    When I click on "Learning plan template 1" "link"
    Then I should see "This plan has not yet been approved"
    And I log out

    # Create plans with status as 'Approved'.
    When I log in as "admin"
    And I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "setaud1"
    And I switch to "Learning Plan" tab
    And I set the field "Plan template" to "Learning plan template 2"
    And I set the field "Create new plans as" to "Approved"
    And I click on "Save and create plans" "button"
    Then I should see "Confirm creation of plans"
    And I should see "This will create new learning plans for 1 user(s)."
    When I click on "Save" "button" in the "Confirm creation of plans" "totaradialogue"
    Then the following should exist in the "cohortplancreatehistory" table:
      | Template                 | User       | Plan status | Number of affected users |
      | Learning plan template 2 | Admin User | Approved    | 1                        |
      | Learning plan template 1 | Admin User | Draft       | 1                        |
    And I log out

    # Check the plan has been created with 'Draft' status.
    When I log in as "learner1"
    And I click on "Dashboard" in the totara menu
    And I click on "Learning Plans" "link"
    Then I should see "Learning plan template 2"
    When I click on "Learning plan template 2" "link"
    Then I should not see "This plan has not yet been approved"

  Scenario: Learning plans are not created within an audience when 'have an existing, manually created plan based on this template' is set
    # As a user create a plan.
    Given I log in as "learner1"
    When I click on "Dashboard" in the totara menu
    And I click on "Learning Plans" "link"
    And I press "Create new learning plan"
    And I set the field "Plan name" to "My Learning Plan"
    And I set the field "Plan description" to "This is the discription..."
    And I press "Create plan"
    Then I should see "Plan creation successful"
    And I log out

    # As admin check additional plans are not created for the user.
    When I log in as "admin"
    And the following "cohorts" exist:
      | name    | idnumber | cohorttype  |
      | setaud1 | setaud1  | 1           |
    And the following "cohort members" exist:
      | user     | cohort    |
      | learner1 | setaud1   |
    And I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "setaud1"
    And I switch to "Learning Plan" tab
    And I set the field "excludecreatedmanual" to "1"
    And I set the field "excludecreatedauto" to "1"
    And I set the field "excludecompleted" to "1"
    And the field "autocreatenew" matches value "0"
    And I click on "Save and create plans" "button"
    Then I should see "Confirm creation of plans"
    And I should see "No users require having learning plans created."
    When I click on "Save" "button" in the "Confirm creation of plans" "totaradialogue"
    Then the following should exist in the "cohortplancreatehistory" table:
      | Template                 | User       | Plan status | Number of affected users |
      | Learning plan template 1 | Admin User | Draft       | 0                        |

    # Check additonal plans are created when excludecreatedmanual is turned off.
    When I set the field "excludecreatedmanual" to "0"
    And I click on "Save and create plans" "button"
    And I should see "Confirm creation of plans"
    And I should see "This will create new learning plans for 1 user(s)."
    And I click on "Save" "button" in the "Confirm creation of plans" "totaradialogue"
    Then the following should exist in the "cohortplancreatehistory" table:
      | Template                 | User       | Plan status | Number of affected users |
      | Learning plan template 1 | Admin User | Draft       | 1                        |

  Scenario: Learning plans are not created within an audience when 'have an existing, automatically created plan based on this template' is set
    Given I log in as "admin"
    And the following "cohorts" exist:
      | name    | idnumber | cohorttype  |
      | setaud1 | setaud1  | 1           |
    And the following "cohort members" exist:
      | user     | cohort    |
      | learner1 | setaud1   |
    When I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "setaud1"
    And I switch to "Learning Plan" tab
    And I set the field "excludecreatedmanual" to "1"
    And I set the field "excludecreatedauto" to "1"
    And I set the field "excludecompleted" to "1"
    And the field "autocreatenew" matches value "0"

    # Create a plan.
    And I click on "Save and create plans" "button"
    Then I should see "Confirm creation of plans"
    And I should see "This will create new learning plans for 1 user(s)."
    When I click on "Save" "button" in the "Confirm creation of plans" "totaradialogue"
    Then the following should exist in the "cohortplancreatehistory" table:
      | Template                 | User       | Plan status | Number of affected users |
      | Learning plan template 1 | Admin User | Draft       | 1                        |
    And I log out

    # Check the plan has been created.
    When I log in as "learner1"
    And I click on "Dashboard" in the totara menu
    And I click on "Learning Plans" "link"
    Then I should see "Learning plan template 1"
    And I log out

    # Check additonal plans are not created for the user.
    When I log in as "admin"
    And I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "setaud1"
    And I switch to "Learning Plan" tab
    And I click on "Save and create plans" "button"
    Then I should see "Confirm creation of plans"
    And I should see "No users require having learning plans created."
    When I click on "Save" "button" in the "Confirm creation of plans" "totaradialogue"
    Then the following should exist in the "cohortplancreatehistory" table:
      | Template                 | User       | Plan status | Number of affected users |
      | Learning plan template 1 | Admin User | Draft       | 0                        |

    # Check that additonal plans are created when excludecreatedauto is turned off.
    When I set the field "excludecreatedauto" to "0"
    And I click on "Save and create plans" "button"
    Then I should see "Confirm creation of plans"
    And I should see "This will create new learning plans for 1 user(s)."
    When I click on "Save" "button" in the "Confirm creation of plans" "totaradialogue"
    Then the following should exist in the "cohortplancreatehistory" table:
      | Template                 | User       | Plan status | Number of affected users |
      | Learning plan template 1 | Admin User | Draft       | 1                        |

  Scenario: Learning plans are not created within an audience when 'have a completed plan based on this template' is set
    # As admin create an approved plan for a user.
    When I log in as "admin"
    And the following "cohorts" exist:
      | name    | idnumber | cohorttype  |
      | setaud1 | setaud1  | 1           |
    And the following "cohort members" exist:
      | user     | cohort    |
      | learner1 | setaud1   |
    And I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "setaud1"
    And I switch to "Learning Plan" tab
    And I set the field "excludecreatedmanual" to "1"
    And I set the field "excludecreatedauto" to "1"
    And I set the field "excludecompleted" to "1"
    Then the field "autocreatenew" matches value "0"
    When I set the field "Create new plans as" to "Approved"
    And I click on "Save and create plans" "button"
    Then I should see "Confirm creation of plans"
    And I should see "This will create new learning plans for 1 user(s)."
    When I click on "Save" "button" in the "Confirm creation of plans" "totaradialogue"
    Then the following should exist in the "cohortplancreatehistory" table:
      | Template                 | User       | Plan status | Number of affected users |
      | Learning plan template 1 | Admin User | Approved    | 1                        |

    # Complete the plan.
    When I navigate to "Browse list of users" node in "Site administration > Users > Accounts"
    And I follow "learner1 learner1"
    And I follow "Learning Plans"
    And I follow "Learning plan template 1"
    And I click on "Complete plan" "button"
    And I click on "Complete plan" "button"
    Then I should see "Successfully completed plan Learning plan template 1"

    # Check additional plans are not created for the user.
    When I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "setaud1"
    And I switch to "Learning Plan" tab
    And I set the field "excludecreatedmanual" to "1"
    And I set the field "excludecreatedauto" to "1"
    And I set the field "excludecompleted" to "1"
    Then the field "autocreatenew" matches value "0"
    When I click on "Save and create plans" "button"
    Then I should see "Confirm creation of plans"
    And I should see "No users require having learning plans created."
    When I click on "Save" "button" in the "Confirm creation of plans" "totaradialogue"
    Then the following should exist in the "cohortplancreatehistory" table:
      | Template                 | User       | Plan status | Number of affected users |
      | Learning plan template 1 | Admin User | Approved    | 0                        |

    # Check that additonal plans are created when excludecreatedauto is turned off.
    When I set the field "excludecreatedmanual" to "0"
    And I set the field "excludecreatedauto" to "0"
    And I set the field "excludecompleted" to "0"
    And I click on "Save and create plans" "button"
    Then I should see "Confirm creation of plans"
    And I should see "This will create new learning plans for 1 user(s)."
    When I click on "Save" "button" in the "Confirm creation of plans" "totaradialogue"
    Then the following should exist in the "cohortplancreatehistory" table:
      | Template                 | User       | Plan status | Number of affected users |
      | Learning plan template 1 | Admin User | Approved    | 1                        |