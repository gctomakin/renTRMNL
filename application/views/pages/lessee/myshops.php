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
            <div id="map" class="text-center" style="width:100%; height:480px;" <?php echo (empty($myshops)) ? 'hidden': '';?>></div>
        </div>
    </div>
    <?php if(empty($myshops)): ?>
    <div class="row no-gutter">
      <div class="col-md-12">
          <div class="alert alert-warning" role="alert"> <strong>Oops!</strong> Better check later, no my shops added yet. </div>
      </div>
    </div>
    <?php else: ?>
    <hr>
    <?php foreach($myshops as $myshop): ?>
    <div class="row no-gutter">
          <div class="col-md-7">
            <?php $shopImage = $myshop->shop_image == NULL ? 'http://placehold.it/700x300' : 'data:image/jpeg;base64,'.base64_encode($myshop->shop_image); ?>
            
              <a href="#">
                  <img class="img-responsive" src="<?php echo $shopImage; ?>" style="width:700px; height:300px;" alt="">
              </a>
          </div>
          <div class="col-md-5">
              <h3><?php echo $myshop->shop_name .' - '. $myshop->shop_branch; ?></h3>
              <h4><?php echo $myshop->shop_branch; ?></h4>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium veniam exercitationem expedita laborum at voluptate. Labore, voluptates totam at aut nemo deserunt rem magni pariatur quos perspiciatis atque eveniet unde.</p>
              <div class="btn-group" role="group" aria-label="Shop Actions">
              <button class="btn btn-info map-modal-trigger" data-shop-name="<?php echo $myshop->shop_name; ?>" data-address="<?php echo $myshop->address; ?>" data-shop-id="<?php echo $myshop->shop_id; ?>"><span class="fa fa-info"> More Info</span></button>
              <a class="btn btn-success locate-trigger" href="#" data-address="<?php echo $myshop->address; ?>"><span class="fa fa-map-marker"> Locate</span></a>
              <a class="btn btn-warning message-trigger" href="#" data-subscriber-id="<?php echo $myshop->subscriber_id; ?>"><span class="fa fa-envelope"> Message</span></a>
              <a class="btn btn-danger delete-trigger" data-shop-name="<?php echo $myshop->shop_name; ?>" data-id="<?php echo $myshop->myshop_id; ?>" href="<?php echo site_url("lessee/myshops/delete/".$myshop->myshop_id); ?>" role="button"><span class="fa fa-trash"> Delete</span></a>
          </div>
      </div>
      </div>
      <hr>
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
  <?php endif; ?>
  </div>
</div>

<div class="modal modal-fullscreen fade" id="map-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                 <h4 class="modal-title" id="map-modal-title" >Modal title</i></h4>

            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row no-gutter">
                        <div class="col-md-12">
                            <div id="map2" class="text-center" style="width:100%; height:480px;"></div>
                        </div>
                    </div>
                    <hr>
                    <div id="modal-shops-item" class="row no-gutter">
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

      <!-- /.modal compose message -->
          <div class="modal fade" id="compose-message-modal">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h4 class="modal-title">Compose Message</h4>
                </div>
                <div class="modal-body">
                <div id="message2" class="alert alert-success text-center" hidden><strong>Successfully</strong> Sent Message</div>
                  <?php $attributes = array('class' => 'form-horizontal', 'id' => 'message-form'); echo form_open_multipart('lessee/send', $attributes);?>
                      <!-- <div class="form-group">
                        <label class="col-sm-2" for="inputTo">To</label>
                        <div class="col-sm-10"><input type="email" name="receiver" class="form-control" id="inputTo" placeholder="comma separated list of recipients"></div>
                      </div> -->
                      <input type="hidden" id="subscriber_id" name="receiver" />
                      <input type="hidden" id="user_type" name="user-type" value="lessor" />
                      <div class="form-group">
                        <label class="col-sm-2" for="inputSubject">Subject</label>
                        <div class="col-sm-10"><input type="text" name="subject" class="form-control" id="inputSubject" placeholder="subject"></div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-12" for="inputBody">Message</label>
                        <div class="col-sm-12"><textarea name="message" class="form-control" id="inputBody" rows="18"></textarea></div>
                      </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary ">Send <i class="fa fa-arrow-circle-right fa-lg"></i></button>
                </div>
                <?php echo form_close(); ?>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal compose message -->
      <script>
        var getShopsJson = "<?php echo $getShopsJson; ?>";
        var shopItemUrl = "<?php echo site_url('/rentalshops/getItems'); ?>";
    
      </script>
