var cropper = false;

$(document).ready(function() {
    $('#form-edit-profile').on('submit', function(e) {
        e.preventDefault();
        $.post("/index.php", $(this).serializeArray(), function(response) {
            if (response !== "success") {
                notify("An error occurred when trying to create your account.", 'danger');
                return;
            }
            notify("Your account information has been saved.", 'success');
        });
    });
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#blah').attr('src', e.target.result)
            if (cropper) cropper.destroy();
        };
        reader.readAsDataURL(input.files[0]);
        setTimeout(initCropper, 250);
    }
}
function initCropper() {
    var image = document.getElementById('blah');
    cropper = new Cropper(image, {
        viewMode: 3,
        aspectRatio: 1 / 1,
        zoomable: false,
        center: false,
        guides: false
    });

    // On crop button clicked
    document.getElementById('crop_button').addEventListener('click', function(){

        // Send image to the server
        cropper.getCroppedCanvas().toBlob(function (blob) {
            var formData = new FormData();
            formData.append('action', "account");
            formData.append('subaction', "save_photo");
            formData.append('cropped_image', blob);
            formData.append('no_template', 1);

            $.ajax("/index.php", {
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response !== "success") {
                        notify("Failed to update your profile picture.", 'danger');
                        return;
                    }
                    location.reload();
                }
            });
        });
    });
}