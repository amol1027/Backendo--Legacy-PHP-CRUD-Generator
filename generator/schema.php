<?php
/**
 * Backendo: Legacy PHP CRUD Generator
 * Database Schema Generator
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/functions.php';

/**
 * Generate MySQL schema from table definitions
 * 
 * @param array $tables The table definitions
 * @param bool $include_sample_data Whether to include sample data
 * @return string The SQL schema
 */
function generate_schema($tables, $include_sample_data = false) {
    $sql = "-- Backendo: Legacy PHP CRUD Generator\n";
    $sql .= "-- Generated on " . date('Y-m-d H:i:s') . "\n\n";
    
    // Add database creation
    $project_name = isset($_SESSION['project']['name']) ? sanitize_input($_SESSION['project']['name']) : 'backendo_project';
    $db_name = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', $project_name));
    
    $sql .= "-- Create database\n";
    $sql .= "CREATE DATABASE IF NOT EXISTS `{$db_name}`;\n";
    $sql .= "USE `{$db_name}`;\n\n";
    
    // Add tables
    foreach ($tables as $table) {
        $table_name = sanitize_input($table['name']);
        $sql .= "-- Table structure for table `{$table_name}`\n";
        $sql .= "CREATE TABLE `{$table_name}` (\n";
        
        $fields = [];
        $primary_keys = [];
        $unique_keys = [];
        $indexes = [];
        $foreign_keys = [];
        
        // Process fields
        foreach ($table['fields'] as $field) {
            $field_name = sanitize_input($field['name']);
            $field_type = get_mysql_type($field['type'], $field['length']);
            $nullable = $field['nullable'] ? 'NULL' : 'NOT NULL';
            $default = '';
            
            // Handle default value
            if (isset($field['default']) && $field['default'] !== '') {
                if ($field['type'] === 'int' || $field['type'] === 'decimal') {
                    $default = "DEFAULT {$field['default']}";
                } else {
                    $default = "DEFAULT '{$field['default']}'";
                }
            } elseif ($field['nullable']) {
                $default = 'DEFAULT NULL';
            }
            
            // Add field definition
            $fields[] = "  `{$field_name}` {$field_type} {$nullable} {$default}";
            
            // Process constraints
            if (isset($field['constraints'])) {
                foreach ($field['constraints'] as $constraint) {
                    if ($constraint === 'primary') {
                        $primary_keys[] = $field_name;
                    } elseif ($constraint === 'unique') {
                        $unique_keys[] = $field_name;
                    } elseif ($constraint === 'index') {
                        $indexes[] = $field_name;
                    } elseif ($constraint === 'foreign') {
                        // Foreign keys will be handled separately
                        // This is a simplified version, in a real app we would need to specify the referenced table and field
                        $foreign_keys[] = $field_name;
                    }
                }
            }
        }
        
        // Add soft delete field if enabled
        if (isset($_SESSION['features']['soft_delete']) && $_SESSION['features']['soft_delete']) {
            $fields[] = "  `deleted_at` DATETIME DEFAULT NULL";
        }
        
        // Add audit trail fields if enabled
        if (isset($_SESSION['features']['audit']) && $_SESSION['features']['audit']) {
            $fields[] = "  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP";
            $fields[] = "  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
            $fields[] = "  `created_by` INT NULL";
            $fields[] = "  `updated_by` INT NULL";
        }
        
        // Add primary key constraint
        if (!empty($primary_keys)) {
            $fields[] = "  PRIMARY KEY (`" . implode('`, `', $primary_keys) . "`)";
        }
        
        // Add unique constraints
        foreach ($unique_keys as $i => $key) {
            $fields[] = "  UNIQUE KEY `{$table_name}_unique_{$i}` (`{$key}`)";
        }
        
        // Add indexes
        foreach ($indexes as $i => $key) {
            $fields[] = "  KEY `{$table_name}_index_{$i}` (`{$key}`)";
        }
        
        // Combine all field definitions
        $sql .= implode(",\n", $fields);
        $sql .= "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";
        
        // Add foreign key constraints (in a real app, we would need to add these after all tables are created)
        // This is just a placeholder for demonstration
        if (!empty($foreign_keys)) {
            $sql .= "-- Foreign key constraints for `{$table_name}` would be added here\n\n";
        }
    }
    
    // Add users table if authentication is enabled
    if (isset($_SESSION['auth']['enabled']) && $_SESSION['auth']['enabled']) {
        $sql .= generate_users_table();
    }
    
    // Add sample data if requested
    if ($include_sample_data) {
        $sql .= generate_sample_data($tables);
    }
    
    return $sql;
}

