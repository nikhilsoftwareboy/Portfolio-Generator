// Wait for the document to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize textareas
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(function(textarea) {
        textarea.addEventListener('input', autoResize);
        // Initialize on page load
        autoResize.call(textarea);
    });
    
    // Password validation for registration
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('confirm_password');
    
    if (passwordField && confirmPasswordField) {
        confirmPasswordField.addEventListener('input', validatePassword);
    }
    
    // Handle dismissing alerts manually since the Bootstrap Alert.getInstance is not available
    const closeButtons = document.querySelectorAll('.alert .close');
    closeButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            // Get the parent alert element
            const alert = this.parentElement;
            // Add a fade-out class
            alert.classList.add('fade');
            // Remove after animation
            setTimeout(function() {
                alert.style.display = 'none';
            }, 150);
        });
    });

    // Handle edit portfolio button clicks
    const editButtons = document.querySelectorAll('.edit-portfolio-btn');
    
    editButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const portfolioId = this.getAttribute('data-id');
            window.location.href = `edit_portfolio.php?id=${portfolioId}`;
        });
    });
});

// Function to validate password matching
function validatePassword() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const feedback = document.getElementById('password_feedback');
    
    if (feedback) {
        if (password != confirmPassword) {
            feedback.innerHTML = 'Passwords do not match';
            feedback.className = 'text-danger';
            return false;
        } else {
            feedback.innerHTML = 'Passwords match';
            feedback.className = 'text-success';
            return true;
        }
    }
    return true;
}

// Function to auto-resize textareas
function autoResize() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
}