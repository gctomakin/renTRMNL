    function initMap() {
      var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: {lat: -34.397, lng: 150.644},
        mapTypeId:google.maps.MapTypeId.ROADMAP
      });
      var geocoder = new google.maps.Geocoder();
      $('.map-modal-trigger').click(function(e){
        e.preventDefault();
        var address = $(this).data('address');
        geocodeAddress(geocoder, map, address);
        $('#map-modal').modal('show');

      });

      $('#map-modal').on('show.bs.modal', function () {
          resizeMap();
      });

      function resizeMap() {
         if(typeof map =="undefined") return;
         setTimeout( function(){resizingMap();} , 400);
      }

      function resizingMap() {
         if(typeof map =="undefined") return;
         var center = map.getCenter();
         google.maps.event.trigger(map, "resize");
         map.setCenter(center);
      }

    }

  function geocodeAddress(geocoder, resultsMap, address) {
    geocoder.geocode({'address': address}, function(results, status) {
      if (status === google.maps.GeocoderStatus.OK) {
        var lat = results[0].geometry.location.lat();
        var lng = results[0].geometry.location.lng();
        var pos = new google.maps.LatLng(lat,lng);
        resultsMap.setCenter(pos);

        var marker = new google.maps.Marker({
          map: resultsMap,
          position: pos,
          title: address
        });
      } else {
        alert('Geocode was not successful for the following reason: ' + status);
      }
    });
  }
