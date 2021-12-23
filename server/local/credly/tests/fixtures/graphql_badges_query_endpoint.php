<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */
// This file is designed as an alternative to calling the Credly Badges API Endpoint.
// Care has been taken to ensure this file cannot be loaded from a production system.
// See /local/credly/tests/badge_test.php for usage.

define('ABORT_AFTER_CONFIG', 1);
require_once __DIR__ . '/../../../../config.php';
global $CFG;
if ($CFG->sitetype !== 'development' && $CFG->debug !== DEBUG_DEVELOPER) {
    header('Content-Type: application/json');
    echo json_encode(['error' => ['message' => 'oops']]);
    die;
}

$response = [
    'data' => [
        [
            'id' => 'badge1',
            'name' => 'Test Badge 1',
        ],
        [
            'id' => 'badge2',
            'name' => 'Test Badge 2',
        ],
        [
            'id' => 'badge3',
            'name' => 'Test Badge 3',
        ]
    ],
    'metadata' => [
        'total_count' => 3
    ]
];

header('Content-Type: application/json');
echo json_encode($response);
