
<div class="container">
	<h1 class="text-center">Subscription Entry Process</h1>
	<div id="wizard" class="form_wizard wizard_horizontal">
    <ul class="wizard_steps">
      <li>
        <a href="#step-1">
            <span class="step_no">1</span>
            <span class="step_descr">
			        Step 1<br />
			        <small>Step 1 Subscription Plans</small>
				    </span>
        </a>
      </li>
      <li>
        <a href="#step-2">
            <span class="step_no">2</span>
            <span class="step_descr">
			        Step 2<br />
			        <small>Step 2 Payment</small>
				    </span>
        </a>
      </li>
      <li>
        <a href="#step-3">
          <span class="step_no">3</span>
          <span class="step_descr">
			        Step 3<br />
			        <small>Step 3 Information</small>
			    </span>
        </a>
      </li>
    </ul>
    <div id="step-1">
    	<h2 class="StepTitle text-center">Step 1 Choose Plan</h2>
      <div class="row-centered">
    		<?php foreach($plans as $p) { ?>
				<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-centered">	
						<!-- PRICE ITEM -->
						<div class="panel price panel-red">
							<div class="panel-heading  text-center">
							<h3><?php echo $p->plan_name; ?></h3>
							</div>
							<div class="panel-body text-center">
								<p class="lead" style="font-size:3rem;"><strong>₱ <?php echo number_format($p->plan_rate, 2); ?></strong></p>
							</div>
							<ul class="list-group list-group-flush text-center">
								<li class="list-group-item"><i class="icon-ok text-danger"></i> <?php echo $p->plan_desc; ?></li>
							</ul>
							<div class="panel-footer">
								<a class="btn btn-lg btn-block btn-danger btn-plan" plan-name="<?php echo $p->plan_name; ?>" iid="<?php echo $p->plan_id; ?>" href="#">Purchase!</a>
							</div>
						</div>
						<!-- /PRICE ITEM -->
				</div>
				<?php } ?>
			</div>
    </div>
    <div id="step-2">
      <h2 class="StepTitle text-center">Step 2 Payment</h2>
      <div class="text-center">
      	<div id="step-2-confirmation" style="display:none;"> Are you sure to purchase Plan <span id="plan-name"></span> ? 
        	<form id='paypal-form' class="standard" action="https://www.sandbox.paypal.com/webapps/adaptivepayment/flow/pay" target="PPDGFrame">
						<input id="type" type="hidden" name="expType" value="light">
						<input id="paykey" type="hidden" name="paykey" value="">
						<!-- <input type="submit" id="submitBtn" value="Ye">  -->
						<button class="btn btn-primary" type="submit" id="btn-confirm-yes">Yes</button> 
						<button class="btn btn-default" type="reset" id="btn-confirm-no">No</button>
					</form>  	
      	</div>
      	<p id="step-2-wait">Please wait...... refresh page if no confirmation was shown.</p>
      </div>
    </div>
    <div id="step-3">
      <h2 class="StepTitle text-center">Step 3 Information</h2>
      <div class="row-centered">
      	<div class="col-lg-6 col-centered" id="step-3-information">        		
      	</div>
      </div>
    </div>
	</div>
  <div class="text-center">
    <hr>
    <a href="/logout">Logout</a>
  </div>
</div>

<script src="https://www.paypalobjects.com/js/external/dg.js" type="text/javascript"></script>
</script>
<script>
var dgFlow = new PAYPAL.apps.DGFlow({ trigger: 'btn-confirm-yes'});
var processPayUrl = "<?php echo site_url('subscriptions/processPay'); ?>";
var confirmPayUrl = "<?php echo site_url('subscriptions/confirmPay'); ?>";

</script>