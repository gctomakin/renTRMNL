var items;
$(document).ready(function() {
	var itemsTemplate = _.template($('#item-list-template').html());
	processItem().then(function() {
		addDetail($('#item-id').val());
	});

	$('#reportrange').on('apply.daterangepicker', function (ev, picker) {
		processItem();
  });

	// $('#quantity').select2({
	// 	'placeholder': 'Choose remaining quantity'
	// });
	// var rate = $('#item-rate').val();
	// $('#quantity').change(function() {
	// 	var qty = $(this).val();
	// 	var total = formatNumber(qty * rate);
	// 	$('#total-info').text(total);
	// });

	$('#reserve-form').submit(function(e) {
		e.preventDefault();
		var form = $(this);
		var action = form.attr('action');
		$.post(action, form.serialize(), function(data) {
			if (data['result']) {
				form[0].reset();
				successMessage(data['message']);
				setTimeout(function() {
					window.location = reservationListUrl;
				}, 1000);
			} else {
				errorMessage(data['message']);
			}
		}, 'JSON');
	});

	function processItem() {
		var d = $.Deferred();
		var process;
		if ($('#shop-id').val() == "") {
			process = getItem($('#item-id').val(), startDate, endDate);
		} else {
			process = getShopItems($('#shop-id').val(), startDate, endDate);
		}
		process.then(function(data) {
			var itemData = {listItems: items};
			$('#item-list').html("");
			$('#item-list').append(itemsTemplate(itemData));
			d.resolve('success');
		});
		return d;
	}
});

function getShopItems(shopId, start, to) {
	var d = $.Deferred();
	$.post(shopItemsUrl, {shopId: shopId, start:start, to:to}, function(data) {
		if (data['result']) {
			items = data['items'];
			d.resolve('success');
		} else {
			errorMessage(data['message']);
			d.reject('failed');
		}
	}, 'JSON');
	return d.promise();
}

function getItem($id, start, to) {
	$.post(itemUrl, {id: id, start:start, to:to}, function(data) {
		if (data['result']) {

		} else {
			errorMessage(data['message']);
		}
	}, 'JSON');	
}

function findItem(id) {
	for (key in items) {
		if (items[key]['item_id'] == id) {
			return items[key];
		}		
	}
	return false;
}

function addDetail(id) {
	var item = findItem(id);
	if (item === false) {
		errorMessage('Item Not Found');
	} else {
		var detail = {
			desc: item['item_desc'],
			qty: item['item_qty'],
			rate: formatNumber(item['item_rate']),
			total: formatNumber(item['item_qty'] * item['item_rate']),
			id: id
		};
		$('#item-detail-table > tbody').append(itemDetailTemplate(detail));	
		successMessage('Added new item for reservation');
	}
}

var itemDetailTemplate = _.template($('#item-detail-template').html());
$(document).on('click', '.btn-add', function() {
	var id = $(this).data('item-id');
	addDetail(id);
});

$(document).on('click', '.btn-view', function() {
	var id = $(this).data('item-id');
});