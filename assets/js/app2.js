// Enable pusher logging - don't include this in production
// Pusher.log = function(message) {
//   if (window.console && window.console.log) {
//     window.console.log(message);
//   }
// };

var pusher = new Pusher('b3c7fc474d668cd4563e', {
  encrypted: true
});
var msg_channel = pusher.subscribe('msg_channel');
var session_id = $('#sessionId').val();
//var notify_channel = pusher.subscribe('notify_channel');

msg_channel.bind('onMessage', function(data) {
  if(data.receiver == session_id){
    // var template = _.template($("#msg-template").html());
    // var tmpl = template({subject: data.subject, message: data.message, date: data.date});
    // $("#table-message").append(tmpl);
    console.log(data);
  }
  return false;
});

$(".modal-transparent").on('show.bs.modal', function () {
  setTimeout( function() {
    $(".modal-backdrop").addClass("modal-backdrop-transparent");
  }, 0);
});

$(".modal-transparent").on('hidden.bs.modal', function () {
  $(".modal-backdrop").addClass("modal-backdrop-transparent");
});

$(".modal-fullscreen").on('show.bs.modal', function () {
  setTimeout( function() {
    $(".modal-backdrop").addClass("modal-backdrop-fullscreen");
  }, 0);
});

$(".modal-fullscreen").on('hidden.bs.modal', function () {
  $(".modal-backdrop").addClass("modal-backdrop-fullscreen");
});