$(document).ready(function(){
    var rowIndex = 1;
    // Handle the addition of new font rows
    $('#addRow').click(function() {
        rowIndex++;
        var fontOptions = '<?php echo $fontOptions; ?>';
        $('#fontRows').append(
            '<div class="flex space-x-4 mb-2 font-row">' +
                '<div class="flex-1">' +
                    '<label for="fontName_' + rowIndex + '" class="block text-sm font-medium text-gray-700">Font Name</label>' +
                    '<input type="text" id="fontName_' + rowIndex + '" name="fontName[]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>' +
                '</div>' +
                '<div class="flex-1">' +
                    '<label for="fontSelect_' + rowIndex + '" class="block text-sm font-medium text-gray-700">Select Font</label>' +
                    '<select id="fontSelect_' + rowIndex + '" name="fontSelect[]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>' +
                        '<option value="">Select a Font</option>' +
                        '<option id="insertFont" value=""></option>' +
                        +loadFonts(rowIndex)+
                    '</select>' +
                '</div>' +
            '</div>'
        );
    });
    

    // Handle form submission
    $('#fontGroupForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize(); // Serialize form data
        $.ajax({
            url: 'classes/font_group.php',
            type: 'POST',
            data: formData,
            success: function(response){
                try {
                    var res = (response);
                    if(res.status === 'success'){
                        alert('Font Group Created Successfully');
                        location.reload(); // Reload the form
                    } else {
                        // $('#error').text(res.message);
                        alert('Group Name Already Exists');
                    }
                } catch (e) {
                    $('#error').text('Error parsing server response: ' + e.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#error').text('Form Submission Error: ' + textStatus + ' - ' + errorThrown);
            }
        });
    });
});


function loadFonts(rowIndex) {
    // Make an AJAX call to the PHP script
    fetch('classes/get-fonts.php', {
        method: 'POST', // Use POST method
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded', // Form data type
        }
    })
    .then(response => response.text()) // Handle response as text
    .then(data => {
        console.log(data);
        console.log("Row Index is :"+ rowIndex);
        $('#fontSelect_'+rowIndex).empty();
        $('#fontSelect_'+rowIndex).append('<option value="">Select a Font</option>');
        $('#fontSelect_'+rowIndex).append(data);
    })
    .catch(error => console.error('Error:', error));
};