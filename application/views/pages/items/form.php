<div class="container-fluid">
  <div class="row no-gutter">
  	<form id="create-form" action="<?php echo site_url('/items/' . $action); ?>" method="POST" class="form-horizontal">
			<?php if (!empty($item)) { ?>
  		<input type="hidden" name="id" value="<?php echo $item['item_id']; ?>">
  		<?php } ?>
			<div class="form-group">
		  	<label for="name" class="control-label col-lg-3">Description</label>
		  	<div class="col-lg-5">
		  		<input type="text" value="<?php echo set_value('description', empty($item['item_desc']) ? '' : $item['item_desc']); ?>" id="description" required class="form-control" placeholder="Description of the item" name="description">
		  	</div>
			</div>
			<div class="form-group">
		  	<label for="name" class="control-label col-lg-3">Picture</label>
		  	<div class="col-lg-5">
		  		<input type="file" value="<?php echo set_value('picture', empty($item['item_pic']) ? '' : $item['item_pic']); ?>" id="picture" required name="picture">
		  	</div>
			</div>
			<div class="form-group">
				<label for="rate" class="control-label col-lg-3">Rate</label>
				<div class="col-lg-5">
					<input type="number" required name="rate" id="rate" value="<?php echo set_value('rate', empty($item['rate']) ? '' : $item['rate']); ?>" placeholder="aaaa" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label for="qty" class="control-label col-lg-3">Quantity</label>
				<div class="col-lg-5">
					<input type="number" required name="qty" id="qty" value="<?php echo set_value('qty', empty($item['qty']) ? '' : $item['qty']); ?>" placeholder="aaaa" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label for="cashbond" class="control-label col-lg-3">Cash Bond</label>
				<div class="col-lg-5">
					<input type="text" required name="cashbond" id="cashbond" value="<?php echo set_value('cashbond', empty($item['cashbond']) ? '' : $item['cashbond']); ?>" placeholder="aaaa" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label for="rentalmode" class="control-label col-lg-3">Rental Mode</label>
				<div class="col-lg-5">
					<input type="text" required name="rentalmode" id="rentalmode" value="<?php echo set_value('rentalmode', empty($item['rentalmode']) ? '' : $item['rentalmode']); ?>" placeholder="aaaa" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label for="penalty" class="control-label col-lg-3">Penalty</label>
				<div class="col-lg-5">
					<input type="text" name="penalty" id="penalty" value="<?php echo set_value('penalty', empty($item['penalty']) ? '' : $item['penalty']); ?>" placeholder="aaaa" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label for="category" class="control-label col-lg-3">Category</label>
				<div class="col-lg-5">
					<select name="category" id="category" class="form-control" multiple></select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-offset-3 col-lg-5">
					<button class="btn btn-primary" type="submit" id="form-submit-btn"><?php echo ucfirst($action); ?></button>
					<button class="btn btn-default" type="reset" id="form-reset-btn">Reset</button>
				</div>
			</div>
		</form>
  </div>
</div>