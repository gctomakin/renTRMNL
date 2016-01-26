var items;
var formItems = [];
var total;
$(document).ready(function() {
	var itemsTemplate = _.template($('#item-list-template').html());
	processItem().then(function() {
		addDetail($('#item-id').val());
	});

	$('#reportrange').on('apply.daterangepicker', function (ev, picker) {
		processItem();
  });

	$('#reservation-form').submit(function(e) {
		e.preventDefault();
		if ($.isEmptyObject(formItems)) {
			errorMessage('You cannot submit because reservation is empty.');
		} else {
			var detail = $('#item-detail-container').clone().find('a').remove().end();
			$('#reservation-modal').find('.details').html(detail.html());
			$('#reservation-modal').find('#from-date').text(startDate);
			$('#reservation-modal').find('#to-date').text(endDate);
			$('#reservation-modal').modal('show');
		}
	});

	$('#btn-reset').click(function() {
		if (confirm('Are you sure to remove all?')) {
			removeAll();
			successMessage('All item detail has been removed');
			$('#item-detail-table > tbody > tr').fadeOut('slow', function() {
				$(this).remove();
			});
			computeTotal();
		}
	});

	$('#btn-confirm-modal').click(function() {
		var action = $('#reservation-form').attr('action');
		var form = {
			details: formItems,
			from: startDate,
			to: endDate,
			total: total
		};
		$('#reservation-modal').modal('hide');

		$.post(action, form, function(data) {
			console.log(data);
			if (data['result']) {
				window.location = reservationListUrl;
			} else {
				errorMessage(data['message']);
			}
		}, 'JSON');		
	});

	$('#reservation-modal').on('hidden.bs.modal', function() {
		$('#reservation-modal').find('.details').html("");
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
	return false ;
}
function addDetail(id) {
	var item = findItem(id);
	if (item === false) {
		errorMessage('Item Not Found');
	} else if (isExistFormItem(id)) {
		errorMessage('Item already exist in detail');
	} else {
		var detail = {
			desc: item['item_desc'],
			qty: item['item_qty'],
			rate: item['item_rate'],
			total: item['item_qty'] * item['item_rate'],
			rate_format: formatNumber(item['item_rate']),
			total_format: formatNumber(item['item_qty'] * item['item_rate']),
			id: id
		};
		formItems.push(detail);
		$('#item-detail-table > tbody').append(itemDetailTemplate(detail));	
		successMessage('Added new item for reservation');
		computeTotal();
	}
}

function removeDetail(id) {
	var item = findItem(id);
	if (item === false) {
		return false;
	} else {
		for (key in formItems) {
			if (formItems[key]['id'] == id) {
				delete formItems[key];
				return true;
			}
		}
	}
}

function removeAll() {
	for (key in formItems) {
		delete formItems[key];
	}
}

function isExistFormItem(id) {
	for (key in formItems) {
		if (formItems[key]['id'] == id) {
			return true;
		}		
	}
	return false;	
}

function computeTotal() {
	total = 0;
	for(key in formItems) {
		total += parseFloat(formItems[key]['total']);
	}
	$('#total-label').text(formatNumber(total));
}

var itemDetailTemplate = _.template($('#item-detail-template').html());
$(document).on('click', '.btn-add', function() {
	var id = $(this).data('item-id');
	addDetail(id);
});

$(document).on('click', '.btn-view', function() {
	var id = $(this).data('item-id');
});

$(document).on('click', '.item-remove', function() {
	var row = $(this).closest('tr');
	var id = row.data('item-detail-row');
	if (confirm("Are you sure to delete Item # : " + id + "?")) {
		if (removeDetail(id)) {
			successMessage('Item # ' + id + ' Deleted');
			row.fadeOut('slow', function() {
				$(this).remove();
			});
			computeTotal();
		} else {
			errorMessage('Item Not Found');	
		}
	}
});

$(document).on('click', '.change-qty', function() {
	var id = $(this).closest('tr').data('item-detail-row');
	var item = findItem(id);
	var qty = prompt("Change reservation quantity of item " + item['item_desc'] + ".", item['item_qty']);
	if (isNaN(qty)) {
		errorMessage('Quantity must be integer');
	} else if (qty > parseFloat(item['item_qty'])) {
		errorMessage('Quantity must not exceed the remaining quantity of item ' + item['item_desc']);
	} else if (qty <= 0) {
		errorMessage('Quantity must not less than 0');
	} else {
		successMessage('Quantity of item ' + item['item_desc'] + ' changed');
		var subtotal =  qty * item['item_rate'];
		for (key in formItems) {
			if (formItems[key]['id'] == id) {
				formItems[key]['qty'] = qty;
				formItems[key]['total'] = subtotal;
				computeTotal();
				$('[data-item-detail-row="'+id+'"]').find('span.label-subtotal').text(formatNumber(subtotal));
				$('[data-item-detail-row="'+id+'"]').find('span.label-qty').text(qty);
				return;
			}		
		}
	}
});