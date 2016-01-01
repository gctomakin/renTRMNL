$('.view-item-detail').on('click', function() {
	var buttonView = $(this);
	var id = buttonView.siblings('.item-property').data('item-id');
	$.post(detailUrl, {id : id}, function(data) {
		if (data['result']) {
			var modal = $('#item-detail-modal');
			modal.find('#item-detail-title').text(buttonView.siblings('.item-property').data('item-description'));
			modal.find('.modal-body').html(data['view']);
			modal.modal('show');
		} else {
			errorMessage(data['message']);
		}
	}, 'JSON');
});

$('.item-delete').on('click', function() {
	if (confirm('Are you sure to delete this item?')) {
		var id = $(this).siblings('.item-property').data('item-id');
		$.post(deleteUrl, {id: id}, function(data) {
			if (data['result']) {
				$("section[data-item-list-id='"+id+"']").slideToggle(false).remove();
				successMessage(data['message']);
			} else {
				errorMessage(data['message']);
			}
		}, 'JSON');
	}
});