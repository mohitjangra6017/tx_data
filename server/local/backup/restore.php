<?php

 /**
 * Page for importing configuration
 *
 * @copyright  City & Guilds Kineo 2023-03-23 {@link http://www.kineo.com}
 * @author     tri.le
 * @version    1.0
 */

namespace local_backup;

require_once('../../config.php');
require_once($CFG->libdir.'/adminlib.php');

require_login();

admin_externalpage_setup('local_backup/restore');

$mform = new form\restore_form();
if ($mform->is_submitted() && $mform->is_validated()) {
    $data = $mform->get_data();
    redirect(new \moodle_url('/local/backup/preview.php', ['fileid' => $data->importfile]));
}

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('importsiteconfiguration', 'local_backup'));

$mform->display();

echo $OUTPUT->footer();