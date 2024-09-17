<?php
header('Content-Type: application/json');

$groupFilePath = __DIR__ . '/../font_groups.json';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['oldGroupName'], $data['newGroupName'], $data['newFontNames'])) {
    if (file_exists($groupFilePath)) {
        $fontGroups = json_decode(file_get_contents($groupFilePath), true);

        foreach ($fontGroups as &$group) {
            if ($group['group_name'] === $data['oldGroupName']) {
                $group['group_name'] = $data['newGroupName'];
                $group['fonts'] = array_map(function ($fontName) {
                    return ['font_name' => $fontName, 'font_file' => ''];
                }, $data['newFontNames']);
                break;
            }
        }

        if (file_put_contents($groupFilePath, json_encode($fontGroups, JSON_PRETTY_PRINT))) {
            echo json_encode(['status' => 'success', 'message' => 'Font group updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update the font group']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'File not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
}
