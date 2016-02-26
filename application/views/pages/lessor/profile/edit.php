<div class="container-fluid">
	<div class="row no-gutter">
		<form id='profile-form' class="form-horizontal" action="<?php echo site_url('lessors/profileSave'); ?>" >
			<div class="form-group">
				<label for="subscriber_fname" class="control-label col-md-2">
					First Name		
				</label>
				<div class="col-md-8">
					<input type="text" id="subscriber_fname" name="fname" value="<?php echo $lessor['subscriber_fname']; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label for="subscriber_midint" class="control-label col-md-2">
					Middle Initial		
				</label>
				<div class="col-md-8">
					<input type="text" id="subscriber_midint" name="mi" value="<?php echo $lessor['subscriber_midint']; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label for="subscriber_lname" class="control-label col-md-2">
					Last Name		
				</label>
				<div class="col-md-8">
					<input type="text" id="subscriber_lname" value="<?php echo $lessor['subscriber_lname']; ?>" name="lname" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label for="address" class="control-label col-md-2">
					Address		
				</label>
				<div class="col-md-8">
					<input type="text" id="address" name="address" value="<?php echo $lessor['address']; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label for="subscriber_telno" class="control-label col-md-2">
					Contac No		
				</label>
				<div class="col-md-8">
					<input type="text" id="subscriber_telno" name="contact" value="<?php echo $lessor['subscriber_telno']; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<button type="submit" class="btn btn-success">Save Profile</button> 
					<button type="reset" class="btn btn-default">Reset</button>
				</div>
			</div>
		</form>
	</div>
</div>