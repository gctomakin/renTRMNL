    function initMap() {
      var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: -34.397, lng: 150.644},
        zoom: 15
      });
      var geocoder = new google.maps.Geocoder();
      $('.map-modal-trigger').click(function(e){
        e.preventDefault();
        var address = $(this).data('address');
        geocodeAddress(geocoder, map, address);
        $('.fa-map-marker').html('').text(" "+address);
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

  function loadScript() {
    var script = document.createElement("script");
    script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyBWWornRguaHPgQJFRn74qHQD3ZxbelM_Q&signed_in=true&callback=initMap";
    document.body.appendChild(script);
  }



  window.onload = loadScript;

  $('.my-shop-trigger').click(function(e){
    e.preventDefault();
    var shop_id = $(this).data('shop-id');
    var shop_name = $(this).data('shop-name');
    var lessee_id = $('#sessionId').val();
    var action = $(this).data('action');
    var message = $('#message');

    $.post(action,{shop_id:shop_id, shop_name:shop_name, lessee_id:lessee_id})
     .done(function(data){

      if(data){
        message.fadeIn(2000,function(){
          $("html, body").animate({ scrollTop: $('body').offset().top }, 100);
          message.delay(2000).fadeOut();
        });
      }else{
        alert('somethings wong..');
      }

     })
     .fail(function(xhr, textStatus, errorThrown){
        throw new Error(xhr.responseText);
     });
  });
