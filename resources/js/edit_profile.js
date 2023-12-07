const saveButton = $('#profile-picture-save');
const removeButton = $('#profile-picture-remove');
const previewImageElement = $('#preview-image');
const profilePictureInput = $('#dropzone-file');
const defaultProfilePicture = $('#default-profile-picture');


// hide or show drag and drop area
$('#change-profile-picture').click(function(){
    toggle($('#profile-picture-upload'));
});

profilePictureInput.change(function(){
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

    // Hide default profile picture and show preview image
    defaultProfilePicture.addClass('hidden');
    previewImageElement.removeClass('hidden');
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

    // If user is trying to remove their profile picture
    if(profilePictureInput.val() === '') {

        // Remove the preview image since they are deleting their profile picture
        previewImageElement.attr('src', null);

        // Nothing to save or remove, so hide these buttons
        saveButton.addClass('hidden');
        removeButton.addClass('hidden');

        // Show default profile picture and hide profile picture preview
        previewImageElement.addClass('hidden');
        defaultProfilePicture.removeClass('hidden');

        // Delete the profile picture
        $('#remove-profile-picture-form').submit();

        return;
    }

    // If user is trying to remove image they uploaded

    // If they never had a profile picture, don't attempt to show it again
    if(originalImage === undefined) {
        previewImageElement.addClass('hidden');
        defaultProfilePicture.removeClass('hidden');

        // Hide remove button since there is nothing to remove
        removeButton.addClass('hidden');

        // remove file from input
        profilePictureInput.val(null);
    } else {

        // Reset preview image to user's original profile picture
        previewImageElement.attr('src', originalImage);
        profilePictureInput.val(null);
    }

    // Hide save button since there is nothing to save
    saveButton.addClass('hidden');
});

saveButton.click(function(){
    $('#save-profile-picture-form').submit();
});

$('.default-profile-picture-button').click(function(event){

    // get clicked submit button
    const submitButton = $(this);

    // get data-picture-id attribute from clicked button
    const pictureId = submitButton.data('picture-id')

    const form = $('#save-profile-picture-form');

    // change form action to /profile-picture/default/{id}
    form.attr('action', '/profile-picture/default/' + pictureId);

    // submit form
    form.submit();
});
