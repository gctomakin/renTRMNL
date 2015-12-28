<div class="container-fluid">
  <div class="row no-gutter">
  	<?php $this->load->view('templates/alertMessage'); ?>
		<form id="shop-form" action="<?php echo site_url($action); ?>" method="POST" class="form-horizontal">
			<div class="form-group">
		  	<label for="name" class="control-label col-lg-3">Name</label>
		  	<div class="col-lg-5">
		  		<input type="text" value="<?php echo set_value('name', ''); ?>" id="name" required class="form-control" placeholder="Name of the Shop" name="name">
		  	</div>
			</div>			
			<div class="form-group">
		  	<label for="branch" class="control-label col-lg-3">Branch</label>
		  	<div class="col-lg-5">
		  		<input type="text" value="<?php echo set_value('branch', ''); ?>" id="branch" required class="form-control" placeholder="What Branch of the Shop" name="branch">
		  	</div>
			</div>			
			<div class="form-group">
		  	<label for="latitude" class="control-label col-lg-3">Latitude</label>
		  	<div class="col-lg-5">
		  		<input type="text" value="<?php echo set_value('latitude', ''); ?>" id="latitude" required class="form-control" placeholder="Latitude of the Shop" name="latitude">
		  	</div>
			</div>
			<div class="form-group">
		  	<label for="longitude" class="control-label col-lg-3">Longitude</label>
		  	<div class="col-lg-5">
		  		<input type="text" value="<?php echo set_value('longitude', ''); ?>" id="longitude" required class="form-control" placeholder="Longitude of the Shop" name="longitude">
		  	</div>
			</div>
			<div class="form-group">
				<div class="col-lg-offset-3 col-lg-5">
					<button class="btn btn-primary" type="submit" id="form-submit-btn">Create</button>
					<button class="btn btn-default" type="reset" id="form-reset-btn">Reset</button>
				</div>
			</div>
		</form>
  </div>
</div>