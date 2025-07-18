<?php
/**
 * Database Import Script
 * 
 * This script imports the database schema from db.sql
 */

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Add basic styling
echo "<!DOCTYPE html>\n<html>\n<head>\n";
echo "<title>Database Import Tool</title>\n";
echo "<style>\n";
echo "body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }\n";
echo ".success { color: green; padding: 10px; background-color: #f0fff0; border: 1px solid #d0e9c6; margin: 20px 0; }\n";
echo ".error { color: #a94442; padding: 10px; background-color: #f2dede; border: 1px solid #ebccd1; margin: 20px 0; }\n";
echo ".info { background-color: #d1ecf1; color: #0c5460; padding: 10px; border: 1px solid #bee5eb; margin: 20px 0; }\n";
echo "h2 { color: #333; }\n";
echo "pre { background: #f4f4f4; padding: 10px; overflow: auto; }\n";
echo "ol li { margin-bottom: 8px; }\n";
echo "</style>\n";
echo "</head>\n<body>\n";

echo "<h2>Database Import Tool</h2>";

// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'php_crud_generator';

// Function to execute SQL statements one by one
function executeSqlFile($pdo, $sqlFile) {
    // Read the SQL file
    $sql = file_get_contents($sqlFile);
    if ($sql === false) {
        throw new Exception("Error reading $sqlFile");
    }
    
    // Split the SQL file at semicolons to get individual queries
    $queries = explode(';', $sql);
    
    // Execute each query
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            try {
                $pdo->exec($query);
            } catch (PDOException $e) {
                // Display the query that caused the error
                echo "<div class='error'>";
                echo "<p><strong>Error in query:</strong></p>";
                echo "<pre>" . htmlspecialchars($query) . "</pre>";
                echo "<p>" . $e->getMessage() . "</p>";
                echo "</div>";
            }
        }
    }
    return true;
}

// Connect to MySQL without selecting a database
try {
    $pdo = new PDO("mysql:host=$db_host", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Execute the SQL file
    if (executeSqlFile($pdo, 'db.sql')) {
        echo "<div class='success'>";
        echo "<p><strong>Success!</strong> Database schema imported successfully.</p>";
        echo "<p>The database 'php_crud_generator' has been created with all required tables.</p>";
        echo "</div>";
        
        // Check if the database was actually created
        try {
            $checkDb = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
            
            // Count tables in the database
            $stmt = $checkDb->query("SHOW TABLES");
            $tableCount = $stmt->rowCount();
            
            echo "<div class='success'>";
            echo "<p>Successfully connected to the '$db_name' database.</p>";
            echo "<p>Found $tableCount tables in the database.</p>";
            echo "</div>";
        } catch (PDOException $e) {
            echo "<div class='error'>";
            echo "<p>Database was created but could not connect to it: " . $e->getMessage() . "</p>";
            echo "</div>";
        }
        
        echo "<h3>Next Steps</h3>";
        echo "<ol>";
        echo "<li>Go to <a href='welcome.php'>welcome page</a> to start using the PHP CRUD Generator</li>";
        echo "<li>Create your first project</li>";
        echo "<li>Define your database tables</li>";
        echo "<li>Choose whether to include authentication (login/registration) in your generated application</li>";
        echo "<li>Select features for your application</li>";
        echo "<li>Generate your PHP application</li>";
        echo "</ol>";
        
        echo "<div class='info'>";
        echo "<p><strong>Note:</strong> Login and registration are optional features that you can choose to include in your generated application. The database schema includes a users table that will be used if you enable authentication.</p>";
        echo "</div>";
    }
} catch (PDOException $e) {
    echo "<div class='error'>";
    echo "<p><strong>Error!</strong> Failed to import database schema.</p>";
    echo "<p>Error message: " . $e->getMessage() . "</p>";
    echo "</div>";
    
    echo "<h3>Troubleshooting</h3>";
    echo "<ol>";
    echo "<li>Make sure MySQL server is running</li>";
    echo "<li>Check that the username and password are correct</li>";
    echo "<li>Ensure that the 'root' user has permission to create databases</li>";
    echo "<li>If the database already exists, you may need to drop it first</li>";
    echo "</ol>";
} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<p><strong>Error!</strong> " . $e->getMessage() . "</p>";
    echo "</div>";
}

echo "</body>\n</html>";

?>