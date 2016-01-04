<div class="container-fluid">
  <div class="row no-gutter">
    <div class="col-md-12">
      <p><a href="<?php echo site_url('lessees');?>" class="text-center"><i class="fa fa-dashboard"></i> Go back to dashboard </a></p>
    </div>
  </div>
  <div id="message" class="alert alert-success text-center" hidden><strong>Successfully</strong> Added to your My Shop list.</div>
  <div class="row no-gutter">
      <div class="col-md-12">
          <div id="map" class="text-center" style="width:100%; height:480px;"></div>
      </div>
  </div>
  <?php if(empty($shops)): ?>
    <div class="col-md-12">
        <div class="alert alert-warning" role="alert"> <strong>Oops!</strong> Better check later, no shops added yet. </div>
    </div>
  <?php else: ?>
    <hr>
    <?php foreach($shops as $shop):?>
    <div class="row no-gutter">
          <div class="col-md-7">
              <a href="#">
                  <img class="img-responsive" src="http://placehold.it/700x300" alt="">
              </a>
          </div>
          <div class="col-md-5">
              <h3><?php echo $shop->shop_name; ?></h3>
              <h4><?php echo $shop->address; ?></h4>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium veniam exercitationem expedita laborum at voluptate. Labore, voluptates totam at aut nemo deserunt rem magni pariatur quos perspiciatis atque eveniet unde.</p>
              <div class="btn-group" role="group" aria-label="Shop Actions">
              <a class="btn btn-info map-modal-trigger" href="#" data-shop-name="<?php echo $shop->shop_name; ?>" data-address="<?php echo $shop->address; ?>"><span class="fa fa-info"> More Info</span></a>
              <a class="btn btn-success locate-trigger" href="#" data-address="<?php echo $shop->address; ?>"><span class="fa fa-map-marker"> Locate</span></a>
              <a class="btn btn-warning message-trigger" href="#" data-subscriber-id="<?php echo $shop->subscriber_id; ?>"><span class="fa fa-envelope"> Message</span></a>
              <a class="btn btn-primary my-shop-trigger" data-shop-id="<?php echo $shop->shop_id; ?>" data-shop-name="<?php echo $shop->shop_name; ?>" href="<?php echo $action; ?>" <?php echo (in_array($shop->shop_id,$myshops)) ? 'disabled=disabled' : ''; ?>><span class="fa fa-plus-circle"> <?php echo (in_array($shop->shop_id,$myshops)) ? 'Added ' : 'My Shop '; ?></span></a>
              </div>
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
                    <div class="row no-gutter">
                      <?php for ($i=0; $i < 12; $i++):?>
                        <div class="col-sm-3 col-lg-3 col-md-3">
                            <div class="thumbnail">
                                <img src="http://placehold.it/320x150" alt="">
                                <div class="caption">
                                    <div class="btn-group inline pull-right" data-toggle="buttons-checkbox">
                                      <a href="#" class="btn btn-info btn-xs">Reserve</a>
                                      <a href="#" class="btn btn-success btn-xs">Rent</a>
                                      <a href="#" class="btn btn-primary btn-xs">My Interest</a>
                                    </div>
                                    <h4><a href="#">First Interest</a>
                                    </h4>
                                    <p>See more snippets like this online store item at <a target="_blank" href="http://www.bootsnipp.com">Bootsnipp - http://bootsnipp.com</a>.</p>
                                </div>
                            </div>
                        </div>
                      <?php endfor; ?>
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
    </script>

