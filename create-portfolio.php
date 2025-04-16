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

$title = $description = $skills = $projects = $education = $experience = $contact_info = "";
$title_err = "";
$success_message = "";

// Process form submission
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate title
    if(empty(trim($_POST["title"]))) {
        $title_err = "Please enter a portfolio title.";
    } else {
        $title = sanitizeInput($_POST["title"]);
    }
    
    // Get other form fields
    $description = sanitizeInput($_POST["description"]);
    $skills = sanitizeInput($_POST["skills"]);
    $projects = sanitizeInput($_POST["projects"]);
    $education = sanitizeInput($_POST["education"]);
    $experience = sanitizeInput($_POST["experience"]);
    $contact_info = sanitizeInput($_POST["contact_info"]);
    
    // Check for errors before inserting into database
    if(empty($title_err)) {
        // Insert portfolio
        $user_id = $_SESSION["user_id"];
        $sql = "INSERT INTO portfolios (user_id, title, description, skills, projects, education, experience, contact_info) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $result = executeQuery($sql, [$user_id, $title, $description, $skills, $projects, $education, $experience, $contact_info], 'isssssss');
        
        if($result) {
            $success_message = "Portfolio created successfully!";
            // Clear the form fields
            $title = $description = $skills = $projects = $education = $experience = $contact_info = "";
        } else {
            displayError("Something went wrong. Please try again later.");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Portfolio - Portfolio Generator</title>
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
                <?php if(isLoggedIn()): ?>
                    <a href="profile.php" class="nav-link-custom">My Profile</a>
                    <a href="create-portfolio.php" class="nav-link-custom nav-link-active">Create Portfolio</a>
                    <a href="logout.php" class="nav-link-custom">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="nav-link-custom">Login</a>
                    <a href="register.php" class="nav-link-custom">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden card-custom animate-fade-in">
            <div class="gradient-card-header py-4 px-6">
                <h2 class="text-xl font-bold">Create a New Portfolio</h2>
            </div>
            <div class="p-6">
                <?php if(!empty($success_message)): ?>
                    <div class="alert-success-custom p-4 mb-6">
                        <p><?php echo $success_message; ?></p>
                        <a href="profile.php" class="underline font-medium">View all your portfolios</a>
                    </div>
                <?php endif; ?>
                
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Portfolio Title</label>
                        <input type="text" name="title" class="shadow appearance-none border <?php echo (!empty($title_err)) ? 'border-red-500' : 'border-gray-300'; ?> rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-light-blue-500 focus:border-transparent input-custom" value="<?php echo $title; ?>" required>
                        <?php if(!empty($title_err)): ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo $title_err; ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                        <textarea name="description" class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-light-blue-500 focus:border-transparent input-custom" rows="3"><?php echo $description; ?></textarea>
                        <p class="text-gray-500 text-xs mt-1">Provide a brief description about yourself and your portfolio.</p>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Skills</label>
                        <textarea name="skills" class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-light-blue-500 focus:border-transparent input-custom" rows="3"><?php echo $skills; ?></textarea>
                        <p class="text-gray-500 text-xs mt-1">List your skills, separated by commas or line breaks.</p>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Projects</label>
                        <textarea name="projects" class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-light-blue-500 focus:border-transparent input-custom" rows="4"><?php echo $projects; ?></textarea>
                        <p class="text-gray-500 text-xs mt-1">Describe your projects, including details about your role and technologies used.</p>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Education</label>
                        <textarea name="education" class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-light-blue-500 focus:border-transparent input-custom" rows="3"><?php echo $education; ?></textarea>
                        <p class="text-gray-500 text-xs mt-1">List your educational background, degrees, and certifications.</p>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Experience</label>
                        <textarea name="experience" class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-light-blue-500 focus:border-transparent input-custom" rows="4"><?php echo $experience; ?></textarea>
                        <p class="text-gray-500 text-xs mt-1">Detail your work experience, including position titles, companies, and responsibilities.</p>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Contact Information</label>
                        <textarea name="contact_info" class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-light-blue-500 focus:border-transparent input-custom" rows="2"><?php echo $contact_info; ?></textarea>
                        <p class="text-gray-500 text-xs mt-1">Provide your contact information (email, phone, social media links, etc.)</p>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end mt-10">
                        <a href="profile.php" class="btn-secondary-custom py-3 px-6 rounded-lg transition duration-300 mr-4">Cancel</a>
                        <button type="submit" class="btn-primary-custom py-3 px-8 rounded-lg transition duration-300 flex items-center">
                            <i class="fas fa-check mr-2"></i> Create Portfolio
                        </button>
                    </div>
                </form>
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