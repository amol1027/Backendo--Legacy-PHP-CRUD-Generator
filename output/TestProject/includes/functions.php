<?php
/**
 * Helper Functions
 */

/**
 * Sanitize user input to prevent XSS attacks
 * 
 * @param string $input The input to sanitize
 * @return string The sanitized input
 */
function sanitize_input($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}
