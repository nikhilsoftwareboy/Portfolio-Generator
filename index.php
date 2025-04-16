<?php
require_once 'config.php';
if(!isset($_SESSION)) { session_start(); }

// Remove the query for recent portfolios since we're removing that section
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Generator - Create Your Professional Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
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
            <a href="index.php" class="text-2xl font-bold animate-fade-in">Portfolio Generator</a>
            <button class="md:hidden focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <div class="hidden md:flex space-x-6">
                <a href="index.php" class="nav-link-custom nav-link-active">Home</a>
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

    <header class="bg-gradient-to-br from-blue-50 to-green-100 py-20 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-light-blue-300 rounded-full opacity-10 -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-green-300 rounded-full opacity-10 -ml-10 -mb-10"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="md:w-1/2 mb-10 md:mb-0 animate-fade-in">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 text-gray-800">Create Your <span class="text-primary-gradient">Professional Portfolio</span> in Minutes</h1>
                    <p class="text-xl text-gray-600 mb-8">Showcase your work, skills, and experience with a beautiful online portfolio.</p>
                    
                    <?php if(isLoggedIn()): ?>
                        <a href="create-portfolio.php" class="btn-primary-custom py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition duration-300 inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i> Create Your Portfolio
                        </a>
                    <?php else: ?>
                        <div class="space-x-4">
                            <a href="register.php" class="btn-primary-custom py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition duration-300 inline-flex items-center">
                                <i class="fas fa-user-plus mr-2"></i> Get Started
                            </a>
                            <a href="#features" class="btn-secondary-custom py-3 px-6 rounded-lg transition duration-300">
                                Learn More
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

    <section id="features" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Why Choose Our <span class="text-primary-gradient">Portfolio Generator</span></h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1 border border-gray-100 hover-card">
                    <div class="text-light-blue-500 mb-4 text-4xl">
                        <i class="fas fa-paint-brush"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Beautiful Designs</h3>
                    <p class="text-gray-600">Choose from professionally designed templates that make your work stand out.</p>
                </div>
                
                <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1 border border-gray-100 hover-card">
                    <div class="text-green-500 mb-4 text-4xl">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Easy to Use</h3>
                    <p class="text-gray-600">No coding required. Our intuitive interface makes creating your portfolio simple.</p>
                </div>
                
                <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1 border border-gray-100 hover-card">
                    <div class="text-light-blue-500 mb-4 text-4xl">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Mobile Responsive</h3>
                    <p class="text-gray-600">Your portfolio looks great on all devices, from smartphones to desktops.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 gradient-primary text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-6">Ready to Create Your Portfolio?</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">Join thousands of professionals who have already created stunning portfolios.</p>
            <?php if(isLoggedIn()): ?>
                <a href="create-portfolio.php" class="btn-primary-custom py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    Create Your Portfolio Now
                </a>
            <?php else: ?>
                <a href="register.php" class="btn-primary-custom py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    Sign Up for Free
                </a>
            <?php endif; ?>
        </div>
    </section>

    <footer class="footer-custom py-10 mt-auto">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h4 class="text-xl font-bold mb-4">Portfolio Generator</h4>
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
                        <li><a href="#features" class="text-gray-400 hover:text-white transition">Features</a></li>
                        <li><a href="about.php" class="text-gray-400 hover:text-white transition">About Us</a></li>
                        <li><a href="contact.php" class="text-gray-400 hover:text-white transition">Contact</a></li>
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
                <p>&copy; <?php echo date('Y'); ?> Portfolio Generator. All rights reserved.</p>
            </div>
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