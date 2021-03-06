<div class="container-fluid">
  <div class="row no-gutter">
  	<form id="item-form" enctype="multipart/form-data" action="<?php echo site_url('/items/' . $action); ?>" method="POST" class="form-horizontal">
  		<?php if (!empty($item)) { ?>
  		<input type="hidden" name="id" value="<?php echo $item['item_id']; ?>">
  		<?php } ?>
  		<div class="form-group">
		  	<label for="shop" class="control-label col-lg-3">Shop</label>
		  	<div class="col-lg-5">
		  		<select id="shop" class="form-control" name="shop">
		  			<?php if (!empty($item['shop_id'])) { ?>
		  			<option value="<?php echo $item['shop_id']; ?>"><?php echo $item['shop_name'] . ' - ' . $item['shop_branch']; ?></option>
		  			<?php }?>
		  		</select>
		  	</div>
			</div>
			<div class="form-group">
		  	<label for="name" class="control-label col-lg-3">Name</label>
		  	<div class="col-lg-5">
		  		<input type="text" value="<?php echo set_value('name', empty($item['item_name']) ? '' : $item['item_name']); ?>" id="name" required class="form-control" placeholder="Name of the item" name="name">
		  	</div>
			</div>
			<div class="form-group">
		  	<label for="description" class="control-label col-lg-3">Description</label>
		  	<div class="col-lg-5">
		  		<input type="text" value="<?php echo set_value('description', empty($item['item_desc']) ? '' : $item['item_desc']); ?>" id="description" required class="form-control" placeholder="Description of the item" name="description">
		  	</div>
			</div>
			<div class="form-group">
		  	<label for="picture" class="control-label col-lg-3">Picture</label>
		  	<div class="col-lg-5">
		  		<input type="file" accept="image/*" value="" id="picture" name="picture">
		  		<?php $itemPic = empty($item['item_pic']) ? '' : 'data:image/jpeg;base64,'.base64_encode($item['item_pic']); ?>
          <img src="<?php echo $itemPic; ?>" id="preview-image" alt="" class="thumbnail" style="<?php echo empty($itemPic) ? 'display:none;' : ''; ?> width:100%; height: auto;">
		  	</div>
			</div>
			<div class="form-group">
				<label for="rate" class="control-label col-lg-3">Rate</label>
				<div class="col-lg-5">
					<input type="number" step="any" min="0" required name="rate" id="rate" value="<?php echo set_value('rate', empty($item['item_rate']) ? '' : $item['item_rate']); ?>" placeholder="Rate of the Item" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label for="qty" class="control-label col-lg-3">Quantity</label>
				<div class="col-lg-5">
					<input type="number" step="any" min="0" required name="qty" id="qty" value="<?php echo set_value('qty', empty($item['item_qty']) ? '' : $item['item_qty']); ?>" placeholder="Current Quantity of the Item" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label for="cashbond" class="control-label col-lg-3">Cash Bond</label>
				<div class="col-lg-5">
					<input type="number" step="any" min="0" required name="cashbond" id="cashbond" value="<?php echo set_value('cashbond', empty($item['item_cash_bond']) ? '' : $item['item_cash_bond']); ?>" placeholder="Amount to bond on renting" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label for="penalty" class="control-label col-lg-3">Penalty</label>
				<div class="col-lg-5">
					<input type="number" step="any" name="penalty" id="penalty" value="<?php echo set_value('penalty', empty($item['item_penalty']) ? '' : $item['item_penalty']); ?>" placeholder="Impose a penalty amount" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label for="rentalmode" class="control-label col-lg-3">Rental Mode</label>
				<div class="col-lg-5">
					<select required name="rentalmode" id="rentalmode" class="form-control">
						<?php
							foreach ($rental_modes as $key => $value) {
								$isSelected = isset($item['item_rental_mode']) && $item['item_rental_mode'] == $key ? 'selected' : '';
						 		echo "<option value='$key' $isSelected>" . $value . "</option>";
						 	}
						?>
					<select>
				</div>
			</div>
			<div class="form-group">
				<label for="category" class="control-label col-lg-3">Category</label>
				<div class="col-lg-5">
					<select name="category[]" id="category" class="form-control" multiple>
						<?php
							if (isset($categories)) {
								foreach($categories as $category) {
						?>
						<option value="<?php echo $category->category_id ?>" selected><?php echo $category->category_type; ?></option>
						<?php
								}
							}
						?>
					</select>
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
<script type="text/javascript">
	var shopUrl = "<?php echo site_url('rentalshops/allByLessor'); ?>";
	var categoryUrl = "<?php echo site_url('categories/all'); ?>";
</script>