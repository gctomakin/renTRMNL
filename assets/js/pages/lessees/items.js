var itemId = 0;
var qtyRent = 0;
var itemRate = 0;  
var rentalMode = 0;
var daysDiff = 1;
var subscriber = 0;
var rentTotal = 0;
var rentAmount = 0;
$(document).ready(function(){
  $('.my-interest-trigger').click(function(e) {
    e.preventDefault();
    var item_id = $(this).data('item-id');
    var interest_name = $(this).data('interest-name');
    var action = this.href;
    var message = $('#message');
    var el = $(this);
    if (el.is('[disabled=disabled]')) {
      return false;
    }
    $.post(action, {
        item_id: item_id,
        interest_name: interest_name
      })
      .done(function(data) {

        if (data) {
          el.attr("disabled", true);
          el.find('.fa-plus-circle').text(" Added");
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
    var interest_id = $(this).data('id');
    var item_name = $(this).data('item-name');

    $('#confirm-modal-content').empty().html('Do you want to remove' + ' <strong>' + item_name + '</strong> ?');
    $('#confirm-modal').modal('show');

  });

  $('.btn-rent').click(function() {
    var message = '';
    var button = $(this);
    itemId = $(this).data('item-id');
    $.post(checkItemRentedUrl, {itemId: itemId}, function(data) {
      if (data['result'] && data['available']) {
        var qty = parseFloat(button.siblings('.item-qty').val());
        var desc = button.siblings('.item-desc').val();
        var name = button.siblings('.item-name').val();
        itemRate = parseFloat(button.siblings('.item-rate').val());
        qtyRent = parseFloat(prompt('How many items you want to rent?', qty));
        rentalMode = button.siblings('.item-mode').val();
        subscriber = button.siblings('.subscriber').val();
        if (isNaN(qtyRent)) {
          message = 'Quantity is not numeric';
        } else if (qtyRent <= 0) {
          message = 'Quantity must exceeds 0';
        } else if (qtyRent > qty) {
          message = 'Quantity must not exceeds ' + qty;
        }

        if (message != '') {
          errorMessage(message);
          itemId = 0;
          qtyRent = 0; 
        } else {
          computeRentTotal();
          $('#confirm-item-desc').text(name + ' - ' + desc);
          $('#confirm-item-details').text(qtyRent + 'pcs x ₱ ' + rentAmount + ' = ₱ ' + formatNumber(rentTotal));
          $('#reservation-modal').modal('show');
        }
      } else {
        errorMessage(data['message']);
      }
    }, 'JSON');
  });
  
  function computeRentTotal() {
    rentAmount = (daysDiff/rentalMode ) * itemRate;
    rentAmount = Math.ceil(rentAmount * 10) / 10;
    rentTotal = qtyRent * rentAmount;  
  }

  $('#reportrange').on('apply.daterangepicker', function (ev, picker) {
    console.log(startDate, endDate);
    var d = moment(endDate).diff(startDate, 'days');
    daysDiff = d + 1;
    computeRentTotal();
    $('#confirm-item-details').text(qtyRent + 'pcs x ₱ ' + rentAmount + ' = ₱ ' + formatNumber(rentTotal));
  });
  var startDate = $('#start-date').val();
  $('#end-date').on('apply.daterangepicker', function (ev, picker) {
    console.log($(this).val());
    var d = moment($(this).val()).diff(startDate, 'days');
    daysDiff = d + 1;
    computeRentTotal();
    $('#confirm-item-details').text(qtyRent + 'pcs x ₱ ' + rentAmount + ' = ₱ ' + formatNumber(rentTotal));
  });
  $('.daterangepicker').css('z-index', 9999);

  $('#btn-confirm-modal').click(function() {
    // Check for item and rented qty
    var detail = [];
    var endDate = $('#end-date').val();
    var rentType = $('.rentType:checked').val();
    if (itemId != 0 && qtyRent != 0) {
      detail.push({amount : rentAmount, id: itemId, qty: qtyRent});
      var forRent = {
        to: endDate,
        total: rentTotal,
        details: detail,
        subscriber: subscriber,
        status: 'rent',
        forRent: true
      };
      console.log(forRent);
      $.post(reservationUrl, forRent, function(data) {
        // successMessage('Your rental needs to be confirm by the lessor');
        if (data['result']) {
          proceedPay(forRent, rentType);
          $('#reservation-modal').modal('hide');
        } else {
          errorMessage(data['message']);
        }
      }, 'JSON');
    } else {
      errorMessage('No Item SELECTED OR specified quantity to rent');
    }
  });
});

function proceedPay(reservation, type) {
  successMessage('Please wait processing transaction');
  reservation.type = type;
  $.post(rentItemUrl, reservation, function(data) {
    if (data['result']) {
      if (data['paypal']['result']) {
        $('#paykey').val(data['paypal']['packet']['payKey']);
        $('#btn-pay').click();
      } else {
        errorMessage(data['paypal']['message']);
      }
    } else {
      errorMessage(data['message']);
    }
  }, 'JSON');
}
function closePaypal()  {
  dgFlow.closeFlow();
  top.close();
  location.reload();
}