<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set up a custom log file
$logFile = __DIR__ . '/error_log.log';
ini_set('log_errors', 1);
ini_set('error_log', $logFile);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Log the $_FILES array for debugging
    error_log(print_r($_FILES, true));

    if (isset($_FILES['croppedImage']) && $_FILES['croppedImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/uploads/';

        // Ensure the uploads directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = uniqid() . '.png';
        $filePath = $uploadDir . $fileName;

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($_FILES['croppedImage']['tmp_name'], $filePath)) {
            echo json_encode(['status' => 'success', 'file' => 'uploads/' . $fileName]);
        } else {
            error_log("Failed to move uploaded file to $filePath");
            echo json_encode(['status' => 'error', 'message' => 'Failed to save the image.']);
        }
    } else {
        $error = $_FILES['croppedImage']['error'] ?? 'Unknown error';
        error_log("File upload error: $error");
        echo json_encode(['status' => 'error', 'message' => "Upload error: $error"]);
    }
} else {
    error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
