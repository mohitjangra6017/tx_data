<?php

 /**
 * Class for backup and restore of each element
 *
 * @copyright  City & Guilds Kineo 2023-03-23 {@link http://www.kineo.com}
 * @author     tri.le
 * @version    1.0
 */

namespace local_backup\element;

abstract class base {

    abstract function get_name();

    function config_definition(\MoodleQuickForm $mform) {
        $classname = get_class($this);
        $mform->addElement('checkbox', "{$classname}[enable]", get_string('enable'));
    }

    abstract function export(\ZipArchive $archive, $config=null);
    abstract function preview_import(\ZipArchive $archive);
    abstract function import(\ZipArchive $zip, $selectedchanges);

    static function get_all() {
        $files = scandir(__DIR__);

        $elements = [];
        foreach ($files as $filename) {
            if ($filename=='.' || $filename=='..' || $filename=='base.php') {
                continue;
            }

            require_once(__DIR__."/{$filename}");
            $clasname = substr($filename, 0, -strlen('.php'));
            $fullclassname = '\\local_backup\\element\\'.$clasname;
            if (class_exists($fullclassname)) {
                $elements[$clasname] = new $fullclassname;
            }
        }
        return $elements;
    }

    /**
     * Get the element class instance
     * @param string $name the name of the element
     * @return base the element class instance
     */
    static function get($name) {
        $classname = "\\local_backup\\element\\{$name}";
        if (class_exists($classname)) {
            return new $classname;
        }
        return null;
    }
}