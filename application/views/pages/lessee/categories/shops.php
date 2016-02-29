<div class="container-fluid">
  <div class="row no-gutter">
    <div class="col-md-12">
      <p><a href="<?php echo site_url('lessees');?>" class="text-center"><i class="fa fa-dashboard"></i> Go back to dashboard </a></p>
    </div>
  </div>
  <div id="message" class="alert alert-success text-center" hidden><strong>Successfully</strong> Added to your My Shop list.</div>
  <div class="row no-gutter">
      <div class="col-md-12">
          <div id="map" class="text-center" style="width:100%; height:480px;" <?php echo (empty($shops)) ? 'hidden': '';?>></div>
      </div>
  </div>
  <?php if(empty($shops)): ?>
  <div class="row no-gutter">
    <div class="col-md-12">
        <div class="alert alert-warning" role="alert"> <strong>Oops!</strong> Better check later, no shops added yet. </div>
    </div>
  </div>
  <?php else: ?>
    <hr>
    <?php foreach($shops as $shop):?>
    <div class="row no-gutter">
          <div class="col-md-7">
            <?php $itemPic = $shop->shop_image == NULL ? 'http://placehold.it/700x300' : 'data:image/jpeg;base64,'.base64_encode($shop->shop_image); ?>
            
              <a href="#">
                  <img class="img-responsive" src="<?php echo $itemPic; ?>" alt="" style="width:700px; height:300px;">
              </a>
          </div>
          <div class="col-md-5">
              <h3><?php echo $shop->shop_name . ' - ' . $shop->shop_branch; ?></h3>
              <h4><?php echo $shop->address; ?></h4>
              <p><?php echo $shop->shop_desc; ?></p>
              <div class="btn-group" role="group" aria-label="Shop Actions">
              <button class="btn btn-info map-modal-trigger" data-shop-name="<?php echo $shop->shop_name; ?>" data-address="<?php echo $shop->address; ?>" data-shop-id="<?php echo $shop->shop_id; ?>"><span class="fa fa-info"> More Info</span></button>
              <a class="btn btn-success locate-trigger" href="#" data-address="<?php echo $shop->address; ?>"><span class="fa fa-map-marker"> Locate</span></a>
              <!-- <a class="btn btn-warning message-trigger" href="#" data-subscriber-id="<?php echo $shop->subscriber_id; ?>"><span class="fa fa-envelope"> Message</span></a> -->
              <a class="btn btn-warning" href="<?php echo site_url('/lessee/message?lessor=' . $shop->subscriber_id); ?>" target="_blank" data-subscriber-id="<?php echo $shop->subscriber_id; ?>"><span class="fa fa-envelope"> Message</span></a>

              <a class="btn btn-primary my-shop-trigger" data-shop-id="<?php echo $shop->shop_id; ?>" data-shop-name="<?php echo $shop->shop_name; ?>" href="<?php echo $action; ?>" <?php echo (in_array($shop->shop_id,$myshops)) ? 'disabled=disabled' : ''; ?>><span class="fa fa-plus-circle"> <?php echo (in_array($shop->shop_id,$myshops)) ? 'Added ' : 'My Shop '; ?></span></a>
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
                        <div class="col-md-8">
                            <div id="map2" class="text-center" style="width:100%; height:480px;"></div>
                        </div>
                        <div class="col-md-4">
                            <div id="right-panel"></div>
                        </div>
                    </div>
                    <hr>
                    <div class="row no-gutter" id="modal-shops-item">
                      
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