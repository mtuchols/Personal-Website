$(document).ready(function() {
    $('button.top-nav-menu-toggle').on('click', function(e) {
        var toggleBtn = $(this);
        var target = toggleBtn.data('target');
        var menu = $(target);

        var isOpen = toggleBtn.hasClass('active');

        $('button.top-nav-menu-toggle').removeClass('active');
        $('div.top-nav-menu').hide();

        if (!isOpen) {
            toggleBtn.addClass('active');
            menu.show();
        }
    });
});
$(document).mouseup(function(e) {
    var target = $(e.target);
    if (
        !target.hasClass('top-nav-menu-toggle') && target.closest('.top-nav-menu-toggle').length === 0
        && !target.hasClass('top-nav-menu') && target.closest('.top-nav-menu').length === 0
    ) {
        $('button.top-nav-menu-toggle').removeClass('active');
        $('div.top-nav-menu').hide();
    }
});

function toggleTopMenu(menu) {
    menu = $('#'+menu);
    console.log(menu);
}

function notify(message, background = "primary") {
    var toastContainer = $('div.toast-container');
    var toast = $(
        '<div class="toast show align-items-center text-white bg-'+background+' border-0" role="alert" aria-live="assertive" aria-atomic="true" style="display:none;">'+
            '<div class="d-flex">'+
                '<div class="toast-body">'+message+'</div>'+
                '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>'+
            '</div>'+
        '</div>'
    );
    toastContainer.append(toast);

    toast.fadeIn(300);
    setTimeout(function() {
        toast.fadeOut(300, function() {
            $(this).remove();
        });
    }, 5000);
}

function likePost(postID) {
    var likeCount = 0;
    var liked = false;
    $('div.post[data-post-id="'+postID+'"] button.post-like').each(function(index) {
        if (index === 0) {
            likeCount = parseInt($(this).find('span').text());
            liked = $(this).hasClass('liked');
        }
        $(this).find('span').text((liked ? --likeCount : ++likeCount).toLocaleString());
        $(this).toggleClass('liked');
    });
    $.post("/index.php", {
        action: "posts",
        subaction: "like",
        post_id: postID,
        like_action: (liked ? "remove" : "add"),
        no_template: 1
    }, function(response) {
        // console.log(response);
    });
}