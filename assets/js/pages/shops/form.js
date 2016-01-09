$(document).ready(function() {
	setTimeout(function() {
		goToAddress($('#address').val());
	}, 1000);
	$('#address').change(function() {
		var address = $(this).val();
		goToAddress(address).done(function() {
			$('#latitude').val(latitude);
			$('#longitude').val(longitude);
		});
	});
	$('#form-reset-btn').click(function() {
		initMap();
	});
	$('#map').click(function() {
		getAddress().done(function() {
			$('#address').val(address);
			$('#latitude').val(latitude);
			$('#longitude').val(longitude);
		});
	});
}); 