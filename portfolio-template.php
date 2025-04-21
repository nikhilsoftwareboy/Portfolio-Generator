<?php
require_once 'config.php';

session_start();

// Check if portfolio ID is provided
if(!isset($_GET["id"])) {
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
$title = $portfolio["title"];
$username = $portfolio["username"];
$description = nl2br($portfolio["description"]);
$skills = nl2br($portfolio["skills"]);
$projects = nl2br($portfolio["projects"]);
$education = nl2br($portfolio["education"]);
$experience = nl2br($portfolio["experience"]);
$contact_info = nl2br($portfolio["contact_info"]);

// New fields for freelancer platform
$industry = isset($portfolio["industry"]) ? $portfolio["industry"] : '';
$service_type = isset($portfolio["service_type"]) ? $portfolio["service_type"] : '';
$work_samples = isset($portfolio["work_samples"]) ? nl2br($portfolio["work_samples"]) : '';
$testimonials = isset($portfolio["testimonials"]) ? nl2br($portfolio["testimonials"]) : '';

// Check if the portfolio belongs to the current user
$is_owner = isset($_SESSION["user_id"]) && $_SESSION["user_id"] == $portfolio["user_id"];

// Simple function to extract URLs from text
function extractUrls($text) {
    $pattern = '/https?:\/\/[^\s]+/';
    preg_match_all($pattern, $text, $matches);
    return $matches[0];
}

// Extract links from work samples
$work_sample_links = !empty($work_samples) ? extractUrls($portfolio["work_samples"]) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - FolioForge</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="serve-css.php">
    <style>
        /* Additional custom styles for portfolio template */
        .testimonial {
            font-style: italic;
            position: relative;
            padding: 10px;
        }
        .testimonial:before {
            content: '"';
            font-size: 30px;
            color: #008080;
            position: absolute;
            left: -10px;
            top: -5px;
        }
    </style>
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
                <?php if(isset($_SESSION["user_id"])): ?>
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

    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="text-center mb-8 animate-fade-in">
            <h1 class="text-3xl font-bold mb-2 text-gray-800"><?php echo $title; ?></h1>
            <p class="text-gray-600 mb-4">Freelancer: <?php echo $username; ?></p>
            
            <?php if(!empty($industry) || !empty($service_type)): ?>
            <div class="mb-4">
                <?php if(!empty($industry)): ?>
                <span class="bg-teal-100 text-teal-800 text-sm font-medium px-3 py-1 rounded-full mr-2 mb-2 inline-block"><?php echo $industry; ?></span>
                <?php endif; ?>
                
                <?php if(!empty($service_type)): ?>
                <span class="bg-teal-100 text-teal-800 text-sm font-medium px-3 py-1 rounded-full mr-2 mb-2 inline-block"><?php echo $service_type; ?></span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <?php if($is_owner): ?>
                <a href="edit-portfolio.php?id=<?php echo $portfolio_id; ?>" class="btn-primary-custom py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition inline-flex items-center">
                    <i class="fas fa-edit mr-2"></i> Edit My Portfolio
                </a>
            <?php endif; ?>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-slide-up">
            <!-- Main content (2/3 width) -->
            <div class="lg:col-span-2">
                <?php if(!empty($description)): ?>
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4 pb-2 border-b border-gray-200 text-gunmetal">About Me</h2>
                    <div class="text-gray-700"><?php echo $description; ?></div>
                </div>
                <?php endif; ?>

                <?php if(!empty($skills)): ?>
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4 pb-2 border-b border-gray-200 text-gunmetal">My Skills</h2>
                    <div class="text-gray-700"><?php echo $skills; ?></div>
                </div>
                <?php endif; ?>

                <?php if(!empty($experience)): ?>
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4 pb-2 border-b border-gray-200 text-gunmetal">Professional Experience</h2>
                    <div class="text-gray-700"><?php echo $experience; ?></div>
                </div>
                <?php endif; ?>

                <?php if(!empty($projects)): ?>
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4 pb-2 border-b border-gray-200 text-gunmetal">Projects</h2>
                    <div class="text-gray-700"><?php echo $projects; ?></div>
                </div>
                <?php endif; ?>

                <?php if(!empty($education)): ?>
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4 pb-2 border-b border-gray-200 text-gunmetal">Education</h2>
                    <div class="text-gray-700"><?php echo $education; ?></div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar (1/3 width) -->
            <div class="lg:col-span-1">
                <?php if(!empty($work_samples)): ?>
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4 pb-2 border-b border-gray-200 text-gunmetal">Work Samples</h2>
                    <div class="text-gray-700 mb-4"><?php echo $work_samples; ?></div>
                    
                    <?php if(!empty($work_sample_links)): ?>
                    <div>
                        <h3 class="font-bold text-gray-700 mb-2">View My Work:</h3>
                        <?php foreach($work_sample_links as $link): ?>
                        <a href="<?php echo $link; ?>" target="_blank" class="block border border-gray-200 p-3 mb-2 rounded-md text-teal-600 hover:bg-gray-50 transition">
                            <i class="fas fa-external-link-alt mr-2"></i><?php echo substr($link, 0, 40) . (strlen($link) > 40 ? '...' : ''); ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <?php if(!empty($testimonials)): ?>
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4 pb-2 border-b border-gray-200 text-gunmetal">Client Testimonials</h2>
                    <div class="testimonial text-gray-700"><?php echo $testimonials; ?></div>
                </div>
                <?php endif; ?>
                
                <?php if(!empty($contact_info)): ?>
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4 pb-2 border-b border-gray-200 text-gunmetal">Contact Information</h2>
                    <div class="text-gray-700"><?php echo $contact_info; ?></div>
                </div>
                <?php endif; ?>
                
                <div class="bg-white rounded-lg shadow-md p-6 mb-6 text-center">
                    <a href="index.php" class="btn-secondary-custom py-2 px-4 rounded-lg inline-flex items-center justify-center w-full">
                        <i class="fas fa-arrow-left mr-2"></i> Back to All Freelancers
                    </a>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer-custom py-6 mt-auto">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; <?php echo date('Y'); ?> FolioForge - Platform for Freelancers and Creators</p>
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
    </script>
</body>
</html>