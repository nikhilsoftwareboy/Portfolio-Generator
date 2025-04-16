<?php
require_once 'config.php';

if(!isset($_SESSION)) {
    session_start();
}

// Check if portfolio ID is provided
if(!isset($_GET["id"]) || empty($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$portfolio_id = $_GET["id"];

// Get the portfolio data
$sql = "SELECT p.*, u.username FROM portfolios p JOIN users u ON p.user_id = u.id WHERE p.id = ?";
$result = executeQuery($sql, [$portfolio_id], 'i');

// Check if portfolio exists
if(!$result || count($result) == 0) {
    header("Location: index.php");
    exit;
}

$portfolio = $result[0];

// Prepare the data for display
$title = htmlspecialchars($portfolio["title"]);
$username = htmlspecialchars($portfolio["username"]);
$description = nl2br(htmlspecialchars($portfolio["description"]));
$skills = nl2br(htmlspecialchars($portfolio["skills"]));
$projects = nl2br(htmlspecialchars($portfolio["projects"]));
$education = nl2br(htmlspecialchars($portfolio["education"]));
$experience = nl2br(htmlspecialchars($portfolio["experience"]));
$contact_info = nl2br(htmlspecialchars($portfolio["contact_info"]));

// Check if the portfolio belongs to the current user
$is_owner = isLoggedIn() && $_SESSION["user_id"] == $portfolio["user_id"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - Portfolio</title>
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
                    <a href="create-portfolio.php" class="nav-link-custom">Create Portfolio</a>
                    <a href="logout.php" class="nav-link-custom">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="nav-link-custom">Login</a>
                    <a href="register.php" class="nav-link-custom">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <header class="gradient-header text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-2"><?php echo $title; ?></h1>
            <p class="text-xl mb-4">By <?php echo $username; ?></p>
            <?php if($is_owner): ?>
                <div class="mt-4">
                    <a href="edit-portfolio.php?id=<?php echo $portfolio_id; ?>" class="btn-primary-custom py-2 px-6 rounded-lg shadow transition duration-300">
                        <i class="fas fa-edit mr-2"></i> Edit Portfolio
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <main class="flex-grow container mx-auto px-4 py-10">
        <?php if(!empty($description)): ?>
            <section class="mb-10">
                <h2 class="text-2xl font-bold mb-4 pb-2 border-b-2 border-purple-500 section-title">About</h2>
                <div class="bg-white rounded-lg shadow-md overflow-hidden card-custom hover-card">
                    <div class="p-6">
                        <p class="text-gray-700"><?php echo $description; ?></p>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php if(!empty($skills)): ?>
            <section class="mb-10">
                <h2 class="text-2xl font-bold mb-4 pb-2 border-b-2 border-purple-500 section-title">Skills</h2>
                <div class="bg-white rounded-lg shadow-md overflow-hidden card-custom hover-card">
                    <div class="p-6">
                        <p class="text-gray-700"><?php echo $skills; ?></p>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php if(!empty($projects)): ?>
            <section class="mb-10">
                <h2 class="text-2xl font-bold mb-4 pb-2 border-b-2 border-purple-500 section-title">Projects</h2>
                <div class="bg-white rounded-lg shadow-md overflow-hidden card-custom hover-card">
                    <div class="p-6">
                        <p class="text-gray-700"><?php echo $projects; ?></p>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php if(!empty($experience)): ?>
            <section class="mb-10">
                <h2 class="text-2xl font-bold mb-4 pb-2 border-b-2 border-purple-500 section-title">Experience</h2>
                <div class="bg-white rounded-lg shadow-md overflow-hidden card-custom hover-card">
                    <div class="p-6">
                        <p class="text-gray-700"><?php echo $experience; ?></p>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php if(!empty($education)): ?>
            <section class="mb-10">
                <h2 class="text-2xl font-bold mb-4 pb-2 border-b-2 border-purple-500 section-title">Education</h2>
                <div class="bg-white rounded-lg shadow-md overflow-hidden card-custom hover-card">
                    <div class="p-6">
                        <p class="text-gray-700"><?php echo $education; ?></p>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php if(!empty($contact_info)): ?>
            <section class="mb-10">
                <h2 class="text-2xl font-bold mb-4 pb-2 border-b-2 border-purple-500 section-title">Contact Information</h2>
                <div class="bg-white rounded-lg shadow-md overflow-hidden card-custom hover-card">
                    <div class="p-6">
                        <p class="text-gray-700"><?php echo $contact_info; ?></p>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <div class="text-center mt-10">
            <a href="index.php" class="btn-secondary-custom py-2 px-6 rounded-lg shadow-md transition duration-300 mr-4">
                Back to Home
            </a>
            <?php if($is_owner): ?>
                <a href="edit-portfolio.php?id=<?php echo $portfolio_id; ?>" class="btn-primary-custom py-2 px-6 rounded-lg shadow-md transition duration-300">
                    Edit Portfolio
                </a>
            <?php endif; ?>
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