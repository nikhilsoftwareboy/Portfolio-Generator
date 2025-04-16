<?php
require_once 'config.php';
require_once 'auth.php';

if(!isset($_SESSION)) {
    session_start();
}

// Check if user is already logged in
if(isLoggedIn()) {
    header("Location: profile.php");
    exit;
}

$username = $email = "";
$username_err = $email_err = $password_err = "";

// Process registration form submission
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if(empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = sanitizeInput($_POST["username"]);
        
        // Check if username already exists
        $sql = "SELECT id FROM users WHERE username = ?";
        $result = executeQuery($sql, [$username], 's');
        
        if($result && count($result) > 0) {
            $username_err = "This username is already taken.";
        }
    }
    
    // Validate email
    if(empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email address.";
    } else {
        $email = sanitizeInput($_POST["email"]);
        
        // Check if email is valid
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Please enter a valid email address.";
        } else {
            // Check if email already exists
            $sql = "SELECT id FROM users WHERE email = ?";
            $result = executeQuery($sql, [$email], 's');
            
            if($result && count($result) > 0) {
                $email_err = "This email is already registered.";
            }
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } elseif(trim($_POST["password"]) != trim($_POST["confirm_password"])) {
        $password_err = "Passwords do not match.";
    }
    
    // Check for errors before inserting into database
    if(empty($username_err) && empty($email_err) && empty($password_err)) {
        // Hash the password
        $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
        
        // Insert new user
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $result = executeQuery($sql, [$username, $email, $password], 'sss');
        
        if($result) {
            // Get the new user ID
            $user_id = $result['insert_id'];
            
            // Start session and log the user in
            session_start();
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $username;
            
            // Redirect to profile page
            header("Location: profile.php");
            exit;
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
    <title>Register - Portfolio Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .animate-fade-in { animation: fadeIn 0.8s ease-in; }
        .animate-slide-up { animation: slideUp 0.6s ease-out; }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body class="flex flex-col min-h-screen bg-gray-50">
    <nav class="gradient-header text-white shadow-lg">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold animate-fade-in">Portfolio Generator</a>
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

    <div class="container mx-auto px-4 py-12 flex-grow">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden animate-slide-up">
            <div class="gradient-card-header px-6 py-8 text-white">
                <h2 class="text-3xl font-bold mb-2">Create Your Account</h2>
                <p class="opacity-80">Join our community and create your portfolio</p>
            </div>
            
            <div class="p-6">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="mb-6">
                        <label for="username" class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-user text-purple-600 mr-2"></i>Username
                        </label>
                        <input type="text" name="username" id="username" class="w-full px-4 py-3 border <?php echo (!empty($username_err)) ? 'border-red-500' : 'border-gray-300'; ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" value="<?php echo $username; ?>" required>
                        <?php if(!empty($username_err)): ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo $username_err; ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-6">
                        <label for="email" class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-envelope text-purple-600 mr-2"></i>Email Address
                        </label>
                        <input type="email" name="email" id="email" class="w-full px-4 py-3 border <?php echo (!empty($email_err)) ? 'border-red-500' : 'border-gray-300'; ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" value="<?php echo $email; ?>" required>
                        <?php if(!empty($email_err)): ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo $email_err; ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-6">
                        <label for="password" class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-lock text-purple-600 mr-2"></i>Password
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="password" class="w-full px-4 py-3 border <?php echo (!empty($password_err)) ? 'border-red-500' : 'border-gray-300'; ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                            <button type="button" id="togglePassword" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <?php if(!empty($password_err)): ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo $password_err; ?></p>
                        <?php else: ?>
                            <p class="text-gray-500 text-sm mt-1">Password must be at least 6 characters long.</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-6">
                        <label for="confirm_password" class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-lock text-purple-600 mr-2"></i>Confirm Password
                        </label>
                        <input type="password" name="confirm_password" id="confirm_password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                        <p id="password_match" class="text-sm mt-1"></p>
                    </div>
                    
                    <div class="mb-6">
                        <button type="submit" class="w-full btn-primary-custom py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center">
                            <i class="fas fa-user-plus mr-2"></i> Create Account
                        </button>
                    </div>
                    
                    <div class="text-center text-gray-600">
                        Already have an account? <a href="login.php" class="text-teal-600 hover:text-teal-800">Login here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="footer-custom py-6">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; <?php echo date('Y'); ?> Portfolio Generator. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Password matching validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirm = this.value;
            const feedback = document.getElementById('password_match');
            
            if (confirm === '') {
                feedback.textContent = '';
                feedback.className = 'text-sm mt-1';
            } else if (password === confirm) {
                feedback.textContent = 'Passwords match!';
                feedback.className = 'text-green-500 text-sm mt-1';
            } else {
                feedback.textContent = 'Passwords do not match!';
                feedback.className = 'text-red-500 text-sm mt-1';
            }
        });
        
        // Mobile menu toggle
        document.querySelector('nav button').addEventListener('click', function() {
            const menu = document.querySelector('nav div.hidden');
            menu.classList.toggle('hidden');
            menu.classList.toggle('flex');
            menu.classList.toggle('flex-col');
            menu.classList.toggle('absolute');
            menu.classList.toggle('top-16');
            menu.classList.toggle('right-4');
            menu.classList.toggle('bg-purple-600');
            menu.classList.toggle('p-4');
            menu.classList.toggle('rounded');
            menu.classList.toggle('shadow-lg');
            menu.classList.toggle('z-50');
        });
    </script>
</body>
</html>