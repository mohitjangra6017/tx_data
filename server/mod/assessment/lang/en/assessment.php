<?php
/**
 * @copyright 2017-2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

defined('MOODLE_INTERNAL') || die();

$string['modulename'] = 'Skills assessment';
$string['modulename_help'] = 'The skills assessment activity is a structured workflow that reaches beyond online activities. Participants interact with evaluators to complete assessments, reflect on real-world practice, prepare for and capture decisions from check-in meetings, and more.';
$string['modulenameplural'] = 'Skills assessments';
$string['pluginadministration'] = 'Skills assessment administration';
$string['pluginname'] = 'Skills assessment';

$string['gradehighest'] = 'Highest grade';
$string['grademethod'] = 'Grading method';
$string['grademethod_help'] = 'When multiple attempts are allowed, the following methods are available for calcualting the final quiz grade:

* Highest grade of all attempts
* (More coming soon!)';
$string['subplugintype_question'] = 'Questions';

$string['accessactivity'] = 'Access activity';
$string['activate'] = 'Activate';
$string['active'] = 'Active';
$string['activationerror'] = 'The skills assessment could not be activated.';
$string['activitymaxattemptssetting'] = 'Activity "Max attempts" setting:';
$string['add'] = 'Add';
$string['addrule'] = 'Add rule';
$string['addstage'] = 'Add new stage';
$string['allowextraattempts'] = 'Allow extra attempts';
$string['assessmentcomplete'] = 'This skills assessment has been completed';
$string['assignroles'] = 'Assign roles';
$string['assignrolesviafileupload'] = 'Assign roles via file upload';
$string['attempt'] = 'Attempt';
$string['attemptarchived'] = 'Attempt archived';
$string['attemptarchivedon'] = 'Attempt archived on {$a}';
$string['attemptcontinue'] = 'Continue the last attempt';
$string['attemptoverride'] = 'Give more attempts';
$string['attemptreview'] = 'Review current attempt';
$string['attemptsremaining'] = 'Remaining attempts';
$string['attemptsremaining'] = 'Remaining attempts';
$string['attemptsoverride'] = 'Allow extra attempts';
$string['attemptstart'] = 'Start a new attempt';
$string['attemptsummarylearner'] = 'Summary of {$a}\'s attempts';
$string['attemptsummaryyours'] = 'Summary of your attempts';
$string['attemptsused'] = 'Attempts used';
$string['backtoeditor'] = 'Back to editor';
$string['backtocourse'] = 'Back to course';
$string['backtodashboard'] = 'Back to dashboard';
$string['backtostages'] = 'Back to stages';
$string['backtosummary'] = 'Back to summary';
$string['cananswer'] = 'Can answer';
$string['canview'] = 'Can view';
$string['canviewsubmitted'] = 'Only view after submission';
$string['canviewother'] = 'View other role\'s answers';
$string['chooseevaluator'] = 'Choose evaluator';
$string['choosereviewer'] = 'Choose reviewer';
$string['closed'] = 'Closed';
$string['completed'] = 'Completed';
$string['completionstatus'] = 'Completion status';
$string['completionstatus_help'] = 'Sets a status required for completion

* Submission requires all roles to complete all stages.
* Passing grade requires both submission and for the learner to earn a passing grade, with the pass grade set in the gradebook.';
$string['confirmdeactivatewarning'] = 'This will deactivate the previous version.';
$string['confirmstagedelete'] = 'Do you really want to remove stage {$a} from this assessment version?<br>
All associated questions will also be removed. Previous versions, if they exist, will not be affected.';
$string['confirmquestiondelete'] = 'Do you really want to remove question {$a} from this assessment version?<br>
Previous versions, if they exist, will not be affected.';
$string['confirmversionactivate'] = 'Are you sure you want to activate?';
$string['confirmversiondeactivate'] = 'Are you sure you want to deactivate?';
$string['continue'] = 'Continue';
$string['csvdelimiter'] = 'CSV delimiter';
$string['deactivate'] = 'Deactivate';
$string['deleteruleconfirm'] = 'Are you sure you want to delete this rule?';
$string['deleteruleitemconfirm'] = 'Are you sure you want to delete this rule item?';
$string['directmanager'] = 'Learner\'s direct manager';
$string['draft'] = 'Draft';
$string['encoding'] = 'Encoding';
$string['extraattemptrestrictions'] = 'Extra restrictions on attempts';
$string['extraattempts'] = 'Extra attempts';
$string['failed'] = 'Failed';
$string['grade'] = 'Grade';
$string['head:activitydefaults'] = 'Activity Defaults';
$string['head:addquestion'] = 'Adding a {$a} question';
$string['head:addstage'] = 'Adding a stage';
$string['head:editquestion'] = 'Editing a {$a} question';
$string['head:editstage'] = 'Editing a stage';
$string['head:importdefaults'] = 'Assessment assignment import defaults';
$string['idfield:evaluator'] = 'Evaluator identifier';
$string['idfield:evaluator_help'] = 'Which user field should the **evaluatorid** map against?  If no match is found, the record will not be processed.';
$string['idfield:learner'] = 'Learner identifier';
$string['idfield:learner_help'] = 'Which user field should the **learnerid** map against?  If no match is found, the record will not be processed.';
$string['idfield:reviewer'] = 'Reviewer identifier';
$string['idfield:reviewer_help'] = 'Which user field should the **reviewerid** map against?  If no match is found, the record will not be processed.';
$string['import:addorreplace'] = 'Uploaded evaluator/reviewer behaviour';
$string['import:addorreplace_help'] = 'Select if an imported list of evaluators/reviewers should be added to the existing pool or if the existing assignments should be fully replaced.';
$string['import:addassignments'] = 'Add assignments';
$string['import:autoenrol'] = 'Unenrolled user behaviour';
$string['import:autoenrol_help'] = 'Select if learners not enrolled should be skipped or enrolled in the course.';
$string['import:autoenroladd'] = 'Enrol in course';
$string['import:autoenrolskip'] = 'Skip';
$string['import:replaceassignments'] = 'Replace existing assignments';
$string['importoptions'] = 'Import options';
$string['indirectmanager'] = 'Learner\'s indirect manager';
$string['inprogress'] = 'In progress';
$string['label:hidegradeinoverview'] = 'Hide Grade in Assessment Overview';
$string['labeldesc:hidegradeinoverview'] = 'If selected, the learner\'s grade column will not be visible in the attempts summary overview';
$string['label:maximumuserwarningcount'] = 'Maximum user assignment warning count';
$string['labeldesc:maximumuserwarningcount'] = 'The number of potential users that trigger a warning on activation, where individual reviewer/evaluator rules refer to large groups of users.';
$string['label:operator'] = 'Ruleset operator';
$string['label:operator_help'] = 'This setting determines how the rules will behave if you define more than one ruleset. It is based on the logical "AND" and "OR" operators.

* If set to "AND", then a user will only be an eligible evluator if they satisfy the conditions of <strong>all</strong> of the rulesets. (They will be omitted if they fail to satisfy even one ruleset)
* If set to "OR", then a user will be an eligible evluator if they satisfy the conditions of <strong>any</strong> of the rulesets. (They only have to satisfy the conditions of a single ruleset to be included)

Note that each ruleset has its own, separate, logical operator to indicate the relationship between the rules within that ruleset.';
$string['label:operatorand'] = 'AND ({$a} matches every ruleset)';
$string['label:operatoror'] = 'OR ({$a} matches any ruleset)';
$string['label:singleevaluator'] = 'Single evaluator per attempt';
$string['labeldesc:singleevaluator'] = 'If disabled, multiple evaluators are automatically assigned access to participate in the assessment.';
$string['label:singlereviewer'] = 'Single reviewer per attempt';
$string['labeldesc:singlereviewer'] = 'If disabled, multiple reviewers are automatically assigned access to participate in the assessment.';
$string['layout'] = 'Layout';
$string['lock:previousstagecompleted'] = 'Until previous stage is complete';
$string['lock:unlocked'] = 'Unlocked';
$string['locked'] = 'Locked';
$string['markasreviewed'] = 'Mark as reviewed';
$string['newpage'] = 'New page';
$string['newversion'] = 'Create a new version';
$string['nextpage'] = 'Next page';
$string['noactions'] = 'There is nothing you need to do in this stage.';
$string['noevaluatorsassigned'] = 'No Evaluators are assigned';
$string['nonquestion'] = 'Non-question';
$string['norole'] = 'No valid role';
$string['notstarted'] = 'Not started';
$string['open'] = 'Open';
$string['options'] = 'Options';
$string['overrideuser'] = 'Override user';
$string['pageqall'] = 'Never, all questions on one page';
$string['pageq1'] = 'Every question';
$string['pageqx'] = 'Every {$a} questions';
$string['participants'] = 'Participants';
$string['plusxmore'] = '(+ {$a} more)';
$string['preview'] = 'Preview';
$string['previewingxasx'] = 'Previewing "{$a->activityname}" as {$a->role}';
$string['previewing_help'] = 'This window displays how the activity will appear to a user with the "{$a->role}" role, including which stages, pages, and questions will be visible.';
$string['question'] = 'Question';
$string['questionpermissions'] = 'Question permissions';
$string['questionpermissions_help'] = 'If a role cannot answer or view other role\'s answers then this question is not to show to that role.';
$string['reqevaluator'] = 'Your evaluator must complete this stage.';
$string['reqlearner'] = 'Learner must complete this this stage.';
$string['reqyou'] = 'You must complete this stage.';
$string['reqyouandevaluator'] = 'You and your evaluator must complete this stage.';
$string['reqyouandlearner'] = 'You and the learner must complete this stage.';
$string['requireanswer'] = 'Requires answer';
$string['requirecomplete'] = 'Require submission';
$string['requirepass'] = 'Require passing grade';
$string['returntorules'] = 'Return to rules';
$string['review'] = 'Review';
$string['role'] = 'Role';
$string['roleadmin'] = 'Admin';
$string['roleevaluator'] = 'Evaluator';
$string['rolelearner'] = 'Learner';
$string['rolereviewer'] = 'Reviewer';
$string['rolescananswer'] = 'Roles that can answer';
$string['rolesmustanswer'] = 'Roles that MUST answer';
$string['rolesonlyview'] = 'Roles that can view other role answers ONLY';
$string['rolesonlyviewsubmitted'] = 'Roles that can view after submission ONLY';
$string['rulecohort'] = 'Audience member';
$string['ruledesc_cohortin'] = 'User is a member of any of these audiences';
$string['ruledesc_cohortnotin'] = 'User is NOT a member of any of these audiences';
$string['ruledesc_managerin'] = 'User is';
$string['ruledesc_managernotin'] = 'User is NOT';
$string['ruledesc_not'] = 'NOT';
$string['ruledesc_rolein'] = 'User has the role';
$string['ruledesc_roleincoursegroupin'] = 'User is in Learner\'s Course Group and has the role';
$string['ruledesc_rolenotin'] = 'User does NOT have the role';
$string['rulemanager'] = 'Manager';
$string['rulerole'] = 'Role';
$string['ruleroleincoursegroup'] = 'Role in Learner\'s Course Group';
$string['rulesetx'] = 'Ruleset #{$a}';
$string['ruleusercountwarning'] = 'Warning: some of the rules you have configured refer to an audience, role or group with many users. Before activating this assessment version, please ensure that this is correct, or that you have configured additional, combined rules which will limit the potential number of assigned evaluators/reviewers.';
$string['ruleusercountwarningx'] = '{$a->rolename} rule for {$a->ruletype} can select {$a->usercount} users';
$string['saveprogress'] = 'Save progress';
$string['selected'] = 'Selected';
$string['stage'] = 'Stage';
$string['stagecontent'] = '{$a}\'s content';
$string['stagelockoptions'] = 'Stage lock';
$string['stagename'] = 'Stage name';
$string['stages'] = 'Stages';
$string['status'] = 'Status';
$string['switchrole'] = 'Switch role';
$string['submissionerror'] = 'You cannot submit this page until you fix all the errors';
$string['submit'] = 'Submit';
$string['submitandnextpage'] = 'Submit and go to next page';
$string['tab_content'] = 'Content';
$string['tab_dashboardarchived'] = 'Archived';
$string['tab_dashboardstandard'] = 'In Progress';
$string['tab_dashboardfailed'] = 'Failed';
$string['tab_dashboardcompleted'] = 'Completed';
$string['tab_dashboardcompleted_reviewed'] = 'Completed &amp; Reviewed';
$string['tab_evaluatorrules'] = 'Evaluator rules';
$string['tab_evaluatorassign'] = 'Assigned evaluators';
$string['tab_reviewerrules'] = 'Reviewer rules';
$string['tab_reviewerassign'] = 'Assigned reviewers';
$string['tab_versions'] = 'Versions';
$string['task:sendassignmentnotifications'] = 'Send Evaluator Assignment Notifications';
$string['task:updateroleassignments'] = 'Update User Role Assignments';
$string['terminated'] = 'Terminated before completion';
$string['timeclosed'] = 'Closed date';
$string['timeopened'] = 'Start date';
$string['uploadfile'] = 'Upload file';
$string['version'] = 'Version';
$string['versions'] = 'Versions';
$string['versionx'] = 'Version {$a}';
$string['versionroleassignment'] = 'Direct role assignments';
$string['viewinguser'] = 'You are viewing {$a}\'s activity';
$string['viewinguserasrole'] = 'You are viewing {$a->learnername}\'s activity as the {$a->rolename}';
$string['viewactivity'] = 'View activity';
$string['visiblesubmittedinfo'] = 'Shown on submission';
$string['waitingforrolex'] = 'Waiting for {$a} to complete attempt';
$string['xuseranswer'] = '{$a}\'s answer';
$string['youranswer'] = 'Your answer';

$string['question_gradeweight'] = 'Grade weight of this response';
$string['question_includegrade'] = 'Include this response in the grade';

$string['assessment:addinstance'] = 'Add a new skills assessment';
$string['assessment:editinstance'] = 'Configure an skills assessment\'s content and rules.';
$string['assessment:view'] = 'View a skills assessment';
$string['assessment:viewarchiveddashboard'] = 'View the archived skills assessment dashboard';
$string['assessment:viewasanotherrole'] = 'View someone else\'s skills assessment as another role (e.g. evaluator)';
$string['assessment:viewdashboard'] = 'View the skills assessment dashboard';
$string['assessment:viewcompleteddashboard'] = 'View the completed skills assessment dashboard';
$string['assessment:viewcompletedrevieweddashboard'] = 'View the completed &amp; reviewed skills assessment dashboard';
$string['assessment:viewfaileddashboard'] = 'View the failed skills assessment dashboard';

$string['messageprovider:completion'] = 'Notification of report\'s completion';
$string['messageprovider:evaluatorselected'] = 'Notification of being selected as an evalutator';

$string['msg:completion:subject'] = '{$a->fullname} has completed the activity {$a->assessmentname}';
$string['msg:completion:body'] = '{$a->fullname} has completed the activity {$a->assessmentname}: {$a->courselink}';
$string['msg:evaluatorassigned:subject'] = 'You have been assigned as an evaluator in {$a->fullname}\'s skills assessment activity';
$string['msg:evaluatorassigned:body'] = 'You have been assigned as an evaluator in {$a->fullname}\'s skills assessment activity: {$a->assessmentlink}';
$string['msg:evaluatorselected:subject'] = '{$a->fullname} has chosen you as the evaluator for a skills assessment activity';
$string['msg:evaluatorselected:body'] = '{$a->fullname} has chosen you as the valuator for a skills assessment activity: {$a->assessmentlink}';

$string['navigation:archiveddashboard'] = 'Archived Skills Assessment Dashboard';
$string['navigation:dashboard'] = 'Skills Assessment Dashboard';
$string['navigation:faileddashboard'] = 'Failed Skills Assessment Dashboard';
$string['navigation:completeddashboard'] = 'Completed Skills Assessment Dashboard';
$string['navigation:completedrevieweddashboard'] = 'Completed &amp; Reviewed Skills Assessment Dashboard';

$string['notification:extraattemptssaved'] = 'The number of attempts for {$a->fullname} in {$a->activityname} has been set to {$a->attempts}.';

$string['error:import_assignmentexists'] = 'Assignment already exists';
$string['error:import_emptycsv'] = 'CSV has no records';
$string['error:import_missingevaluatorid'] = 'CSV missing the required column: evaluatorid';
$string['error:import_missingreviewerid'] = 'CSV missing the required column: reviewerid';
$string['error:import_missinglearnerid'] = 'CSV missing the required column: learnerid';
$string['error:import_unknown'] = 'CSV row encountered an unknown error';
$string['error:import_learnernotenrolled'] = 'Learner is not enrolled';
$string['error:import_learnernotfound'] = 'Learner does not exist';
$string['error:import_usernotfound'] = 'User does not exist';
$string['error:missingrequired'] = 'There are missing required fields.  Please review your answers.';
$string['error:noattemptselected'] = 'No skills assessment or attempt selected';
$string['error:nodashboardaccess'] = 'You do not have permissions to view this dashboard.';
$string['error:nonnegativeint'] = 'Must be a non-negative integer';
$string['error:notevaluator'] = 'You are not the evaluator for this user.';
$string['error:notevaluatororreviewer'] = 'You are not the evaluator/reviewer for this user.';
$string['error:norole'] = 'You have no role for this attempt.';
$string['error:positiveint'] = 'Must be a positive integer';
$string['error_inactive'] = 'This skills assessment is not currently active.';
$string['error:invalidmove'] = 'Cannot move this item this direction.';
$string['error_noactiveattempts'] = 'No active attempts to review.';
$string['error_noavailableattempts'] = 'No more attempts available.';
$string['error_noevaluatorrules'] = 'Evaluator rules must be configured.';
$string['error_noreviewerrules'] = 'Reviewer rules must be configured, if question any permissions for reviewers have been set.';
$string['error_gradepassrequired'] = '\'Grade to pass\' must be greater than 0 when \'Require passing grade\' activity completion setting is enabled.';
$string['error_noevaluatorselected'] = 'No evaluator selected';
$string['error_nobodyselected'] = 'Nobody selected';
$string['error_noonecanview'] = 'No users can answer or view the question';
$string['error_nouserrules'] = 'No rules set for {$a} role';
$string['error_stagelockonfirststage'] = 'The first stage cannot have an active lock';
$string['error_versionlock'] = 'This action is not allowed at this time.  The version is not in draft mode';
$string['error:noaccess'] = 'You do not have access to this course module instance';

$string['duedates:saved'] = 'Assessment due dates saved.';
$string['duedates:usersupdated'] = 'Also updated the due dates for enrolled users';
$string['duetype:enrolled'] = 'Course Enrollment Date';
$string['duetype:firstlogin'] = 'First Login';
$string['duetype:fixed'] = 'Fixed Due Date';
$string['duetype:none'] = 'None';
$string['duetype:profilefield'] = 'Profile Field Date';
$string['label:duefieldid'] = 'Profile field';
$string['label:duefieldid_help'] = 'Select a date or date time custom user profile field';
$string['label:duetype'] = 'Due Date Type';
$string['label:duetype_help'] = '
* Fixed date requires a fixed due date for all users
* First Login - will calculate the due date from when the user first logged in
* Course enrollment - will calculate the due date from when the user was enrolled on the course
* Profile field - will calculate the due date using a custom profile date field';
$string['label:periodgroup'] = 'Due date period';
$string['label:periodgroup_help'] = 'The due date will be calculated by adding the period to the date for these types

* First Login
* Course enrollment
* Profile field';
$string['label:timedue'] = 'Fixed due date';
$string['label:timedue_help'] = 'The due date is fixed for all users';
$string['period:days'] = 'Days';
$string['period:months'] = 'Months';
$string['period:weeks'] = 'Weeks';
$string['period:years'] = 'Years';
$string['tab_duedates'] = 'Due dates';
$string['userdataitem-user-attempts'] = 'Attempts';
$string['userdataitem-user-attempts_help'] = 'Export/purge user attempt data and files uploaded to an attempt';
$string['userdataitem-user-version-assignment'] = 'Imported version assignment';
$string['userdataitem-user-version-assignment_help'] = 'Export/purge user assignments that were made via the "direct role assignments" functionality, including import logs';
