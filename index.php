<?php
// Form processing at the top to prevent header issues
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'DB_Ops.php';
    require_once 'FileUploader.php';

    try {
        // CSRF validation
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            throw new Exception('Invalid CSRF token');
        }

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

    <main class="form-page-container">
        <div class="form-wrapper">
            <h2>Register</h2>
            <form id="registerForm" action="DB_Ops.php" method="POST" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="full_name" required>
                </div>

                <div class="form-group">
                    <label for="user_name">Username:</label>
                    <input type="text" id="user_name" name="user_name" required>
                    <span class="error" id="usernameError"></span>
                </div>

                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" required>
                </div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required>
                </div>

                <div class="form-group">
                    <label for="whatsapp">WhatsApp Number:</label>
                    <input type="text" name="whatsapp" id="whatsapp">
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <span class="error" id="emailError"></span>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <span class="error" id="passwordError"></span>
                </div>

                <div class="form-group">
                    <label for="user_image">Upload Image:</label>
                    <input type="file" id="user_image" name="user_image" accept="image/*">
                </div>

                <button type="submit" id="submitBtn">Register</button>
            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#user_name").on("keyup", function () {
                let username = $(this).val();
                let submitBtn = $("#submitBtn");
                let usernameError = $("#usernameError");

                if (username.length > 2) {
                    $.ajax({
                        type: "POST",
                        url: "DB_Ops.php",
                        data: { check_username: username },
                        success: function (response) {
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

</html>