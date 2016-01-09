<div class="container-fluid">
	<div class="row no-gutter">
		<div class="col-lg-5">
			<div id="map" class="text-center" style="width:100%; height:480px;"></div>
		</div>
		<div class="col-lg-7">
	  	<form id="create-form" action="<?php echo site_url('/rentalshops/' . $action); ?>" method="POST" class="form-horizontal">
				<?php if (!empty($shop)) { ?>
	  		<input type="hidden" name="id" value="<?php echo $shop['shop_id']; ?>">
	  		<?php } ?>
				<div class="form-group">
			  	<label for="name" class="control-label col-lg-2">Name</label>
			  	<div class="col-lg-8">
			  		<input type="text" value="<?php echo set_value('name', empty($shop['shop_name']) ? '' : $shop['shop_name']); ?>" id="name" required class="form-control" placeholder="Name of the Shop" name="name">
			  	</div>
				</div>			
				<div class="form-group">
			  	<label for="branch" class="control-label col-lg-2">Branch</label>
			  	<div class="col-lg-8">
			  		<input type="text" value="<?php echo set_value('branch', empty($shop['shop_branch']) ? '' : $shop['shop_branch']); ?>" id="branch" required class="form-control" placeholder="What Branch of the Shop" name="branch">
			  	</div>
				</div>
				<div class="form-group">
					<label for="address" class="control-label col-lg-2">Address</label>
					<div class="col-lg-8">
						<input type="text" class="form-control" name="address" id="address" value="<?php echo set_value('address', empty($shop['address']) ? '' : $shop['address']); ?>" required placeholder="Address of the shop">
					</div>
				</div>	
				<!-- <div class="form-group">
			  	<label for="latitude" class="control-label col-lg-2">Latitude</label>
			  	<div class="col-lg-8"> -->
			  		<input type="hidden" value="<?php echo set_value('latitude', empty($shop['latitude']) ? '' : $shop['latitude']); ?>" id="latitude" name="latitude">
			  <!-- 	</div>
				</div>
				<div class="form-group">
			  	<label for="longitude" class="control-label col-lg-2">Longitude</label>
			  	<div class="col-lg-8"> -->
			  		<input type="hidden" value="<?php echo set_value('longitude', empty($shop['longitude']) ? '' : $shop['longitude']); ?>" id="longitude" name="longitude">
			  <!-- 	</div>
				</div> -->
				<div class="form-group">
					<div class="col-lg-offset-3 col-lg-8">
						<button class="btn btn-primary" type="submit" id="form-submit-btn"><?php echo ucfirst($action); ?></button>
						<button class="btn btn-default" type="reset" id="form-reset-btn">Reset</button>
					</div>
				</div>
			</form>
		</div>
  </div>
</div>