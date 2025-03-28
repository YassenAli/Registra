<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = ["success" => false];

    // Validate required fields
    if (empty($_POST["full_name"]) || empty($_POST["email"]) || empty($_FILES["user_image"])) {
        $response["message"] = "All fields are required!";
        echo json_encode($response);
        exit();
    }

    // Save the uploaded image (example)
    $uploadDir = "uploads/";
    $uploadFile = $uploadDir . basename($_FILES["user_image"]["name"]);

    if (move_uploaded_file($_FILES["user_image"]["tmp_name"], $uploadFile)) {
        $response["success"] = true;
        $response["message"] = "User registered successfully!";
    } else {
        $response["message"] = "Error uploading image!";
    }

    echo json_encode($response);
}
?>