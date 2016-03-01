<?php
	foreach ($messages as $message) {
		$position = ($this->session->has_userdata('lessor_logged_in') && $message->from_type == 'lessor') ? 'pull-right' : 'pull-left';
		$name = "ME";
?>
	<div class="col-lg-12" id="">
    <blockquote class='<?php echo $position; ?>' style='width:100%; display:block; border-bottom: 1px #bbb solid; margin-bottom: 20px;'>
      <div class="col-lg-12">
          <span style="color:#444;"><?php echo $name; ?></span>
          <a href="javascript:;" style="display: none;">
            <span>
              <i title='Delete Message' data-placement="left"
                 class="fa fa-1x fa-remove <?php echo $position; ?>">
              </i>
            </span>
          </a> 
      </div>
      <div class="col-lg-2">
      <?php if ($position != "pull-right") {  ?>
        <img src="http://placehold.it/140x100" class="thumbnail" data-toggle="popover" data-placement="right" title="<?php echo $name; ?>" style="width:140px; height:100px;"><br/>
      <?php }  ?>
      </div>
      <!-- {/if} -->
      <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
        <p style='word-wrap: break-word; min-height: 70px;' class='dotdotdotWrapper'>
          <?php echo $message->message; ?>
        </p>
        <span style='font-size:10px;'>
        <small><?php echo date('m/d/Y H:i:s', strtotime($message->sent)); ?></small>
        </span>
      </div>
    </blockquote>
  </div>
<?php
	}
?>

