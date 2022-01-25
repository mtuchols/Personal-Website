var map;
var geocoder;

$(document).ready(function() {

    // Event creation
    $('form#event-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var location = form.find('input#location-input').val();
        axios.get('https://maps.googleapis.com/maps/api/geocode/json', {
            params:{
                address: location,
                key: "AIzaSyAIVM2-1v7g7aiCwf3DZCNGVjet0x8SIL4"
            }
        }).then(function(response) {
            form.find('input[name="lat"]').val(response.data.results[0].geometry.location.lat);
            form.find('input[name="lng"]').val(response.data.results[0].geometry.location.lng);
            map.setCenter(response.data.results[0].geometry.location);
            $.post("/index.php", form.serializeArray(), function(response) {
                if (response !== "success") {
                    notify("An error occurred when trying to create your event.", 'danger');
                    return;
                }
                notify("Your event information has been saved.", 'success');
            });
        });
    });

    // Post creation
    $('form#form-create-post').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        $.post("/index.php", form.serializeArray(), function(response) {
            if (response !== "success") {
                notify("An error occurred when trying to create your post.", 'danger');
                return;
            }
            notify("Posted!", 'success');
        });
    });

    // Location search
    $('div#location-search').find('button').on('click', function(e) {
        geocoder.geocode({
            address: $('div#location-search').find('input').val()
        }).then((result) => {
            const { results } = result;
            map.setCenter(results[0].geometry.location);
        }).catch((e) => {
            alert("Geocode was not successful for the following reason: " + e);
        });
    });

});

function initMap() {
    // Initialize geocoder
    geocoder = new google.maps.Geocoder();

    // Custom markers
    // Credit to user geocodezip @ https://stackoverflow.com/questions/46416883/how-add-circle-shape-in-google-maps-custom-icon
    function CustomMarker(latlng, map, post_id, handle) {
        this.latlng_ = latlng;
        this.postID = post_id;
        this.imageSrc = "/?action=account&subaction=get_photo&handle="+handle+"&no_template=1";
        // Once the LatLng and text are set, add the overlay to the map.  This will
        // trigger a call to panes_changed which should in turn call draw.
        this.setMap(map);
    }
    CustomMarker.prototype = new google.maps.OverlayView();
    CustomMarker.prototype.draw = function () {
        // Check if the div has been created.
        var div = this.div_;
        if (!div) {
            // Create a overlay text DIV
            div = this.div_ = document.createElement('div');
            // Create the DIV representing our CustomMarker
            div.className = "customMarker"
    
            var img = document.createElement("img");
            img.src = this.imageSrc;
            div.appendChild(img);
            var me = this;
            google.maps.event.addDomListener(div, "click", function (event) {
                let postDetailsModal = $('#modal-post-details');
                postDetailsModal.find('div.modal-body').html('<h3 class="text-center"><i class="fas fa-circle-notch fa-spin"></i></h3>');
                postDetailsModal.modal('show');
                $.get("/index.php", {
                    action: "posts",
                    subaction: "details",
                    post_id: me.postID,
                    no_template: 1
                }, function(response) {
                    postDetailsModal.find('div.modal-body').html(response);
                });
            });
    
            // Then add the overlay to the DOM
            var panes = this.getPanes();
            panes.overlayImage.appendChild(div);
        }
    
        // Position the overlay 
        var point = this.getProjection().fromLatLngToDivPixel(this.latlng_);
        if (point) {
            div.style.left = point.x + 'px';
            div.style.top = point.y + 'px';
        }
    };
    CustomMarker.prototype.remove = function () {
        // Check if the overlay was on the map and needs to be removed.
        if (this.div_) {
            this.div_.parentNode.removeChild(this.div_);
            this.div_ = null;
        }
    };
    CustomMarker.prototype.getPosition = function () {
        return this.latlng_;
    };

    if (navigator.geolocation) {
        // We have the user's location
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };
                map = new google.maps.Map(document.getElementById("map"), {
                    center: pos,
                    restriction: {
                        latLngBounds: {
                            north: 84.99,
                            south: -84.99,
                            west: -179.99,
                            east: 179.99
                        },
                        strictBounds: false
                    },
                    zoom: 17,
                    minZoom: 3,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    mapTypeControl: false,
                    fullscreenControl: false,
                    zoomControlOptions: {
                        position: google.maps.ControlPosition.RIGHT_TOP
                    },
                    streetViewControl: false
                });

                // Add position to post form
                var postForm = $('form#form-create-post');
                postForm.find('input[name="lat"]').val(pos.lat);
                postForm.find('input[name="lng"]').val(pos.lng);

                // Get all posts
                $.get("/index.php", {
                    action: "posts",
                    subaction: "get_data",
                    no_template: 1
                }, function(data) {
                    $.each(data, function(i, post) {
                        new CustomMarker(new google.maps.LatLng(post.lat, post.lng), map, post.id, post.created_by);
                    });
                });
				
                var icon = {
                    url: "/?action=account&subaction=get_photo&handle="+userHandle+"&no_template=1", 
                    scaledSize: new google.maps.Size(50, 50), // scaled size
                    origin: new google.maps.Point(0,0), // origin
                    anchor: new google.maps.Point(0, 0),
                };
                const shape = {
                    coords: [25, 25, 25],
                    type: 'circle',
                };
                var marker = new google.maps.Marker({
                    position: pos,
                    map: map,
                    animation: google.maps.Animation.DROP,
                    icon : icon		
                });

                const infowindow = new google.maps.InfoWindow();
                const centerControlDiv = document.createElement("div");
                CenterControl(centerControlDiv, map);
                map.controls[google.maps.ControlPosition.TOP_CENTER].push(centerControlDiv);

                const centerPostControlDiv = document.createElement("postdiv");
                CenterPostControl(centerPostControlDiv, map);
                map.controls[google.maps.ControlPosition.TOP_LEFT].push(centerPostControlDiv);
            
                makeRequest('/index.php?action=events&subaction=get_events&no_template=1', function(data) {
                    var data = JSON.parse(data.responseText);
                    for (var i = 0; i < data.length; i++) {
                        displayLocation(data[i]);
                    }
                });
            }
        );
    }
}

