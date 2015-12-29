$(document).ready(function() {
	$('.shop-remove-btn').click(function() {
		if (confirm("Are you sure to delete this?")) {
			var id = $(this).attr('iid');
			$.post(removeUrl, {id:id}, function(data) {
				if (data['result']) {
					$("[data-shop-id='"+id+"']").slideToggle(false);
					successMessage(data['message']);
				} else {
					errorMessage(data['message']);
				}
			}, 'JSON');
		}
	});
}); 