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
    <?php if(empty($myinterests)): ?>
      <div class="col-md-12">
        <div class="alert alert-warning" role="alert"> <strong>Oops!</strong> Better check later, no items added yet. </div>
      </div>
    <?php else: ?>
      <?php foreach($myinterests as $myinterest):?>
        <div class="col-sm-3 col-lg-3 col-md-3">
            <div class="thumbnail">
              <?php
                $itemPic = $myinterest->item_pic == NULL ? 
                  'http://placehold.it/320x150' :
                  'data:image/jpeg;base64,'.base64_encode($myinterest->item_pic);
              ?>
                <img src="<?php echo $itemPic; ?>" alt="">
                <div class="caption">
                    <h4><a href="#"><?php echo empty($myinterest->item_name) ? '<span class="text-danger">No Name</span>' : $myinterest->item_name; ?></a></h4>
                    <p><?php echo $myinterest->item_desc; ?></p>
                    <dl>
                      <dt>Item Rate</dt>
                      <dd><?php echo $myinterest->item_rate; ?></dd>
                      <dt>Item Qty</dt>
                      <dd><?php echo $myinterest->item_qty; ?></dd>
                    </dl>
                    <div class="btn-group">
                      <a href="<?php echo site_url('reservations/item/' . $myinterest->item_id); ?>" class="btn btn-info btn-xs">Reserve</a>
                      <a href="javascript:;" class="btn btn-success btn-xs btn-rent" data-item-id="<?php echo $myinterest->item_id; ?>">Rent</a>
                      <a class="btn btn-danger btn-xs delete-trigger" data-item-name="<?php echo $myinterest->item_desc; ?>" data-id="<?php echo $myinterest->interest_id; ?>" href="<?php echo site_url("lessee/myinterests/delete/".$myinterest->interest_id); ?>" role="button"><span class="fa fa-trash"> Delete</span></a>
                      <input type="hidden" value="<?php echo $myinterest->item_qty; ?>" class="item-qty">
                      <input type="hidden" value="<?php echo $myinterest->item_rate; ?>" class="item-rate">
                      <input type="hidden" value="<?php echo $myinterest->item_desc; ?>" class="item-desc">
                      <input type="hidden" value="<?php echo $myinterest->item_rental_mode; ?>" class="item-mode">
                      <input type="hidden" value="<?php echo $myinterest->subscriber_id; ?>" class="subscriber">
                    
                    </div>
                </div>
            </div>
        </div>
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
                <a role="button" class="btn btn-danger" href="<?php echo site_url("lessee/myinterests/delete/".$myinterest->interest_id); ?>">Remove</a>
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
<script>
  var rentUrl = "<?php echo site_url('reservations/save'); ?>";
</script>