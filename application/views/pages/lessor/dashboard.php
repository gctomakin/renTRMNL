<div class="container-fluid">
	<div class="row no-gutter">
		<?php if (empty($subscriber['subscriber_paypal_account'])) { ?>
		<div class="col-lg-12">
			<p class="alert alert-danger">
				<b>Hey!!</b> You must setup your papypal account first, so lessee may pay you in your paypal account. 
				Change your Account Settings <a href="<?php echo site_url('lessor/account'); ?>">here.</a>
			</p>
		</div>
		<?php } ?>
		<div class="col-lg-12">
			<p class="text-right">Welcome, <b><?php echo ucfirst($subscriber['subscriber_fname']); ?></b></p>
		</div>
	</div>
</div>