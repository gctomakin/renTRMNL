// Enable pusher logging - don't include this in production
// Pusher.log = function(message) {
//   if (window.console && window.console.log) {
//     window.console.log(message);
//   }
// };

var pusher = new Pusher('b3c7fc474d668cd4563e', {
  encrypted: true
});
var channel = pusher.subscribe('msg_channel');
channel.bind('onMessage', function(data) {
  console.log(data);
  var template = _.template($("#msg-template").html());
  var tmpl = template({subject: data.subject, message: data.message, date: data.date});
  $("#table-message").append(tmpl);
});