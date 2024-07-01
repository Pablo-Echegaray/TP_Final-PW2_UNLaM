function loadMap() {


    // Crear el mapa
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: -34.67058107014105, lng: -58.56279447647644},
        zoom: 12
    });

    // Evento para agregar marcador
    map.addListener('click', function(event) {
        placeMarker(event.latLng);
    });

    var marker;

    // Función para colocar el marcador
    function placeMarker(location) {
        if (marker) {
            marker.setPosition(location);
        } else {
            marker = new google.maps.Marker({
                position: location,
                map: map
            });
        }

        // Obtener las coordenadas
        //var lat = location.lat();
       // var lng = location.lng();
        console.log("Latitude: " + location.lat() + ", Longitude: " + location.lng());
        document.getElementById('lat').value = location.lat();
        document.getElementById('lng').value = location.lng();
        // Enviar las coordenadas al servidor
       // saveCoordinates(lat, lng);
    }
    google.maps.event.addDomListener(window, 'load', loadMap);
}

function initMap() {
    //-34.67058107014105, -58.56279447647644
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

// Inicializar el mapa
//google.maps.event.addDomListener(window, 'load', loadMap);
//google.maps.event.addDomListener(window, 'load', initMap);
