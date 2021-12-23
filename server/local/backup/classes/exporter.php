<?php

 /**
 * Class for exporting all configuration to a Zip file
 *
 * @copyright  City & Guilds Kineo 2023-03-23 {@link http://www.kineo.com}
 * @author     tri.le
 * @version    1.0
 */

namespace local_backup;

class exporter {

    /** @var element\base[] */
    private $_elements = [];

    /** @var string */
    private $_filepath;

    /** @var \ZipArchive */
    private $_zip;

    function add_element(element\base $element) {
        $names = explode('\\', get_class($element));
        $shortname = end($names);
        $this->_elements[$shortname]= $element;
    }

    function export() {
        global $CFG;

        $this->_zip = new \ZipArchive();

        $this->_filepath = @tempnam($CFG->tempdir, 'configexport');
        if (!$this->_zip->open($this->_filepath, \ZipArchive::CREATE)) {
            // should never happen
            print_error('cannot_create_archive');
        }

        foreach ($this->_elements as $element) {
            $element->export($this->_zip);
        }
        $this->_zip->addFromString('elements.json', json_encode(array_keys($this->_elements)));

        $this->_zip->close();
    }

    function download($filename=null) {
        global $SITE;

        if (class_exists('\local_core\ComposerPluginInfo')) {
            $tke_version = \local_core\ComposerPluginInfo::getInstance()->getInstalledPluginVersion('tke');
        }

        $this->_zip->open($this->_filepath);
        $this->_zip->addFromString('version.txt', $tke_version ?? '');
        $this->_zip->close();

        if (!$filename) {
            $filename =
                'config_export_' .
                preg_replace('/\s+/', '_', strtolower($SITE->shortname)) .
                userdate(time(), '%Y%m%d%H%M%S') .
                '.zip';
        }
        send_file($this->_filepath, $filename, 0, 0, false, true, 'application/zip');
    }

    function __destruct() {
        unlink($this->_filepath);
    }
}