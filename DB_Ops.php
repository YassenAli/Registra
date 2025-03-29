<?php
class DBOperations {
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'registra';
    private $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        if ($this->conn->connect_error) {
            throw new Exception("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function insertUser($userData) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO users(full_name, user_name, phone, whatsapp, address, password, user_image, email)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param("ssssssss",
                $userData['full_name'],
                $userData['user_name'],
                $userData['phone'],
                $userData['whatsapp'],
                $userData['address'],
                $userData['password'],
                $userData['user_image'],
                $userData['email']
            );

            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    public function checkUsernameExists($username) {
        $stmt = $this->conn->prepare("SELECT user_name FROM users WHERE user_name = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function checkEmailExists($email) {
        $stmt = $this->conn->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function __destruct() {
        $this->conn->close();
    }
}

class FileUploader {
    private $uploadDir;
    private $allowedTypes;
    private $maxSize;

    public function __construct($uploadDir = 'uploads/', $allowedTypes = ['jpg', 'jpeg', 'png'], $maxSize = 2097152) {
        $this->uploadDir = $uploadDir;
        $this->allowedTypes = $allowedTypes;
        $this->maxSize = $maxSize;
        $this->createUploadDir();
    }

    private function createUploadDir() {
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function upload($file) {
        if (!$this->validate($file)) {
            throw new Exception('Invalid file');
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $targetPath = $this->uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception('File upload failed');
        }

        return $filename;
    }

    private function validate($file) {
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        return in_array($extension, $this->allowedTypes) &&
            $file['size'] <= $this->maxSize &&
            getimagesize($file['tmp_name']);
    }
}

// Handle all AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        // require_once 'C:\xampp\htdocs\Registra\FileUploader.php';
        $db = new DBOperations();
        $uploader = new FileUploader();
        $response = [];

        // CSRF validation for all actions
        // if (!isset($_POST['csrf_token']) {
        //     throw new Exception('CSRF token missing');
        // }
        
        // if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        //     throw new Exception('Invalid CSRF token');
        // }

        switch ($_POST['action'] ?? '') {
            case 'check_username':
                $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                if (empty($username)) {
                    echo json_encode(['valid' => false, 'message' => 'Username required']);
                    exit;
                }
                try {
                    $exists = $db->checkUsernameExists($username);
                    echo json_encode([
                        'valid' => !$exists,
                        'message' => $exists ? 'Username already taken' : 'Username available'
                    ]);
                } catch (Exception $e) {
                    echo json_encode(['valid' => false, 'message' => 'Validation error']);
                }
                exit;
        
            case 'check_email':
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                if (empty($email)) {
                    echo json_encode(['valid' => false, 'message' => 'Email required']);
                    exit;
                }
                try {
                    $exists = $db->checkEmailExists($email);
                    echo json_encode([
                        'valid' => !$exists,
                        'message' => $exists ? 'Email already registered' : 'Email available'
                    ]);
                } catch (Exception $e) {
                    echo json_encode(['valid' => false, 'message' => 'Validation error']);
                }
                exit;

            case 'register':
                try {
                    // Get regular POST data
                    $postData = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    
                    // Server-side validation
                    $errors = [];
                    
                    // Required fields
                    $required = ['full_name', 'user_name', 'phone', 'address', 'password', 'email', 'confirm_password'];
                    foreach ($required as $field) {
                        if (empty($postData[$field])) {
                            $errors[$field] = 'This field is required';
                        }
                    }
            
                    if (!filter_var($postData['email'], FILTER_VALIDATE_EMAIL)) {
                        $errors['email'] = 'Invalid email format';
                    }
            
                    if ($postData['password'] !== $postData['confirm_password']) {
                        $errors['confirm_password'] = 'Passwords do not match';
                    }
            
                    if ($db->checkUsernameExists($postData['user_name'])) {
                        $errors['user_name'] = 'Username already taken';
                    }

                    if ($db->checkEmailExists($postData['email'])) {
                        $errors['email'] = 'Email already registered';
                    }

                    if (!isset($_FILES['user_image']['name']) || $_FILES['user_image']['name'] === '') {
                        $errors['user_image'] = 'Profile image required';
                    }
            
                    if (!empty($errors)) {
                        throw new Exception(json_encode(['errors' => $errors]));
                    }
            
                    // Process file upload
                    $filename = $uploader->upload($_FILES['user_image']);
            
                    // Prepare user data
                    $userData = [
                        'full_name' => $postData['full_name'],
                        'user_name' => $postData['user_name'],
                        'phone' => $postData['phone'],
                        'whatsapp' => $postData['whatsapp'] ?? '',
                        'address' => $postData['address'],
                        'password' => password_hash($postData['password'], PASSWORD_DEFAULT),
                        'user_image' => $filename,
                        'email' => filter_var($postData['email'], FILTER_SANITIZE_EMAIL)
                    ];
            
                    if ($db->insertUser($userData)) {
                        $response['success'] = true;
                        $response['redirect'] = 'success.php';
                    } else {
                        throw new Exception('Registration failed');
                    }
                } catch (Exception $e) {
                    $errorData = json_decode($e->getMessage(), true);
                    $response = $errorData ?: ['success' => false, 'message' => $e->getMessage()];
                    echo json_encode($response);
                    exit;
                }
                break;

            default:
                throw new Exception('Invalid action');
        }
    } catch (Exception $e) {
        http_response_code(400);
        $response = ['success' => false, 'message' => $e->getMessage()];
    }

    echo json_encode($response);
    exit;
}
?>