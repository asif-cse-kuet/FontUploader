$(document).ready(function(){
    $('#uploadForm').submit(function(e){
        e.preventDefault();
        var fileInput = $('#fontFile')[0];
        var file = fileInput.files[0];
        
        if(file && file.name.split('.').pop().toLowerCase() !== 'ttf'){
            $('#error').text('File type should be .ttf type');
            return;
        }

        var formData = new FormData(this);
        
        $.ajax({
            url: 'classes/upload.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response){
                try {
                    var res = response;
                    if(res.status === 'success'){
                        $('#error').text(res.message);
                        location.reload();
                        // Reset form and file name display
                        $('#uploadForm')[0].reset();
                    } else {
                        $('#error').text(res.message);
                    }
                } catch (e) {
                    $('#error').text('Error parsing server response: ' + e.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#error').text('File Upload Error: ' + textStatus + ' - ' + errorThrown);
            }
        });
    });

    // Handle Delete Button Click
    $(document).on('click', '.delete-btn', function() {
        var fontName = $(this).data('font');
        $.ajax({
            url: 'classes/delete.php', // Endpoint to handle font deletion
            type: 'POST',
            data: { font: fontName },
            success: function(response) {
                try {
                    var res = response;
                    if(res.status === 'success') {
                        $('#font_delete_message').text(res.message);
                        location.reload(); // Reload after successful deletion
                    } else {
                        $('#font_delete_message').text(res.message);
                    }
                } catch (e) {
                    $('#font_delete_message').text('Error parsing server response: ' + e.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#font_delete_message').text('Delete Error: ' + textStatus + ' - ' + errorThrown);
            }
        });
    });
});
