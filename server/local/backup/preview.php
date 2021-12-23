<?php

 /**
 * Page for previewing changes before exporting
 *
 * @copyright  City & Guilds Kineo 2023-03-23 {@link http://www.kineo.com}
 * @author     tri.le
 * @version    1.0
 */

namespace local_backup;

use core\notification;

global $CFG, $OUTPUT;

require_once('../../config.php');
require_once($CFG->libdir.'/adminlib.php');

require_login();

admin_externalpage_setup('local_backup/restore');

$fileid = required_param('fileid', PARAM_INT);
$cancelled = optional_param('cancel', '', PARAM_TEXT);
$restoreurl = new \moodle_url('/local/backup/restore.php');

$fs = get_file_storage();
$filecontext = \context_user::instance($USER->id);
$files = $fs->get_area_files($filecontext->id, 'user', 'draft', $fileid, 'id', false);

if ($cancelled) {
    foreach ($files as $file) {
        $file->delete();
    }
    notification::warning(get_string('restore_cancelled', 'local_backup'));
    redirect($CFG->wwwroot . '/local/backup/restore.php');
}

if (!$files || array_values($files)[0]->get_userid()!=$USER->id) {
    print_error('invalid_parameter: file_id');
}
$file = array_values($files)[0];

$filepath = tempnam($CFG->tempdir, 'configimport');
$file->copy_content_to($filepath);

$importer = new importer($filepath);

if (($data = data_submitted()) && !empty($data->submitapplybutton)) {
    $importer->import($data);
    redirect($restoreurl, get_string('selectedsettingsapplied', 'local_backup'));
}

if ($importer->backup_version !== $importer->our_version) {
    notification::warning(get_string('version_warning', 'local_backup', ['source' => $importer->backup_version, 'dest' => $importer->our_version]));
    redirect($CFG->wwwroot . '/local/backup/restore.php');
}

echo $OUTPUT->header();

echo \html_writer::start_tag('form', ['method' => 'POST']);
echo $importer->preview();
echo \html_writer::empty_tag('input', ['type' => 'submit', 'name' => 'submitapplybutton', 'value' => get_string('applychanges', 'local_backup')]);
echo \html_writer::empty_tag('input', ['type' => 'submit', 'name' => 'cancel', 'value' => get_string('cancel_restore', 'local_backup')]);
echo \html_writer::end_tag('form');

unset($importer);
unlink($filepath);
echo $OUTPUT->footer();