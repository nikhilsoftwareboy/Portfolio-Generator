<?php
// Database configuration for XAMPP (MySQL)
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');  // Default XAMPP username
define('DB_PASSWORD', '');      // Default XAMPP password (usually empty)
define('DB_NAME', 'portfolio_db');

// Attempt to connect to MySQL database
try {
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    if(!$conn) {
        throw new Exception(mysqli_connect_error());
    }
} catch (Exception $e) {
    // If database connection fails, create an in-memory data storage
    if(!isset($_SESSION)) {
        session_start();
    }
    
    // Initialize in-memory storage if it doesn't exist yet
    if(!isset($_SESSION['in_memory_db'])) {
        $_SESSION['in_memory_db'] = [
            'users' => [],
            'portfolios' => []
        ];
    }
    
    // Log the error
    error_log("Database connection failed: " . $e->getMessage());
    $conn = null;
}

// Check if connection was successful
if($conn) {
    // Create tables if they don't exist
    // MySQL version of the users table
    $sql_users = "CREATE TABLE IF NOT EXISTS users (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    // MySQL version of the portfolios table
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
    if (!mysqli_query($conn, $sql_users) || !mysqli_query($conn, $sql_portfolios)) {
        error_log("Error creating tables: " . mysqli_error($conn));
    }
}

/**
 * Function to execute database queries with proper error handling
 * 
 * @param string $sql The SQL query to execute
 * @param array $params Parameters for prepared statement
 * @param string $types Types of parameters (e.g., 'ssi' for string, string, integer)
 * @return array|bool Result array or false on failure
 */
