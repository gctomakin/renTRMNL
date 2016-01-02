
function initMap() {

  var map = new google.maps.Map(document.getElementById('map'), {
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

  var map2 = new google.maps.Map(document.getElementById('map2'), {
    center: {
      lat: 14.2990183,
      lng: 120.9589699
    },
    zoom: 5,
    scrollwheel: false,
  });

  var imageBounds = {
    north: 40.773941,
    south: 40.712216,
    east: -74.12544,
    west: -74.22655
  };

  historicalOverlay = new google.maps.GroundOverlay(
    'https://www.lib.utexas.edu/maps/historical/newark_nj_1922.jpg',
    imageBounds);
  historicalOverlay.setMap(map);
  var geocoder = new google.maps.Geocoder();

  $.getJSON(getShopsJson)
    .done(function(data) {
      console.log('success');
      var addresses = [];
      $.each(data, function(i, obj) {
        addresses.push(obj.address);
      });
      geocodeAddress(geocoder, map, addresses);

    })
    .fail(function(xhr, textStatus, errorThrown) {
      throw new Error(xhr.responseText);
    })
    .always(function() {
      console.log("complete");
    });


  $('.map-modal-trigger').click(function(e) {
    e.preventDefault();
    var address = $(this).data('address');
    goToAddress(geocoder, map2, address);
    $('#map-modal').modal('show');

  });

  $('.locate-trigger').click(function(e) {
    e.preventDefault();
    var address = $(this).data('address');
    goToAddress(geocoder, map, address);
    $("html, body").animate({
      scrollTop: $('body').offset().top
    }, 100);

  });

  $('#map-modal').on('show.bs.modal', function() {
    resizeMap();
  });

  $('#map-modal').on('hide.bs.modal', function() {
    map2.panTo({
      lat: 0,
      lng: 0
    });
    map2.setZoom(2);
  });

  function resizeMap() {
    if (typeof map2 == "undefined") return;
    setTimeout(function() {
      resizingMap();
    }, 400);
  }

  function resizingMap() {
    if (typeof map2 == "undefined") return;
    var center = map2.getCenter();
    google.maps.event.trigger(map2, "resize");
    map2.setCenter(center);
  }

}

function geocodeAddress(geocoder, resultsMap, address) {
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
          map: resultsMap,
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

function goToAddress(geocoder, resultsMap, address) {
  geocoder.geocode({
    'address': address
  }, function(results, status) {
    if (status === google.maps.GeocoderStatus.OK) {
      var lat = results[0].geometry.location.lat();
      var lng = results[0].geometry.location.lng();
      var pos = new google.maps.LatLng(lat, lng);
      var infowindow = new google.maps.InfoWindow();
      var marker = new google.maps.Marker({
        map: resultsMap,
        position: pos,
        title: address
      });
      resultsMap.panTo(pos);
      resultsMap.setZoom(16);
      google.maps.event.addListener(marker, 'mouseover', function() {
        infowindow.setContent(this.title);
        infowindow.open(this.map, this);
      });
      google.maps.event.addListener(marker, 'mouseout', function() {
        infowindow.close();
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

$('.my-shop-trigger').click(function(e) {
  e.preventDefault();
  var shop_id = $(this).data('shop-id');
  var shop_name = $(this).data('shop-name');
  var action = this.href;
  var message = $('#message');
  var el = $(this);
  if (el.is('[disabled=disabled]')) {
    return false;
  }
  $.post(action, {
      shop_id: shop_id,
      shop_name: shop_name
    })
    .done(function(data) {

      if (data) {
        el.attr("disabled", true);
        $(".fa-plus-circle").text(" Added");
        message.fadeIn(2000, function() {
          $("html, body").animate({
            scrollTop: $('body').offset().top
          }, 100);
          message.delay(2000).fadeOut();
        });
      } else {
        alert('somethings wong..');
      }

    })
    .fail(function(xhr, textStatus, errorThrown) {
      throw new Error(xhr.responseText);
    });
});

$('.delete-trigger').click(function(e) {
  e.preventDefault();
  var action = this.href;
  var shop_id = $(this).data('id');
  var shop_name = $(this).data('shop-name');

  $('#confirm-modal-content').empty().html('Do you want to remove' + ' <strong>' + shop_name + '</strong> ?');
  $('#confirm-modal').modal('show');

});

$('.message-trigger').click(function(e){
  e.preventDefault();
  var subscriber_id = $(this).data('subscriber-id');
  $('#subscriber_id').val(subscriber_id);
  $('#compose-message-modal').modal('show');
});

$('#message-form').submit(function(e){
  e.preventDefault();
  var action = this.action;
  $.post(action,$(this).serialize())
  .done(function(data) {
    if (data) {
      $('#message2').fadeIn(2000, function() {
        $("html, body").animate({
          scrollTop: $('body').offset().top
        }, 100);
        $('#message2').delay(2000).fadeOut();
      });

    } else {
      alert('somethings wong..');
    }
    console.log(data);

  })
  .fail(function(xhr, textStatus, errorThrown) {
    throw new Error(xhr.responseText);
  });

});