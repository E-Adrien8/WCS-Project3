
$(document).ready(function() {
    $("#lat_area").addClass("d-none");
    $("#long_area").addClass("d-none");
});
google.maps.event.addDomListener(window, 'load', initialize);
function initialize() {
    var input = document.getElementById('autocomplete');
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        $('#latitude').val(place.geometry['location'].lat());
        $('#longitude').val(place.geometry['location'].lng());
// --------- show lat and long ---------------
        $("#lat_area").removeClass("d-none");
        $("#long_area").removeClass("d-none");
    });
}
