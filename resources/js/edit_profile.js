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
    let output = $('#preview-image')[0];

    output.src = URL.createObjectURL(image);
    output.onload = function() {
        URL.revokeObjectURL(output.src)
    }
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