/**
 * Generate users table for authentication
 * 
 * @return string The SQL for users table
 */
function generate_users_table() {
    $sql = "-- Table structure for users authentication\n";
    $sql .= "CREATE TABLE `users` (\n";
    $sql .= "  `id` INT NOT NULL AUTO_INCREMENT,\n";
    $sql .= "  `username` VARCHAR(50) NOT NULL,\n";
    $sql .= "  `password` VARCHAR(255) NOT NULL,\n";
    $sql .= "  `email` VARCHAR(100) NOT NULL,\n";
    $sql .= "  `role` ENUM('admin', 'user', 'guest') NOT NULL DEFAULT 'user',\n";
    $sql .= "  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,\n";
    $sql .= "  `last_login` DATETIME DEFAULT NULL,\n";
    $sql .= "  PRIMARY KEY (`id`),\n";
    $sql .= "  UNIQUE KEY `users_username_unique` (`username`),\n";
    $sql .= "  UNIQUE KEY `users_email_unique` (`email`)\n";
    $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";
    
    // Add default admin user
    $sql .= "-- Default admin user (password: admin123)\n";
    $sql .= "INSERT INTO `users` (`username`, `password`, `email`, `role`) VALUES\n";
    $sql .= "('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 'admin');\n\n";
    
    return $sql;
}

/**
 * Generate sample data for tables
 * 
 * @param array $tables The table definitions
 * @return string The SQL for sample data
 */
function generate_sample_data($tables) {
    $sql = "-- Sample data\n";
    
    foreach ($tables as $table) {
        $table_name = sanitize_input($table['name']);
        $sql .= "-- Sample data for table `{$table_name}`\n";
        
        // Generate 5 sample records for each table
        $records = [];
        for ($i = 1; $i <= 5; $i++) {
            $fields = [];
            $values = [];
            
            foreach ($table['fields'] as $field) {
                $field_name = sanitize_input($field['name']);
                $fields[] = "`{$field_name}`";
                
                // Generate sample value based on field type
                switch ($field['type']) {
                    case 'int':
                        $values[] = $i;
                        break;
                    case 'varchar':
                        $values[] = "'Sample {$field_name} {$i}'";
                        break;
                    case 'text':
                        $values[] = "'This is a sample text for record {$i}. It contains multiple sentences to demonstrate a longer text field.'";
                        break;
                    case 'date':
                        $values[] = "'" . date('Y-m-d', strtotime("+{$i} days")) . "'";
                        break;
                    case 'datetime':
                        $values[] = "'" . date('Y-m-d H:i:s', strtotime("+{$i} hours")) . "'";
                        break;
                    case 'decimal':
                        $values[] = number_format($i * 10.5, 2, '.', '');
                        break;
                    case 'boolean':
                        $values[] = $i % 2;
                        break;
                    case 'enum':
                        $values[] = "'Option {$i}'";
                        break;
                    default:
                        $values[] = "'Sample {$i}'";
                }
            }
            
            $records[] = "(" . implode(", ", $values) . ")";
        }
        
        if (!empty($records)) {
            $sql .= "INSERT INTO `{$table_name}` (" . implode(", ", $fields) . ") VALUES\n";
            $sql .= implode(",\n", $records) . ";\n\n";
        }
    }
    
    return $sql;
}
?>