<div class="container-fluid">
  <div class="row no-gutter">
    <div class="col-md-12">
      <a href="<?php echo site_url('lessees');?>" class="text-center"><i class="fa fa-dashboard"></i> Go back to dashboard </a>
    </div>
  </div>
  <?php
  if($this->session->flashdata('success')) {
      echo '<p class="alert alert-success text-center"><strong>Success!</strong> My Shop Removed</p>';
   }
  if($this->session->flashdata('error')) {
      echo '<p class="alert alert-danger text-center"><strong>Failed!</strong> Unable to removed :(</p>';
  }
  ?>
    <div class="row no-gutter">
        <div class="col-md-12">
            <div id="map" class="text-center" style="width:100%; height:480px;"></div>
        </div>
    </div>
    <?php if(empty($myshops)): ?>
      <div class="col-md-12">
          <div class="alert alert-warning" role="alert"> <strong>Oops!</strong> Better check later, no my shops added yet. </div>
      </div>
    <?php else: ?>
    <hr>
    <?php foreach($myshops as $myshop): ?>
    <div class="row no-gutter">
          <div class="col-md-7">
              <a href="#">
                  <img class="img-responsive" src="http://placehold.it/700x300" alt="">
              </a>
          </div>
          <div class="col-md-5">
              <h3><?php echo $myshop->shop_name; ?></h3>
              <h4><?php echo $myshop->shop_branch; ?></h4>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium veniam exercitationem expedita laborum at voluptate. Labore, voluptates totam at aut nemo deserunt rem magni pariatur quos perspiciatis atque eveniet unde.</p>
              <div class="btn-group" role="group" aria-label="Shop Actions">
              <a class="btn btn-info map-modal-trigger" href="#" data-address="<?php echo $myshop->address; ?>"><span class="fa fa-info"> More Info</span></a>
              <a class="btn btn-success locate-trigger" href="#" data-address="<?php echo $myshop->address; ?>"><span class="fa fa-map-marker"> Locate</span></a>
              <a class="btn btn-warning message-trigger" href="#" data-subscriber-id="<?php echo $myshop->subscriber_id; ?>"><span class="fa fa-envelope"> Message</span></a>
              <a class="btn btn-danger delete-trigger" data-shop-name="<?php echo $myshop->shop_name; ?>" data-id="<?php echo $myshop->myshop_id; ?>" href="<?php echo site_url("lessee/myshops/delete/".$myshop->myshop_id); ?>" role="button"><span class="fa fa-trash"> Delete</span></a>
          </div>
      </div>
      <hr>
    <?php endforeach; ?>
        <!--pagination-->
        <div class="row text-center">
          <div class="col-lg-12">
              <ul class="pagination">
                  <li>
                      <a href="#">«</a>
                  </li>
                  <li class="active">
                      <a href="#">1</a>
                  </li>
                  <li>
                      <a href="#">2</a>
                  </li>
                  <li>
                      <a href="#">3</a>
                  </li>
                  <li>
                      <a href="#">4</a>
                  </li>
                  <li>
                      <a href="#">5</a>
                  </li>
                  <li>
                      <a href="#">»</a>
                  </li>
              </ul>
          </div>
        </div>
  <?php endif; ?>
  </div>
</div>

<div class="modal modal-fullscreen fade" id="map-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                 <h4 class="modal-title">Modal title</i></h4>

            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="map2" class="text-center" style="width:100%; height:480px;"></div>
                        </div>
                    </div>
                    <div class="row">
                      Content here
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

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
                  <a role="button" class="btn btn-danger" href="<?php echo site_url("lessee/myshops/delete/".$myshop->myshop_id); ?>">Remove</a>
              </div>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
      </div>
      <script>
        var getShopsJson = "<?php echo $getShopsJson; ?>";
      </script>
