<div class="container-fluid">
  <div class="row">
		<div class="col-md-12">
      <section class="panel panel-red">
        <header class="panel-heading">
          <strong><span class="fa fa-line-chart"></span> Lessors</strong> 
          <div class='pull-right'>
            <span id='interval'>0</span> / <span id='total-range'>0</span> 
            <span id='type-range'>days</span>
          </div>
        </header>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-12">
              <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                  <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                  <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
              </div>
            </div>
            <div class="col-md-11 col-sm-11">
              <canvas id="canvas-line-graph"></canvas>
            </div>
          </div>
        </div>
      </section>
    </div>
    <div class="col-md-12">
      <section class="panel panel-default">
        <header class="panel-heading"><span class="fa fa-list"></span> Details</header>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-12" id="details"></div>
          </div>
        </div>
      </section>
    </div>
	</div>
</div>
<script type="text/javascript">
	var reportUrl = "<?php echo site_url('reports/lessors'); ?>";
</script>