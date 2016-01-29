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
    var itemId = $(this).data('item-id');
    $.post(rentalPayUrl, {id: itemId}, function(data) {
      if (data['result']) {
        $('#paykey').val(data['split_pay']['payKey']);
        $('#btn-pay').trigger('click');
      } else {
        errorMessage(data['message']);
      }
    }, 'JSON');
  });

});