<div class="container-fluid">
	<div class="row no-gutter">
		<div class="col-lg-12">
			<form method="POST" id="account-form" action="<?php echo site_url('lessors/accountSave'); ?>" class="form-horizontal">
				<div class="form-group">
					<label class="control-label col-lg-2" for="email">Email</label>
					<div class="col-lg-7">
						<input type="text" value="<?php echo $subscriber['subscriber_email']; ?>" id="email" name="email" placeholder="Your Email" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-lg-2" for="paypal">Paypal</label>
					<div class="col-lg-7">
						<input type="text" value="<?php echo $subscriber['subscriber_paypal_account']; ?>" id="paypal" name="paypal" placeholder="Your Paypal Account" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-lg-2" for="username">Username</label>
					<div class="col-lg-7">
						<input type="text" value="<?php echo $subscriber['username']; ?>" id="username" name="username" placeholder="Your Username" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-lg-2" for="password_old">Old Password</label>
					<div class="col-lg-7">
						<input type="password" id="password_old" name="password_old" placeholder="Your Old Password" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-lg-2" for="password">Password</label>
					<div class="col-lg-7">
						<input type="password" id="password" name="password" placeholder="Your Password" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-lg-2" for="password_confirm">Confirm Password</label>
					<div class="col-lg-7">
						<input type="password" id="password_confirm" name="password_confirm" placeholder="Confirm Password" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-offset-2 col-lg-7">
						<button class="btn btn-primary" type="submit">Update Account</button>
						<button class="btn" type="reset">Reset</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>