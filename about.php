<?php
require_once 'config.php';
if(!isset($_SESSION)) { session_start(); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Our Team - FolioForge</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="serve-css.php">
    <style>
        .team-member-card {
            transition: all 0.3s ease;
        }
        .team-member-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .member-img {
            transition: all 0.5s ease;
            position: relative;
            overflow: hidden;
        }
        .member-img:after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, transparent 0%, rgba(0, 128, 128, 0.7) 100%);
            opacity: 0;
            transition: all 0.3s ease;
        }
        .team-member-card:hover .member-img:after {
            opacity: 1;
        }
        .social-icons {
            transition: all 0.3s ease;
            transform: translateY(20px);
            opacity: 0;
        }
        .team-member-card:hover .social-icons {
            transform: translateY(0);
            opacity: 1;
        }
        .section-divider {
            height: 5px;
            background: linear-gradient(to right, #008080, transparent);
            margin: 3rem auto;
            width: 30%;
            border-radius: 5px;
        }
        .highlight-text {
            position: relative;
            display: inline-block;
        }
        .highlight-text:after {
            content: '';
            position: absolute;
            width: 100%;
            height: 8px;
            background-color: rgba(0, 128, 128, 0.2);
            bottom: 5px;
            left: 0;
            z-index: -1;
        }
        .bg-pattern {
            background-color: #f9fafb;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23008080' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="flex flex-col min-h-screen bg-pattern">
    <nav class="gradient-header text-white shadow-lg">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold animate-fade-in">FolioForge</a>
            <button class="md:hidden focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <div class="hidden md:flex space-x-6">
                <a href="index.php" class="nav-link-custom">Home</a>
                <a href="about.php" class="nav-link-custom nav-link-active">About</a>
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

    <!-- Hero Section -->
    <section class="relative py-20 overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-teal-500 rounded-full opacity-10 -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-teal-800 rounded-full opacity-10 -ml-10 -mb-10"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center animate-fade-in">
                <h1 class="text-5xl font-bold mb-6 text-gray-800 leading-tight">Meet the <span class="text-primary-gradient">Dream Team</span></h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    We are a diverse group of passionate professionals dedicated to creating the ultimate platform for freelancers and creators to showcase their talents.
                </p>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mb-16 text-center">
                <h2 class="text-3xl font-bold mb-4 inline-block highlight-text">The Talent Behind FolioForge</h2>
                <p class="text-gray-600 mt-4 max-w-2xl mx-auto">Our core team combines skills in development, design, and project management to bring you the best portfolio platform.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 animate-slide-up">
                <!-- Team Member 1 -->
                <div class="team-member-card bg-white rounded-xl overflow-hidden shadow-md">
                    <div class="member-img h-40 bg-gradient-to-r from-teal-500 to-teal-600 flex items-center justify-center p-5">
                        <h3 class="text-2xl font-bold text-white">SHUBHAM KUMAR SINGH</h3>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-1 text-gray-800">SHUBHAM KUMAR SINGH</h3>
                        <div class="w-10 h-1 bg-teal-500 mb-3"></div>
                        <p class="text-teal-600 font-medium mb-3 text-sm">LEAD DEVELOPER</p>
                        <p class="text-gray-600 text-sm mb-4">Responsible for the core functionality and database architecture of FolioForge.</p>
                        <div class="flex justify-center space-x-4">
                            <a href="https://github.com/shubham2027" class="text-gray-400 hover:text-teal-500 transition-colors"><i class="fab fa-github text-xl"></i></a>
                            <a href="https://www.linkedin.com/in/nx-shubham-kumar-singh/" class="text-gray-400 hover:text-teal-500 transition-colors"><i class="fab fa-linkedin text-xl"></i></a>
                            <!-- <a href="mailto:sahaashubhamkumar2001@gmail.com" class="text-gray-400 hover:text-teal-500 transition-colors"><i class="fas fa-envelope text-xl"></i></a> -->
                        </div>
                    </div>
                </div>

                <!-- Team Member 2 -->
                <div class="team-member-card bg-white rounded-xl overflow-hidden shadow-md">
                    <div class="member-img h-40 bg-gradient-to-r from-teal-600 to-teal-700 flex items-center justify-center p-5">
                        <h3 class="text-2xl font-bold text-white">KUNDAN KUMAR</h3>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-1 text-gray-800">KUNDAN KUMAR</h3>
                        <div class="w-10 h-1 bg-teal-600 mb-3"></div>
                        <p class="text-teal-600 font-medium mb-3 text-sm">UI/UX DESIGNER</p>
                        <p class="text-gray-600 text-sm mb-4">Created the beautiful interface and responsive design that makes FolioForge a pleasure to use.</p>
                        <div class="flex justify-center space-x-4">
                            <a href="https://github.com/kundan9199" class="text-gray-400 hover:text-teal-500 transition-colors"><i class="fab fa-github text-xl"></i></a>
                            <a href="https://www.linkedin.com/in/kundan-kumar-sharma-448581284/" class="text-gray-400 hover:text-teal-500 transition-colors"><i class="fab fa-linkedin text-xl"></i></a>
                            <!-- <a href="mailto:kundankumar86826@gmail.com" class="text-gray-400 hover:text-teal-500 transition-colors"><i class="fas fa-envelope text-xl"></i></a> -->
                        </div>
                    </div>
                </div>

                <!-- Team Member 3 -->
                <div class="team-member-card bg-white rounded-xl overflow-hidden shadow-md">
                    <div class="member-img h-40 bg-gradient-to-r from-teal-700 to-teal-800 flex items-center justify-center p-5">
                        <h3 class="text-2xl font-bold text-white">NIKHIL KUMAR SINGH</h3>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-1 text-gray-800">NIKHIL KUMAR SINGH</h3>
                        <div class="w-10 h-1 bg-teal-700 mb-3"></div>
                        <p class="text-teal-600 font-medium mb-3 text-sm">BACKEND DEVELOPER</p>
                        <p class="text-gray-600 text-sm mb-4">Implemented the robust authentication system and database operations for portfolio management.</p>
                        <div class="flex justify-center space-x-4">
                            <a href="https://github.com/nikhilsoftwareboy" class="text-gray-400 hover:text-teal-500 transition-colors"><i class="fab fa-github text-xl"></i></a>
                            <a href="https://www.linkedin.com/in/nikhil--kumar--singh/" class="text-gray-400 hover:text-teal-500 transition-colors"><i class="fab fa-linkedin text-xl"></i></a>
                            <!-- <a href="mailto:nikhilsingh7899.in@gmail.com" class="text-gray-400 hover:text-teal-500 transition-colors"><i class="fas fa-envelope text-xl"></i></a> -->
                        </div>
                    </div>
                </div>

                <!-- Team Member 4 -->
                <div class="team-member-card bg-white rounded-xl overflow-hidden shadow-md">
                    <div class="member-img h-40 bg-gradient-to-r from-teal-500 to-teal-700 flex items-center justify-center p-5">
                        <h3 class="text-2xl font-bold text-white">NITHIN</h3>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-1 text-gray-800">NITHIN</h3>
                        <div class="w-10 h-1 bg-teal-500 mb-3"></div>
                        <p class="text-teal-600 font-medium mb-3 text-sm">PROJECT MANAGER</p>
                        <p class="text-gray-600 text-sm mb-4">Coordinated the development process and ensured the project met all requirements and deadlines.</p>
                        <div class="flex justify-center space-x-4">
                            <a href="https://github.com/meccashan" class="text-gray-400 hover:text-teal-500 transition-colors"><i class="fab fa-github text-xl"></i></a>
                            <a href="https://www.linkedin.com/in/nithin-sai-543b34298/" class="text-gray-400 hover:text-teal-500 transition-colors"><i class="fab fa-linkedin text-xl"></i></a>
                            <!-- <a href="#" class="text-gray-400 hover:text-teal-500 transition-colors"><i class="fas fa-envelope text-xl"></i></a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    <!-- Mission Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row items-center">
                <div class="lg:w-1/2 lg:pr-16 mb-10 lg:mb-0">
                    <h2 class="text-4xl font-bold mb-6 text-gray-800 leading-tight">Our <span class="text-primary-gradient">Mission</span> & Vision</h2>
                    <p class="text-lg text-gray-600 mb-6">
                        We believe every creative professional deserves a beautiful portfolio that helps them stand out. 
                        Our mission is to provide freelancers with powerful tools to showcase their work and connect with clients, 
                        without requiring any technical skills.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-8">
                        <div class="bg-gray-50 p-5 rounded-lg">
                            <div class="text-teal-500 text-3xl mb-3">
                                <i class="fas fa-rocket"></i>
                            </div>
                            <h3 class="font-bold text-gray-800 mb-2">Innovation</h3>
                            <p class="text-gray-600 text-sm">Continuously improving our platform with cutting-edge features.</p>
                        </div>
                        <div class="bg-gray-50 p-5 rounded-lg">
                            <div class="text-teal-500 text-3xl mb-3">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3 class="font-bold text-gray-800 mb-2">Community</h3>
                            <p class="text-gray-600 text-sm">Building connections between freelancers and potential clients.</p>
                        </div>
                    </div>
                </div>
                <div class="lg:w-1/2 bg-gradient-to-br from-teal-500 to-teal-700 rounded-2xl p-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white rounded-full opacity-10 -mr-10 -mt-10"></div>
                    <div class="relative z-10">
                        <h3 class="text-2xl font-bold mb-4">Our Core Values</h3>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mt-1 mr-3 text-teal-200"></i>
                                <div>
                                    <h4 class="font-bold">Accessibility</h4>
                                    <p class="text-teal-100 text-sm">Making professional portfolio creation accessible to everyone.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mt-1 mr-3 text-teal-200"></i>
                                <div>
                                    <h4 class="font-bold">Quality</h4>
                                    <p class="text-teal-100 text-sm">Maintaining high standards in design and functionality.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mt-1 mr-3 text-teal-200"></i>
                                <div>
                                    <h4 class="font-bold">Empowerment</h4>
                                    <p class="text-teal-100 text-sm">Giving freelancers the tools they need to succeed professionally.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-8">Ready to Join Our <span class="text-primary-gradient">Community?</span></h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-10">
                Create your professional portfolio today and showcase your work to potential clients around the world.
            </p>
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="register.php" class="btn-primary-custom py-4 px-8 rounded-lg shadow-md hover:shadow-lg transition duration-300 inline-flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i> Create an Account
                </a>
                <a href="index.php" class="bg-white border border-teal-500 text-teal-600 hover:bg-teal-50 py-4 px-8 rounded-lg shadow-md hover:shadow-lg transition duration-300 inline-flex items-center justify-center">
                    <i class="fas fa-home mr-2"></i> Back to Homepage
                </a>
            </div>
        </div>
    </section>

    <footer class="footer-custom py-8 mt-auto">
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
    </script>
</body>
</html> 