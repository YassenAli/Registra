<?php
class DBOperations {
    private $host = 'localhost:8080';
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

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    try {
        $db = new DBOperations();
        
        switch ($_POST['action']) {
            case 'check_username':
                if (empty($_POST['username'])) {
                    echo json_encode(['valid' => false, 'message' => 'Username required']);
                    exit;
                }
                $exists = $db->checkUsernameExists($_POST['username']);
                echo json_encode(['valid' => !$exists, 'message' => $exists ? 'Username taken' : 'Available']);
                break;

            case 'check_email':
                if (empty($_POST['email'])) {
                    echo json_encode(['valid' => false, 'message' => 'Email required']);
                    exit;
                }
                $exists = $db->checkEmailExists($_POST['email']);
                echo json_encode(['valid' => !$exists, 'message' => $exists ? 'Email exists' : 'Valid']);
                break;
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'Database error']);
    }
    exit;
}

?>