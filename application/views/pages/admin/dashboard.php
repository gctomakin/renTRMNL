<div class="container-fluid">
  <div class="row no-gutter">
		<div class="col-md-6">
			<div class="panel panel-primary">
	      <div class="panel-heading">
	        <div class="row">
	          <div class="col-xs-3">
	            <i class="fa fa-cubes fa-5x"></i>
	          </div>
	          <div class="col-xs-9 text-right">
	            <div class="huge"><?php echo $total_items; ?></div>
	            <div>Total Items!</div>
	          </div>
	        </div>
	      </div>
	      <a href="<?php echo site_url('admin/monitor/items'); ?>">
	        <div class="panel-footer">
	          <span class="pull-left">View Details</span>
	          <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	          <div class="clearfix"></div>
	        </div>
	      </a>
	    </div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-info">
	      <div class="panel-heading">
	        <div class="row">
	          <div class="col-xs-3">
	            <i class="fa fa-users fa-5x"></i>
	          </div>
	          <div class="col-xs-9 text-right">
	            <div class="huge"><?php echo $total_lessee; ?></div>
	            <div>Total Lessees!</div>
	          </div>
	        </div>
	      </div>
	      <a href="<?php echo site_url('admin/monitor/lessee'); ?>">
	        <div class="panel-footer">
	          <span class="pull-left">View Details</span>
	          <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	          <div class="clearfix"></div>
	        </div>
	      </a>
	    </div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-success">
	      <div class="panel-heading">
	        <div class="row">
	          <div class="col-xs-3">
	            <i class="fa fa-users fa-5x"></i>
	          </div>
	          <div class="col-xs-9 text-right">
	            <div class="huge"><?php echo $total_lessor; ?></div>
	            <div>Total Lessors!</div>
	          </div>
	        </div>
	      </div>
	      <a href="<?php echo site_url('admin/monitor/lessor'); ?>">
	        <div class="panel-footer">
	          <span class="pull-left">View Details</span>
	          <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	          <div class="clearfix"></div>
	        </div>
	      </a>
	    </div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-default">
	      <div class="panel-heading">
	        <div class="row">
	          <div class="col-xs-3">
	            <i class="fa fa-building fa-5x"></i>
	          </div>
	          <div class="col-xs-9 text-right">
	            <div class="huge"><?php echo $total_shop; ?></div>
	            <div>Total Shop!</div>
	          </div>
	        </div>
	      </div>
	      <a href="<?php echo site_url('admin/monitor/shops'); ?>">
	        <div class="panel-footer">
	          <span class="pull-left">View Details</span>
	          <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	          <div class="clearfix"></div>
	        </div>
	      </a>
	    </div>
		</div>
  </div>
</div>