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
        getPosIP();
    }
}

function getPosIP() {
    $.ajax({
        method: "GET",
        url: "http://ip-api.com/json",
        dataType: "json"
    }).done(function(e){
        lat = e.lat;
        lon = e.lon;
    });
}