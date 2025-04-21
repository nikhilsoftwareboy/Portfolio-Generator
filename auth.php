<?php
// Authentication logic and helper functions
require_once 'config.php';

// Function to check if username exists
function usernameExists($username) {
    $result = executeQuery("SELECT id FROM users WHERE username = ?", [$username], 's');
    return $result && count($result) > 0;
}

// Function to check if email exists
function emailExists($email) {
    $result = executeQuery("SELECT id FROM users WHERE email = ?", [$email], 's');
    return $result && count($result) > 0;
}

// Function to register a new user
function registerUser($username, $email, $password) {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert the user into the database
    $result = executeQuery(
        "INSERT INTO users (username, email, password) VALUES (?, ?, ?)",
        [$username, $email, $hashed_password],
        'sss'
    );
    
    if($result) {
        return $result['insert_id'];
    }
    
    return false;
}

// Function to login a user
function loginUser($login_field, $password) {
    // Check if the user exists by username or email
    $result = executeQuery(
        "SELECT * FROM users WHERE username = ? OR email = ?",
        [$login_field, $login_field],
        'ss'
    );
    
    if($result && count($result) > 0) {
        $user = $result[0];
        
        // Verify password
        if(password_verify($password, $user['password'])) {
            // Start a new session
            if(!isset($_SESSION)) {
                session_start();
            }
            
            // Store data in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            return true;
        }
    }
    
    return false;
}

// Function to logout a user
function logoutUser() {
    if(!isset($_SESSION)) {
        session_start();
    }
    
    // Unset all session values
    $_SESSION = [];
    
    // Destroy the session
    session_destroy();
}
?>