<?php
/**
 * Test Simple Export Script
 * This script sets up session data and then includes the simplified export script
 */

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// Set up project data
$_SESSION['project_name'] = 'TestProject';
$_SESSION['author'] = 'Test User';

// Set up a simple table structure
$_SESSION['tables'] = [
    [
        'name' => 'users',
        'fields' => [
            [
                'name' => 'username',
                'type' => 'varchar',
                'length' => '50',
                'default' => '',
                'nullable' => false,
                'constraints' => ['unique']
            ],
            [
                'name' => 'email',
                'type' => 'varchar',
                'length' => '100',
                'default' => '',
                'nullable' => false,
                'constraints' => ['unique']
            ],
            [
                'name' => 'password',
                'type' => 'varchar',
                'length' => '255',
                'default' => '',
                'nullable' => false,
                'constraints' => []
            ],
            [
                'name' => 'created_at',
                'type' => 'datetime',
                'default' => 'CURRENT_TIMESTAMP',
                'nullable' => false,
                'constraints' => []
            ]
        ]
    ]
];

// Set up authentication options
$_SESSION['auth'] = [
    'enabled' => true,
    'roles' => ['admin', 'user']
];

// Set up features
$_SESSION['features'] = [
    'crud' => true,
    'export_csv' => true,
    'search' => true,
    'soft_delete' => false,
    'bootstrap' => true
];

// Set up output options
$_SESSION['output_options'] = [
    'include_readme' => true,
    'include_sample_data' => true,
    'minify_output' => false
];

// Include the simple export script
include('generator/export_simple.php');