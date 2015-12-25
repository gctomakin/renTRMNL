$(document).ready(function() {
  $('.btn-signin-type').click(function() {
  	var action = ($(this).find('input').val() == 'lessee') ? signinLesseeUrl : signinLessorUrl;
    if($(this).find('input').val() == 'lessee'){
      $('#social-media-login').show();
    } else {
      $('#social-media-login').hide();
    }
    $('#form-signin').attr('action', action);
  });
});