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

function formatNumber(number, decimalsLength, decimalSeparator, thousandSeparator) {
  var n = number,
     decimalsLength = isNaN(decimalsLength = Math.abs(decimalsLength)) ? 2 : decimalsLength,
     decimalSeparator = decimalSeparator == undefined ? "." : decimalSeparator,
     thousandSeparator = thousandSeparator == undefined ? "," : thousandSeparator,
     sign = n < 0 ? "-" : "",
     i = parseInt(n = Math.abs(+n || 0).toFixed(decimalsLength)) + "",
     j = (j = i.length) > 3 ? j % 3 : 0;

  return sign +
     (j ? i.substr(0, j) + thousandSeparator : "") +
     i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousandSeparator) +
     (decimalsLength ? decimalSeparator + Math.abs(n - i).toFixed(decimalsLength).slice(2) : "");
}

function proccessAction(url, pData) {
  var d = $.Deferred();
  $.post(url, pData, function(data) {
    if (data['result']) {
      successMessage(data['message']);
      d.resolve('success');
    } else {
      errorMessage(data['message']);
      d.reject('failed');
    }
  }, 'JSON');
  return d.promise();
}