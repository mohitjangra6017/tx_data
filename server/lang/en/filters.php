<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'filters', language 'en', branch 'MOODLE_20_STABLE'
 *
 * @package   core_filters
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['actfilterhdr'] = 'Active filters';
$string['addfilter'] = 'Add filter';
$string['anycategory'] = 'any category';
$string['anycourse'] = 'any course';
$string['anyfield'] = 'any field';
$string['anyrole'] = 'any role';
$string['anyvalue'] = 'any value';
$string['matchesanyselected'] = 'matches any selected';
$string['matchesallselected'] = 'matches all selected';
$string['applyto'] = 'Apply to';
$string['categoryrole'] = 'Category role';
$string['filtercheckbox'] = 'Checkbox filter';
$string['filtercheckbox_help'] = 'This filter allows you to filter information based on a set of checkboxes.

The filter has the following options:

* **is any value**: This option disables the filter (i.e. all information is accepted by this filter).
* **matches any selected**: This option allows information, if it matches any of the checked options.
* **matches all selected**: This option allows information, if it matches all of the checked options.';
$string['filterdate'] = 'Date filter';
$string['filterdate_help'] = 'This filter allows you to filter information from:

* Before and/or after given dates.
* A number of days before or after today.
* List any records where no date is set.';
$string['filternumber'] = 'Number filter';
$string['filternumber_help'] = 'This filter allows you to filter numerical information based on its value.

The filter has the following options:

* **is equal to**: This option allows only information that is equal to the text entered (if no text is entered, then the filter is disabled).
* **is not equal to**: This option allows only information that is not equal to the text entered (if no text is entered, then the filter is disabled).
* **is greater than**: This option allows only information that has a numerical value greater than the text entered (if no text is entered, then the filter is disabled).
* **is greater than**: This option allows only information that has a numerical value greater than the text entered (if no text is entered, then the filter is disabled).
* **is less than**: This option allows only information that has a numerical value less than the text entered (if no text is entered, then the filter is disabled).
* **is greater than or equal to**: This option allows only information that has a numerical value greater than or equal to the text entered (if no text is entered, then the filter is disabled).
* **is less than or equal to**: This option allows only information that has a numerical value less than or equal to the text entered (if no text is entered, then the filter is disabled).';
$string['filternumbersetoperator'] = 'Number with operator';
$string['filternumbersetoperator_help'] = 'This filter allows you to filter results based on a set operator';
$string['filternumbersetoperatorequal'] = 'Number equals filter';
$string['filternumbersetoperatorequal_help'] = 'This filter allows you to filter numerical information that is equal to the number specified';
$string['filternumbersetoperatornotequal'] = 'Number not equal filter';
$string['filternumbersetoperatornotequal_help'] = 'This filter allows you to filter numerical information that is not equal to the number specified';
$string['filternumbersetoperatorgreaterthan'] = 'Number greater than filter';
$string['filternumbersetoperatorgreaterthan_help'] = 'This filter allows you to filter numerical information that is greater than the number specified';
$string['filternumbersetoperatorlessthan'] = 'Number less than filter';
$string['filternumbersetoperatorlessthan_help'] = 'This filter allows you to filter numerical information that is less than the number specified';
$string['filternumbersetoperatorgreaterorequal'] = 'Number greater than or equal filter';
$string['filternumbersetoperatorgreaterorequal_help'] = 'This filter allows you to filter numerical information that is greater than or equal to the number specified';
$string['filternumbersetoperatorlessorequal'] = 'Number less than or equal filter';
$string['filternumbersetoperatorlessorequal_help'] = 'This filter allows you to filter numerical information that is less than or equal to the number specified';
$string['filtersimpleselect'] = 'Simple select filter';
$string['filtersimpleselect_help'] = 'This filter allows you to filter information based on a dropdown list. This filter does not have any extra options.';
$string['filtertext'] = 'Text filter';
$string['filtertext_help'] = 'This filter allows you to filter information based on a free form text.

The filter has the following options:

* **contains**: This option allows only information that contains the text entered (if no text is entered, then the filter is disabled).
* **doesn\'t contain**: This option allows only information that does not contain the text entered (if no text is entered, then the filter is disabled).
* **is equal to**: This option allows only information that is equal to the text entered (if no text is entered, then the filter is disabled).
* **starts with**: This option allows only information that starts with the text entered (if no text is entered, then the filter is disabled).
* **ends with**: This option allows only information that ends with the text entered (if no text is entered, then the filter is disabled).
* **is empty**: This option allows only information that is equal to an empty string (the text entered is ignored).';
$string['filterenrol'] = 'Enrol filter';
$string['filterenrol_help'] = 'This filter allows you to filter information based on whether a user is or isn\'t enrolled in a particular course.

