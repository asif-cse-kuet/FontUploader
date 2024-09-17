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

    loadFontGroups();
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

function loadFontGroups() {
    fetch('classes/get-font-groups.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error(data.error);
            } else {
                populateTable(data);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Function to populate the table with font group data
function populateTable(fontGroups) {
    const tableBody = document.getElementById('fontGroupTableBody');
    tableBody.innerHTML = ''; // Clear existing content
    fontGroups.forEach(group => {
        const fontNames = group.fonts.map(font => font.font_name).join('<br>'); // Font names separated by <br>
        const fontCount = group.fonts.length; // Count of fonts
        // Create table row
        const row = `
            <tr class="bg-white border-b border-gray-200">
                <td class="px-4 py-2">${group.group_name}</td>
                <td class="px-4 py-2">${fontNames}</td>
                <td class="px-4 py-2">${fontCount}</td>
                <td class="px-4 py-2">
                    <button class="text-blue-500 hover:text-blue-700" onclick="editGroup('${group.group_name}')">Edit</button>
                    <button class="text-red-500 hover:text-red-700 ml-4" onclick="deleteGroup('${group.group_name}')">Delete</button>
                </td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', row); // Append row to the table body
    });
}

function editGroup(groupName) {
    alert(`Editing group: ${groupName}`);
    // Logic to handle editing the group
}

function deleteGroup(groupName) {
    if (confirm(`Are you sure you want to delete the group "${groupName}"?`)) {
        alert(`Deleting group: ${groupName}`);
        // Logic to handle deleting the group
    }
}
