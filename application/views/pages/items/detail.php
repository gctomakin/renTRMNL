<div class="row">
	<div class="col-lg-12">
		<form method="POST" id="item-detail-form">
			<table id="item-detail-table" class="table table-border">
				<thead>
					<tr>
						<th>TYPE</th>
						<th>SIZE</th>
						<th>BRAND</th>
						<th>COLOR</th>
						<th width="200">OPTION</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($detail as $d) { ?>
					<tr data-item-detail-id="<?php echo $d->id; ?>">
						<td class="item-type"><?php echo $d->type; ?></td>
						<td class="item-size"><?php echo $d->size; ?></td>
						<td class="item-brand"><?php echo $d->brand; ?></td>
						<td class="item-color"><?php echo $d->color; ?></td>
						<td>
							<a class="btn btn-xs btn-success item-detial-edit-btn"><span class="fa fa-pencil"></span> Edit</a>
							<a class="btn btn-xs btn-primary item-detail-remove-btn"><span class="fa fa-times"></span> Remove</a>
							<input type="hidden" class="item-detail-property" data-item-detail-id="<?php echo $d->id; ?>">
						</td>
					</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td><input type="text" required name="type" class="form-control" placeholder="Item Type"></td>
						<td><input type="text" required name="size" class="form-control" placeholder="Item Size"></td>
						<td><input type="text" required name="brand" class="form-control" placeholder="Item Brand"></td>
						<td><input type="text" required name="color" class="form-control" placeholder="Item Color"></td>
						<td>
							<button id="btn-save-detail" class="btn btn-success btn-sm">save item detail</button>
							<button id="btn-reset-detail" type="reset" class="btn btn-default btn-sm">reset</button>
						</td>
					</tr>
				</tfoot>
			</table>
			<input type="hidden" id="itemId" name="itemId" value="<?php echo $itemId; ?>">
			<input type="hidden" id="itemDetailId" name="id" value="">
		</form>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#item-detail-form').submit(function(e) {
			e.preventDefault();
			$.post("<?php echo site_url('items/detailSave') ?>", $('#item-detail-form').serialize(), function(data) {
				if (data['result']) {
					successMessage(data['message']);
					$('#item-detail-modal').find('.modal-body').html(data['view']);
				} else {
					errorMessage(data['message']);
				}
			}, 'JSON');
		});

		$('.item-detial-edit-btn').click(function() {
			var id = $(this).siblings('.item-detail-property').data('item-detail-id');
			var parent = $('#item-detail-table tbody tr[data-item-detail-id="'+id+'"]');
			var detail = {
				type : parent.find('.item-type').text(),
				size : parent.find('.item-size').text(),
				brand : parent.find('.item-brand').text(),
				color : parent.find('.item-color').text()
			};
			var parentInput = $('#item-detail-table tfoot tr');
			parentInput.find('input[name="type"]').val(detail.type);
			parentInput.find('input[name="size"]').val(detail.size);
			parentInput.find('input[name="brand"]').val(detail.brand);
			parentInput.find('input[name="color"]').val(detail.color);
			$('#itemDetailId').val(id);
		});

		$('.item-detail-remove-btn').click(function() {
			if (confirm("Are you sure to delete this detail?")) {
				var id = $(this).siblings('.item-detail-property').data('item-detail-id');
				$.post("<?php echo site_url('items/detailRemove') ?>", {id: id}, function(data) {
					if (data['result']) {
						successMessage(data['message']);
						$('#item-detail-table tbody tr[data-item-detail-id="'+id+'"]').fadeOut('slow');
					} else {
						errorMessage(data['message']);
					}
				}, 'JSON');	
			}
		});

		$('#btn-reset-detail').click(function() {
			$('#itemDetailId').val('');
		});
	});
</script>