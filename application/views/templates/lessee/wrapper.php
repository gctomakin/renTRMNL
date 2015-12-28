<div id="page-wrapper">
	<div class="row">
      <div class="col-lg-12">
          <h1 class="page-header"><?php echo empty($title) ? 'No-Title' : $title; ?></h1>
          Page rendered in <strong>{elapsed_time}</strong> seconds.
      </div>
      <!-- /.col-lg-12 -->
  </div>
  <div class="row">
  	<?php echo $content; ?>
  </div>
</div>