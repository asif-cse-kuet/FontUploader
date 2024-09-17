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

        <!-- Font Show Section -->
        <div class="bg-white mt-6">
            <h4 class="p-2"><strong>Uploaded Fonts</strong></h4>
            <p id="font_delete_message" class="text-red-500 mt-4"></p>
            <table class="w-full mt-4" id="fontPreviewTable">
                <!-- Table Header -->
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
                                '<td class="py-4 px-6">' . $fontName . '</td>' .
                                '<td class="py-4 px-6" style="font-family: ' . $fontId . '">Sample Text</td>' .
                                '<td class="py-4 px-6 text-red-500 cursor-pointer">' .
                                '<button class="delete-btn" data-font="' . $file . '">Delete</button>' . // Add the font filename in the data attribute
                                '</td>' .
                                '</tr>';
                            // Include the @font-face CSS rule dynamically
                            echo '<style>@font-face {font-family: "' . $fontId . '"; src: url("' . $fontDir . $file . '");}</style>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

    <!-- jQuery and JS -->
    <script src="ajax/upload.js"></script>
    <script>
        // Show selected file name on the button
        $('#fontFile').change(function() {
            var fileName = $(this).val().split('\\').pop();
            $('#selectedFile').text(fileName);
        });
    </script>
</body>

</html>