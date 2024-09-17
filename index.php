<?php
$fontDir = 'fonts/';
$fontOptions = '';

if (is_dir($fontDir)) {
    $fonts = array_diff(scandir($fontDir), ['..', '.']);
    foreach ($fonts as $font) {
        $fontName = htmlspecialchars(pathinfo($font, PATHINFO_FILENAME));
        $fontValue = htmlspecialchars($font);
        $fontOptions .= '<option value="' . $fontValue . '">' . $fontName . '</option>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload TTF File</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-white flex items-center justify-center">
    <!-- Main body wrapper -->
    <div class="w-3/5 bg-white m-8">

        <!-- Upload Section -->
        <form id="uploadForm" class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-8 flex flex-col items-center justify-center text-center">
            <div class="mb-4">
                <input type="file" id="fontFile" name="fontFile" accept=".ttf" class="hidden">
                <label for="fontFile" class="cursor-pointer">
                    <div id="fileLabel" class="text-gray-500">
                        <p class="text-lg font-semibold">Click to upload or drag and drop</p>
                        <p class="text-sm text-gray-400">Only TTF file allowed</p>
                    </div>
                </label>
                <p id="selectedFile" class="mt-2 text-gray-600"></p> <!-- File name display -->
            </div>
            <!-- Add a submit button to trigger the form -->
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Upload</button>
        </form>
        <p id="error" class="text-red-500 mt-4"></p>
        <br><br>
        <!-- Font Show Section -->
        <div class="bg-white mt-6">
            <h4 class="p-2"><strong>Our Fonts</strong></h4>
            <p class="text-gray-400 pl-2 text-sm">Browse a list of Zepto fonts to build font group</p>
            <p id="font_delete_message" class="text-red-500 mt-4"></p>
            <table class="w-full mt-4 table-fixed" id="fontPreviewTable">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="text-left py-4 px-6 text-gray-500 text-md">Font Name</th>
                        <th class="text-left py-4 px-6 text-gray-500 text-md">Preview</th>
                        <th class="py-4 px-6"></th>
                    </tr>
                </thead>
                <tbody class="text-sm" id="fontPreviewBody">
                    <?php
                    $fontDir = 'fonts/';
                    if (is_dir($fontDir)) {
                        $files = array_diff(scandir($fontDir), array('..', '.'));
                        foreach ($files as $file) {
                            $fontInfo = pathinfo($file);
                            $fontName = $fontInfo['filename']; // Get font name without extension
                            $fontId = 'font_' . time() . '_' . $fontName;
                            echo '<tr id="' . $fontId . '">' .
                                '<td class="py-4 px-3">' . $fontName . '</td>' .
                                '<td class="py-4 px-3" style="font-family: ' . $fontId . '">Sample Text</td>' .
                                '<td class="py-4 px-3 text-red-500 cursor-pointer text-right">' .
                                '<button class="delete-btn" data-font="' . $file . '">Delete</button>' . // Add the font filename in the data attribute
                                '</td>' .
                                '</tr>';
                            echo '<tr>' . '<td colspan="3" class="border-b-2 border-gray-200">' . '</td>' . '</tr>';
                            // Include the @font-face CSS rule dynamically
                            echo '<style>@font-face {font-family: "' . $fontId . '"; src: url("' . $fontDir . $file . '");}</style>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <br><br>
        <!-- Font Group Create Modal -->
        <div class="mt-8 mb-6 p-2">
            <form id="fontGroupForm">
                <div class="mb-4">
                    <label for="groupName" class="block text-sm font-medium text-gray-700">Font Group Name</label>
                    <input type="text" id="groupName" name="groupName" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>

                <div id="fontRows">
                    <div class="flex space-x-4 mb-2 font-row">
                        <div class="flex-1">
                            <label for="fontName_1" class="block text-sm font-medium text-gray-700">Font Name</label>
                            <input type="text" id="fontName_1" name="fontName[]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>
                        <div class="flex-1">
                            <label for="fontSelect_" class="block text-sm font-medium text-gray-700">Select Font</label>
                            <select id="fontSelect_" name="fontSelect[]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="">Select a Font</option>
                                <?php
                                // Load fonts from the directory
                                $fontDir = 'fonts/';
                                if (is_dir($fontDir)) {
                                    $fonts = array_diff(scandir($fontDir), array('..', '.'));
                                    foreach ($fonts as $font) {
                                        echo '<option value="' . htmlspecialchars($font) . '">' . htmlspecialchars(pathinfo($font, PATHINFO_FILENAME)) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between">
                    <button type="button" id="addRow" class="px-4 py-2 bg-blue-500 text-white rounded-md">+Add Row</button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md">Create</button>
                </div>
                <div id="error" class="mt-4 text-red-500"></div>
            </form>
        </div>
        <br><br>
        <!-- Font Group Show Modal  -->
        <div class="bg-white mt-6">
            <h4 class="p-2"><strong>Our Font Groups</strong></h4>
            <p class="text-gray-400 pl-2 text-sm">List of available font groups</p>
            <p id="fontGroupUpdateText" class="text-red-500 mt-4"></p>
            <table class="w-full mt-4 table-fixed" id="fontPreviewTable">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="text-left py-4 px-6 text-gray-500 text-md">Name</th>
                        <th class="text-left py-4 px-6 text-gray-500 text-md">Fonts</th>
                        <th class="text-left py-4 px-6 text-gray-500 text-md">Count</th>
                        <th class="py-4 px-6"></th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php
                    $groupFilePath = 'font_groups.json';

                    if (file_exists($groupFilePath)) {
                        $fontGroups = json_decode(file_get_contents($groupFilePath), true);

                        foreach ($fontGroups as $group) {
                            // Count the number of fonts in the group
                            $fontCount = count($group['fonts']);

                            // Create a string to display all font names
                            $fontNames = '';
                            foreach ($group['fonts'] as $font) {
                                $fontNames .= htmlspecialchars($font['font_name']) . ',' . ' ';
                            }

                            // Display table row for each font group
                            echo '<tr class="p-4">';
                            echo '<td>' . htmlspecialchars($group['group_name']) . '</td>';
                            echo '<td>' . $fontNames . '</td>';
                            echo '<td>' . $fontCount . '</td>';
                            echo '<td>
                                    <button onclick="editGroup(\'' . htmlspecialchars($group['group_name']) . '\')">Edit</button>
                                    <button onclick="deleteGroup(\'' . htmlspecialchars($group['group_name']) . '\')">Delete</button>
                                </td>';
                            echo '</tr>';
                        }
                    }
                    ?>

                </tbody>
            </table>
        </div>
        <div class="mb-16"></div>
        <br><br>

    </div>

    <!-- jQuery and JS -->
    <script src="ajax/upload.js"></script>
    <script src="ajax/fontGroup.js"></script>
    <script>
        // Show selected file name on the button
        $('#fontFile').change(function() {
            var fileName = $(this).val().split('\\').pop();
            $('#selectedFile').text(fileName);
        });
    </script>
</body>

</html>