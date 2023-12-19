/**Show toast */
function showToast(message) {
    $.toast({
        heading: "success",
        text: message,
        position: "top-right",
        loaderBg: "#4CAF50",
        icon: "success",
        hideAfter: 3500,
    });
}
function closeToast() {
    $("#successToast").toast("hide");
}

/**ckfinder */

function openCKFinder() {
    selectFileWithCKFinder("image");
}

function selectFileWithCKFinder(elementId) {
    CKFinder.popup({
        chooseFiles: true,
        width: 800,
        height: 600,
        onInit: function(finder) {
            finder.on('files:choose', function(evt) {
                var file = evt.data.files.first();
                var output = document.getElementById(elementId);
                output.value = file.getUrl();
                previewImage(file.getUrl());
            });
        }
    });
}

/**Preview Image */
function previewImage(url) {
    var output = document.getElementById('preview-image');
    output.src = url;
}
