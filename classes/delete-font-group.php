<?php
header('Content-Type: application/json');

// Path to the font group JSON file
$groupFilePath = __DIR__ . '/../font_groups.json';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['groupName'])) {
    if (file_exists($groupFilePath)) {
        $fontGroups = json_decode(file_get_contents($groupFilePath), true);

        // Remove the group by its name
        $updatedGroups = array_filter($fontGroups, function ($group) use ($data) {
            return $group['group_name'] !== $data['groupName'];
        });

        // Reindex the array to ensure zero-based indexing
        $updatedGroups = array_values($updatedGroups);

        // Save the updated data back to the JSON file
        if (file_put_contents($groupFilePath, json_encode($updatedGroups, JSON_PRETTY_PRINT))) {
            echo json_encode(['status' => 'success', 'message' => 'Font group deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete the font group']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'File not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
}