function executeQuery($sql, $params = [], $types = '') {
    global $conn;
    
    // Check if we're using in-memory storage
    if(!$conn) {
        return handleInMemoryQuery($sql, $params);
    }
    
    // MySQL code path
    $stmt = mysqli_prepare($conn, $sql);
    
    if(!$stmt) {
        error_log("Query preparation failed: " . mysqli_error($conn));
        return false;
    }
    
    // Bind parameters if there are any
    if(!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    // Execute the statement
    if(!mysqli_stmt_execute($stmt)) {
        error_log("Query execution failed: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return false;
    }
    
    // Get result if it's a SELECT query
    if(strpos(strtoupper($sql), 'SELECT') === 0) {
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
 * Handle queries for in-memory database (fallback method)
 * 
 * @param string $sql SQL query
 * @param array $params Parameters for the query
 * @return array|bool Result array or false on failure
 */
function handleInMemoryQuery($sql, $params) {
    if(!isset($_SESSION)) {
        session_start();
    }
    
    // Simple parser to mimic database queries for in-memory storage
    $sql_upper = strtoupper($sql);
    
    // Handle INSERT queries
    if(strpos($sql_upper, 'INSERT INTO USERS') === 0) {
        // Extract user values from parameters
        $username = $params[0];
        $email = $params[1];
        $password = $params[2];
        
        // Generate a new user ID
        $id = count($_SESSION['in_memory_db']['users']) + 1;
        
        // Check if username or email already exists
        foreach($_SESSION['in_memory_db']['users'] as $user) {
            if($user['username'] === $username) {
                return false; // Username already exists
            }
            if($user['email'] === $email) {
                return false; // Email already exists
            }
        }
        
        // Add new user
        $_SESSION['in_memory_db']['users'][] = [
            'id' => $id,
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return ['affected_rows' => 1, 'insert_id' => $id];
    }
    elseif(strpos($sql_upper, 'INSERT INTO PORTFOLIOS') === 0) {
        // Extract portfolio data from parameters
        $user_id = $params[0];
        $title = $params[1];
        $description = $params[2];
        $skills = $params[3];
        $projects = $params[4];
        $education = $params[5];
        $experience = $params[6];
        $contact_info = $params[7];
        
        // Generate a new portfolio ID
        $id = count($_SESSION['in_memory_db']['portfolios']) + 1;
        
        // Add new portfolio
        $_SESSION['in_memory_db']['portfolios'][] = [
            'id' => $id,
            'user_id' => $user_id,
            'title' => $title,
            'description' => $description,
            'skills' => $skills,
            'projects' => $projects,
            'education' => $education,
            'experience' => $experience,
            'contact_info' => $contact_info,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return ['affected_rows' => 1, 'insert_id' => $id];
    }
    // Handle UPDATE queries
    elseif(strpos($sql_upper, 'UPDATE PORTFOLIOS') === 0) {
        // Simplified update for in-memory
        $portfolio_id = $params[7]; // Assuming ID is the last parameter
        
        foreach($_SESSION['in_memory_db']['portfolios'] as $key => $portfolio) {
            if($portfolio['id'] == $portfolio_id) {
                $_SESSION['in_memory_db']['portfolios'][$key]['title'] = $params[0];
                $_SESSION['in_memory_db']['portfolios'][$key]['description'] = $params[1];
                $_SESSION['in_memory_db']['portfolios'][$key]['skills'] = $params[2];
                $_SESSION['in_memory_db']['portfolios'][$key]['projects'] = $params[3];
                $_SESSION['in_memory_db']['portfolios'][$key]['education'] = $params[4];
                $_SESSION['in_memory_db']['portfolios'][$key]['experience'] = $params[5];
                $_SESSION['in_memory_db']['portfolios'][$key]['contact_info'] = $params[6];
                $_SESSION['in_memory_db']['portfolios'][$key]['updated_at'] = date('Y-m-d H:i:s');
                
                return ['affected_rows' => 1, 'insert_id' => 0];
            }
        }
        
        return ['affected_rows' => 0, 'insert_id' => 0];
    }
    // Handle SELECT queries
    elseif(strpos($sql_upper, 'SELECT') === 0) {
        if(strpos($sql_upper, 'FROM USERS') !== false) {
            // User queries
            if(strpos($sql_upper, 'WHERE USERNAME') !== false || strpos($sql_upper, 'WHERE EMAIL') !== false) {
                $loginField = $params[0];
                
                foreach($_SESSION['in_memory_db']['users'] as $user) {
                    if($user['username'] === $loginField || $user['email'] === $loginField) {
                        return [$user];
                    }
                }
            }
            elseif(strpos($sql_upper, 'WHERE ID') !== false) {
                $userId = $params[0];
                
                foreach($_SESSION['in_memory_db']['users'] as $user) {
                    if($user['id'] == $userId) {
                        return [$user];
                    }
                }
            }
        }
        elseif(strpos($sql_upper, 'FROM PORTFOLIOS') !== false) {
            // Portfolio queries
            if(strpos($sql_upper, 'WHERE USER_ID') !== false) {
                $userId = $params[0];
                $results = [];
                
                foreach($_SESSION['in_memory_db']['portfolios'] as $portfolio) {
                    if($portfolio['user_id'] == $userId) {
                        $results[] = $portfolio;
                    }
                }
                
                return $results;
            }
            elseif(strpos($sql_upper, 'WHERE ID') !== false) {
                $portfolioId = $params[0];
                
                foreach($_SESSION['in_memory_db']['portfolios'] as $portfolio) {
                    if($portfolio['id'] == $portfolioId) {
                        return [$portfolio];
                    }
                }
            }
            else {
                // Return all portfolios
                return $_SESSION['in_memory_db']['portfolios'];
            }
        }
    }
    // Handle DELETE queries
    elseif(strpos($sql_upper, 'DELETE FROM PORTFOLIOS') === 0) {
        $portfolioId = $params[0];
        $found = false;
        
        foreach($_SESSION['in_memory_db']['portfolios'] as $key => $portfolio) {
            if($portfolio['id'] == $portfolioId) {
                unset($_SESSION['in_memory_db']['portfolios'][$key]);
                $found = true;
                break;
            }
        }
        
        // Reindex array after deletion
        $_SESSION['in_memory_db']['portfolios'] = array_values($_SESSION['in_memory_db']['portfolios']);
        
        return ['affected_rows' => $found ? 1 : 0, 'insert_id' => 0];
    }
    
    return false;
}

// Function to display error messages
function displayError($message) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($message) . '</div>';
}

// Function to display success messages
function displaySuccess($message) {
    echo '<div class="alert alert-success">' . htmlspecialchars($message) . '</div>';
}

// Function to validate and sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to check if user is logged in
function isLoggedIn() {
    if(!isset($_SESSION)) {
        session_start();
    }
    
    return isset($_SESSION['user_id']);
}

// Function to get current user data
function getCurrentUser() {
    if(!isLoggedIn()) {
        return null;
    }
    
    $userId = $_SESSION['user_id'];
    $result = executeQuery("SELECT * FROM users WHERE id = ?", [$userId], 'i');
    
    if($result && count($result) > 0) {
        return $result[0];
    }
    
    return null;
}
?>