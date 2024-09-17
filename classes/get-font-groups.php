<?php
header('Content-Type: application/json');

// Path to the font group JSON file
$groupFilePath = __DIR__ . '/../font_groups.json';

if (file_exists($groupFilePath)) {
    $fontGroups = json_decode(file_get_contents($groupFilePath), true);

    // Send the font groups data back to the client
    echo json_encode($fontGroups);
} else {
    echo json_encode(['error' => 'File not found']);
}
