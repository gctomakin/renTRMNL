var geocoder;
var directionsDisplay;
var directionsService;
var map;
var latitude = '';
var longitude = '';
var address;
var latLng;

function initMap() {
  directionsDisplay = new google.maps.DirectionsRenderer();
  directionsService = new google.maps.DirectionsService();
  geocoder = new google.maps.Geocoder();
  latitude = '';
  longitude = '';
  var imageBounds = {
    north: 40.773941,
    south: 40.712216,
    east: -74.12544,
    west: -74.22655
  };
  var historicalOverlay = new google.maps.GroundOverlay(
    'https://www.lib.utexas.edu/maps/historical/newark_nj_1922.jpg',
    imageBounds);
  map = new google.maps.Map(document.getElementById('map'), {
    center: {
      lat: 14.2990183,
      lng: 120.9589699
    },
    zoom: 5,
    scrollwheel: false,
    navigationControl: false,
    mapTypeControl: false,
    scaleControl: false,

  });
  historicalOverlay.setMap(map);

  google.maps.event.addListener(map, 'click', function(event) {
    latLng = event.latLng;
  });
}

function getAddress() {
  var d = $.Deferred();
  address = '';
  geocoder.geocode( {'latLng': latLng}, function(results, status) {
    if(status == google.maps.GeocoderStatus.OK) {
      if(results[0]) {
        address = results[0].formatted_address;
        latitude = results[0].geometry.location.lat();
        longitude = results[0].geometry.location.lng();
      } else {
        address = "No results";
      }
    } else {
      address = status;
    }
    d.resolve();
  });
  return d;
}

function geocodeAddress(geocoder, map, address) {
  for (i = 0; i < address.length; i++) {
    var addr = address[i];
    geocoder.geocode({
      'address': address[i]
    }, addMarkers(addr));
  }

  function addMarkers(myTitle) {
    return function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        var marker = new google.maps.Marker({
          map: map,
          position: results[0].geometry.location,
          title: myTitle
        });
        var infoWindow = new google.maps.InfoWindow();
        google.maps.event.addListener(marker, "mouseover", function(e) {
          this.map.panTo(this.position);
          infoWindow.setContent(this.title);
          infoWindow.open(this.map, this);
        });
        google.maps.event.addListener(marker, "mouseout", function(e) {
          infoWindow.close();
        });
      } else {
        alert('Geocode was not successful for the following reason: ' + status);
      }
    };
  }
}

function goToAddress(address) {
  if (address == '') { return; }
  var d = $.Deferred();
  latitude = '';
  longitude = '';
  geocoder.geocode({
    'address': address
  }, function(results, status) {
    if (status === google.maps.GeocoderStatus.OK) {
      var geolocate;
      latitude = results[0].geometry.location.lat();
      longitude = results[0].geometry.location.lng();
      var pos = new google.maps.LatLng(latitude, longitude);
      var infowindow = new google.maps.InfoWindow();
      var marker = new google.maps.Marker({
        map: map,
        position: pos,
        title: address
      });
      map.panTo(pos);
      map.setZoom(16);
      google.maps.event.addListener(marker, 'mouseover', function() {
        infowindow.setContent(this.title);
        infowindow.open(this.map, this);
      });
      google.maps.event.addListener(marker, 'mouseout', function() {
        infowindow.close();
      });
      if (navigator && navigator.geolocation) {
        function getLocation(position) {
          geolocate = {
            "lat": position.coords.latitude,
            "lng": position.coords.longitude
          }
          var marker = new google.maps.Marker({
            map: map,
            position: geolocate
          });
          var infowindow = new google.maps.InfoWindow({
            map: map,
            position: geolocate,
            content: '<h1>Your Current location!</h1>' +
              '<h2>Latitude: ' + position.coords.latitude + '</h2>' +
              '<h2>Longitude: ' + position.coords.longitude + '</h2>'
          });

          directionsDisplay.setMap(map);
          directionsDisplay.setPanel(document.getElementById('right-panel'));

          directionsService.route({
            origin: geolocate,
            destination: pos,
            travelMode: google.maps.TravelMode.DRIVING
          }, function(response, status) {
            if (status === google.maps.DirectionsStatus.OK) {
              directionsDisplay.setDirections(response);
            } else {
              errorMessage('Directions request failed due to ' + status);
            }
          });
        }
        navigator.geolocation.getCurrentPosition(getLocation);
      } else {
        // Google AJAX API fallback GeoLocation
        if ((typeof google == 'object') && google.loader && google.loader.ClientLocation) {
          geolocate = {
            "lat": google.loader.ClientLocation.latitude,
            "lng": google.loader.ClientLocation.longitude
          }
        }
      }

    } else {
      errorMessage('Geocode was not successful for the following reason: ' + status);
    }
    d.resolve();
  });
  return d;
}

function loadScript() {
  var script = document.createElement("script");
  script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyBWWornRguaHPgQJFRn74qHQD3ZxbelM_Q&signed_in=true&callback=initMap";
  document.body.appendChild(script);
}
window.onload = loadScript;