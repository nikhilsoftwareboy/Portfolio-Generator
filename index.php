<?php
require_once 'config.php';
if(!isset($_SESSION)) { session_start(); }

// Get recent portfolios to display
$recent_portfolios = [];
$sql = "SELECT p.*, u.username FROM portfolios p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC LIMIT 6";

// Apply filters if they exist
$filter_industry = isset($_GET['industry']) ? sanitizeInput($_GET['industry']) : '';
$filter_service = isset($_GET['service_type']) ? sanitizeInput($_GET['service_type']) : '';
$filter_skill = isset($_GET['skill']) ? sanitizeInput($_GET['skill']) : '';

// Build query with filters
if (!empty($filter_industry) || !empty($filter_service) || !empty($filter_skill)) {
    $sql = "SELECT p.*, u.username FROM portfolios p JOIN users u ON p.user_id = u.id WHERE 1=1";
    
    if (!empty($filter_industry)) {
        $sql .= " AND p.industry = '" . $filter_industry . "'";
    }
    
    if (!empty($filter_service)) {
        $sql .= " AND p.service_type = '" . $filter_service . "'";
    }
    
    if (!empty($filter_skill)) {
        $sql .= " AND p.skills LIKE '%" . $filter_skill . "%'";
    }
    
    $sql .= " ORDER BY p.created_at DESC LIMIT 6";
}

try {
    $recent_portfolios = executeQuery($sql);
} catch (Exception $e) {
    // Fallback to unfiltered query if there's an error
    $recent_portfolios = executeQuery("SELECT p.*, u.username FROM portfolios p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC LIMIT 6");
}

// Get unique industries and service types for filter dropdowns
$industries = [];
$service_types = [];

try {
    $industries = executeQuery("SELECT DISTINCT industry FROM portfolios WHERE industry IS NOT NULL AND industry != '' ORDER BY industry");
} catch (Exception $e) {
    // Silently fail if the column doesn't exist yet
    $industries = [];
}

try {
    $service_types = executeQuery("SELECT DISTINCT service_type FROM portfolios WHERE service_type IS NOT NULL AND service_type != '' ORDER BY service_type");
} catch (Exception $e) {
    // Silently fail if the column doesn't exist yet
    $service_types = [];
}

