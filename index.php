<?php
// Form processing at the top to prevent header issues
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'DB_Ops.php';
    require_once 'FileUploader.php';

    try {
        // CSRF validation
        // if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        //     throw new Exception('Invalid CSRF token');
        // }

        $uploader = new FileUploader();
        $filename = $uploader->upload($_FILES['user_image']);

        $userData = [
            'full_name' => htmlspecialchars($_POST['full_name']),
            'user_name' => htmlspecialchars($_POST['user_name']),
            'phone' => htmlspecialchars($_POST['phone']),
            'whatsapp' => htmlspecialchars($_POST['whatsapp']),
            'address' => htmlspecialchars($_POST['address']),
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'user_image' => $filename,
            'email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)
        ];

        $db = new DBOperations();
        if ($db->insertUser($userData)) {
            $_SESSION['success_message'] = "Registration successful! Welcome " . $userData['full_name'];
            header("Location: success.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header("Location: index.php");
        exit();
    }
}
?>

<?php include 'header.php'; ?>

<main class="registration-main form-page-container">
    <div class="registration-container animate__animated animate__fadeIn form-wrapper">
        <div class="form-header">
            <h2>Register</h2>
        </div>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <form id="registrationForm" action="DB_Ops.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <!-- <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>"> -->

            <div class="form-grid">
                    <div class="form-group floating-label">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" class="form-control" required>
                        <div class="invalid-feedback" id="fullnameFeedback"></div>
                    </div>

                    <div class="form-group floating-label">
                        <label for="user_name">Username</label>
                        <input type="text" id="user_name" name="user_name" class="form-control" required
                            pattern="[a-zA-Z0-9_]{3,}">
                        <div class="invalid-feedback" id="usernameFeedback"></div>
                    </div>

                    <div class="form-group floating-label">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                        <div class="invalid-feedback" id="emailFeedback"></div>
                        <div class="invalid-feedback" id="emailFeedback"></div>
                    </div>

                    <div class="form-group floating-label">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone" class="form-control" required pattern="[0-9]{10}">
                        <div class="invalid-feedback" id="phoneFeedback"></div>
                    </div>

                    <div class="form-group floating-label">
                        <label for="whatsapp">WhatsApp Number</label>
                        <input type="text" id="whatsapp" name="whatsapp" class="form-control"
                            pattern="[0-9]{10}">
                        <button type="button" class="whatsapp-check" id="validateWhatsApp">
                            <i class="fab fa-whatsapp"></i> Validate
                        </button>
                        <div class="invalid-feedback" id="whatsAppFeedback"></div>
                    </div>

                    <div class="form-group floating-label">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" class="form-control" required>
                        <div class="invalid-feedback" id="addressFeedback"></div>
                    </div>

                    <div class="form-group floating-label">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required
                            pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$">
                        <div class="password-strength">
                            <div class="strength-bar"></div>
                            <span class="strength-text"></span>
                        </div>
                        <div class="invalid-feedback" id="confirmFeedback"></div>
                    </div>

                    <div class="form-group floating-label">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        <div class="invalid-feedback" id="confirmPasswordFeedback"></div>
                    </div>

                    <div class="form-group file-upload">
                        <label class="upload-label">
                            <span class="upload-button"><i class="fas fa-cloud-upload-alt"></i> Choose Profile Image</span>
                            <span class="file-name"></span>
                            <input type="file" id="user_image" name="user_image" accept="image/*" required>
                            <div class="invalid-feedback" id="userImageFeedback"></div>
                        </label>
                    </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="submit-btn" id="submitBtn">
                    <i class="fas fa-user-plus"></i> Register
                </button>
                <p class="form-note">Already have an account? <a href="#">Sign In</a></p>
            </div>
        </form>
    </div>
</main>

    <?php include 'footer.php'; ?>

    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <!-- <script>
        $(document).ready(function () {
            $("#user_name").on("keyup", function () {
                console.log("Checking username...");
                let username = $(this).val();
                let submitBtn = $("#submitBtn");
                let usernameError = $("#usernameError");

                if (username.length > 3) {
                    console.log("Username length is valid");
                    $.ajax({
                        type: "POST",
                        url: "DB_Ops.php",
                        data: { check_username: username },
                        success: function (response) {
                            console.log("Response from server:", response);
                            if (response === "exists") {
                                usernameError.text("Username already taken! Choose another.");
                                submitBtn.prop("disabled", true);
                            } else {
                                usernameError.text("");
                                submitBtn.prop("disabled", false);
                            }
                        }
                    });
                } else {
                    usernameError.text("");
                }
            });

            $("#registerForm").on("submit", function (event) {
                console.log("Form submission triggered");
                let password = $("#password").val();
                let confirmPassword = $("#confirm_password").val();
                let passwordPattern = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/;
                let passwordError = $("#passwordError");
                let isValid = true;

                passwordError.text("");

                if (!password.match(passwordPattern)) {
                    passwordError.text("Password must be 8+ chars, with a number & special character.");
                    isValid = false;
                } else if (password !== confirmPassword) {
                    passwordError.text("Passwords do not match.");
                    isValid = false;
                }
                console.log("Password validation:", isValid);

                if (!isValid) {
                    event.preventDefault();
                    $("#submitBtn").prop("disabled", true);
                } else {
                    $("#submitBtn").prop("disabled", false);
                }
            });
        });
    </script>
</body>
</html> -->