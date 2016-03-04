<div class="container-fluid">
  <div class="row no-gutter">
    <div class="col-md-12">
      <p><a href="<?php echo site_url('lessees');?>" class="text-center"><i class="fa fa-dashboard"></i> Go back to dashboard </a></p>
    </div>
   </div>
    <?php
    if($this->session->flashdata('success')) {
        echo '<p class="alert alert-success text-center"><strong>Success!</strong> My Interest Removed</p>';
     }
    if($this->session->flashdata('error')) {
        echo '<p class="alert alert-danger text-center"><strong>Failed!</strong> Unable to removed :(</p>';
    }
    ?>
    <?php if(empty($items)): ?>
      <div class="col-md-12">
        <div class="alert alert-warning" role="alert"> <strong>Oops!</strong> Better check later, no items added yet. </div>
      </div>
    <?php else: ?>
      <?php foreach($items as $item):
        $itemLeft = $item['info']['item_qty'] - $item['info']['rented_qty'];
      ?>
      <div class="col-md-7 col-sm-7">
        <a href="#">
          <img src="<?php echo $item['info']['item_pic']; ?>" class="thumbnail img-responsive" alt="">
        </a>
      </div>
      <div class="col-md-5 col-sm-5">
        <h4>
          <a href="#">
            <?php echo empty($item['info']['item_name']) ? '<span class="text-danger">No Name</span>' : $item['info']['item_name']; ?>
          </a>
        </h4>
        <p>(<?php echo $item['info']['item_desc']; ?>)</p>
        <p>₱ <?php echo number_format($item['info']['item_rate'], 2); ?> 
          <small>
            <?php echo $rentalMode[$item['info']['item_rental_mode']]; ?>
          </small>
          <br>
          <?php echo number_format($itemLeft); ?> pcs left
        </p>
        <p><a href="<?php echo site_url('lessee/shops?id=' . $item['info']['shop_id']); ?>"><?php echo $item['info']['shop']; ?></a></p>
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
          <a href="<?php echo site_url('reservations/item/' . $item['info']['item_id']); ?>" class="btn btn-info btn-xs">Reserve</a>
          <?php if ($itemLeft > 0) { ?>
          <button class="btn btn-success btn-xs btn-rent" data-item-id="<?php echo $item['info']['item_id']; ?>">Rent</button>
          <?php } ?>
          <a class="btn btn-danger btn-xs delete-trigger" data-item-name="<?php echo $item['info']['item_name']; ?>" data-id="<?php echo $item['myinterest']; ?>" href="<?php echo site_url("lessee/myinterests/delete/".$item['myinterest']); ?>" role="button"><span class="fa fa-trash"> Delete</span></a>
          <input type="hidden" value="<?php echo $itemLeft; ?>" class="item-qty">
          <input type="hidden" value="<?php echo $item['info']['item_rate']; ?>" class="item-rate">
          <input type="hidden" value="<?php echo $item['info']['item_desc']; ?>" class="item-desc">
          <input type="hidden" value="<?php echo $item['info']['item_name']; ?>" class="item-name">
          <input type="hidden" value="<?php echo $item['info']['item_rental_mode']; ?>" class="item-mode">
          <input type="hidden" value="<?php echo $item['info']['subscriber_id']; ?>" class="subscriber">
        </div>
      </div>
      <div class="col-md-12"><hr></div>
      <?php endforeach; ?>
      <!--pagination-->
      <div class="row text-center">
        <div class="col-lg-12">
          <?php 
            if (!empty($pagination)) {
              echo $pagination;
            }
          ?>
        </div>
      </div>
    <?php endif;?>
  </div>
</div>

<div class="modal modal fade" id="confirm-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                 <h4 class="modal-title">Delete Confirmation</i></h4>

            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row" id="confirm-modal-content">
                      Content here
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <a role="button" class="btn btn-danger" id="remove-myinterest" href="">Remove</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
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
          <p id="confirm-item-details"></p>  
          <input type="hidden" id="min-date" value="<?php echo date('m/d/Y'); ?>">
          <label for="end-date">Rent Until</label>
          <input type="text" id="end-date" class="single-datepicker" value="<?php echo date('Y-m-d', strtotime('+1 days')); ?>">
          <input type="hidden" id="startDate" value="<?php echo date('Y-m-d'); ?>">
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
<form id='paypal-form' class="standard" action="https://www.sandbox.paypal.com/webapps/adaptivepayment/flow/pay" target="PPDGFrame">
  <input id="type" type="hidden" name="expType" value="light">
  <input id="paykey" type="hidden" name="paykey" value="">
  <input class="hidden" type="submit" id="btn-pay"> 
</form>
<script src="https://www.paypalobjects.com/js/external/dg.js" type="text/javascript"></script>
<script>
  var dgFlow = new PAYPAL.apps.DGFlow({ trigger: 'btn-pay'});
  var rentUrl = "<?php echo site_url('reservations/save'); ?>";
  var checkItemRentedUrl = "<?php echo site_url('rental/checkItemRented'); ?>";
  var rentItemUrl = "<?php echo site_url('rental/item'); ?>";
  var reservationUrl = "<?php echo site_url('reservations/save'); ?>";
</script>