<?php
// Config file for Portfolio Generator
// Database connection settings
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', '');   
define('DB_NAME', 'portfolio_db');

// Try to connect to the database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create tables if they don't exist
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

$sql_portfolios = "CREATE TABLE IF NOT EXISTS portfolios (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    skills TEXT,
    projects TEXT,
    education TEXT,
    experience TEXT,
    contact_info TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

// Execute the SQL to create tables
mysqli_query($conn, $sql_users);
mysqli_query($conn, $sql_portfolios);

/**
 * Function to execute database queries with params
 */
function executeQuery($sql, $params = [], $types = '') {
    global $conn;
    
    $stmt = mysqli_prepare($conn, $sql);
    
    if(!$stmt) {
        echo "Error preparing statement: " . mysqli_error($conn);
        return false;
    }
    
    // Bind parameters if there are any
    if(!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    // Execute the statement
    if(!mysqli_stmt_execute($stmt)) {
        echo "Error executing statement: " . mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        return false;
    }
    
    // Get result if it's a SELECT query
    if(strpos(strtoupper($sql), 'SELECT') === 0 || strpos(strtoupper($sql), 'SHOW') === 0) {
        $result = mysqli_stmt_get_result($stmt);
        $data = [];
        
        while($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        
        mysqli_stmt_close($stmt);
        return $data;
    } else {
        // For INSERT, UPDATE, DELETE queries
        $affected_rows = mysqli_stmt_affected_rows($stmt);
        $insert_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
        
        return [
            'affected_rows' => $affected_rows,
            'insert_id' => $insert_id
        ];
    }
}

/**
 * Function to display error message
 */
function displayError($message) {
    echo '<div class="alert-danger-custom px-4 py-3 rounded mb-4">' . $message . '</div>';
}

/**
 * Function to display success message
 */
function displaySuccess($message) {
    echo '<div class="alert-success-custom px-4 py-3 rounded mb-4">' . $message . '</div>';
}

/**
 * Function to sanitize input data
 */
function sanitizeInput($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    if(!isset($_SESSION)) {
        session_start();
    }
    
    return isset($_SESSION["user_id"]);
}

/**
 * Get current user data
 */
function getCurrentUser() {
    if(!isLoggedIn()) {
        return null;
    }
    
    $user_id = $_SESSION["user_id"];
    $result = executeQuery("SELECT * FROM users WHERE id = ?", [$user_id], 'i');
    
    if(!$result || count($result) == 0) {
        return null;
    }
    
    return $result[0];
}
?>