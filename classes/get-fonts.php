<?php
// Load fonts from the directory
$fontDir = '../fonts/';
if (is_dir($fontDir)) {
    $fonts = array_diff(scandir($fontDir), array('..', '.'));
    foreach ($fonts as $font) {
        echo '<option value="' . htmlspecialchars($font) . '">' . htmlspecialchars(pathinfo($font, PATHINFO_FILENAME)) . '</option>';
    }
} else {
    echo "Invalid font directory";
}
