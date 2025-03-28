document.getElementById("registrationForm").addEventListener("submit", function (event) {
    event.preventDefault(); 
    let isValid = true;

    function showError(inputId, message) {
        let errorElement = document.getElementById(inputId);
        errorElement.textContent = message;
        errorElement.style.color = "red"; 
        isValid = false;
    }

    function clearError(inputId) {
        document.getElementById(inputId).textContent = "";
    }

    let fullName = document.getElementById("full_name").value.trim();
    let userName = document.getElementById("user_name").value.trim();
    let email = document.getElementById("email").value.trim();
    let phone = document.getElementById("phone").value.trim();
    let whatsapp = document.getElementById("whatsapp").value.trim();
    let address = document.getElementById("address").value.trim();
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirm_password").value;
    let userImage = document.getElementById("user_image").files[0]; 
    let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    let phonePattern = /^\d{10}$/;

    document.querySelectorAll(".error").forEach(error => error.textContent = "");

    if (fullName.length < 3) showError("fullNameError", "Full Name must be at least 3 characters");
    else clearError("fullNameError");

    if (userName.length < 4) showError("userNameError", "Username must be at least 4 characters");
    else clearError("userNameError");

    if (!emailPattern.test(email)) showError("emailError", "Enter a valid email address");
    else clearError("emailError");

    if (!phonePattern.test(phone)) showError("phoneError", "Enter a valid 10-digit phone number");
    else clearError("phoneError");

    if (!phonePattern.test(whatsapp)) showError("whatsappError", "Enter a valid 10-digit WhatsApp number");
    else clearError("whatsappError");

    if (address.length < 5) showError("addressError", "Address must be at least 5 characters");
    else clearError("addressError");

    if (password.length < 6) showError("passwordError", "Password must be at least 6 characters");
    else clearError("passwordError");

    if (password !== confirmPassword) showError("confirmPasswordError", "Passwords do not match");
    else clearError("confirmPasswordError");

    if (!userImage) showError("imageError", "Please upload a profile picture");
    else clearError("imageError");

    if (!isValid) return;

    let formData = new FormData();
    formData.append("full_name", fullName);
    formData.append("user_name", userName);
    formData.append("email", email);
    formData.append("phone", phone);
    formData.append("whatsapp", whatsapp);
    formData.append("address", address);
    formData.append("password", password);
    formData.append("user_image", userImage);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "backend.php", true); 
    xhr.onload = function () {
        if (xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert("Registration Successful!");
                document.getElementById("registrationForm").reset(); 
            } else {
                alert("Error: " + response.message);
            }
        } else {
            alert("Server Error! Please try again.");
        }
    };

    xhr.send(formData); 
});
