// Enable pusher logging - don't include this in production
// Pusher.log = function(message) {
//   if (window.console && window.console.log) {
//     window.console.log(message);
//   }
// };

var pusher = new Pusher('b3c7fc474d668cd4563e', {encrypted: true});
var msg_channel = pusher.subscribe('msg-channel');
var notify_channel = pusher.subscribe('notify-channel');
var inbox_channel = pusher.subscribe('inbox-channel');
var session_id = $('#sessionId').val();
var user_type = $('#userType').val();
var template = _.template($("#notify-template").html());

// Reservation
var top_template = _.template($("#top-notify-template").html());
var top_notify_channel = pusher.subscribe('top-notify-channel');

$("body").on('click', ".notify-msg-show", function(e){
  e.preventDefault();

  $(this).next("p").toggle();

});

msg_channel.bind('msg-event', function(data) {
  if(data.receiver == session_id && data.usertype == user_type){
    var tmpl = template({receiver: data.receiver, subject: data.subject, message: data.message, date: data.date});
    $("#notify-list").append(tmpl);
    console.log(data);
  }

  return false;
});

inbox_channel.bind('inbox-event', function(data) {
  if(data.receiver == session_id && data.usertype == user_type){
    var tmpl = template({receiver: data.receiver, subject: data.subject, message: data.message, date: data.date});
    $("#notify-list").append(tmpl);
    console.log(data);
  }

  return false;
});

notify_channel.bind('notify-event', function(data) {
  if(data.receiver == session_id && data.usertype == user_type){
    var tmpl = template({receiver: data.receiver, subject: data.subject, message: data.message, date: data.date});
    $("#notify-list").append(tmpl);
    console.log(data);
  }

  return false;
});

top_notify_channel.bind('top-notify-event', function(data) {
  if(data.receiver == session_id && data.usertype == user_type){
    var badge = $('#top-notification').find('.nav-badge');
    var count = badge.text();
    badge.text(parseFloat(count) + 1);
    badge.fadeIn('fast');
    var tmpl = top_template({link:data.link, sender: data.sender, notification: data.notification, date: data.date});
    $('#top-notification-list').append(tmpl);
  }
  return false;
});

$('#top-notification').on('click', function() {
  $(this).find('.nav-badge').text(0);
  $(this).find('.nav-badge').fadeOut('fast');
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