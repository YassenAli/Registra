</div> <!-- Close content-wrapper -->
    <footer class="main-footer">
        <div class="footer-container container">
            <div class="footer-grid">
                <div class="footer-section">
                    <h4 class="footer-heading">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="index.php">Registration</a></li>
                        <li><a href="terms.php">Terms of Service</a></li>
                        <li><a href="privacy.php">Privacy Policy</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4 class="footer-heading">Connect With Us</h4>
                    <div class="social-links social-icons">
                        <a href="#" class="social-icon" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-icon" aria-label="GitHub"><i class="fab fa-github"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4 class="footer-heading">Contact Info</h4>
                    <p class="contact-info"><i class="fas fa-map-marker-alt"></i> شارع أحمد زويل، الدقي، قسم الدقي، محافظة الجيزة</p>
                    <p class="contact-info"><i class="fas fa-phone"></i> +1 (555) 123-4567</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p class="copyright">&copy; 2025 Registra. All rights reserved.</p>
                <p class="credits">Designed with <i class="fas fa-heart"></i> Registra Team</p>
            </div>
        </div>
    </footer>

    <!-- Notification Toast -->
    <div id="notificationToast" class="toast" aria-live="polite"></div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registrationForm');
    const fields = {
        full_name: { pattern: /^[a-zA-Z\s]{3,}$/, message: 'At least 3 letters' },
        user_name: { pattern: /^[a-zA-Z0-9_]{3,}$/, message: '3+ chars (letters, numbers, _)' },
        email: { pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, message: 'Invalid email format' },
        phone: { pattern: /^\d{10}$/, message: '10 digits required' },
        whatsapp: { pattern: /^\d{10}$/, message: '10 digits required' },
        address: { pattern: /.{5,}/, message: 'At least 5 characters' },
        password: { pattern: /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/, 
                message: '8+ chars with number & special' },
        user_image: { pattern: null, message: 'Image required' }
    };

    // Real-time Username Validation
    document.getElementById('user_name').addEventListener('blur', async function() {
        const username = this.value.trim();
        const feedback = document.getElementById('usernameFeedback');
        
        if (!fields.user_name.pattern.test(username)) {
            showValidation(feedback, 'Invalid username format', false);
            return;
        }

        try {
            const response = await fetch('DB_Ops.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=check_username&username=${encodeURIComponent(username)}`
            });
            
            const data = await response.json();
            showValidation(feedback, data.message, data.valid);
        } catch (error) {
            showValidation(feedback, 'Validation service unavailable', false);
        }
    });

    // Real-time Field Validation
    Object.entries(fields).forEach(([fieldId, config]) => {
        const input = document.getElementById(fieldId);
        if (!input) return;

        input.addEventListener('blur', function() {
            const value = this.value.trim();
            const feedback = this.parentElement.querySelector('.invalid-feedback');
            
            if (fieldId === 'confirm_password') {
                const password = document.getElementById('password').value;
                validateConfirmPassword(password, value);
            } else if (config.pattern && !config.pattern.test(value)) {
                showValidation(feedback, config.message, false);
            } else {
                showValidation(feedback, '', true);
            }
        });
    });

    // Password Match Validation
    function validateConfirmPassword(password, confirmPassword) {
        const feedback = document.getElementById('passwordError');
        if (password !== confirmPassword) {
            showValidation(feedback, 'Passwords do not match', false);
            return false;
        }
        showValidation(feedback, '', true);
        return true;
    }

    // Form Submission Handler
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        let isValid = true;

        // Validate all fields
        Object.entries(fields).forEach(([fieldId, config]) => {
            const input = document.getElementById(fieldId);
            const feedback = input.parentElement.querySelector('.invalid-feedback');
            const value = input.value.trim();

            if (fieldId === 'confirm_password') {
                const password = document.getElementById('password').value;
                if (!validateConfirmPassword(password, value)) isValid = false;
            } else if (input.required && !value) {
                showValidation(feedback, 'This field is required', false);
                isValid = false;
            } else if (config.pattern && !config.pattern.test(value)) {
                showValidation(feedback, config.message, false);
                isValid = false;
            }
        });

        // Validate image
        const fileInput = document.getElementById('user_image');
        if (!fileInput.files[0]) {
            showValidation(fileInput.parentElement.querySelector('.invalid-feedback'), 
                        'Profile image required', false);
            isValid = false;
        }

        if (!isValid) return;

        // Submit form
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

        try {
            const formData = new FormData(form);
            formData.append('action', 'register');
            const response = await fetch('DB_Ops.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                Object.entries(data.errors || {}).forEach(([field, message]) => {
                    const feedback = document.getElementById(`${field}Feedback`) ||
                                document.getElementById(`${field}Error`);
                    if (feedback) showValidation(feedback, message, false);
                });
                if (data.message) showToast(data.message, 'error');
            }
        } catch (error) {
            showToast('Network error - please try again', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-user-plus"></i> Register';
        }
    });

    function showValidation(element, message, isValid) {
        if (!element) return;
        element.textContent = message;
        element.style.color = isValid ? '#28a745' : '#dc3545';
        element.previousElementSibling.style.borderColor = isValid ? '#28a745' : '#dc3545';
    }

    function showToast(message, type = 'success') {
        const toast = document.getElementById('notificationToast');
        toast.textContent = message;
        toast.className = `toast ${type} show`;
        setTimeout(() => toast.classList.remove('show'), 3000);
    }
});
    </script>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <!-- <script src="scripts.js"></script> -->
</body>
</html>