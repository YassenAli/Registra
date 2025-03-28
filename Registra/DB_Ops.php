<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "testdb";  

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

if (isset($_POST['check_username'])) {
    $username = $_POST['check_username'];
    $query = "SELECT * FROM users WHERE user_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    echo ($result->num_rows > 0) ? "exists" : "available";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $user_name = $_POST['user_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); 
    $checkQuery = "SELECT * FROM users WHERE email = ? OR user_name = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ss", $email, $user_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Error: This email or username is already registered!";
        exit();
    }
    $stmt->close();

    $image_name = "";
    if (isset($_FILES["user_image"]) && $_FILES["user_image"]["error"] == 0) {
        $uploadDir = "uploads/";
        $fileName = basename($_FILES["user_image"]["name"]);
        $targetFilePath = $uploadDir . $fileName;
        $allowedTypes = array("jpg", "jpeg", "png");

        if (in_array(strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION)), $allowedTypes)) {
            if ($_FILES["user_image"]["size"] <= 2 * 1024 * 1024) {
                if (move_uploaded_file($_FILES["user_image"]["tmp_name"], $targetFilePath)) {
                    $image_name = $fileName;
                }
            }
        }
    }

    $query = "INSERT INTO users (full_name, user_name, phone, address, email, password, image) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssss", $full_name, $user_name, $phone, $address, $email, $password, $image_name);

    if ($stmt->execute()) {
        header("Location: success.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>