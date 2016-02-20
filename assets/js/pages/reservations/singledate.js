$('.single-datepicker').daterangepicker({
    singleDatePicker: true,
    calender_style: "picker_3",
    minDate: $('#end-date').val(),
    showDropdowns: true,
    format: 'YYYY-MM-DD'
}, function (start, end, label) {
    //console.log(start.toISOString(), end.toISOString(), label);
});