<?php

 /**
 * Page for exporting configuration
 *
 * @copyright  City & Guilds Kineo 2023-03-23 {@link http://www.kineo.com}
 * @author     tri.le
 * @version    1.0
 */

namespace local_backup;

require_once('../../config.php');
require_once($CFG->libdir.'/adminlib.php');

require_login();

admin_externalpage_setup('local_backup/backup');

$mform = new form\backup_form();
if ($mform->is_submitted() && $mform->is_validated()) {
    $data = $mform->get_data();

    $exporter = new exporter();
    foreach (array_keys($data->elements) as $elementname) {
        $element = element\base::get($elementname);
        $exporter->add_element($element);
    }

    $exporter->export();
    $exporter->download();
}

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('exportsiteconfiguration', 'local_backup'));

$mform->display();

echo $OUTPUT->footer();