function loadMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: -34.67058107014105, lng: -58.56279447647644},
        zoom: 12
    });

    map.addListener('click', function(event) {
        placeMarker(event.latLng);
    });

    var marker;

    function placeMarker(location) {
        if (marker) {
            marker.setPosition(location);
        } else {
            marker = new google.maps.Marker({
                position: location,
                map: map
            });
        }

        console.log("Latitude: " + location.lat() + ", Longitude: " + location.lng());
        document.getElementById('lat').value = location.lat();
        document.getElementById('lng').value = location.lng();
    }
    google.maps.event.addDomListener(window, 'load', loadMap);
}

function initMap() {
    let latitud = parseFloat(document.getElementById('lat').value);
    let longitud= parseFloat(document.getElementById('lng').value);
    console.log(typeof latitud);
    console.log(typeof longitud);
    let marcador = {lat: latitud, lng: longitud};
    let map = new google.maps.Map(document.getElementById('map'), {
        center: marcador,
        zoom: 12
    });

    let marker = new google.maps.Marker({
        position: marcador,
        map: map
    });
    google.maps.event.addDomListener(window, 'load', initMap);
}