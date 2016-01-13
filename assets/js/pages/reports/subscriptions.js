$(document).ready(function() {
	$('#reportrange').on('apply.daterangepicker', function (ev, picker) {
		//console.log(getDifference(startDate, endDate));
		getReport();
  });
}); 