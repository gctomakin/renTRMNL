$(document).ready(function() {
  $('.btn-signin-type').click(function() {
  	var action = ($(this).find('input').val() == 'lessee') ? signinLesseeUrl : signinLessorUrl;
    $('#form-signin').attr('action', action);
  });
}); 