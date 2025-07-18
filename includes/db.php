<?php
/**
 * Backendo: Legacy PHP CRUD Generator
 * Database Connection
 */

// Include configuration
require_once 'config.php';

/**
 * Connect to the database
 * 
 * @return mysqli|false Database connection or false on failure
 */
function db_connect() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        return false;
    }
    
    // Set charset
    $conn->set_charset("utf8mb4");
    
    return $conn;
}

/**
 * Execute a query and return the result
 * 
 * @param string $sql The SQL query
 * @param array $params The parameters to bind
 * @param string $types The types of the parameters
 * @return mysqli_result|bool Result object or false on failure
 */
function db_query($sql, $params = [], $types = '') {
    $conn = db_connect();
    
    if (!$conn) {
        return false;
    }
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("Query preparation failed: " . $conn->error);
        $conn->close();
        return false;
    }
    
    // Bind parameters if any
    if (!empty($params)) {
        // If types not provided, generate them
        if (empty($types)) {
            $types = '';
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_float($param)) {
                    $types .= 'd';
                } elseif (is_string($param)) {
                    $types .= 's';
                } else {
                    $types .= 'b';
                }
            }
        }
        
        // Create references for bind_param
        $bindParams = [$stmt, $types];
        for ($i = 0; $i < count($params); $i++) {
            $bindParams[] = &$params[$i];
        }
        
        call_user_func_array('mysqli_stmt_bind_param', $bindParams);
    }
    
    // Execute the statement
    if (!$stmt->execute()) {
        error_log("Query execution failed: " . $stmt->error);
        $stmt->close();
        $conn->close();
        return false;
    }
    
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    
    return $result;
}

/**
 * Get a single row from the database
 * 
 * @param string $sql The SQL query
 * @param array $params The parameters to bind
 * @param string $types The types of the parameters
 * @return array|null The row or null if not found
 */
function db_get_row($sql, $params = [], $types = '') {
    $result = db_query($sql, $params, $types);
    
    if (!$result) {
        return null;
    }
    
    $row = $result->fetch_assoc();
    $result->free();
    
    return $row;
}

/**
 * Get multiple rows from the database
 * 
 * @param string $sql The SQL query
 * @param array $params The parameters to bind
 * @param string $types The types of the parameters
 * @return array The rows
 */
function db_get_rows($sql, $params = [], $types = '') {
    $result = db_query($sql, $params, $types);
    
    if (!$result) {
        return [];
    }
    
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    
    $result->free();
    
    return $rows;
}

/**
 * Insert a row into the database
 * 
 * @param string $table The table name
 * @param array $data The data to insert
 * @return int|false The inserted ID or false on failure
 */
function db_insert($table, $data) {
    $conn = db_connect();
    
    if (!$conn) {
        return false;
    }
    
    $columns = array_keys($data);
    $values = array_values($data);
    $placeholders = array_fill(0, count($values), '?');
    
    $sql = "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $placeholders) . ")";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("Insert preparation failed: " . $conn->error);
        $conn->close();
        return false;
    }
    
    // Generate types string
    $types = '';
    foreach ($values as $value) {
        if (is_int($value)) {
            $types .= 'i';
        } elseif (is_float($value)) {
            $types .= 'd';
        } elseif (is_string($value)) {
            $types .= 's';
        } else {
            $types .= 'b';
        }
    }
    
    // Create references for bind_param
    $bindParams = [$stmt, $types];
    for ($i = 0; $i < count($values); $i++) {
        $bindParams[] = &$values[$i];
    }
    
    call_user_func_array('mysqli_stmt_bind_param', $bindParams);
    
    // Execute the statement
    if (!$stmt->execute()) {
        error_log("Insert execution failed: " . $stmt->error);
        $stmt->close();
        $conn->close();
        return false;
    }
    
    $insertId = $conn->insert_id;
    
    $stmt->close();
    $conn->close();
    
    return $insertId;
}

/**
 * Update a row in the database
 * 
 * @param string $table The table name
 * @param array $data The data to update
 * @param string $where The WHERE clause
 * @param array $whereParams The parameters for the WHERE clause
 * @return bool True on success, false on failure
 */
function db_update($table, $data, $where, $whereParams = []) {
    $conn = db_connect();
    
    if (!$conn) {
        return false;
    }
    
    $columns = array_keys($data);
    $values = array_values($data);
    $set = [];
    
    foreach ($columns as $column) {
        $set[] = "`$column` = ?";
    }
    
    $sql = "UPDATE `$table` SET " . implode(', ', $set) . " WHERE $where";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("Update preparation failed: " . $conn->error);
        $conn->close();
        return false;
    }
    
    // Combine values and where params
    $allParams = array_merge($values, $whereParams);
    
    // Generate types string
    $types = '';
    foreach ($allParams as $param) {
        if (is_int($param)) {
            $types .= 'i';
        } elseif (is_float($param)) {
            $types .= 'd';
        } elseif (is_string($param)) {
            $types .= 's';
        } else {
            $types .= 'b';
        }
    }
    
    // Create references for bind_param
    $bindParams = [$stmt, $types];
    for ($i = 0; $i < count($allParams); $i++) {
        $bindParams[] = &$allParams[$i];
    }
    
    call_user_func_array('mysqli_stmt_bind_param', $bindParams);
    
    // Execute the statement
    if (!$stmt->execute()) {
        error_log("Update execution failed: " . $stmt->error);
        $stmt->close();
        $conn->close();
        return false;
    }
    
    $affected = $stmt->affected_rows;
    
    $stmt->close();
    $conn->close();
    
    return $affected > 0;
}

/**
 * Delete a row from the database
 * 
 * @param string $table The table name
 * @param string $where The WHERE clause
 * @param array $params The parameters for the WHERE clause
 * @return bool True on success, false on failure
 */
function db_delete($table, $where, $params = []) {
    $conn = db_connect();
    
    if (!$conn) {
        return false;
    }
    
    $sql = "DELETE FROM `$table` WHERE $where";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("Delete preparation failed: " . $conn->error);
        $conn->close();
        return false;
    }
    
    // Generate types string
    $types = '';
    foreach ($params as $param) {
        if (is_int($param)) {
            $types .= 'i';
        } elseif (is_float($param)) {
            $types .= 'd';
        } elseif (is_string($param)) {
            $types .= 's';
        } else {
            $types .= 'b';
        }
    }
    
    // Create references for bind_param
    if (!empty($params)) {
        $bindParams = [$stmt, $types];
        for ($i = 0; $i < count($params); $i++) {
            $bindParams[] = &$params[$i];
        }
        
        call_user_func_array('mysqli_stmt_bind_param', $bindParams);
    }
    
    // Execute the statement
    if (!$stmt->execute()) {
        error_log("Delete execution failed: " . $stmt->error);
        $stmt->close();
        $conn->close();
        return false;
    }
    
    $affected = $stmt->affected_rows;
    
    $stmt->close();
    $conn->close();
    
    return $affected > 0;
}
?>