var planId = "";
var planName = "";
var paykey = "";
	
$(document).ready(function() {
	$('#wizard').smartWizard({hideButtonsOnDisabled: true, keyNavigation: false});
  function onFinishCallback() {
    $('#wizard').smartWizard('showMessage', 'Finish Clicked');
  }
  $('#wizard').find('.actionBar').hide();

  $('.btn-plan').click(function() {
  	planId = $(this).attr('iid');
  	planName = $(this).attr('plan-name');
  	$('#wizard').smartWizard('disableStep');
  	$('#wizard').smartWizard('goForward');
  	$('#plan-name').text(planName);
  	$.post(processPayUrl, {id: planId}, function(data) {
  		if (data['result']) {
  			paykey = data['split_pay']['payKey'];
  			$('#paykey').val(paykey);
  			$('#step-3-confirmation').fadeIn('fast');
				$('#step-3-wait').hide();
  			console.log(data);
  		} else {
  			errorMessage(data['message']);
  		}
  	}, 'JSON');
  });

  $('#paypal-form').submit(function(e) {
  	$('#step-3-confirmation').hide();
  	$('#step-3-wait').fadeIn('fast');
  });

  $('#btn-confirm-no').click(function() {
  	$('#wizard').smartWizard('goBackward');
  });

  $('#lessor-form').submit(function(e) {
    e.preventDefault();
    var form = $(this);
    var url = $(this).attr('action');
    proccessAction(url, form.serialize()).then(function(data) {
      form.remove();
      $('#wizard').smartWizard('disableStep', 1);
      $('#wizard').smartWizard('goForward');
    });
  });
});

function confirmPay()  {
	$.post(confirmPayUrl, {plan_id: planId, paykey: paykey}, function(data) {
		if (data['result']) {
      $('#wizard').smartWizard('disableStep', 2);
			$('#wizard').smartWizard('disableStep', 3);
			$('#wizard').smartWizard('goForward');
			$('#step-4-information').html(data['view']);
		} else {
			errorMessage(data['message']);
			$('#wizard').smartWizard('goBackward');
		}
		dgFlow.closeFlow();
  	top.close();
	}, 'JSON');
}

function cancelPay() {
	dgFlow.closeFlow();
  top.close();
  $('#wizard').smartWizard('enableStep', 1);
	$('#wizard').smartWizard('goBackward');
}