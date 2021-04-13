@totara @totara_notification @javascript @vuejs
Feature: Overridden notifications at lower context

  Scenario: Admin edit inherited notification at the system context
    Given I log in as "admin"
    And I navigate to system notifications page

    When I click on "Totara comment" "button"
    Then I should see "New comment created"
    And "New comment created details" "button" should exist
    And "Actions for New comment created event" "button" should exist

    When I click on "Actions for New comment created event" "button"
    Then I should see "Create notification"
    And "Create notification" "link" should exist

    When I click on "New comment created details" "button"
    Then I should see "Comment created"

    When I click on "Actions for Comment created" "button"
    Then I should see "Edit"
    And "Edit" "link" should exist

    When I click on "//a[@title='Edit notification Comment created'][contains(text(),'Edit')]" "xpath_element"
    Then I should not see "Overridden subject at system"
    And I should not see "Overridden body at system"
    And "Enable customising field recipient" "checkbox" should exist
    And "Enable customising field subject" "checkbox" should exist
    And "Enable customising field schedule" "checkbox" should exist
    And "Enable customising field body" "checkbox" should exist
    And "Enable customising forced delivery channels" "checkbox" should exist
    And "Enable customising notification status" "checkbox" should exist
    And the "Recipient" "field" should be disabled
    And the "Name" "field" should be disabled
    And the "On notification trigger event" "field" should be disabled
    And the "Days after" "field" should be disabled
    And the "Enabled" "field" should be disabled
    And the "Force channel Email" "checkbox" should be disabled
    And the "Force channel Site notifications" "checkbox" should be disabled
    And the "Force channel Microsoft Teams" "checkbox" should be disabled
    And the "Force channel Alerts" "checkbox" should be disabled
    And the "Force channel Tasks" "checkbox" should be disabled

    When I click on the "override_recipient" tui checkbox
    And I click on the "override_subject" tui checkbox
    And I click on the "override_schedule" tui checkbox
    And I click on the "override_body" tui checkbox
    And I click on the "override_forced_delivery_channels" tui checkbox
    And I click on the "override_enabled" tui checkbox
    Then the "Recipient" "field" should be enabled
    And the "On notification trigger event" "field" should be enabled
    And the "Days after" "field" should be enabled
    And the "Force channel Email" "checkbox" should be enabled
    And the "Force channel Site notifications" "checkbox" should be enabled
    And the "Force channel Microsoft Teams" "checkbox" should be enabled
    And the "Force channel Alerts" "checkbox" should be enabled
    And the "Force channel Tasks" "checkbox" should be enabled
    And the "Enabled" "field" should be enabled

    When I set the field with xpath "//select[@class='tui-select__input']" to "Owner"
    And I set the weka editor with css ".tui-notificationPreferenceForm__subjectEditor" to "Overridden subject at system"
    And I set the weka editor with css ".tui-notificationPreferenceForm__bodyEditor" to "Overridden body at system"
    And I click on the "Days after" tui radio
    And I set the field "Number" to "3"
    And I click on the "force_email" tui checkbox
    And I click on the "force_popup" tui checkbox
    # The status field that handled by TUI form. At this point it does not understand the label associated with it.
    # Hence we are going to have to use the checkbox's field name.
    And I click on the "enabled[value]" tui checkbox
    And I click on "Save" "button"
    And I click on "Actions for Comment created" "button"
    Then I click on "//a[@title='Edit notification Comment created'][contains(text(),'Edit')]" "xpath_element"
    Then the "Recipient" "field" should be enabled
    And the "On notification trigger event" "field" should be enabled
    And the "Days after" "field" should be enabled
    And the "Enabled" "field" should be enabled
    And the field "Recipient" matches value "Owner"
    And I should see "Overridden subject at system"
    And I should see "Overridden body at system"
    And the field "Number" matches value "3"
    And the field "Force channel Email" matches value "email"
    And the field "Force channel Site notifications" matches value "popup"
    And the field "Force channel Microsoft Teams" does not match value "msteams"
    And the field "Force channel Alerts" does not match value "totara_alert"
    And the field "Force channel Tasks" does not match value "totara_task"
    # By default the notification preference is enabled, hence after click should result it to be disabled.
    And the field "Enabled" matches value "0"

    When I click on the "override_recipient" tui checkbox
    And I click on the "override_subject" tui checkbox
    And I click on the "override_body" tui checkbox
    And I click on the "override_schedule" tui checkbox
    And I click on the "override_forced_delivery_channels" tui checkbox
    And I click on the "override_enabled" tui checkbox
    Then I should not see "Overridden subject at system"
    And I should not see "Overridden body at system"
    And the "Force channel Email" "checkbox" should be disabled
    And the "Force channel Site notifications" "checkbox" should be disabled
    And the "Force channel Microsoft Teams" "checkbox" should be disabled
    And the "Force channel Alerts" "checkbox" should be disabled
    And the "Force channel Tasks" "checkbox" should be disabled
    And the field "Force channel Email" does not match value "email"
    And the field "Force channel Site notifications" does not match value "popup"
    And the field "Force channel Microsoft Teams" does not match value "msteams"
    And the field "Force channel Alerts" does not match value "totara_alert"
    And the field "Force channel Tasks" does not match value "totara_task"

    When I click on "Save" "button"
    And I click on "Actions for Comment created" "button"
    And I click on "//a[@title='Edit notification Comment created'][contains(text(),'Edit')]" "xpath_element"
    Then I should not see "Overridden subject at system"
    And I should not see "Overridden body at system"
    And the field "Number" does not match value "3"
    And the "Force channel Email" "checkbox" should be disabled
    And the "Force channel Site notifications" "checkbox" should be disabled
    And the "Force channel Microsoft Teams" "checkbox" should be disabled
    And the "Force channel Alerts" "checkbox" should be disabled
    And the "Force channel Tasks" "checkbox" should be disabled
    And the field "Force channel Email" does not match value "email"
    And the field "Force channel Site notifications" does not match value "popup"
    And the field "Force channel Microsoft Teams" does not match value "msteams"
    And the field "Force channel Alerts" does not match value "totara_alert"
    And the field "Force channel Tasks" does not match value "totara_task"
    # Default of status is enabled.
    And the field "Enabled" matches value "1"