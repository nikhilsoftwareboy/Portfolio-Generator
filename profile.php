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

// Get user data
$user = getCurrentUser();

// Get user's portfolios
$portfolios = executeQuery("SELECT * FROM portfolios WHERE user_id = ? ORDER BY created_at DESC", [$_SESSION["user_id"]], 'i');
if(!$portfolios) {
    $portfolios = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Portfolio Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="serve-css.php">
    <script>
        // Force cache clearing on page load - one time only
        window.onload = function() {
            // Only reload once to avoid an infinite loop
            const hasNoCacheParam = window.location.href.indexOf('nocache') !== -1;
            
            // If we came here via a normal navigation (no nocache param)
            if (!hasNoCacheParam && !sessionStorage.getItem('cache_cleared')) {
                // Mark that we've cleared the cache in this session
                sessionStorage.setItem('cache_cleared', 'true');
                // Reload with nocache parameter
                window.location.href = window.location.href + 
                    (window.location.href.indexOf('?') === -1 ? '?nocache=' : '&nocache=') + 
                    new Date().getTime();
            }
        }
    </script>
</head>
<body class="flex flex-col min-h-screen bg-blue-50">
    <nav class="gradient-header text-white shadow-lg">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold text-white">Portfolio Generator</a>
            <button class="md:hidden focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <div class="hidden md:flex space-x-6">
                <a href="index.php" class="nav-link-custom">Home</a>
                <a href="profile.php" class="nav-link-custom nav-link-active">My Profile</a>
                <a href="create-portfolio.php" class="nav-link-custom">Create Portfolio</a>
                <a href="logout.php" class="nav-link-custom">Logout</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-md overflow-hidden card-custom mb-6">
                    <div class="gradient-card-header py-4 px-6">
                        <h2 class="text-xl font-bold text-white">Profile Information</h2>
                    </div>
                    <div class="p-6">
                        <p class="mb-2"><span class="font-semibold">Username:</span> <?php echo htmlspecialchars($user["username"]); ?></p>
                        <p class="mb-4"><span class="font-semibold">Email:</span> <?php echo htmlspecialchars($user["email"]); ?></p>
                        <a href="create-portfolio.php" class="block w-full text-center font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300 btn-primary-custom">
                            Create New Portfolio
                        </a>
                    </div>
                </div>
            </div>
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden card-custom">
                    <div class="gradient-card-header py-4 px-6">
                        <h2 class="text-xl font-bold text-white">My Portfolios</h2>
                    </div>
                    <div class="p-6">
                        <?php if(empty($portfolios)): ?>
                            <div class="alert-info-custom p-4 mb-4">
                                You haven't created any portfolios yet. <a href="create-portfolio.php" class="underline font-medium">Create your first portfolio now!</a>
                            </div>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php foreach($portfolios as $portfolio): ?>
                                    <div class="border rounded-lg p-4 hover:bg-blue-50 transition duration-300 hover-card">
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-lg font-semibold mb-2 text-gray-800"><?php echo htmlspecialchars($portfolio["title"]); ?></h3>
                                        </div>
                                        <p class="text-gray-600 mb-4">
                                            <?php 
                                            $desc = $portfolio["description"];
                                            echo htmlspecialchars(substr($desc, 0, 150)) . (strlen($desc) > 150 ? '...' : '');
                                            ?>
                                        </p>
                                        <div class="flex space-x-2">
                                            <a href="portfolio-template.php?id=<?php echo $portfolio["id"]; ?>" class="btn-primary-custom px-3 py-1 rounded text-sm">View</a>
                                            <a href="edit-portfolio.php?id=<?php echo $portfolio["id"]; ?>" class="btn-secondary-custom px-3 py-1 rounded text-sm">Edit</a>
                                            <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $portfolio['id']; ?>)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition duration-300">Delete</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
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
        // Mobile menu toggle
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

        function confirmDelete(portfolioId) {
            if(confirm("Are you sure you want to delete this portfolio? This action cannot be undone.")) {
                window.location.href = "delete-portfolio.php?id=" + portfolioId;
            }
        }
    </script>
</body>
</html>