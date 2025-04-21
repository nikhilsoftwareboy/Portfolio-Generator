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
$industry = $service_type = $testimonials = $work_samples = "";
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
    $industry = sanitizeInput($_POST["industry"]);
    $service_type = sanitizeInput($_POST["service_type"]);
    $testimonials = sanitizeInput($_POST["testimonials"]);
    $work_samples = sanitizeInput($_POST["work_samples"]);
    
    // Check for errors before inserting into database
    if(empty($title_err)) {
        // Insert portfolio
        $user_id = $_SESSION["user_id"];
        
        try {
            // Try using the new schema first
            $sql = "INSERT INTO portfolios (user_id, title, description, skills, projects, education, experience, contact_info, industry, service_type, testimonials, work_samples) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $result = executeQuery($sql, [$user_id, $title, $description, $skills, $projects, $education, $experience, $contact_info, $industry, $service_type, $testimonials, $work_samples], 'isssssssssss');
        } catch (Exception $e) {
            // Fallback to the old schema if columns don't exist
            $sql = "INSERT INTO portfolios (user_id, title, description, skills, projects, education, experience, contact_info) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $result = executeQuery($sql, [$user_id, $title, $description, $skills, $projects, $education, $experience, $contact_info], 'isssssss');
            
            // Show message about the database schema update
            displayError("Some advanced features (industry, service type, etc.) are not available. Please <a href='db_update.php' class='underline'>run the database update</a> to enable all features.");
        }
        
        if($result) {
            $success_message = "Portfolio created successfully!";
            // Clear the form fields
            $title = $description = $skills = $projects = $education = $experience = $contact_info = $industry = $service_type = $testimonials = $work_samples = "";
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
    <link rel="stylesheet" href="serve-css.php">
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
                <a href="profile.php" class="nav-link-custom">My Profile</a>
                <a href="create-portfolio.php" class="nav-link-custom nav-link-active">Create Portfolio</a>
                <a href="logout.php" class="nav-link-custom">Logout</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto px-4 py-8">
        <?php if(!empty($success_message)): ?>
            <div class="alert-success-custom p-4 mb-6 rounded-lg animate-fade-in">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-teal-600 text-xl mr-3"></i>
                    <p class="font-medium"><?php echo $success_message; ?></p>
                </div>
                <div class="mt-2 ml-8">
                    <a href="profile.php" class="underline font-medium text-teal-700 hover:text-teal-800 transition duration-200">View all your portfolios</a>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Left sidebar with steps and tips -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-md overflow-hidden card-custom sticky top-4">
                    <div class="gradient-card-header py-4 px-6">
                        <h2 class="text-lg font-bold text-white">Portfolio Creation Guide</h2>
                    </div>
                    <div class="p-4">
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <div class="bg-lavender text-teal rounded-full h-6 w-6 flex items-center justify-center mt-0.5 mr-2 flex-shrink-0">
                                    <span class="text-sm font-bold">1</span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Title</h3>
                                    <p class="text-sm text-gray-600">Choose a clear and professional title</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="bg-lavender text-teal rounded-full h-6 w-6 flex items-center justify-center mt-0.5 mr-2 flex-shrink-0">
                                    <span class="text-sm font-bold">2</span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">About You</h3>
                                    <p class="text-sm text-gray-600">Brief professional summary</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="bg-lavender text-teal rounded-full h-6 w-6 flex items-center justify-center mt-0.5 mr-2 flex-shrink-0">
                                    <span class="text-sm font-bold">3</span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Skills</h3>
                                    <p class="text-sm text-gray-600">List key technical and soft skills</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="bg-lavender text-teal rounded-full h-6 w-6 flex items-center justify-center mt-0.5 mr-2 flex-shrink-0">
                                    <span class="text-sm font-bold">4</span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Projects</h3>
                                    <p class="text-sm text-gray-600">Highlight your best work</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="bg-lavender text-teal rounded-full h-6 w-6 flex items-center justify-center mt-0.5 mr-2 flex-shrink-0">
                                    <span class="text-sm font-bold">5</span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Experience & Education</h3>
                                    <p class="text-sm text-gray-600">Show your background</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="bg-lavender text-teal rounded-full h-6 w-6 flex items-center justify-center mt-0.5 mr-2 flex-shrink-0">
                                    <span class="text-sm font-bold">6</span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Contact Info</h3>
                                    <p class="text-sm text-gray-600">How people can reach you</p>
                                </div>
                            </li>
                        </ul>
                        
                        <div class="mt-6 p-3 bg-columbia-blue bg-opacity-30 rounded-lg border border-columbia-blue">
                            <h3 class="text-sm font-semibold text-gunmetal flex items-center">
                                <i class="fas fa-lightbulb text-yellow-500 mr-2"></i> Pro Tips
                            </h3>
                            <ul class="mt-2 text-sm text-gray-600 space-y-2">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-teal mt-0.5 mr-1.5"></i>
                                    <span>Keep descriptions concise and relevant</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-teal mt-0.5 mr-1.5"></i>
                                    <span>Use bullet points for readability</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-teal mt-0.5 mr-1.5"></i>
                                    <span>Quantify achievements when possible</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main form area -->
            <div class="md:col-span-3">
                <div class="bg-white rounded-lg shadow-md overflow-hidden card-custom animate-fade-in">
                    <div class="gradient-card-header py-4 px-6 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-white">Create Your Professional Portfolio</h2>
                        <span class="text-white text-sm bg-white bg-opacity-20 py-1 px-3 rounded-full">All fields optional except title</span>
                    </div>
                    
                    <div class="p-6">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <!-- Title Section -->
                            <div class="mb-8">
                                <div class="flex items-center mb-4">
                                    <div class="bg-lavender text-teal rounded-full h-6 w-6 flex items-center justify-center mr-2">
                                        <span class="text-sm font-bold">1</span>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800">Portfolio Title</h3>
                                </div>
                                
                                <div class="ml-8">
                                    <input type="text" name="title" class="shadow appearance-none border <?php echo (!empty($title_err)) ? 'border-red-500' : 'border-gray-300'; ?> rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent input-custom" value="<?php echo $title; ?>" placeholder="e.g., Full-Stack Developer Portfolio" required>
                                    <?php if(!empty($title_err)): ?>
                                        <p class="text-red-500 text-sm mt-1"><?php echo $title_err; ?></p>
                                    <?php endif; ?>
                                    <p class="text-gray-500 text-sm mt-1">A professional title that reflects your expertise and career goals.</p>
                                </div>
                            </div>
                            
                            <!-- Industry & Service Type Section -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                                <!-- Industry Section -->
                                <div>
                                    <div class="flex items-center mb-4">
                                        <div class="bg-lavender text-teal rounded-full h-6 w-6 flex items-center justify-center mr-2">
                                            <span class="text-sm font-bold">2a</span>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-800">Industry</h3>
                                    </div>
                                    
                                    <div class="ml-8">
                                        <select name="industry" class="shadow appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent input-custom">
                                            <option value="" <?php echo empty($industry) ? 'selected' : ''; ?>>Select your industry</option>
                                            <option value="Web Development" <?php echo $industry == 'Web Development' ? 'selected' : ''; ?>>Web Development</option>
                                            <option value="Graphic Design" <?php echo $industry == 'Graphic Design' ? 'selected' : ''; ?>>Graphic Design</option>
                                            <option value="Digital Marketing" <?php echo $industry == 'Digital Marketing' ? 'selected' : ''; ?>>Digital Marketing</option>
                                            <option value="Content Writing" <?php echo $industry == 'Content Writing' ? 'selected' : ''; ?>>Content Writing</option>
                                            <option value="Photography" <?php echo $industry == 'Photography' ? 'selected' : ''; ?>>Photography</option>
                                            <option value="Video Production" <?php echo $industry == 'Video Production' ? 'selected' : ''; ?>>Video Production</option>
                                            <option value="UI/UX Design" <?php echo $industry == 'UI/UX Design' ? 'selected' : ''; ?>>UI/UX Design</option>
                                            <option value="Mobile Development" <?php echo $industry == 'Mobile Development' ? 'selected' : ''; ?>>Mobile Development</option>
                                            <option value="Illustration" <?php echo $industry == 'Illustration' ? 'selected' : ''; ?>>Illustration</option>
                                            <option value="Other" <?php echo $industry == 'Other' ? 'selected' : ''; ?>>Other</option>
                                        </select>
                                        <p class="text-gray-500 text-sm mt-1">Select the industry that best represents your work.</p>
                                    </div>
                                </div>
                                
                                <!-- Service Type Section -->
                                <div>
                                    <div class="flex items-center mb-4">
                                        <div class="bg-lavender text-teal rounded-full h-6 w-6 flex items-center justify-center mr-2">
                                            <span class="text-sm font-bold">2b</span>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-800">Service Type</h3>
                                    </div>
                                    
                                    <div class="ml-8">
                                        <select name="service_type" class="shadow appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent input-custom">
                                            <option value="" <?php echo empty($service_type) ? 'selected' : ''; ?>>Select your service type</option>
                                            <option value="Freelancer" <?php echo $service_type == 'Freelancer' ? 'selected' : ''; ?>>Freelancer</option>
                                            <option value="Agency" <?php echo $service_type == 'Agency' ? 'selected' : ''; ?>>Agency</option>
                                            <option value="Consultant" <?php echo $service_type == 'Consultant' ? 'selected' : ''; ?>>Consultant</option>
                                            <option value="Full-time Availability" <?php echo $service_type == 'Full-time Availability' ? 'selected' : ''; ?>>Full-time Availability</option>
                                            <option value="Part-time Availability" <?php echo $service_type == 'Part-time Availability' ? 'selected' : ''; ?>>Part-time Availability</option>
                                            <option value="Project-based" <?php echo $service_type == 'Project-based' ? 'selected' : ''; ?>>Project-based</option>
                                        </select>
                                        <p class="text-gray-500 text-sm mt-1">Specify your service model to help clients understand your availability.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Description Section -->
                            <div class="mb-8">
                                <div class="flex items-center mb-4">
                                    <div class="bg-lavender text-teal rounded-full h-6 w-6 flex items-center justify-center mr-2">
                                        <span class="text-sm font-bold">3</span>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800">About You</h3>
                                </div>
                                
                                <div class="ml-8">
                                    <textarea name="description" class="shadow appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent input-custom" rows="4" placeholder="A brief professional summary about yourself, your experience, and what you're passionate about..."><?php echo $description; ?></textarea>
                                    <p class="text-gray-500 text-sm mt-1">A concise professional summary highlighting your expertise and career objectives.</p>
                                </div>
                            </div>
                            
                            <!-- Skills Section -->
                            <div class="mb-8">
                                <div class="flex items-center mb-4">
                                    <div class="bg-lavender text-teal rounded-full h-6 w-6 flex items-center justify-center mr-2">
                                        <span class="text-sm font-bold">4</span>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800">Skills</h3>
                                </div>
                                
                                <div class="ml-8">
                                    <textarea name="skills" class="shadow appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent input-custom" rows="3" placeholder="JavaScript, React, Node.js, UI/UX Design, Team Leadership..."><?php echo $skills; ?></textarea>
                                    <p class="text-gray-500 text-sm mt-1">List your technical and soft skills, separated by commas or line breaks. Focus on skills relevant to your target roles.</p>
                                </div>
                            </div>
                            
                            <!-- Projects Section -->
                            <div class="mb-8">
                                <div class="flex items-center mb-4">
                                    <div class="bg-lavender text-teal rounded-full h-6 w-6 flex items-center justify-center mr-2">
                                        <span class="text-sm font-bold">5</span>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800">Projects</h3>
                                </div>
                                
                                <div class="ml-8">
                                    <textarea name="projects" class="shadow appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent input-custom" rows="5" placeholder="Project Name: Brief description of the project, your role, technologies used, and outcomes. Include links if available.

Project Name: Another project description..."><?php echo $projects; ?></textarea>
                                    <p class="text-gray-500 text-sm mt-1">Showcase your notable projects with descriptions of your role, technologies used, and outcomes achieved.</p>
                                </div>
                            </div>
                            
                            <!-- Two columns for Education and Experience -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                                <!-- Education Section -->
                                <div>
                                    <div class="flex items-center mb-4">
                                        <div class="bg-lavender text-teal rounded-full h-6 w-6 flex items-center justify-center mr-2">
                                            <span class="text-sm font-bold">6a</span>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-800">Education</h3>
                                    </div>
                                    
                                    <div class="ml-8">
                                        <textarea name="education" class="shadow appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent input-custom" rows="4" placeholder="Degree, Institution, Year
Certifications or additional training"><?php echo $education; ?></textarea>
                                        <p class="text-gray-500 text-sm mt-1">Your academic background, degrees, and certifications.</p>
                                    </div>
                                </div>
                                
                                <!-- Experience Section -->
                                <div>
                                    <div class="flex items-center mb-4">
                                        <div class="bg-lavender text-teal rounded-full h-6 w-6 flex items-center justify-center mr-2">
                                            <span class="text-sm font-bold">6b</span>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-800">Experience</h3>
                                    </div>
                                    
                                    <div class="ml-8">
                                        <textarea name="experience" class="shadow appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent input-custom" rows="4" placeholder="Position, Company, Duration
Key responsibilities and achievements"><?php echo $experience; ?></textarea>
                                        <p class="text-gray-500 text-sm mt-1">Your work history with positions, companies, and key achievements.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Work Samples Section -->
                            <div class="mb-8">
                                <div class="flex items-center mb-4">
                                    <div class="bg-lavender text-teal rounded-full h-6 w-6 flex items-center justify-center mr-2">
                                        <span class="text-sm font-bold">7</span>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800">Work Samples</h3>
                                </div>
                                
                                <div class="ml-8">
                                    <textarea name="work_samples" class="shadow appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent input-custom" rows="4" placeholder="Sample 1: Title - Description
Link: https://example.com/work-sample

Sample 2: Title - Description
Link: https://example.com/work-sample"><?php echo $work_samples; ?></textarea>
                                    <p class="text-gray-500 text-sm mt-1">List your best work samples with links to demonstrate your skills.</p>
                                </div>
                            </div>
                            
                            <!-- Client Testimonials Section -->
                            <div class="mb-8">
                                <div class="flex items-center mb-4">
                                    <div class="bg-lavender text-teal rounded-full h-6 w-6 flex items-center justify-center mr-2">
                                        <span class="text-sm font-bold">8</span>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800">Client Testimonials</h3>
                                </div>
                                
                                <div class="ml-8">
                                    <textarea name="testimonials" class="shadow appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent input-custom" rows="4" placeholder='"Quote from client" - Client Name, Company

"Another testimonial" - Another Client, Company'><?php echo $testimonials; ?></textarea>
                                    <p class="text-gray-500 text-sm mt-1">Add testimonials from clients to build trust with potential new clients.</p>
                                </div>
                            </div>
                            
                            <!-- Contact Information Section -->
                            <div class="mb-8">
                                <div class="flex items-center mb-4">
                                    <div class="bg-lavender text-teal rounded-full h-6 w-6 flex items-center justify-center mr-2">
                                        <span class="text-sm font-bold">9</span>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800">Contact Information</h3>
                                </div>
                                
                                <div class="ml-8">
                                    <textarea name="contact_info" class="shadow appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent input-custom" rows="3" placeholder="Email: your@email.com
LinkedIn: linkedin.com/in/yourprofile
GitHub: github.com/yourusername
Personal Website: yourdomain.com"><?php echo $contact_info; ?></textarea>
                                    <p class="text-gray-500 text-sm mt-1">Provide your professional contact information and social media profiles.</p>
                                </div>
                            </div>
                            
                            <!-- Submit Buttons -->
                            <div class="flex items-center justify-end mt-10">
                                <a href="profile.php" class="btn-secondary-custom font-medium py-3 px-6 rounded-lg transition duration-300 mr-4">Cancel</a>
                                <button type="submit" class="btn-primary-custom font-medium py-3 px-8 rounded-lg transition duration-300 flex items-center">
                                    <i class="fas fa-check mr-2"></i> Create Portfolio
                                </button>
                            </div>
                        </form>
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