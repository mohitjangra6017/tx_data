<?php

// This file is part of the Certificate module for Moodle - http://moodle.org/
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
 * Language strings for the certificate module
 *
 * @package    mod_certificate
 * @copyright  Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['addlinklabel'] = 'Add another linked activity option';
$string['addlinktitle'] = 'Click to add another linked activity option';
$string['areaintro'] = 'Certificate introduction';
$string['awarded'] = 'Awarded';
$string['awardedto'] = 'Awarded To';
$string['back'] = 'Back';
$string['border'] = 'Border';
$string['borderblack'] = 'Black';
$string['borderblue'] = 'Blue';
$string['borderbrown'] = 'Brown';
$string['bordercolor'] = 'Border Lines';
$string['bordercolor_help'] = 'Since images can substantially increase the size of the pdf file, you may choose to print a border of lines instead of using a border image (be sure the **Border Image** option is set to **No**).  The **Border Lines** option will print a nice border of three lines of varying widths in the chosen colour.';
$string['bordergreen'] = 'Green';
$string['borderlines'] = 'Lines';
$string['borderstyle'] = 'Border Image';
$string['borderstyle_help'] = 'The **Border Image** option allows you to choose a border image from the certificate/pdfresources/borders folder.  Select the border image that you want around the certificate edges or select no border.';
$string['certificate'] = 'Verification for certificate code:';
$string['certificate:addinstance'] = 'Add a certificate instance';
$string['certificate:manage'] = 'Manage a certificate instance';
$string['certificate:printteacher'] = 'Be listed as a trainer on the certificate if the print trainer setting is on';
$string['certificate:receivenotification'] = 'Receive notifications when users are awarded a certificate';
$string['certificate:student'] = 'Retrieve a certificate';
$string['certificate:view'] = 'View a certificate';
$string['certificatename'] = 'Certificate Name';
$string['certificateremoved'] = 'Delete Certificates';
$string['certificatereport'] = 'Certificates Report';
$string['certificatesfor'] = 'Certificates for';
$string['certificatetype'] = 'Certificate Type';
$string['certificatetype_help'] = 'This is where you determine the layout of the certificate. The certificate type folder includes four default certificates:

* **A4 Embedded**: Prints on A4 size paper with embedded font.
* **A4 Non-Embedded**: Prints on A4 size paper without embedded fonts.
* **Letter Embedded**: Prints on letter size paper with embedded font.
* **Letter Non-Embedded**: Prints on letter size paper without embedded fonts.

The non-embedded types use the Helvetica and Times fonts. If you feel your users will not have these fonts on their computer, or if your language uses characters or symbols that are not accommodated by the Helvetica and Times fonts, then choose an embedded type.  The embedded types use the Dejavusans and Dejavuserif fonts.  This will make the pdf files rather large; thus it is not recommended to use an embedded type unless you must.

New type folders can be added to the certificate/type folder. The name of the folder and any new language strings for the new type must be added to the certificate language file.';
$string['certify'] = 'This is to certify that';
$string['code'] = 'Code';
$string['completiondate'] = 'Course Completion';
$string['course'] = 'For';
$string['coursegrade'] = 'Course Grade';
$string['coursename'] = 'Course';
$string['coursetimereq'] = 'Required minutes in course';
$string['coursetimereq_help'] = 'Enter here the minimum amount of time, in minutes, that a student must be logged into the course before they will be able to receive the certificate.';
$string['coursetimereqdisabled'] = 'Only available when Legacy log or Standard log plugin is enabled.';
$string['credithours'] = 'Credit Hours';
$string['customtext'] = 'Custom Text';
$string['customtext_help'] = 'If you want the certificate to print different names for the trainer than those who are assigned
the role of trainer, do not select **Print trainer** or any signature image except for the line image. Enter the trainer names in this text box as you would like them to appear.  By default, this text is placed in the lower left of the certificate. The following HTML tags are available: &lt;br&gt;, &lt;p&gt;, &lt;b&gt;, &lt;i&gt;, &lt;u&gt;, &lt;img&gt; (src and width (or height) are mandatory), &lt;a&gt; (href is mandatory), &lt;font&gt; (possible attributes are: color, (hex color code), face, (arial, times, courier, helvetica, symbol)).';
$string['date'] = 'On';
$string['datefmt'] = 'Date Format';
$string['datefmt_help'] = 'Choose a date format to print the date on the certificate. Or, choose the last option to have the date printed in the format of the user\'s chosen language.';
$string['datehelp'] = 'Date';
$string['deletissuedcertificates'] = 'Delete issued certificates';
$string['delivery'] = 'Delivery';
$string['delivery_help'] = 'Choose here how you would like your learners to get their certificate.

