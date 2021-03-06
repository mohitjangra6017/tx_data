@totara @totara_reportbuilder @javascript
Feature: Per user visibility of user report source custom field values
    Depending on the visibility settings for a user profile custom field,
    its value will be shown or masked in a report.

  Background:
    Given I am on a totara site
    And the following "users" exist:
      | username | firstname | lastname | email               |
      | agent86  | Maxwell   | Smart    | agent86@example.com |
      | agent99  | Agent     | 99       | agent99@example.com |
      | kaos     | Kaos      | Inc      | kaos@example.com    |
      | chief    | The       | Chief    | chief@example.com   |
    And the following "roles" exist:
      | shortname  |
      | BigBrother |
    And the following "role assigns" exist:
      | user  | role       | contextlevel | reference |
      | chief | BigBrother | System       |           |
    And the following "permission overrides" exist:
      | capability                 | permission | role       | contextlevel | reference |
      | moodle/user:viewalldetails | Allow      | BigBrother | System       |           |

    Given I log in as "admin"
    And I navigate to "User profile fields" node in "Site administration > Users"
    And I set the following fields to these values:
      | datatype | checkbox |
    And I set the following fields to these values:
      | Short name                   | TestCheckbox          |
      | Name                         | TestCheckbox          |
      | Is this field required       | No                    |
      | Is this field locked         | No                    |
      | Should the data be unique    | No                    |
      | Who is this field visible to | Restricted visibility |
      | Checked by default           | No                    |
    And I press "Save changes"

    Given I set the following fields to these values:
      | datatype | date |
    And I set the following fields to these values:
      | Short name                   | TestDate              |
      | Name                         | TestDate              |
      | Is this field required       | No                    |
      | Is this field locked         | No                    |
      | Should the data be unique    | No                    |
      | Who is this field visible to | Restricted visibility |
    And I press "Save changes"

    Given I set the following fields to these values:
      | datatype | datetime |
    And I set the following fields to these values:
      | Short name                   | TestDT1               |
      | Name                         | TestDT1               |
      | Is this field required       | No                    |
      | Is this field locked         | No                    |
      | Should the data be unique    | No                    |
      | Who is this field visible to | Restricted visibility |
      | Start year                   | 2000                  |
    And I press "Save changes"

    Given I set the following fields to these values:
      | datatype | datetime |
    And I set the following fields to these values:
      | Short name                   | TestDT2             |
      | Name                         | TestDT2             |
      | Is this field required       | No                  |
      | Is this field locked         | No                  |
      | Should the data be unique    | No                  |
      | Who is this field visible to | Restricted visibility |
      | Start year                   | 2000                |
      | Include time?                | 1                   |
    And I press "Save changes"

    Given I set the following fields to these values:
      | datatype | menu |
    And I set the following fields to these values:
      | Short name                   | TestMenu              |
      | Name                         | TestMenu              |
      | Is this field required       | No                    |
      | Is this field locked         | No                    |
      | Should the data be unique    | No                    |
      | Who is this field visible to | Restricted visibility |
      | Default value                | CCC                   |
    And I set the field "Menu options (one per line)" to multiline:
      """
      AAA
      BBB
      CCC
      """
    And I press "Save changes"

    Given I set the following fields to these values:
      | datatype | textarea |
    And I set the following fields to these values:
      | Short name                   | TestTextArea               |
      | Name                         | TestTextArea               |
      | Is this field required       | No                         |
      | Is this field locked         | No                         |
      | Should the data be unique    | No                         |
      | Who is this field visible to | Restricted visibility      |
      | Default value                | TestTextArea default value |
    And I press "Save changes"

    Given I set the following fields to these values:
      | datatype | text |
    And I set the following fields to these values:
      | Short name                   | TestTextField               |
      | Name                         | TestTextField               |
      | Is this field required       | No                          |
      | Is this field locked         | No                          |
      | Should the data be unique    | No                          |
      | Who is this field visible to | Restricted visibility       |
      | Default value                | TestTextField default value |
    And I press "Save changes"

    Given I navigate to "Manage users" node in "Site administration > Users"
    And I follow "Maxwell Smart"
    And I follow "Edit profile"
    And I expand all fieldsets
    And I set the following fields to these values:
      | TestCheckbox                       | 1                              |
      | profile_field_TestDate[enabled]    | Yes                            |
      | profile_field_TestDate[day]        | 16                             |
      | profile_field_TestDate[month]      | 10                             |
      | profile_field_TestDate[year]       | 2005                           |
      | profile_field_TestDT1[enabled]     | Yes                            |
      | profile_field_TestDT1[day]         | 10                             |
      | profile_field_TestDT1[month]       | 10                             |
      | profile_field_TestDT1[year]        | 2008                           |
      | profile_field_TestDT2[enabled]     | Yes                            |
      | profile_field_TestDT2[day]         | 10                             |
      | profile_field_TestDT2[month]       | 10                             |
      | profile_field_TestDT2[year]        | 2008                           |
      | profile_field_TestDT2[hour]        | 5                              |
      | profile_field_TestDT2[minute]      | 30                             |
      | TestMenu                           | AAA                            |
      | TestTextArea                       | agent86 textarea value         |
      | TestTextField                      | agent86 text value             |
    And I press "Update profile"

    Given I navigate to "Manage users" node in "Site administration > Users"
    And I follow "Agent 99"
    And I follow "Edit profile"
    And I expand all fieldsets
    And I set the following fields to these values:
      | TestCheckbox                       | 0                              |
      | profile_field_TestDate[enabled]    | Yes                            |
      | profile_field_TestDate[day]        | 16                             |
      | profile_field_TestDate[month]      | 10                             |
      | profile_field_TestDate[year]       | 2015                           |
      | profile_field_TestDT1[enabled]     | Yes                            |
      | profile_field_TestDT1[day]         | 10                             |
      | profile_field_TestDT1[month]       | 10                             |
      | profile_field_TestDT1[year]        | 2010                           |
      | profile_field_TestDT2[enabled]     | Yes                            |
      | profile_field_TestDT2[day]         | 11                             |
      | profile_field_TestDT2[month]       | 11                             |
      | profile_field_TestDT2[year]        | 2008                           |
      | profile_field_TestDT2[hour]        | 6                              |
      | profile_field_TestDT2[minute]      | 45                             |
      | TestMenu                           | BBB                            |
      | TestTextArea                       | agent99 textarea value         |
      | TestTextField                      | agent99 text value             |
    And I press "Update profile"

    Given I navigate to "Manage users" node in "Site administration > Users"
    And I follow "Kaos Inc"
    And I follow "Edit profile"
    And I expand all fieldsets
    And I set the following fields to these values:
      | Description | An international organization of evil bent on world domination |
    And I press "Update profile"

  Scenario: rb_source_user_customfield001: view report with custom field per user visibility as various users
    Given the following "standard_report" exist in "totara_reportbuilder" plugin:
      | fullname                        | shortname                              | source | accessmode |
      | Per user visibility user report | report_per_user_visibility_user_report | user   | 0          |
    And I navigate to my "Per user visibility user report" report
    And I press "Edit this report"
    And I switch to "Columns" tab
    And I add the "TestCheckbox" column to the report
    And I add the "TestDate" column to the report
    And I add the "TestDT1" column to the report
    And I add the "TestDT2" column to the report
    And I add the "TestMenu" column to the report
    And I add the "TestTextArea" column to the report
    And I add the "TestTextField" column to the report
    And I press "Save changes"

    When I navigate to my "Per user visibility user report" report
    And the following should exist in the "report_per_user_visibility_user_report" table:
      | username | TestCheckbox | TestDate    | TestDT1     | TestDT2              | TestMenu | TestTextArea               | TestTextField               |
      | agent86  | Yes          | 16 Oct 2005 | 10 Oct 2008 | 10 Oct 2008 at 05:30 | AAA      | agent86 textarea value     | agent86 text value          |
      | agent99  | No           | 16 Oct 2015 | 10 Oct 2010 | 11 Nov 2008 at 06:45 | BBB      | agent99 textarea value     | agent99 text value          |
      | kaos     | No           |             |             |                      | CCC      | TestTextArea default value | TestTextField default value |
      | chief    | No           |             |             |                      | CCC      |                            | TestTextField default value |

    Given I log out
    And I log in as "agent86"

    When I navigate to my "Per user visibility user report" report
    And the following should exist in the "report_per_user_visibility_user_report" table:
      | username | TestCheckbox | TestDate    | TestDT1     | TestDT2              | TestMenu | TestTextArea           | TestTextField      |
      | agent86  | Yes          | 16 Oct 2005 | 10 Oct 2008 | 10 Oct 2008 at 05:30 | AAA      | agent86 textarea value | agent86 text value |
      | agent99  | <hidden>     | <hidden>    | <hidden>    | <hidden>             | <hidden> | <hidden>               | <hidden>           |
      | kaos     | <hidden>     | <hidden>    | <hidden>    | <hidden>             | <hidden> | <hidden>               | <hidden>           |
      | chief    | <hidden>     | <hidden>    | <hidden>    | <hidden>             | <hidden> | <hidden>               | <hidden>           |

    Given I log out
    And I log in as "agent99"

    When I navigate to my "Per user visibility user report" report
    And the following should exist in the "report_per_user_visibility_user_report" table:
      | username | TestCheckbox | TestDate    | TestDT1     | TestDT2              | TestMenu | TestTextArea           | TestTextField      |
      | agent86  | <hidden>     | <hidden>    | <hidden>    | <hidden>             | <hidden> | <hidden>               | <hidden>           |
      | agent99  | No           | 16 Oct 2015 | 10 Oct 2010 | 11 Nov 2008 at 06:45 | BBB      | agent99 textarea value | agent99 text value |
      | kaos     | <hidden>     | <hidden>    | <hidden>    | <hidden>             | <hidden> | <hidden>               | <hidden>           |
      | chief    | <hidden>     | <hidden>    | <hidden>    | <hidden>             | <hidden> | <hidden>               | <hidden>           |

    Given I log out
    And I log in as "kaos"

    When I navigate to my "Per user visibility user report" report
    And the following should exist in the "report_per_user_visibility_user_report" table:
      | username | TestCheckbox | TestDate | TestDT1  | TestDT2  | TestMenu | TestTextArea               | TestTextField               |
      | agent86  | <hidden>     | <hidden> | <hidden> | <hidden> | <hidden> | <hidden>                   | <hidden>                    |
      | agent99  | <hidden>     | <hidden> | <hidden> | <hidden> | <hidden> | <hidden>                   | <hidden>                    |
      | kaos     | No           |          |          |          | CCC      | TestTextArea default value | TestTextField default value |
      | chief    | <hidden>     | <hidden> | <hidden> | <hidden> | <hidden> |     <hidden>               | <hidden>                    |


    Given I log out
    And I log in as "chief"

    When I navigate to my "Per user visibility user report" report
    And the following should exist in the "report_per_user_visibility_user_report" table:
      | username | TestCheckbox | TestDate    | TestDT1     | TestDT2              | TestMenu | TestTextArea               | TestTextField               |
      | agent86  | Yes          | 16 Oct 2005 | 10 Oct 2008 | 10 Oct 2008 at 05:30 | AAA      | agent86 textarea value     | agent86 text value          |
      | agent99  | No           | 16 Oct 2015 | 10 Oct 2010 | 11 Nov 2008 at 06:45 | BBB      | agent99 textarea value     | agent99 text value          |
      | kaos     | No           |             |             |                      | CCC      | TestTextArea default value | TestTextField default value |
      | chief    | No           |             |             |                      | CCC      |                            | TestTextField default value |
