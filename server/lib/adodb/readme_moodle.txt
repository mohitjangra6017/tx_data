Description of ADODB library import into Totara

This library is now used only in enrol and auth db plugins.
The core DML drivers are not using ADODB any more.

Removed:
 * contrib/
 * cute_icons_for_site/
 * docs/
 * pear/
 * replicate/
 * scripts/
 * session/
 * tests/
 * composer.json
 * server.php
 * lang/* except en (because they were not in utf8)

Added:
 * index.html - prevent directory browsing on misconfigured servers
 * readme_moodle.txt - this file ;-)

Our changes:
 * MDL-52286 Added muting errors in ADORecordSet::__destruct().
   Check if fixed upstream during the next upgrade and remove this note.
 * TL-14768 Added fix in ADODB_mssqlnative::_connect() to ensure that host and port are separated by ,
 * TL-29157 temporary PHP 8.0 fixes in ADODB_oci8po

skodak, iarenaza, moodler, stronk7, abgreeve, lameze, rianar
