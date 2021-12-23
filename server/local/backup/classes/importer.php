<?php

 /**
 * Class for importing all configurations from a Zip file
 *
 * @copyright  City & Guilds Kineo 2023-03-23 {@link http://www.kineo.com}
 * @author     tri.le
 * @version    1.0
 */

namespace local_backup;

use core\notification;

class importer {

    /** @var \ZipArchive */
    private $_zip;

    public $backup_version;

    public $our_version;

    function __construct($filepath) {
        $zip = new \ZipArchive();
        if (($status = $zip->open($filepath)) !== true) {
            print_error('ZIP Failed to open with error: ' . $status);
        }
        $this->_zip = $zip;

        $this->backup_version = $this->_zip->getFromName('version.txt') ?: 'source unknown';
        if (class_exists('\local_core\ComposerPluginInfo')) {
            $this->our_version = \local_core\ComposerPluginInfo::getInstance()->getInstalledPluginVersion('tke');
        } else {
            $this->our_version = 'destination unknown';
        }
    }

    function preview() {
        $elements = json_decode($this->_zip->getFromName('elements.json'));
        $html = '';
        foreach ($elements as $elementname) {
            $element = element\base::get($elementname);
            $html .= \html_writer::tag('h2', $element->get_name());
            $html .= $element->preview_import($this->_zip);
        }
        return $html;
    }

    function import($selecteddata) {
        $elements = json_decode($this->_zip->getFromName('elements.json'));
        foreach ($elements as $elementname) {
            $element = element\base::get($elementname);
            $element->import($this->_zip, $selecteddata->$elementname);
        }
    }

    function __destruct() {
        $this->_zip->close();
    }
}