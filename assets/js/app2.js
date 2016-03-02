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

// CHAT
var chat_channel = pusher.subscribe('chat-channel');
var chat_template = _.template($('#top-message-notify-template').html());
var detailTemplate = _.template($('#message-detail-template').html());

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

top_notify_channel.bind('top-notify-all-event', function(data) {
  if(data.usertype == user_type){
    var badge = $('#top-notification').find('.nav-badge');
    var count = badge.text();
    badge.text(parseFloat(count) + 1);
    badge.fadeIn('fast');
    var tmpl = top_template({link:data.link, sender: "System", notification: data.notification, date: data.date});
    $('#top-notification-list').append(tmpl);
  }
  return false;
});

chat_channel.bind('chat-event', function(data) {
  console.log(data);
  if(data.to == session_id && data.toType == user_type) {
    var receiver = $('#receiver :selected').val();
    if (data.from == receiver) {
      var body = $('#body-convo');
      body.append(detailTemplate({
        name: data.name,
        message: _.escape(data.message),
        position: 'pull-left',
        date: data.date,
        image: 'http://placehold.it/150x90'
      }));
      body.animate({ scrollTop: body[0].scrollHeight - body.height() }, "slow");
    } else {
      var badge = $('#top-message-notification').find('.nav-badge');
      var count = badge.text();
      badge.text(parseFloat(count) + 1);
      badge.fadeIn('fast');
      var tmpl = chat_template({
        name: data.name,
        date: data.date,
        message: data.message,
        link: data.link
      });
      $('#top-message-notification-list').append(tmpl);   
    }
  }
});

$('#top-notification, #top-message-notification').on('click', function() {
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