$(document).ready(function() {

    $('#form-signup').on('submit', function(e) {
        e.preventDefault();
        $.post("/index.php", $(this).serializeArray(), function(response) {
            if (response !== "success") {
                notify("An error occurred when trying to create your account.", 'danger');
                return;
            }
            location.reload();
        });
    });

    $('#form-signin').on('submit', function(e) {
        e.preventDefault();
        $.post("/index.php", $(this).serializeArray(), function(response) {
            if (response !== "success") {
                notify("Incorrect email or password.", 'danger');
                return;
            }
            location.reload();
        });
    });

});
