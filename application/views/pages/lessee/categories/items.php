<div class="container-fluid">
  <div class="row no-gutter">
    <div class="col-md-12">
      <p><a href="<?php echo site_url('lessees');?>" class="text-center"><i class="fa fa-dashboard"></i> Go back to dashboard </a></p>
    </div>
   </div>
    <div id="message" class="alert alert-success text-center" hidden><strong>Successfully</strong> Added to your My Interests list.</div>
    <?php if(empty($items)): ?>
      <div class="col-md-12">
        <div class="alert alert-warning" role="alert"> <strong>Oops!</strong> No item found. </div>
      </div>
    <?php else: ?>
      <?php foreach($items as $item):?>
        <div class="col-sm-3 col-lg-3 col-md-3">
            <div class="thumbnail">
            <?php
              $itemPic = $item['info']->item_pic == NULL ? 
                        'http://placehold.it/320x150' :
                        'data:image/jpeg;base64,'.base64_encode($item['info']->item_pic);
            ?>
                <img src="<?php echo $itemPic; ?>" alt="" style="width:320px; height:150px;">
                <div class="caption">
                    <h4><a href="#"><?php echo empty($item['info']->item_name) ? '<span class="text-danger">No Name</span>' : $item['info']->item_name; ?></a></h4>
                    <p>(<?php echo $item['info']->item_desc; ?>)</p>
                    <p>₱ <?php echo number_format($item['info']->item_rate, 2); ?> 
                      <small>
                        <?php echo $rentalMode[$item['info']->item_rental_mode]; ?>
                      </small>
                      <br>
                      <?php echo number_format($item['info']->item_qty); ?> pcs
                    </p>
                    <?php if (isset($item['info']->shop_name)) { ?>
                    <p><?php echo $item['info']->shop_name . ' - ' . $item['info']->shop_branch; ?></p>
                    <?php } ?>
                    <p>
                      <?php 
                        if (!empty($item['categories'])) {
                          $label = "<span class='label label-default'>";
                          echo $label;
                          $categoryType = array();
                          foreach ($item['categories'] as $category) {
                            $categoryType[] = $category->category_type;
                          }
                          echo implode("</span>  $label", $categoryType);
                          echo "</span>";
                        }
                      ?>
                    </p>
                    <div class="btn-group">
                      <a href="<?php echo site_url('reservations/item/' . $item['info']->item_id); ?>" class="btn btn-info btn-xs">Reserve</a>
                      <button class="btn btn-success btn-xs btn-rent" data-item-id="<?php echo $item['info']->item_id; ?>">Rent</button>
                      <a class="btn btn-primary btn-xs my-interest-trigger" data-item-id="<?php echo $item['info']->item_id; ?>" data-interest-name="<?php echo $item['info']->item_desc; ?>" href="<?php echo $action; ?>" <?php echo (in_array($item['info']->item_id,$myinterests)) ? 'disabled=disabled' : ''; ?>><span class="fa fa-plus-circle"> <?php echo (in_array($item['info']->item_id,$myinterests)) ? 'Added ' : 'My Interest '; ?></span></a>
                      <input type="hidden" value="<?php echo $item['info']->item_qty; ?>" class="item-qty">
                      <input type="hidden" value="<?php echo $item['info']->item_rate; ?>" class="item-rate">
                      <input type="hidden" value="<?php echo $item['info']->item_desc; ?>" class="item-desc">
                      <input type="hidden" value="<?php echo $item['info']->item_rental_mode; ?>" class="item-mode">
                      <input type="hidden" value="<?php echo $item['info']->subscriber_id; ?>" class="subscriber">
                    </div>
                </div>
            </div>
        </div>
      <?php endforeach; ?>
      <!--pagination-->
    <?php endif;?>
    <div class="text-center col-lg-12">
      <?php echo empty($pagination) ? '' : $pagination; ?>
    </div>
  </div>
</div>
<div class="modal modal-medium fade" id="reservation-modal" tabindex='-1'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="reservation-modal-title" >Rental Confirmation</i></h4>
      </div>
      <div class="modal-body">
        <h2><i class="fa fa-info"></i> Are you sure about this rental?</h2>
        <div class="details">
          <h3>ITEM <span id="confirm-item-desc"></span></h3>
          <p id="confirm-item-details"></p>
        </div>
        <div class="other-details">
          <div id="reportrange" style="z-index: 99999; background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
            <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
          </div>
          <input type="hidden" id="min-date" value="<?php echo date('m/d/Y'); ?>">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="btn-cancel-modal" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="btn-confirm-modal">Submit Reservation</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
  var rentUrl = "<?php echo site_url('reservations/save'); ?>";
</script>