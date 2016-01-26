<div class="container-fluid">
	<div class="row no-gutter">
		<div class="col-lg-12">
			<p><a href="<?php echo site_url('lessee/items') ?>"><i class="fa fa-cubes"></i> Back to Items</a></p>
		</div>
		<div class="col-lg-12">
  		<div class="panel panel-default">
  			<div class="panel-heading">Reservation Form</div>
  			<div class="panel-body">
  				<form action="" class="form-horizontal">
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
		  						<label for="shop" class="control-label col-lg-12">Shop : My Shop</label>
		  						<label for="shop" class="control-label col-lg-12">OWner : My Shop</label> 
	  						</div>
	  					</div>
	  				</div>
	  				<div class="form-group">
	  					<div class="col-lg-12">
	  						<div class="well">
	  							<table id="item-detail-table" class="table table-bordered">
	  								<thead>
	  									<th width="20"></th>
	  									<th>ITEM DESCRIPTION</th>
	  									<th>QUANTITY</th>
	  									<th class="text-right">RATE</th>
	  									<th class="text-right">SUBTOTAL</th>
	  								</thead>
	  								<tbody>
	  									<!-- <tr>
	  										<td></td>
	  										<td><?php echo $item['item_desc']; ?></td>
	  										<td><?php echo $item['item_qty']; ?> <a href="#"><i class="fa fa-caret-down"></i></a></td>
	  										<td class="text-right"><?php echo number_format($item['item_rate'], 2); ?></td>
	  										<td class="text-right"><?php echo number_format($item['item_rate'] * $item['item_qty'], 2); ?></td>
	  									</tr> -->
	  								</tbody>
	  								<tfoot>
	  									<tr>
	  										<th class="text-right" colspan="4">TOTAL</th>
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
	  						<button class="btn btn-default pull-right" type="reset">Reset</button>
	  					</div>
	  				</div>
	  				<div class="form-group">
	  					<hr>
	  					<input type="hidden" id="shop-id" value="<?php echo $item['shop_id']; ?>">
	  					<input type="hidden" id="item-id" value="<?php echo $item['item_id']; ?>">
	  				</div>
	  			</form>
	  			<div class="col-lg-12" id="item-list">
					</div>
  			</div>
  		</div>
  	</div>
  </div>
</div>
<script type="text/javascript">
	var reservationListUrl = "<?php echo site_url('lessee/reserved'); ?>";
	var shopItemsUrl = "<?php echo site_url('items/shopItems'); ?>";
	var itemUrl = "<?php echo site_url('items/find'); ?>";
</script>
<script type="text/template" id="item-detail-template">
	<tr>
		<td><a href="javascript:;"><i class="fa fa-times"></i></a></td>
		<td><%-desc%></td>
		<td><%-qty%> <a href="#"><i class="fa fa-caret-down"></i></a></td>
		<td class="text-right"><%-rate%></td>
		<td class="text-right"><%-total%></td>
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
	        <dt>Rate <%-listItem.item_rate%></dt>
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