<?php
/**
 * Backendo: Legacy PHP CRUD Generator
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

/**
 * Generate a valid PHP variable name from a string
 * 
 * @param string $string The string to convert
 * @return string A valid PHP variable name
 */
function to_variable_name($string) {
    // Replace spaces and special chars with underscores
    $string = preg_replace('/[^a-zA-Z0-9_]/', '_', $string);
    // Remove leading numbers
    $string = preg_replace('/^[0-9]+/', '', $string);
    // Convert to camelCase
    $string = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $string))));
    return $string;
}

/**
 * Convert a string to singular form (very basic implementation)
 * 
 * @param string $string The string to convert
 * @return string The singular form
 */
function to_singular($string) {
    $rules = [
        '/(s)tatuses$/i' => '\1tatus',
        '/^(.*)(e)s$/i' => '\1\2',
        '/(database)s$/i' => '\1',
        '/(quiz)zes$/i' => '\1',
        '/^(ox)en/i' => '\1',
        '/(matr|vert|ind)ices$/i' => '\1ix',
        '/(x|ch|ss|sh)es$/i' => '\1',
        '/([^aeiouy]|qu)ies$/i' => '\1y',
        '/([lr])ves$/i' => '\1f',
        '/(shea|lea|loa|thie)ves$/i' => '\1f',
        '/(^analy)ses$/i' => '\1sis',
        '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '\1\2sis',
        '/([ti])a$/i' => '\1um',
        '/(n)ews$/i' => '\1ews',
        '/(ss)$/i' => '\1',
        '/s$/i' => ''
    ];

    foreach ($rules as $pattern => $replacement) {
        if (preg_match($pattern, $string)) {
            return preg_replace($pattern, $replacement, $string);
        }
    }

    return $string;
}

/**
 * Convert a string to plural form (very basic implementation)
 * 
 * @param string $string The string to convert
 * @return string The plural form
 */
function to_plural($string) {
    $rules = [
        '/(quiz)$/i' => '\1zes',
        '/^(ox)$/i' => '\1en',
        '/([m|l])ouse$/i' => '\1ice',
        '/(matr|vert|ind)ix|ex$/i' => '\1ices',
        '/(x|ch|ss|sh)$/i' => '\1es',
        '/([^aeiouy]|qu)y$/i' => '\1ies',
        '/(hive)$/i' => '\1s',
        '/(?:([^f])fe|([lr])f)$/i' => '\1\2ves',
        '/sis$/i' => 'ses',
        '/([ti])um$/i' => '\1a',
        '/(buffal|tomat)o$/i' => '\1oes',
        '/(bu)s$/i' => '\1ses',
        '/(alias|status)$/i' => '\1es',
        '/(octop|vir)us$/i' => '\1i',
        '/(ax|test)is$/i' => '\1es',
        '/s$/i' => 's',
        '/$/' => 's'
    ];

    foreach ($rules as $pattern => $replacement) {
        if (preg_match($pattern, $string)) {
            return preg_replace($pattern, $replacement, $string);
        }
    }

    return $string;
}

/**
 * Convert a string to title case
 * 
 * @param string $string The string to convert
 * @return string The title case string
 */
function to_title_case($string) {
    // Replace underscores with spaces
    $string = str_replace('_', ' ', $string);
    // Convert to title case
    return ucwords($string);
}

/**
 * Generate a random string
 * 
 * @param int $length The length of the string
 * @return string A random string
 */
function generate_random_string($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Get MySQL data type from field type
 * 
 * @param string $type The field type
 * @param int $length The field length
 * @return string The MySQL data type
 */
function get_mysql_type($type, $length = null) {
    switch ($type) {
        case 'int':
            return 'INT' . ($length ? "($length)" : '');
        case 'varchar':
            return 'VARCHAR' . ($length ? "($length)" : '(255)');
        case 'text':
            return 'TEXT';
        case 'date':
            return 'DATE';
        case 'datetime':
            return 'DATETIME';
        case 'decimal':
            return 'DECIMAL(10,2)';
        case 'boolean':
            return 'TINYINT(1)';
        case 'enum':
            return 'ENUM';
        default:
            return 'VARCHAR(255)';
    }
}

/**
 * Get PHP form input type from field type
 * 
 * @param string $type The field type
 * @return string The HTML input type
 */
function get_input_type($type) {
    switch ($type) {
        case 'int':
            return 'number';
        case 'varchar':
            return 'text';
        case 'text':
            return 'textarea';
        case 'date':
            return 'date';
        case 'datetime':
            return 'datetime-local';
        case 'decimal':
            return 'number';
        case 'boolean':
            return 'checkbox';
        case 'enum':
            return 'select';
        default:
            return 'text';
    }
}

/**
 * Check if a directory is empty
 * 
 * @param string $dir The directory to check
 * @return bool True if empty, false otherwise
 */
function is_dir_empty($dir) {
    if (!is_readable($dir)) return NULL; 
    return (count(scandir($dir)) == 2);
}

/**
 * Recursively delete a directory
 * 
 * @param string $dir The directory to delete
 * @return bool True on success, false on failure
 */
function delete_directory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }

    return rmdir($dir);
}

/**
 * Create a ZIP archive of a directory
 * 
 * @param string $source The source directory
 * @param string $destination The destination ZIP file
 * @return bool True on success, false on failure
 */
function create_zip($source, $destination) {
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file) {
            $file = str_replace('\\', '/', $file);

            // Skip dot files/dirs
            if (substr($file, strrpos($file, '/') + 1) == '.' || 
                substr($file, strrpos($file, '/') + 1) == '..') {
                continue;
            }

            $file = realpath($file);

            if (is_dir($file) === true) {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            } else if (is_file($file) === true) {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    } else if (is_file($source) === true) {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}
?>