The filter has the following options:

* **Is any value**: This option disables the filter (i.e. all information is accepted by this filter).
* **Yes**: This option only returns records where the user is enrolled in the specified course.
* **No**: This option only returns records where the user is not enrolled in the specified course.';
$string['filterselect'] = 'Select filter';
$string['filterselect_help'] = 'This filter allows you to filter information via a dropdown list of options.

The filter has the following options:

* **is any value**: This option disables the filter (i.e. all information is accepted by this filter).
* **is equal to**: This option allows only information that is equal to the value selected from the list.
* **is not equal to**: This option allows only information that is different from the value selected from the list.';
$string['filterurl'] = 'URL filter';
$string['filterurl_help'] = 'This filter allows you to filter information based on a dropdown list.

The filter has the following options:

* **is any value**: This option disables the filter (i.e. all information is accepted by this filter).
* **is empty**: This option allows only information that is equal to an empty string.
* **is not empty (NOT NULL)**: This option allows only information that is not equal to an empty string.';
$string['contains'] = 'contains';
$string['content'] = 'Content';
$string['contentandheadings'] = 'Content and headings';
$string['coursecategory'] = 'course category';
$string['courserole'] = 'Course role';
$string['courserolelabel'] = '{$a->label} is {$a->rolename} in {$a->coursename} from {$a->categoryname}';
$string['courserolelabelerror'] = '{$a->label} error: course {$a->coursename} does not exist';
$string['coursevalue'] = 'course value';
$string['datelabelisafter'] = '{$a->label} is after {$a->after}';
$string['datelabelisbefore'] = '{$a->label} is before {$a->before}';
$string['datelabelisbetween'] = '{$a->label} is between {$a->after} and {$a->before}';
$string['defaultx'] = 'Default ({$a})';
$string['disabled'] = 'Disabled';
$string['doesnotcontain'] = 'doesn\'t contain';
$string['endswith'] = 'ends with';
$string['filterallwarning'] = 'Applying filters to headings as well as content can greatly increase the load on your server. Please use that \'Apply to\' settings sparingly. The main use is with the multilang filter.';
$string['filtersettings'] = 'Filter settings';
$string['filtersettings_help'] = 'This page lets you turn filters on or off in a particular part of the site.

Some filters may also let you set local settings, in which case there will be a settings link next to their name.';
$string['filtersettingsforin'] = 'Filter settings for {$a->filter} in {$a->context}';
$string['filtersettingsin'] = 'Filter settings in {$a}';
$string['firstaccess'] = 'First access';
$string['globalrolelabel'] = '{$a->label} is {$a->value}';
$string['includesubcategories'] = 'Include sub-categories?';
$string['isactive'] = 'Active?';
$string['isafter'] = 'is after';
$string['isanyvalue'] = 'is any value';
$string['isbefore'] = 'is before';
$string['isdefined'] = 'is defined';
$string['isempty'] = 'is empty';
$string['isequalto'] = 'is equal to';
$string['isgreaterthan'] = 'is greater than';
$string['islessthan'] = 'is less than';
$string['isgreaterorequalto'] = 'is greater than or equal to';
$string['islessthanorequalto'] = 'is less than or equal to';
$string['isenrolled'] = 'The user is enrolled in the course';
$string['isnotenrolled'] = 'The user is not enrolled in the course';
$string['isnotdefined'] = 'isn\'t defined';
$string['isnotequalto'] = 'isn\'t equal to';
$string['limiterfor'] = '{$a} field limiter';
$string['neveraccessed'] = 'Never accessed';
$string['nevermodified'] = 'Never modified';
$string['newfilter'] = 'New filter';
$string['nofiltersenabled'] = 'No filter plugins have been enabled on this site.';
$string['off'] = 'Off';
$string['offbutavailable'] = 'Off, but available';
$string['on'] = 'On';
$string['profilefilterfield'] = 'Profile field name';
$string['profilefilterlimiter'] = 'Profile field operator';
$string['profilelabel'] = '{$a->label}: {$a->profile} {$a->operator} {$a->value}';
$string['profilelabelnovalue'] = '{$a->label}: {$a->profile} {$a->operator}';
$string['removeall'] = 'Remove all filters';
$string['removeselected'] = 'Remove selected';
$string['selectlabel'] = '{$a->label} {$a->operator} {$a->value}';
$string['selectlabelnoop'] = '{$a->label} {$a->value}';
$string['startswith'] = 'starts with';
$string['tablenosave'] = 'Changes in table above are saved automatically.';
$string['textlabel'] = '{$a->label} {$a->operator} {$a->value}';
$string['textlabelnovalue'] = '{$a->label} {$a->operator}';
$string['valuefor'] = '{$a} value';
