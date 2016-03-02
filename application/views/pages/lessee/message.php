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
            <div class="panel-body" id="body-convo" style="background:#eee; height: 350px; overflow-y: scroll;">
              <?php echo empty($conversation) ? '' : $conversation; ?>
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
  </div>
</div>

<script type="text/javascript">
  var lessorListUrl = "<?php echo site_url('lessees/lessorsList'); ?>";
  var messageSendUrl = "<?php echo site_url('messages/send'); ?>";
  var messageConverstationUrl = "<?php echo site_url('messages/conversation'); ?>";
</script>