$(document).ready(function() {
  $('#lessee-form').submit(function(e) {
    e.preventDefault();
    var form = $(this);
    var url = form.attr('action');
    proccessAction(url, form.serialize()).then(function(data) {
      form[0].reset();
    });
  });
  $('.btn-signin-type').click(function() {
  	var action = ($(this).find('input').val() == 'lessee') ? signinLesseeUrl : signinLessorUrl;
    if($(this).find('input').val() == 'lessee'){
      $('#social-media-login').show();
    } else {
      $('#social-media-login').hide();
    }
    $('#form-signin').attr('action', action);
  });

  $('.portfolio-box').click(function() {
  	catId = $(this).data('category-id');
  	var catName = $(this).data('category-name');
  	$('#category-modal').find('#category-modal-title').text(catName);
  	viewCategory(catId, 1);
  });
});

$(document).on('click', '.pagination a', function(e) {
	e.preventDefault();
	var page = $(this).data('ci-pagination-page');
	viewCategory(catId, page);
});

var catId;

function viewCategory(id, page) {
	$.post(categoryItemsUrl+'/'+id+'/'+page, {}, function(data) {
		if (data['result']) {
			$('#category-modal').find('.modal-body').html(data['view']);
		} else {
			$('#category-modal').find('.modal-body').html(data['message']);
		}
		$('#category-modal').modal('show');
		
	}, 'JSON');
}