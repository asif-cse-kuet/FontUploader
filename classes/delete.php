<?php
header('Content-Type: application/json');

if (isset($_POST['font'])) {
    $fontFile = basename($_POST['font']); // Sanitize font name to prevent directory traversal attacks
    $fontPath = __DIR__ . '/../fonts/' . $fontFile;

    if (file_exists($fontPath)) {
        if (unlink($fontPath)) {
            echo json_encode(['status' => 'success', 'message' => 'Font deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete font']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Font not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No font specified']);
}
