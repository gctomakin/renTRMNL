$(document).ready(function() {
	var startDate = $('#start-date').val();
	$('#date').daterangepicker({
    format: 'YYYY-MM-DD',
    calender_style: "picker_3",
    startDate: startDate,
    endDate: startDate,
	});
	$('#quantity').select2({
		'placeholder': 'Choose remaining quantity'
	});
	var rate = $('#item-rate').val();
	$('#quantity').change(function() {
		var qty = $(this).val();
		var total = formatNumber(qty * rate);
		$('#total-info').text(total);
	});

	$('#reserve-form').submit(function(e) {
		e.preventDefault();
		var form = $(this);
		var action = form.attr('action');
		$.post(action, form.serialize(), function(data) {
			if (data['result']) {
				form[0].reset();
				successMessage(data['message']);
				setTimeout(function() {
					window.location = reservationListUrl;
				}, 1000);
			} else {
				errorMessage(data['message']);
			}
		}, 'JSON');
	});
}); 