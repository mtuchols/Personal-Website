<?php

$cssIncludes[] = "includes/css/map.css";
$cssIncludes[] = "includes/css/post.css";
$jsIncludes[] = "includes/js/map.js";
$jsIncludes[] = "https://unpkg.com/axios/dist/axios.min.js";
$jsIncludes[] = "https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js";
$jsAsyncIncludes[] = "https://maps.googleapis.com/maps/api/js?key=AIzaSyAIVM2-1v7g7aiCwf3DZCNGVjet0x8SIL4&callback=initMap";

?>

<div id="map"></div>

<div class="input-group" id="location-search">
    <input type="text" class="form-control" placeholder="Enter a location">
    <button type="button" class="btn btn-primary">Search</button>
</div>

<div class="offcanvas offcanvas-start" tabindex="-1" id="addEventContainer" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Create a new event</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form action="/index.php" method="post" id="event-form">
            <input type="hidden" name="action" value="events">
            <input type="hidden" name="subaction" value="add_event">
            <input type="hidden" name="lat" required>
            <input type="hidden" name="lng" required>
            <input type="hidden" name="no_template" value="1">
            <div class="overlay-form">
                <p>Add a public event for the whole world to see!</p>
                <div class="form-floating mb-3">
                    <input type="text" name="title" id="title" class="form-control" placeholder="Title" required>
                    <label for="title">Title</label>
                </div>
                <div class="form-floating mb-3">
                    <textarea class="form-control" name="about" id="about" style="height: 100px;"></textarea>
                    <label for="about">Description</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="date" name="date_of_event" id="date_of_event" class="form-control" placeholder="Date" required>
                    <label for="date_of_event">Date</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" id="location-input" class="form-control" required>
                    <label for="location-input">Location</label>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary" value="Encode">Create Event</button>
                </div>
            </div>
        </form>
        <div class="card-block" id="geometry"></div>
    </div>
</div>

<div class="modal fade" id="modal-create-post" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-create-post" method="post" action="/index.php">
                    <input type="hidden" name="action" value="posts">
                    <input type="hidden" name="subaction" value="create">
                    <input type="hidden" name="lat" value="" required>
                    <input type="hidden" name="lng" value="" required>
                    <input type="hidden" name="no_template" value="1">
                    <div class="mb-3 post-header">
                        <img class="user-img" src="/?action=account&subaction=get_photo&handle=<?= $GLOBALS['user']->getHandle() ?>&no_template=1" alt="<?= $GLOBALS['user']->getName() ?>">
                        <div class="user-data">
                            <p class="user-name"><?= $GLOBALS['user']->getName() ?></p>
                            <p class="user-handle">@<?= $GLOBALS['user']->getHandle() ?></p>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea type="text" name="content" id="content" class="form-control" placeholder="Content" style="height: 100px;" required></textarea>
                        <label for="content">What's on your mind, <?= $GLOBALS['user']->getName() ?>?</label>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-post-details" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Modal content
            </div>
        </div>
    </div>
</div>

<script>var userHandle = "<?= $GLOBALS['user']->getHandle() ?>";</script>