* **Open in Browser**: Opens the certificate in a new browser window.
* **Force Download**: Opens the browser file download window.
* **Email Certificate**: Choosing this option sends the certificate to the learner as an email attachment.

After a user receives their certificate, if they click on the certificate link from the course homepage, they will see the date they received their certificate and will be able to review their received certificate.';
$string['designoptions'] = 'Design Options';
$string['download'] = 'Force download';
$string['emailcertificate'] = 'Email';
$string['emailothers'] = 'Notify Others';
$string['emailothers_help'] = 'Enter the email addresses here, separated by a comma, of those who should be alerted with an email whenever learners receive a certificate.';
$string['emailstudenttext'] = 'Attached is your certificate for {$a->course}.';
$string['emailteachers'] = 'Send Notifications';
$string['emailteachers_help'] = 'If enabled then all users with the **mod/certificate:receivenotifications** capability in this modules context will receive a notification whenever learners are awarded the certificate.';
$string['emailteachermail'] = '
{$a->student} has received their certificate: \'{$a->certificate}\'
for {$a->course}.

You can review it here:

    {$a->url}';
$string['emailteachermailhtml'] = '
{$a->student} has received their certificate: \'<i>{$a->certificate}</i>\'
for {$a->course}.

You can review it here:

    <a href="{$a->url}">Certificate Report</a>.';
$string['entercode'] = 'Enter certificate code to verify:';
$string['fontsans'] = 'Sans-serif font family';
$string['fontsans_desc'] = 'Sans-serif font family for certificates with embedded fonts';
$string['fontserif'] = 'Serif font family';
$string['fontserif_desc'] = 'Serif font family for certificates with embedded fonts';
$string['getcertificate'] = 'Get your certificate';
$string['grade'] = 'Grade';
$string['gradedate'] = 'Grade Date';
$string['gradefmt'] = 'Grade Format';
$string['gradefmt_help'] = 'There are three available formats if you choose to print a grade on the certificate:

