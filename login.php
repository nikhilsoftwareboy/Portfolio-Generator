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

$login_err = "";

// Process login form submission
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate login input
    if(empty(trim($_POST["login_field"])) || empty(trim($_POST["password"]))) {
        $login_err = "Please enter username/email and password.";
    } else {
        $login_field = sanitizeInput($_POST["login_field"]);
        $password = $_POST["password"];
        
        // Check if the user exists
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $result = executeQuery($sql, [$login_field, $login_field], 'ss');
        
        if(!$result || count($result) == 0) {
            $login_err = "Invalid username/email or password.";
        } else {
            $user = $result[0];
            
            // Verify password
            if(password_verify($password, $user["password"])) {
                // Password is correct, start a new session
                session_start();
                
                // Store data in session variables
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                
                // Redirect to profile page
                header("Location: profile.php");
                exit;
            } else {
                $login_err = "Invalid username/email or password.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portfolio Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="flex flex-col min-h-screen bg-blue-50">
    <nav class="gradient-header text-white shadow-lg">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold">Portfolio Generator</a>
            <button class="md:hidden focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <div class="hidden md:flex space-x-6">
                <a href="index.php" class="nav-link-custom">Home</a>
                <a href="login.php" class="nav-link-custom nav-link-active">Login</a>
                <a href="register.php" class="nav-link-custom">Register</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-lg shadow-md overflow-hidden card-custom animate-fade-in">
                <div class="gradient-card-header py-4 px-6">
                    <h2 class="text-xl font-bold">Login to Your Account</h2>
                </div>
                <div class="p-6">
                    <?php if(!empty($login_err)): ?>
                        <div class="alert-danger-custom px-4 py-3 rounded mb-4">
                            <?php echo $login_err; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Username or Email</label>
                            <input type="text" name="login_field" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-light-blue-500 focus:border-transparent input-custom" required>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                            <input type="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-light-blue-500 focus:border-transparent input-custom" required>
                        </div>
                        <div class="mb-4">
                            <button type="submit" class="w-full btn-primary-custom py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 flex items-center justify-center">
                                <i class="fas fa-sign-in-alt mr-2"></i> Login
                            </button>
                        </div>
                        <p class="text-center text-gray-600">Don't have an account? <a href="register.php" class="text-teal-600 hover:text-teal-800">Register here</a></p>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer-custom py-6 mt-auto">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; <?php echo date('Y'); ?> Portfolio Generator. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Simple mobile menu toggle
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