var autocomplete, geocoder;
var componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'short_name',
    country: 'long_name',
    postal_code: 'short_name'
    };
$(document).ready(function ()
{
    geocoder = new google.maps.Geocoder();

    $("#address").focusout(function ()
    {
        var address = document.getElementById('address').value;
        console.log('address',address);
        geocoder.geocode({ 'address': address }, function (results, status)
        {
            console.log('result',results)
            if(results.length == 0)
            {

            }
            else
            {
                console.log('map', results[0].geometry.location.lat())
                $(".searchRow input[name='lat']").val(results[0].geometry.location.lat())
                $(".searchRow input[name='lang']").val(results[0].geometry.location.lng())
                if (status == 'OK')
                {
                    map.setCenter(results[0].geometry.location);
                    var marker = new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location
                    });
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            }
        });
    });

    var latlng = new google.maps.LatLng(22.3039,70.8022);
    var mapOptions = {
    zoom: 8,
    center: latlng
    }
    if($('#address_map')){
        address_map = new google.maps.Map(document.getElementById('address_map'), mapOptions);

        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(22.3039, 70.8022),
            title: address_map,
            map: address_map,
            draggable: true
        });

        google.maps.event.addListener(marker, 'dragend', function(evt){
            $('#lat').val(evt.latLng.lat().toFixed(5));
            $('#lang').val(evt.latLng.lng().toFixed(5));
        });
    }
});
function loadAddressMap()
{
    var latlng = new google.maps.LatLng(22.3039,70.8022);
    var mapOptions =
    {
        zoom: 8,
        center: latlng
    }
    address_map = new google.maps.Map(document.getElementById('address_map'), mapOptions);

    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(22.3039, 70.8022),
        title: address_map,
        map: address_map,
        draggable: true
    });

    google.maps.event.addListener(marker, 'dragend', function(evt){
        $('#lat').val(evt.latLng.lat().toFixed(5));
        $('#lang').val(evt.latLng.lng().toFixed(5));
    });
}

function geolocate() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var geolocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            var circle = new google.maps.Circle(
                { center: geolocation, radius: position.coords.accuracy });
            autocomplete.setBounds(circle.getBounds());
        });
    }
}

function initAutocomplete()
{
    setTimeout(() => {
        autocomplete = new google.maps.places.Autocomplete(document.getElementById('address'));
        autocomplete.setFields(['address_component']);
        autocomplete.addListener('place_changed', fillInAddress);
    }, 1000);
}

function fillInAddress()
{
    var place = autocomplete.getPlace();
    console.log('place',place);
    for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
        }
    }
}
