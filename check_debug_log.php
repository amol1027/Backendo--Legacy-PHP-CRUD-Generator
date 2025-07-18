<?php
// Check if debug.log exists and display its contents
$debug_log_path = __DIR__ . '/debug.log';
$export_error_log_path = __DIR__ . '/export_error.log';

echo "<h1>Debug Log Check</h1>";

echo "<h2>Debug Log</h2>";
if (file_exists($debug_log_path)) {
    echo "<p>debug.log exists at: {$debug_log_path}</p>";
    echo "<pre>" . htmlspecialchars(file_get_contents($debug_log_path)) . "</pre>";
} else {
    echo "<p>debug.log does not exist at: {$debug_log_path}</p>";
}

echo "<h2>Export Error Log</h2>";
if (file_exists($export_error_log_path)) {
    echo "<p>export_error.log exists at: {$export_error_log_path}</p>";
    echo "<pre>" . htmlspecialchars(file_get_contents($export_error_log_path)) . "</pre>";
} else {
    echo "<p>export_error.log does not exist at: {$export_error_log_path}</p>";
}

// Check output and temp directories
echo "<h2>Output Directory</h2>";
$output_dir = __DIR__ . '/output';
if (is_dir($output_dir)) {
    echo "<p>Output directory exists at: {$output_dir}</p>";
    echo "<p>Contents:</p><ul>";
    $files = scandir($output_dir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "<li>{$file}</li>";
        }
    }
    echo "</ul>";
} else {
    echo "<p>Output directory does not exist at: {$output_dir}</p>";
}

echo "<h2>Temp Directory</h2>";
$temp_dir = __DIR__ . '/temp';
if (is_dir($temp_dir)) {
    echo "<p>Temp directory exists at: {$temp_dir}</p>";
    echo "<p>Contents:</p><ul>";
    $files = scandir($temp_dir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "<li>{$file}</li>";
        }
    }
    echo "</ul>";
} else {
    echo "<p>Temp directory does not exist at: {$temp_dir}</p>";
}