* **Percentage Grade**: Prints the grade as a percentage.
* **Points Grade**: Prints the point value of the grade.
* **Letter Grade**: Prints the percentage grade as a letter.';
$string['gradeletter'] = 'Letter Grade';
$string['gradepercent'] = 'Percentage Grade';
$string['gradepoints'] = 'Points Grade';
$string['imagetype'] = 'Image Type';
$string['incompletemessage'] = 'In order to download your certificate, you must first complete all required activities. Please return to the course to complete your coursework.';
$string['intro'] = 'Introduction';
$string['issueoptions'] = 'Issue Options';
$string['issued'] = 'Issued';
$string['issueddate'] = 'Date Issued';
$string['landscape'] = 'Landscape';
$string['lastviewed'] = 'You last received this certificate on:';
$string['letter'] = 'Letter';
$string['lockingoptions'] = 'Locking Options';
$string['modulename'] = 'Certificate';
$string['modulenameplural'] = 'Certificates';
$string['mycertificates'] = 'My Certificates';
$string['nocertificates'] = 'There are no certificates';
$string['nocertificatesissued'] = 'There are no certificates that have been issued';
$string['nocertificatesreceived'] = 'has not received any course certificates.';
$string['nofileselected'] = 'Must choose a file to upload!';
$string['nogrades'] = 'No grades available';
$string['notapplicable'] = 'N/A';
$string['notfound'] = 'The certificate number could not be validated.';
$string['notissued'] = 'Not Issued';
$string['notissuedyet'] = 'Not issued yet';
$string['notreceived'] = 'You have not received this certificate';
$string['openbrowser'] = 'Open in new window';
$string['opendownload'] = 'Click the button below to save your certificate to your computer.';
$string['openemail'] = 'Click the button below and your certificate will be sent to you as an email attachment.';
$string['openwindow'] = 'Click the button below to open your certificate in a new browser window.';
$string['or'] = 'Or';
$string['orientation'] = 'Orientation';
$string['orientation_help'] = 'Choose whether you want your certificate orientation to be portrait or landscape.';
$string['pluginadministration'] = 'Certificate administration';
$string['pluginname'] = 'Certificate';
$string['portrait'] = 'Portrait';
$string['printdate'] = 'Print Date';
$string['printdate_help'] = 'This is the date that will be printed, if a print date is selected. If the course completion date is selected but the learner has not completed the course, the date received will be printed. You can also choose to print the date based on when an activity was graded. If a certificate is issued before that activity is graded, the date received will be printed.';
$string['printerfriendly'] = 'Printer-friendly page';
$string['printhours'] = 'Print Credit Hours';
$string['printhours_help'] = 'Enter here the number of credit hours to be printed on the certificate.';
$string['printgrade'] = 'Print Grade';
$string['printgrade_help'] = 'You can choose any available course grade items from the gradebook to print the user\'s grade received for that item on the certificate.  The grade items are listed in the order in which they appear in the gradebook. Choose the format of the grade below.';
$string['printnumber'] = 'Print Code';
$string['printnumber_help'] = 'A unique 10-digit code of random letters and numbers can be printed on the certificate. This number can then be verified by comparing it to the code number displayed in the certificates report.';
$string['printoutcome'] = 'Print Outcome';
$string['printoutcome_help'] = 'You can choose any course outcome to print the name of the outcome and the user\'s received outcome on the certificate.  An example might be, **Assignment Outcome: Proficient**.';
$string['printseal'] = 'Seal or Logo Image';
$string['printseal_help'] = 'This option allows you to select a seal or logo to print on the certificate from the certificate/pdfresources/seals folder. By default, this image is placed in the lower right corner of the certificate.';
$string['printsignature'] = 'Signature Image';
$string['printsignature_help'] = 'This option allows you to print a signature image from the certificate/pdfresources/signatures folder.  You can print a graphic representation of a signature, or print a line for a written signature. By default, this image is placed in the lower left of the certificate.';
$string['printteacher'] = 'Print Trainer Name(s)';
$string['printteacher_help'] = 'For printing the trainer name on the certificate, set the role of trainer at the module level.  Do this if, for example, you have more than one trainer for the course or you have more than one certificate in the course and you want to print different trainer names on each certificate.  Click to edit the certificate, then click on the **Locally assigned roles** tab.  Then assign the role of trainer (editing trainer) to the certificate (they do not have to be a trainer in the course as you can assign that role within the certificate to anyone).  Those names will be printed on the certificate for trainer.';
$string['printwmark'] = 'Watermark Image';
$string['printwmark_help'] = 'A watermark file can be placed in the background of the certificate. A watermark is a faded graphic. A watermark could be a logo, seal, crest, wording, or whatever you want to use as a graphic background.';
$string['receivedcerts'] = 'Received certificates';
$string['receiveddate'] = 'Date Received';
$string['removecert'] = 'Issued certificates removed';
$string['report'] = 'Report';
$string['reportcert'] = 'Report Certificates';
$string['reportcert_help'] = 'If you choose **yes** here, then this certificate\'s date received, code number, and the course name will be shown on the user certificate reports.  If you choose to print a grade on this certificate, then that grade will also be shown on the certificate report.';
$string['requiredtimenotmet'] = 'You must spend at least a minimum of {$a->requiredtime} minutes in the course before you can access this certificate';
$string['requiredtimenotvalid'] = 'The required time must be a valid number greater than 0';
$string['requiredtimelogstorenotvalid'] = 'Legacy log or Standard log plugin must be enabled.';
$string['reviewcertificate'] = 'Review your certificate';
$string['savecert'] = 'Save Certificates';
$string['savecert_help'] = 'If you choose this option, then a copy of each user\'s certificate PDF file is saved in the course files moddata folder for that certificate. A link to each user\'s saved certificate will be displayed in the certificate report.';
$string['seal'] = 'Seal';
$string['sigline'] = 'line';
$string['signature'] = 'Signature';
$string['statement'] = 'has completed the course';
$string['summaryofattempts'] = 'Summary of Previously Received Certificates';
$string['textoptions'] = 'Text Options';
$string['title'] = 'CERTIFICATE of ACHIEVEMENT';
$string['to'] = 'Awarded to';
$string['typeA4_embedded'] = 'A4 Embedded';
$string['typeA4_non_embedded'] = 'A4 Non-Embedded';
$string['typeletter_embedded'] = 'Letter Embedded';
$string['typeletter_non_embedded'] = 'Letter Non-Embedded';
$string['unsupportedfiletype'] = 'File must be a jpeg or png file';
$string['uploadimage'] = 'Upload image';
$string['uploadimagedesc'] = 'This button will take you to a new screen where you will be able to upload images.';
$string['userdateformat'] = 'User\'s Language Date Format';
$string['userdata_certificate_issues'] = 'Certificate issues';
$string['userdata_certificate_issues_history'] = 'Certificate issues history';
$string['userdata_notify_users'] = 'Certificates using an email address in Notify Users field';
$string['validate'] = 'Verify';
$string['verifycertificate'] = 'Verify Certificate';
$string['view_html_version'] = 'View HTML version';
$string['viewcertificateviews'] = 'View {$a} issued certificates';
$string['viewed'] = 'You received this certificate on:';
$string['viewtranscript'] = 'View Certificates';
$string['watermark'] = 'Watermark';

