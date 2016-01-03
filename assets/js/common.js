function successMessage(message) {
    new PNotify({
        title: 'Success!!',
        text: message,
        type: 'success'
    });
}

function errorMessage(message) {
    new PNotify({
        title: 'Failed!!',
        text: message,
        type: 'error'
    });
}

// Preview image
function previewImage(input) {
  if (input.files && input.files[0]) {
      var sizeMB = input.files[0].size/1024/1024;
      if (sizeMB > 0.5) {
        errorMessage("Image Size exceeds the limit. Please try again.");
        $(input).val('');
      } else {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#preview-image').fadeIn('fast');
          $('#preview-image').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
      }
  }
}