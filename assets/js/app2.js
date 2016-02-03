// Enable pusher logging - don't include this in production
// Pusher.log = function(message) {
//   if (window.console && window.console.log) {
//     window.console.log(message);
//   }
// };

var pusher = new Pusher('b3c7fc474d668cd4563e', {encrypted: true});
var msg_channel = pusher.subscribe('msg-channel');
var notify_channel = pusher.subscribe('notify-channel');
var session_id = $('#sessionId').val();
var template = _.template($("#notify-template").html());

msg_channel.bind('msg-event', function(data) {
  if(data.receiver == session_id){
    var tmpl = template({receiver: data.receiver, subject: data.subject, message: data.message, date: data.date});
    $("#notify-list").append(tmpl);
    console.log(data);
  }
  return false;
});

notify_channel.bind('notify-event', function(data) {
  if(data.receiver == session_id){
    var tmpl = template({receiver: data.receiver, subject: data.subject, message: data.message, date: data.date});
    $("#notify-list").append(tmpl);
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