function makeRequest(url, callback) {
    var request;
    if (window.XMLHttpRequest) {
        request = new XMLHttpRequest(); // IE7+, Firefox, Chrome, Opera, Safari
    } else {
        request = new ActiveXObject("Microsoft.XMLHTTP"); // IE6, IE5
    }
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            callback(request);
        }
    }
    request.open("GET", url, true);
    request.send();
}

function displayLocation(location) {
    var data =   '<div class="infoWindow"><strong>'  + location.title + '</strong>'
        + '<br/>'     + location.about
        + '<br/>'     + location.date_of_event + '</div>'+ location.userhandle + '</div>';
    var position = new google.maps.LatLng(parseFloat(location.latitude), parseFloat(location.longitude));
	
	var icon = {
	url: "/?action=account&subaction=get_photo&handle="+location.userhandle+"&no_template=1", 
	scaledSize: new google.maps.Size(50, 50), // scaled size
	origin: new google.maps.Point(0,0), // origin
	anchor: new google.maps.Point(0, 0) // anchor
	};
    var marker = new google.maps.Marker({
        map: map,
        position: position,
        title: location.about,
		icon : icon
    });
	
     var infoWindow = new google.maps.InfoWindow();
	(function (marker, data) {
		google.maps.event.addListener(marker, "click", function (e) {
			infoWindow.setContent("<div style = 'width:200px;min-height:40px'>" + data + "</div>");
			infoWindow.open(map, marker);
		});
	})(marker, data);
}
 

function CenterControl(controlDiv, map) {
    const controlUI = document.createElement("div");
    controlUI.style.backgroundColor = "#fff";
    controlUI.style.border = "2px solid #fff";
    controlUI.style.borderRadius = "3px";
    controlUI.style.boxShadow = "0 2px 6px rgba(0,0,0,.3)";
    controlUI.style.cursor = "pointer";
    controlUI.style.marginTop = "8px";
    controlUI.style.marginBottom = "22px";
    controlUI.style.textAlign = "center";
    controlUI.title = "Add Event";
    controlDiv.appendChild(controlUI);
    const controlText = document.createElement("div");
    controlText.style.color = "rgb(25,25,25)";
    controlText.style.fontFamily = "Roboto,Arial,sans-serif";
    controlText.style.fontSize = "16px";
    controlText.style.lineHeight = "38px";
    controlText.style.paddingLeft = "5px";
    controlText.style.paddingRight = "5px";
    controlText.innerHTML = "Add Event";
    controlUI.appendChild(controlText);
    controlUI.addEventListener("click", () => {
        var addEventContainer = new bootstrap.Offcanvas(document.getElementById("addEventContainer")).show();
    });
}

function CenterPostControl(controlDiv, map) {
    const controlUI = document.createElement("div");
    controlUI.style.backgroundColor = "#fff";
    controlUI.style.border = "2px solid #fff";
    controlUI.style.borderRadius = "3px";
    controlUI.style.boxShadow = "0 2px 6px rgba(0,0,0,.3)";
    controlUI.style.cursor = "pointer";
    controlUI.style.marginTop = "8px";
    controlUI.style.marginLeft = "8px";
    controlUI.style.textAlign = "center";
    controlUI.title = "Add Post";
    controlDiv.appendChild(controlUI);
    const controlText = document.createElement("div");
    controlText.style.color = "rgb(25,25,25)";
    controlText.style.fontSize = "18px";
    controlText.style.lineHeight = "38px";
    controlText.style.paddingLeft = "10px";
    controlText.style.paddingRight = "10px";
    controlText.innerHTML = '<i class="fas fa-pen"></i>';
    controlUI.appendChild(controlText);
    controlUI.addEventListener("click", () => {
        var createPostModal = new bootstrap.Modal(document.getElementById("modal-create-post"));
        createPostModal.show();
    });
}