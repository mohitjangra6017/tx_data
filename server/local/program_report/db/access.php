<?php
defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'local/program_report:viewreport' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
            'manager' => CAP_ALLOW
        ]
    ]
];