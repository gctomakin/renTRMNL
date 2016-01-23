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
		var action = $(this).attr('action');
		$.post(action, $(this).serialize(), function(data) {
			if (data['result']) {
				successMessage(data['message']);
			} else {
				errorMessage(data['message']);
			}
		}, 'JSON');
	});
}); 