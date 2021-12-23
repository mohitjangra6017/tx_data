<?php

defined('MOODLE_INTERNAL') || die;

function local_core_requires(string $library): void
{
    global $PAGE;
    $PAGE->requires->js('/local/core/js/shim.js');

    if (file_exists(__DIR__ . "/libs/{$library}/{$library}.min.css")) {
        $PAGE->requires->css("/local/core/libs/{$library}/{$library}.min.css");
    } else if (file_exists(__DIR__ . "/libs/{$library}/{$library}.css")) {
        $PAGE->requires->css("/local/core/libs/{$library}/{$library}.css");
    }
}