<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "testdb";

$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$upload_dir = "uploads/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0775, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["user_image"])) {
    $file_tmp = $_FILES["user_image"]["tmp_name"];
    $file_size = $_FILES["user_image"]["size"];
    $file_name = pathinfo($_FILES["user_image"]["name"], PATHINFO_FILENAME);
    $file_ext = strtolower(pathinfo($_FILES["user_image"]["name"], PATHINFO_EXTENSION));

    $allowed_types = ["jpg", "jpeg", "png", "gif"];

    if (!in_array($file_ext, $allowed_types)) {
        die("Error: Only JPG, JPEG, PNG & GIF files are allowed.");
    }

    if ($file_size > 2 * 1024 * 1024) {
        die("Error: File size must be less than 2MB.");
    }

    if (!getimagesize($file_tmp)) {
        die("Error: File is not a valid image.");
    }

    $new_filename = sprintf("%s_%s.%s", $file_name, uniqid(), $file_ext);
    $final_file = $upload_dir . $new_filename;

    if (move_uploaded_file($file_tmp, $final_file)) {
        $query = "INSERT INTO users (user_image) VALUES (?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $new_filename);

        if (mysqli_stmt_execute($stmt)) {
            echo "Image successfully uploaded! <a href='display.php'>View Images</a>";
        } else {
            die("Database error: " . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt);
    } else {
        die("Error moving file to upload directory.");
    }
} else {
    die("No file was uploaded.");
}

mysqli_close($conn);
?>
