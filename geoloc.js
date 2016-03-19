var lat;
var lon;

function getPos() {
    if (navigator.geolocation) {
        var geoLoc = navigator.geolocation;

        geoLoc.getCurrentPosition(function(position) {
            lat = position.coords.latitude;
            lon = position.coords.longitude;
        }, function() {
            getPosIP();
        });
    } else {
        console.log("Enable location on device");
    }
}