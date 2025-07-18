<?php
// Test error logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Trigger an error
trigger_error('This is a test error', E_USER_WARNING);

// Try to use an undefined variable
echo $undefined_variable;

echo 'Test completed';
?>