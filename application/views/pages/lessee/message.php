<div class="container-fluid">
  <?php //if($this->session->flashdata('success')){ echo '<div class="alert alert-success"><strong>Successfully</strong> Sent Message!</div>';} ?>
  <div class="row no-gutter">
    <div class="col-md-12">
      <form id="message-form" class="form-horizontal">
        <div class="form-group">
          <select id="receiver" name="receiver" class="form-control input-lg">
          <?php 
            $lessorname = empty($lessor) ? 'No' : $lessor['subscriber_lname'] . ', ' . $lessor['subscriber_fname'];
            if (!empty($lessor)) {
          ?>
            <option value="<?php echo $lessor['subscriber_id']; ?>">
              <?php echo $lessorname; ?>
            </option>
          <?php } ?>
          </select>
        </div>
        <div class="form-group">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h2>
                <span id="name-convo"><?php echo $lessorname; ?></span> <small>Conversation</small>
              </h2>
            </div>
            <div class="panel-body" id="body-convo" style="background:#eee; height: 350px; overflow-y: scroll;" id="">
            </div>
            <div class="panel-footer" style="background:rgba(0,0,0, .5);">
              <div class="col-xs-10">
                <textarea <?php echo $isDisable; ?> id="text-convo" style="height:68px;" class="form-control" rows="3" id="textArea"></textarea>
              </div>
              <div class="col-xs-2">
                <button id="btn-convo" <?php echo $isDisable; ?> class="btn btn-default btn-lg col-md-12" style="vertical-align: middle;">
                  <i class="fa-2x fa fa-send"></i>
                </button>
              </div>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
      </form>
      <input type="hidden" id="message" value="<?php echo empty($message) || empty($lessor) ? '' : $message ?>">
    </div>
    <!-- <div class="col-sm-3 col-md-2">
       <a href="#" class="btn btn-primary btn-sm btn-block" role="button" data-toggle="modal" data-target="#compose-message-modal">COMPOSE</a>
    </div> -->

   <!-- <div class="col-sm-9 col-md-10">
      <table class="table table-inbox table-hover">
        <tr>
            <th>
                <input type="checkbox" class="mail-checkbox">
            </th>
            <th><i class="fa fa-star"></i></th>
            <th>Subject</th>
            <th>Message</th>
            <th><i class="fa fa-paperclip"></i></th>
            <th class="text-right">Date/Time</th>
        </tr>
        <tbody id="table-message">

        </tbody>
        <script type="text/template" id="msg-template">
          <tr>
              <td>
                  <input type="checkbox" class="mail-checkbox">
              </td>
              <td><i class="fa fa-star"></i></td>
              <td><%= subject %></td>
              <td><%= message %></td>
              <td class="inbox-small-cells"><i class="fa fa-paperclip"></i></td>
              <td class="text-right"><%= date %></td>
          </tr>
        </script>
      </table>
   </div> -->
  </div>
</div>

<!-- /.modal compose message -->
<!-- <div class="modal fade" id="compose-message-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Compose Message</h4>
      </div>
      <div class="modal-body">
        <?php $attributes = array('class' => 'form-horizontal', 'id' => 'message-form'); echo form_open_multipart('lessee/sendinbox', $attributes);?>
            <div class="form-group">
              <?php if($this->session->has_userdata('lessee_id')):?>
              <input type="hidden" id="user_type" name="user-type" value="lessor"/>
              <?php elseif($this->session->has_userdata('lessor_id')):?>
              <input type="hidden" id="user_type" name="user-type" value="lessee"/>
              <?php endif; ?>
              <label class="col-sm-2" for="inputTo">To</label>
              <div class="col-sm-10">
                <select name="receiver" id="inputTo">
                  <?php foreach($lessors as $lessor): ?>
                    <option value="<?php echo $lessor->subscriber_id;?>"><?php echo $lessor->subscriber_fname.' '.$lessor->subscriber_lname?></option>
                  <?php endforeach; ?>

                </select>
              </div>
            </div>
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
    </div>
  </div>
</div> --><!-- /.modal compose message -->

<script type="text/javascript">
  var lessorListUrl = "<?php echo site_url('lessees/lessorsList'); ?>";
  var messageSendUrl = "<?php echo site_url('messages/send'); ?>";
</script>