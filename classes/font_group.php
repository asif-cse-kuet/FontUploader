<?php
header('Content-Type: application/json');

if (isset($_POST['groupName']) && !empty($_POST['fontName']) && !empty($_POST['fontSelect'])) {
    $groupName = $_POST['groupName'];
    $fontNames = $_POST['fontName'];
    $fontFiles = $_POST['fontSelect'];

    // Path to the JSON file
    $groupFilePath = __DIR__ . '/../font_groups.json';

    // Read existing groups from the JSON file
    $existingGroups = [];
    if (file_exists($groupFilePath)) {
        $existingGroups = json_decode(file_get_contents($groupFilePath), true);
    }

    // Check if the group name already exists
    foreach ($existingGroups as $group) {
        if (isset($group['group_name']) && $group['group_name'] === $groupName) {
            // Group name already exists, return an error message
            echo json_encode(['status' => 'error', 'message' => 'Group name already exists']);
            exit;
        }
    }

    // If the group name doesn't exist, create a new group
    $groupData = [
        'group_name' => $groupName,
        'fonts' => []
    ];

    // Populate font data
    foreach ($fontNames as $index => $fontName) {
        $groupData['fonts'][] = [
            'font_name' => $fontName,
            'font_file' => basename($fontFiles[$index])
        ];
    }

    // Add the new group to the existing groups
    $existingGroups[] = $groupData;

    // Save back to the JSON file
    if (file_put_contents($groupFilePath, json_encode($existingGroups, JSON_PRETTY_PRINT))) {
        echo json_encode(['status' => 'success', 'message' => 'Font group created successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save font group']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}
