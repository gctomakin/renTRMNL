<div class="container-fluid">
	<div class="row no-gutter">
		<div class="col-lg-12">
			<p><a href="<?php echo site_url('lessee/items') ?>"><i class="fa fa-cubes"></i> Back to Items</a></p>
		</div>
		<div class="col-lg-12">
  		<div class="panel panel-default">
  			<div class="panel-heading">Reservation Form</div>
  			<div class="panel-body">
  				<form action="<?php echo $action; ?>" id="reservation-form" class="form-horizontal">
	  				<div class="form-group">
	  					<div class="col-lg-6">
	  						<label for="date">Date Duration : </label>
	              <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                  <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                  <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
              	</div>
	  					</div>
	  					<div class="col-lg-6">
	  						<div class="row">
		  						<label for="shop" class="control-label col-lg-12">Shop : 
		  							<a href="#"><?php echo $item['shop_name']; ?></a>
		  						</label>
		  						<label for="shop" class="control-label col-lg-12">OWner : 
		  							<a href="#"><?php echo $item['subscriber_fname'] ;?></a>
		  						</label> 
	  						</div>
	  					</div>
	  				</div>
	  				<div class="form-group">
	  					<div class="col-lg-12">
	  						<div class="well" id="item-detail-container">
	  							<table id="item-detail-table" class="table table-bordered">
	  								<thead>
	  									<th width="20"></th>
	  									<th>ITEM DESCRIPTION</th>
	  									<th class="text-right">RATE</th>
	  									<th class="text-right">DURATION AMOUNT</th>
	  									<th class="text-right">QUANTITY</th>
	  									<th class="text-right">SUBTOTAL</th>
	  								</thead>
	  								<tbody></tbody>
	  								<tfoot>
	  									<tr>
	  										<th class="text-right" colspan="5">TOTAL</th>
	  										<td class="text-right"><span id="total-label">0.00</span></td>
	  									</tr>
	  								</tfoot>
	  							</table>
	  						</div>
	  					</div>
	  				</div>
	  				<div class="form-group">
	  					<div class="col-lg-10">
	  						<h4>Shop's item list below : </h4>
	  					</div>
	  					<div class="col-lg-1">
	  						<button class="btn btn-primary pull-right" type="submit">Submit Reservation</button>
	  					</div>
	  					<div class="col-lg-1">
	  						<button class="btn btn-default pull-right" id="btn-reset" type="reset">Reset</button>
	  					</div>
	  				</div>
	  				<div class="form-group">
	  					<hr>
	  					<input type="hidden" id="shop-id" value="<?php echo $item['shop_id']; ?>">
	  					<input type="hidden" id="item-id" value="<?php echo $item['item_id']; ?>">
	  					<input type="hidden" id="sub-id" value="<?php echo $item['subscriber_id']; ?>">
	  					<input type="hidden" id="min-date" value="<?php echo $startDate; ?>">
	  				</div>
	  			</form>
	  			<div class="col-lg-12" id="item-list">
					</div>
  			</div>
  		</div>
  	</div>
  </div>
</div>
<div class="modal modal-fullscreen fade" id="reservation-modal" tabindex='-1'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="reservation-modal-title" >Reservation Confirmation</i></h4>
      </div>
      <div class="modal-body">
      	<h2><i class="fa fa-info"></i> Are you sure about this reservation?</h2>
      	<div class="details"></div>
      	<div class="other-details">
      		<p>From : <span id="from-date"></span></p>
      		<p>Until : <span id="to-date"></span></p>
      	</div>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-default" id="btn-cancel-modal" data-dismiss="modal">Cancel</button>
      	<button type="button" class="btn btn-primary" id="btn-confirm-modal">Submit Reservation</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script type="text/javascript">
	var reservationListUrl = "<?php echo site_url('lessee/reserved'); ?>";
	var shopItemsUrl = "<?php echo site_url('items/shopItems'); ?>";
	var itemUrl = "<?php echo site_url('items/find'); ?>";
	var modeUrl = "<?php echo site_url('items/getMode'); ?>";
</script>
<script type="text/template" id="item-detail-template">
	<tr data-item-detail-row="<%-id%>">
		<td><a href="javascript:;" class="item-remove"><i class="fa fa-times"></i></a></td>
		<td><%-desc%></td>
		<td class="text-right"><%-rate_format%> / <%-mode%></td>
		<td class="text-right"><%-amount%></td>
		<td class="text-right"><span class="label-qty"><%-qty%></span> <a href="javascript:;" class="change-qty"><i class="fa fa-caret-down"></i></a></td>
		<td class="text-right"><span class="label-subtotal"><%-total_format%></span></td>
	</tr>
</script>
<script type="text/template" id="item-list-template">
	<% _.each( listItems, function( listItem ){ %>
	<div class="col-md-3 item-panel">
	  <div class="thumbnail">
	  	<img src="<%-listItem.item_pic%>" alt="<%-listItem.item_desc%>" style="width:250px; height: 150px;">
	  	<div class="caption">
	  		<h4><%-listItem.item_desc%></h4>
	  		<dl>
	        <dt>₱ <%-listItem.item_rate%> / <%-listItem.mode_label%></dt>
	        <dt><%-listItem.item_qty%> pcs</dt>
	      </dl>
	  	</div>
	  	<div class="menu">
	  		<button class="btn btn-primary btn-xs btn-add" data-item-id="<%-listItem.item_id%>">
	  			<i class="fa fa-plus"></i> Add
	  		</button>
	  		<button class="btn btn-default btn-xs btn-view" data-item-id="<%-listItem.item_id%>">
	  			<i class="fa fa-eye"></i> View
	  		</button>
	  	</div>
		</div>
	</div>
	<% }); %>
</script>