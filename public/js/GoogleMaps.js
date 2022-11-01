
"use strict";

function initMap()
{
    const CONFIGURATION = {
        "ctaTitle": "Rechercher",
        "mapOptions": {"center":{"lat":44.8378,"lng":-0.594},"fullscreenControl":true,"mapTypeControl":false,"streetViewControl":true,"zoom":13,"zoomControl":true,"maxZoom":22},
        "mapsApiKey": "AIzaSyByMYFDVGydFuJZELlp1HDv2m5zWcDvFjA",
        "capabilities": {"addressAutocompleteControl":true,"mapDisplayControl":true,"ctaControl":true}
    };

    const getFormInputElement = (component) => document.getElementById(component);
    const map = new google.maps.Map(document.getElementById("gmp-map"), {
        zoom: CONFIGURATION.mapOptions.zoom,
        center: { lat: 44.8378, lng: -0.594 },
        mapTypeControl: false,
        fullscreenControl: CONFIGURATION.mapOptions.fullscreenControl,
        zoomControl: CONFIGURATION.mapOptions.zoomControl,
        streetViewControl: CONFIGURATION.mapOptions.streetViewControl
    });
    const marker = new google.maps.Marker({map: map, draggable: false});
    const autocompleteInput = getFormInputElement('restaurant_add_address');
    const autocomplete = new google.maps.places.Autocomplete(autocompleteInput, {
        bounds: new google.maps.LatLngBounds({lat: 42, lng: -4}, {lat: 51, lng: 8}),
        fields: ["address_components", "geometry", "name"],
        types: ["address"],
    });
    autocomplete.addListener('place_changed', function () {
        marker.setVisible(false);
        const place = autocomplete.getPlace();
        if (!place.geometry) {
            // User entered the name of a Place that was not suggested and
            // pressed the Enter key, or the Place Details request failed.
            window.alert('No details available for input: \'' + place.name + '\'');
            return;
        }
        renderAddress(place);
        fillInAddress(place);
    });

    function fillInAddress(place)
    {
        console.log(place);

        const addressNameFormat = {
            'street_number': 'short_name',
            'route': 'long_name',
            'locality': 'long_name',
            'administrative_area_level_1': 'short_name',
            'country': 'long_name',
            'postal_code': 'short_name',
        };

        const getAddressComp = function (type) {
            for (const component of place.address_components) {
                if (component.types[0] === type) {
                    return component[addressNameFormat[type]];
                }
            }
            return '';
        };
        // getFormInputElement('restaurant_add_address').value = getAddressComp('street_number') + getAddressComp('route');
        getFormInputElement('restaurant_add_zipCode').value = getAddressComp('postal_code');
        getFormInputElement('restaurant_add_city').value = getAddressComp('locality');
        getFormInputElement('restaurant_add_latitude').value = place.geometry.location.lat();
        getFormInputElement('restaurant_add_longitude').value = place.geometry.location.lng();
    }

    function renderAddress(place)
    {
        map.setCenter(place.geometry.location);
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);
    }
}
