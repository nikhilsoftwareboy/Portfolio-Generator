<?php
require_once 'config.php';

// Check if user is logged in and is an admin (optional security measure)
// if(!isset($_SESSION)) { session_start(); }
// if(!isLoggedIn() || $_SESSION["user_id"] != 1) {
//     header("Location: login.php");
//     exit;
// }

// Create a connection to the database directly
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "<h2>Database Update for Freelancer Platform</h2>";

// Check if columns already exist
$result = $mysqli->query("SHOW COLUMNS FROM portfolios LIKE 'industry'");
$industry_exists = ($result->num_rows > 0);

$result = $mysqli->query("SHOW COLUMNS FROM portfolios LIKE 'service_type'");
$service_type_exists = ($result->num_rows > 0);

$result = $mysqli->query("SHOW COLUMNS FROM portfolios LIKE 'testimonials'");
$testimonials_exists = ($result->num_rows > 0);

$result = $mysqli->query("SHOW COLUMNS FROM portfolios LIKE 'work_samples'");
$work_samples_exists = ($result->num_rows > 0);

// Add columns if they don't exist
$queries = array();

if(!$industry_exists) {
    $queries[] = "ALTER TABLE portfolios ADD COLUMN industry VARCHAR(100) DEFAULT NULL AFTER contact_info";
}

if(!$service_type_exists) {
    $queries[] = "ALTER TABLE portfolios ADD COLUMN service_type VARCHAR(100) DEFAULT NULL AFTER industry";
}

if(!$testimonials_exists) {
    $queries[] = "ALTER TABLE portfolios ADD COLUMN testimonials TEXT DEFAULT NULL AFTER service_type";
}

if(!$work_samples_exists) {
    $queries[] = "ALTER TABLE portfolios ADD COLUMN work_samples TEXT DEFAULT NULL AFTER testimonials";
}

// Execute queries
$success = true;
foreach($queries as $query) {
    echo "<p>Executing: $query</p>";
    if(!$mysqli->query($query)) {
        echo "<p style='color: red;'>Error: " . $mysqli->error . "</p>";
        $success = false;
    } else {
        echo "<p style='color: green;'>Success!</p>";
    }
}

// Show final status
if($success && count($queries) > 0) {
    echo "<h3 style='color: green;'>Database updated successfully!</h3>";
} elseif(count($queries) == 0) {
    echo "<h3 style='color: blue;'>No updates needed. All columns already exist.</h3>";
} else {
    echo "<h3 style='color: red;'>Some errors occurred during the update.</h3>";
}

// Close connection
$mysqli->close();

// Provide a link back to the home page
echo "<p><a href='index.php'>Return to Home Page</a></p>";
?> 