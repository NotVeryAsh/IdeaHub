const saveButton = $('#profile-picture-save');
const removeButton = $('#profile-picture-remove');
const previewImageElement = $('#preview-image');
const profilePictureInput = $('#profile_picture');


// hide or show drag and drop area
$('#change-profile-picture').click(function(){
    toggle($('#profile-picture-upload'));
});

$('#dropzone-file').change(function(){
    let file = event.target.files[0];

    previewImage(file);
});

// When an image is uploaded, show the preview
function previewImage(image) {
    let output = previewImageElement[0];

    output.src = URL.createObjectURL(image);
    output.onload = function() {
        URL.revokeObjectURL(output.src)
    }

    saveButton.removeClass('hidden');
    removeButton.removeClass('hidden');
}

$('#profile-picture-upload').on("drop", function(ev) {

    // Prevent default behavior (Prevent file from being opened)
    ev.preventDefault();

    if (ev.originalEvent.dataTransfer.items) {
        // Use DataTransferItemList interface to access the file(s)
        [...ev.originalEvent.dataTransfer.items].forEach((item, i) => {
            // If dropped items aren't files, reject them
            if (item.kind === "file") {
                const file = item.getAsFile();
                previewImage(file);
            }
        });
    } else {
        // Use DataTransfer interface to access the file(s)
        [...ev.originalEvent.dataTransfer.files].forEach((file, i) => {
            previewImage(file);
        });
    }
}).on('dragover', function(ev) {
    // Prevent default behavior (Prevent file from being opened)
    ev.preventDefault();
});

removeButton.click(function(){

    let originalImage = previewImageElement.data('original-image');
console.log(originalImage);
    // If user is trying to remove their profile picture
    if(profilePictureInput.val() === '') {

        // Remove the preview image since they are deleting their profile picture
        previewImageElement.attr('src', null);

        // Nothing to save or remove, so hide these buttons
        saveButton.addClass('hidden');
        removeButton.addClass('hidden');

        // Delete the profile picture
        $('#remove-profile-picture-form').submit();

        return;
    }

    // Reset preview image to user's original profile picture
    previewImageElement.attr('src', originalImage);

    // Hide save button since there is nothing to save
    saveButton.addClass('hidden');
});

saveButton.click(function(){
    $('#remove-profile-picture-form').submit();
});
