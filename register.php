<?php
// Include config file
require_once "config.php";

session_start();

// Check if user is already logged in
if(isset($_SESSION["user_id"])){
    header("location: index.php");
    exit;
}

// Variables to store form data and errors
$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";

// Process form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate username
    if(empty($_POST["username"])){
        $username_err = "Please enter a username.";
    } else {
        // Get username from form
        $username = $_POST["username"];
        
        // Check if username already exists
        $sql = "SELECT id FROM users WHERE username = ?";
        $result = executeQuery($sql, [$username], "s");
        
        if($result && count($result) > 0){
            $username_err = "This username is already taken.";
        }
    }
    
    // Validate email
    if(empty($_POST["email"])){
        $email_err = "Please enter an email.";
    } else {
        $email = $_POST["email"];
        
        // Check if email already exists
        $sql = "SELECT id FROM users WHERE email = ?";
        $result = executeQuery($sql, [$email], "s");
        
        if($result && count($result) > 0){
            $email_err = "This email is already registered.";
        }
    }
    
    // Validate password
    if(empty($_POST["password"])){
        $password_err = "Please enter a password.";     
    } else {
        $password = $_POST["password"];
        
        if(strlen($password) < 6){
            $password_err = "Password must have at least 6 characters.";
        }
    }
    
    // Validate confirm password
    if(empty($_POST["confirm_password"])){
        $confirm_password_err = "Please confirm password.";     
    } else {
        $confirm_password = $_POST["confirm_password"];
        if($password != $confirm_password){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check for errors before adding to database
    if(empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Create hashed password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user into database
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $result = executeQuery($sql, [$username, $email, $hashed_password], "sss");
        
        if($result){
            // Redirect to login page
            header("location: login.php");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FolioForge</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="serve-css.php">
</head>
<body class="flex flex-col min-h-screen bg-blue-50">
    <nav class="gradient-header text-white shadow-lg">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold animate-fade-in">FolioForge</a>
            <button class="md:hidden focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <div class="hidden md:flex space-x-6">
                <a href="index.php" class="nav-link-custom">Home</a>
                <a href="login.php" class="nav-link-custom">Login</a>
                <a href="register.php" class="nav-link-custom nav-link-active">Register</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6 animate-fade-in">
            <h1 class="text-2xl font-bold mb-2 text-gray-800">Create an Account</h1>
            <p class="text-gray-600 mb-6">Please fill this form to create an account.</p>

            <form action="register.php" method="post">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                    <input type="text" name="username" value="<?php echo $username; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-teal-500">
                    <?php if(!empty($username_err)): ?>
                        <span class="text-red-500 text-xs mt-1"><?php echo $username_err; ?></span>
                    <?php endif; ?>
                </div>    
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" value="<?php echo $email; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-teal-500">
                    <?php if(!empty($email_err)): ?>
                        <span class="text-red-500 text-xs mt-1"><?php echo $email_err; ?></span>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" name="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-teal-500">
                    <?php if(!empty($password_err)): ?>
                        <span class="text-red-500 text-xs mt-1"><?php echo $password_err; ?></span>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-teal-500">
                    <?php if(!empty($confirm_password_err)): ?>
                        <span class="text-red-500 text-xs mt-1"><?php echo $confirm_password_err; ?></span>
                    <?php endif; ?>
                </div>
                <div class="mb-4 flex items-center">
                    <input type="checkbox" id="show_password" onclick="togglePassword()" class="mr-2">
                    <label for="show_password" class="text-sm text-gray-700">Show Password</label>
                </div>
                <div class="flex items-center justify-between mb-6">
                    <input type="submit" value="Register" class="btn-primary-custom px-4 py-2 rounded hover:shadow-lg transition">
                    <input type="reset" value="Reset" class="btn-secondary-custom px-4 py-2 rounded hover:shadow-lg transition">
                </div>
                <p class="text-sm text-gray-600 text-center">Already have an account? <a href="login.php" class="text-teal-600 hover:underline">Login here</a>.</p>
            </form>
        </div>
    </main>

    <footer class="footer-custom py-6 mt-auto">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; <?php echo date('Y'); ?> FolioForge. All rights reserved.</p>
        </div>
    </footer>

    <script src="js/cache-buster.js"></script>
    <script>
        // Toggle mobile menu
        document.querySelector('nav button').addEventListener('click', function() {
            const menu = document.querySelector('nav div.hidden');
            menu.classList.toggle('hidden');
            menu.classList.toggle('flex');
            menu.classList.toggle('flex-col');
            menu.classList.toggle('absolute');
            menu.classList.toggle('top-16');
            menu.classList.toggle('right-4');
            menu.classList.toggle('bg-teal-500');
            menu.classList.toggle('p-4');
            menu.classList.toggle('rounded');
            menu.classList.toggle('shadow-lg');
            menu.classList.toggle('z-50');
        });

        // Toggle password visibility
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var confirmField = document.getElementById("confirm_password");
            
            if (passwordField.type === "password") {
                passwordField.type = "text";
                confirmField.type = "text";
            } else {
                passwordField.type = "password";
                confirmField.type = "password";
            }
        }
    </script>
</body>
</html>