// Display message about database update if needed
$db_needs_update = empty($industries) && empty($service_types);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FolioForge - Create Your Professional Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="serve-css.php">
    <style>
        .animate-fade-in { animation: fadeIn 0.8s ease-in; }
        .animate-slide-up { animation: slideUp 0.5s ease-out; }
        
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
<body class="flex flex-col min-h-screen bg-blue-50">
    <nav class="gradient-header text-white shadow-lg">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold animate-fade-in">FolioForge</a>
            <button class="md:hidden focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <div class="hidden md:flex space-x-6">
                <a href="index.php" class="nav-link-custom nav-link-active">Home</a>
                <a href="about.php" class="nav-link-custom">About</a>
                <?php if(isset($_SESSION['user_id'])): ?>
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

    <header class="bg-gradient-to-br from-blue-50 to-green-100 py-20 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-light-blue-300 rounded-full opacity-10 -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-green-300 rounded-full opacity-10 -ml-10 -mb-10"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="md:w-1/2 mb-10 md:mb-0 animate-fade-in">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 text-gray-800">Find & Connect with <span class="text-primary-gradient">Professional Freelancers</span></h1>
                    <p class="text-xl text-gray-600 mb-8">Discover talented creators across various industries or showcase your own portfolio to potential clients.</p>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="create-portfolio.php" class="btn-primary-custom py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition duration-300 inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i> Create Your Portfolio
                        </a>
                    <?php else: ?>
                        <div>
                            <a href="login.php" class="btn-primary-custom py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition duration-300 inline-flex items-center">
                                <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="md:w-2/5 animate-slide-up">
                    <img src="https://images.unsplash.com/photo-1499951360447-b19be8fe80f5?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Portfolio Preview" class="rounded-lg shadow-xl transform hover:-translate-y-2 transition duration-500">
                </div>
            </div>
        </div>
    </header>

    <section class="py-16 bg-blue-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-6">Why Choose <span class="text-primary-gradient">FolioForge</span></h2>
            <p class="text-center text-gray-600 mb-12 max-w-3xl mx-auto">The ultimate platform to showcase your talents and connect with potential clients</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300 hover-card">
                    <div class="text-teal-500 text-4xl mb-4">
                        <i class="fas fa-paint-brush"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Beautiful Portfolios</h3>
                    <p class="text-gray-600">Create professional, eye-catching portfolios that showcase your work in the best possible light.</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300 hover-card">
                    <div class="text-teal-500 text-4xl mb-4">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Increased Visibility</h3>
                    <p class="text-gray-600">Get discovered by clients looking for your exact skills and services through our tailored filtering system.</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300 hover-card">
                    <div class="text-teal-500 text-4xl mb-4">
                        <i class="fas fa-code"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">No Coding Required</h3>
                    <p class="text-gray-600">Our intuitive interface allows you to create stunning portfolios without any technical knowledge.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-4">Featured <span class="text-primary-gradient">Professionals</span></h2>
            <?php if (!empty($filter_industry) || !empty($filter_service) || !empty($filter_skill)): ?>
                <p class="text-center text-gray-600 mb-12">
                    <?php 
                    $filters = [];
                    if (!empty($filter_industry)) $filters[] = "Industry: <strong>" . htmlspecialchars($filter_industry) . "</strong>";
                    if (!empty($filter_service)) $filters[] = "Service: <strong>" . htmlspecialchars($filter_service) . "</strong>";
                    if (!empty($filter_skill)) $filters[] = "Skill: <strong>" . htmlspecialchars($filter_skill) . "</strong>";
                    echo "Filtered by " . implode(", ", $filters);
                    ?>
                </p>
            <?php else: ?>
                <p class="text-center text-gray-600 mb-12">Discover talented freelancers and creators to collaborate with</p>
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach($recent_portfolios as $portfolio): ?>
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition duration-300 animate-slide-up hover-card">
                    <?php
                    $image = !empty($portfolio['cover_image']) ? $portfolio['cover_image'] : 'https://images.unsplash.com/photo-1457369804613-52c61a468e7d?auto=format&fit=crop&w=800&q=80';
                    ?>
                    <div class="h-48 bg-cover bg-center" style="background-image: url('<?php echo $image; ?>')"></div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-xl"><?php echo htmlspecialchars($portfolio['title']); ?></h3>
                            <?php if(!empty($portfolio['industry'])): ?>
                            <span class="bg-teal-100 text-teal-800 text-xs font-medium px-2.5 py-0.5 rounded"><?php echo htmlspecialchars($portfolio['industry']); ?></span>
                            <?php endif; ?>
                        </div>
                        <p class="text-gray-500 text-sm mb-3">By <?php echo htmlspecialchars($portfolio['username']); ?></p>
                        
                        <?php if(!empty($portfolio['service_type'])): ?>
                        <p class="text-gray-700 text-sm mb-3"><i class="fas fa-briefcase text-teal-500 mr-2"></i><?php echo htmlspecialchars($portfolio['service_type']); ?></p>
                        <?php endif; ?>
                        
                        <?php if(!empty($portfolio['skills'])): ?>
                        <div class="mb-4">
                            <?php
                            $skills = explode(',', $portfolio['skills']);
                            $skills = array_slice($skills, 0, 3); // Show max 3 skills
                            foreach($skills as $skill): 
                                $skill = trim($skill);
                                if(!empty($skill)):
                            ?>
                                <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"><?php echo htmlspecialchars($skill); ?></span>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                        <?php endif; ?>
                        
                        <a href="portfolio-template.php?id=<?php echo $portfolio['id']; ?>" class="text-teal-600 hover:text-teal-800 inline-flex items-center">
                            View Portfolio <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-4">Client <span class="text-primary-gradient">Testimonials</span></h2>
            <p class="text-center text-gray-600 mb-12 max-w-3xl mx-auto">Hear what clients and freelancers have to say about their experience with our platform</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <div class="mb-4">
                        <i class="fas fa-quote-left text-teal-500 opacity-50 text-3xl"></i>
                    </div>
                    <p class="text-gray-700 mb-6 italic">"FolioForge helped me showcase my design portfolio in a professional way. I've received three new client projects since joining the platform!"</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-teal-700 font-bold">SD</span>
                        </div>
                        <div>
                            <h4 class="font-bold">Sarah Designs</h4>
                            <p class="text-sm text-gray-500">Graphic Designer</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <div class="mb-4">
                        <i class="fas fa-quote-left text-teal-500 opacity-50 text-3xl"></i>
                    </div>
                    <p class="text-gray-700 mb-6 italic">"As a startup founder, I needed to find specialized developers quickly. The filtering system on FolioForge helped me connect with the perfect match for my project."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-teal-700 font-bold">JT</span>
                        </div>
                        <div>
                            <h4 class="font-bold">James Tech</h4>
                            <p class="text-sm text-gray-500">Startup Founder</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <div class="mb-4">
                        <i class="fas fa-quote-left text-teal-500 opacity-50 text-3xl"></i>
                    </div>
                    <p class="text-gray-700 mb-6 italic">"The client testimonials feature has been a game-changer for me. Being able to showcase feedback from previous clients has greatly increased my credibility."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-teal-700 font-bold">MW</span>
                        </div>
                        <div>
                            <h4 class="font-bold">Mark Writer</h4>
                            <p class="text-sm text-gray-500">Content Strategist</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 gradient-primary text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-6">Connect with Top Creative Talent</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">Whether you're looking to hire talented professionals or showcase your skills to potential clients, our platform brings creators and opportunities together.</p>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="create-portfolio.php" class="btn-primary-custom py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    Showcase Your Work
                </a>
            <?php else: ?>
                <div class="flex flex-col md:flex-row justify-center space-y-4 md:space-y-0">
                    <a href="login.php" class="btn-primary-custom py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                        Sign In
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <footer class="footer-custom py-10 mt-auto">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h4 class="text-xl font-bold mb-4">FolioForge</h4>
                    <p class="mb-4">Create professional portfolios with ease.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="text-xl font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="index.php" class="text-gray-400 hover:text-white transition">Home</a></li>
                        <li><a href="about.php" class="text-gray-400 hover:text-white transition">About</a></li>
                        <li><a href="#features" class="text-gray-400 hover:text-white transition">Features</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xl font-bold mb-4">Contact Us</h4>
                    <ul class="space-y-2">
                        <li class="flex items-start"><i class="fas fa-envelope mt-1.5 mr-3 text-gray-400"></i> info@portfoliogenerator.com</li>
                        <li class="flex items-start"><i class="fas fa-phone mt-1.5 mr-3 text-gray-400"></i> +1 (555) 123-4567</li>
                        <li class="flex items-start"><i class="fas fa-map-marker-alt mt-1.5 mr-3 text-gray-400"></i> 123 Portfolio St, Creative City</li>
                    </ul>
                </div>
            </div>
            <div class="text-center pt-8 mt-8 border-t border-gray-800">
                <p>&copy; <?php echo date('Y'); ?> FolioForge. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="js/cache-buster.js"></script>
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
            menu.classList.toggle('bg-teal-500');
            menu.classList.toggle('p-4');
            menu.classList.toggle('rounded');
            menu.classList.toggle('shadow-lg');
            menu.classList.toggle('z-50');
        });
    </script>
</body>
</html>