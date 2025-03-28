<?php
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
?>
