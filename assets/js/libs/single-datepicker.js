$('.single-datepicker').daterangepicker({
    singleDatePicker: true,
    calender_style: "picker_3",
    showDropdowns: true,
    format: 'YYYY-MM-DD'
}, function (start, end, label) {
    //console.log(start.toISOString(), end.toISOString(), label);
});