// Totara strings.

// Date format strings - note format 2 is date() format, the others are srtftime() format
$string['dateformat1'] = '%B %d, %Y';
$string['dateformat2'] = 'F jS, Y';

// Upload stuff.
$string['uplborderdesc'] = 'Upload Certificate Border Files';
$string['uplwatermarkdesc'] = 'Upload Certificate Watermark Files';
$string['uplsealdesc'] = 'Upload Certificate Seal Files';
$string['uplsignaturedesc'] = 'Upload Certificate Signature Files';

// Archive strings
$string['error:certissuenotfound'] = 'Missing certificate issue history record for id : {$a}';
$string['error:certificatenotfound'] = 'Missing certificate record for id : {$a}';
$string['error:usernotfound'] = 'Missing user record for id : {$a}';
$string['error:coursenotfound'] = 'Missing course record for id : {$a}';
$string['viewarchive'] = 'Certificate Archive';
$string['timecompleted'] = 'Time completed course';
$string['outcome'] = 'Outcome';
$string['timearchived'] = 'Time archived';
$string['certificate:viewarchive'] = 'View archive';

// View archive form
$string['filteroptions'] = 'Filter options';
$string['coursenamefilter'] = 'Course name contains';
$string['certnamefilter'] = 'Certificate name contains';
$string['usernamefilter'] = 'Username contains';
$string['firstnamefilter'] = 'First name contains';
$string['lastnamefilter'] = 'Last name contains';

