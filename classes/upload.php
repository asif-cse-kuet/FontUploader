<?php
header('Content-Type: application/json');

$response = "not set"; // Initialize response array

if (isset($_FILES['fontFile'])) {
    $file = $_FILES['fontFile'];
    $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if ($fileType == 'ttf') {
        $targetDir = __DIR__ . '/../fonts/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $filePath = $targetDir . basename($file['name']);

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            $response = ['status' => 'success', 'file' => '/fonts/' . basename($file['name']), 'name' => basename($file['name']), 'message' => 'File upload successful'];
        } else {
            $response = ['status' => 'error', 'message' => 'File upload failed'];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'File type should be .ttf type'];
    }
} else {
    $response = ['status' => 'error', 'message' => 'No file Selected'];
}

echo json_encode($response); // Output only one JSON response
