<div class="container-fluid">
	<!-- <div class="row no-gutter"> -->
	<?php foreach($items as $item) { ?>
	<section data-item-list-id="<?php echo $item['info']->item_id; ?>">
    <div class="row no-gutter">
  		<div class="col-md-7 col-sm-7">
        <a href="#">
          <img class="thumbnail img-responsive" src="http://placehold.it/700x300" alt="">
        </a>
      </div>
      <div class="col-md-5 col-sm-5">
        <h3><?php echo $item['info']->item_desc;?></h3>
        <h4><?php echo $item['info']->shop_name . ' - ' . $item['info']->shop_branch; ?></h4>
        <div class="row">
        	<div class="col-md-6">
        		RATE : <?php echo number_format($item['info']->item_rate, 2); ?> <br>
  					CASH BOND : <?php echo number_format($item['info']->item_cash_bond, 2); ?> <br>
        		RENTAL MODE : <?php echo $rental_modes[$item['info']->item_rental_mode]; ?>
        	</div>
        	<div class="col-md-6">
  					PENALTY : <?php echo $item['info']->item_penalty; ?> <br>
  					QUANTITY : <?php echo $item['info']->item_qty; ?>
  				</div>
  				<div class="col-md-12">
  					<p>
  						CATEGORY :
  						<?php 
  							$categoryType = array();
  							foreach ($item['categories'] as $category) {
  								$categoryType[] = $category->category_type;
  							}
  							echo implode(', ', $categoryType);
  						?>
  					</p>
  				</div>
  				<div class="col-md-12">
  					<button class="btn btn-default btn-sm view-item-detail">View details</button>
  					<a class="btn btn-success btn-sm" href="<?php echo site_url('lessor/items/edit/' . $item['info']->item_id); ?>">Edit</a>
  	  			<button class="btn btn-sm btn-primary item-delete">Delete</button>
            <input type="hidden" class="item-property" data-item-id="<?php echo $item['info']->item_id; ?>" data-item-description="<?php echo $item['info']->item_desc; ?>">
  	      </div>
        </div>
      </div>
    </div>
    <hr>
  </section>
  <?php } ?>
	<!-- </div> -->
	<div class="text-center">
	<?php echo empty($pagination) ? '' : $pagination; ?>
	</div>
</div>
<div id="item-detail-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h3 class="modal-title" id="myModalLabel">Item <span id="item-detail-title"></span>'s details</h3>
      </div>
      <div class="modal-body">aaaaa</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var detailUrl = "<?php echo site_url('items/detail'); ?>";
  var deleteUrl = "<?php echo site_url('items/delete'); ?>";
</script>