var startDate;
var endDate;

$(document).ready(function() {
	// SET UP DATE
	startDate = moment().format('YYYY-MM-DD');
  endDate = moment().format('YYYY-MM-DD');
  
	var cb = function (start, end, label) {
    //console.log(start.toISOString(), end.toISOString(), label);
    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    //alert("Callback has fired: [" + start.format('MMMM D, YYYY') + " to " + end.format('MMMM D, YYYY') + ", label = " + label + "]");
  }
  console.log($('#min-date').val());
  var optionSet1 = {
    startDate: moment(),
    endDate: moment(),
    minDate: $('#min-date').val(),
    showDropdowns: true,
    showWeekNumbers: true,
    timePicker: false,
    timePickerIncrement: 1,
    timePicker12Hour: true,
    ranges: { 'Tomorow': [moment().add(1, 'days'), moment().add(1, 'days')] },
    opens: 'left',
    buttonClasses: ['btn btn-default'],
    applyClass: 'btn-small btn-primary',
    cancelClass: 'btn-small',
    format: 'MM/DD/YYYY',
    separator: ' to ',
    locale: {
      applyLabel: 'Submit',
      cancelLabel: 'Clear',
      fromLabel: 'From',
      toLabel: 'To',
      customRangeLabel: 'Custom',
      daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
      monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      firstDay: 1
    }
	};
	$('#reportrange span').html(moment().format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
  $('#reportrange').daterangepicker(optionSet1, cb);
  $('#reportrange').on('show.daterangepicker', function () {
    console.log("show event fired");
  });
  $('#reportrange').on('hide.daterangepicker', function () {
    console.log("hide event fired");
  });
  $('#reportrange').on('apply.daterangepicker', function (ev, picker) {
    startDate = picker.startDate.format('YYYY-MM-DD');
    endDate = picker.endDate.format('YYYY-MM-DD');
    console.log("apply event fired, start/end dates are " + picker.startDate.format('MMMM D, YYYY') + " to " + picker.endDate.format('MMMM D, YYYY'));
  });
  $('#reportrange').on('cancel.daterangepicker', function (ev, picker) {
    console.log("cancel event fired");
  });
  $('#options1').click(function () {
		$('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
  });
  $('#options2').click(function () {
    $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
  });
  $('#destroy').click(function () {
    $('#reportrange').data('daterangepicker').remove();
  });
}); 