<?php
/**
 * Backendo: Legacy PHP CRUD Generator
 * Configuration Settings
 */

// Error reporting (turn off in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Application settings
define('APP_NAME', 'Backendo');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']));

// Database connection settings for the generator app itself
// (not used for the generated projects)
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Default XAMPP username
define('DB_PASS', '');     // Default XAMPP password
define('DB_NAME', 'backendo');

// Template settings
define('TEMPLATE_PATH', __DIR__ . '/../templates/');
define('GENERATOR_PATH', __DIR__ . '/../generator/');

// Output settings
define('OUTPUT_PATH', __DIR__ . '/../output/');
define('TEMP_PATH', __DIR__ . '/../temp/');

// Create output and temp directories if they don't exist
if (!file_exists(OUTPUT_PATH)) {
    mkdir(OUTPUT_PATH, 0755, true);
}

if (!file_exists(TEMP_PATH)) {
    mkdir(TEMP_PATH, 0755, true);
}

// Default field types for table designer
$FIELD_TYPES = [
    'int' => 'Integer',
    'varchar' => 'Text (short)',
    'text' => 'Text (long)',
    'date' => 'Date',
    'datetime' => 'Date & Time',
    'decimal' => 'Decimal',
    'boolean' => 'Boolean',
    'enum' => 'Dropdown'
];

// Default constraints
$CONSTRAINTS = [
    'primary' => 'Primary Key',
    'unique' => 'Unique',
    'index' => 'Index',
    'foreign' => 'Foreign Key'
];
?>