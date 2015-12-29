<div class="container-fluid">
  <div class="row no-gutter">
  <?php foreach($shops as $shop) { ?>
		<div data-shop-id="<?php echo $shop->shop_id; ?>" class="col-lg-3 col-md-3 col-sm-3">
        <h3><?php echo ucfirst($shop->shop_name); ?></h3>
        <h4><?php echo ucfirst($shop->shop_branch); ?></h4>
        <p>(<?php echo $shop->latitude . ", " . $shop->longitude; ?>)</p>
        <a class="btn btn-info btn-xs" href="<?php echo site_url('lessor/shops/edit/'. $shop->shop_id); ?>">Edit <span class="fa fa-pencil"></span></a>
        <button class="btn btn-primary btn-xs shop-remove-btn" iid="<?php echo $shop->shop_id; ?>">
          <span class="fa fa-trash"></span> Remove
        </button>
    </div>
	<?php } ?>
  </div>
  <?php if (!empty($pagination)) { ?>
  <div class="row text-center">
    <?php echo $pagination; ?>
  </div>
  <?php } ?>
</div>
<script type="text/javascript">
  var removeUrl = "<?php echo site_url('rentalshops/delete'); ?>";
</script>