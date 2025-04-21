<?php
require_once 'config.php';

if(!isset($_SESSION)) {
    session_start();
}

// Check if user is logged in
if(!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Check if portfolio ID is provided
if(!isset($_GET["id"]) || empty($_GET["id"])) {
    header("Location: profile.php");
    exit;
}

$portfolio_id = $_GET["id"];
$user_id = $_SESSION["user_id"];

// Check if the portfolio belongs to the current user
$sql = "SELECT id FROM portfolios WHERE id = ? AND user_id = ?";
$result = executeQuery($sql, [$portfolio_id, $user_id], 'ii');

if(!$result || count($result) == 0) {
    header("Location: profile.php");
    exit;
}

// Delete the portfolio
$delete_sql = "DELETE FROM portfolios WHERE id = ? AND user_id = ?";
executeQuery($delete_sql, [$portfolio_id, $user_id], 'ii');

// Redirect back to profile
header("Location: profile.php");
exit;
?>