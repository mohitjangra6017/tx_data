<?php
defined('MOODLE_INTERNAL') || die();
$capabilities = [
    'theme/kineo:viewadminregion' => [
        'riskbitmask' => RISK_CONFIG,
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [],
    ],
];