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
                <img src="http://placehold.it/320x150" alt="">
                <div class="caption">
                    <h4><a href="#"><?php echo $myinterest->item_desc; ?></a></h4>
                    <dl>
                      <dt>Item Rate</dt>
                      <dd><?php echo $myinterest->item_rate; ?></dd>
                      <dt>Item Qty</dt>
                      <dd><?php echo $myinterest->item_qty; ?></dd>
                    </dl>
                    <div class="btn-group">
                      <a href="#" class="btn btn-info btn-xs">Reserve</a>
                      <a href="#" class="btn btn-success btn-xs">Rent</a>
                       <a class="btn btn-danger btn-xs delete-trigger" data-item-name="<?php echo $myinterest->item_desc; ?>" data-id="<?php echo $myinterest->interest_id; ?>" href="<?php echo site_url("lessee/myinterests/delete/".$myinterest->interest_id); ?>" role="button"><span class="fa fa-trash"> Delete</span></a>
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