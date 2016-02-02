<table class="monitor-table table">
	<thead>
		<tr>
			<th>ID</th>
			<th>IMG</th>
			<th>FULL NAME</th>
			<th>EMAIL</th>
			<th>CONTACT</th>
			<th>RENTED</th>
			<th>PENALTY</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($lessees as $lessee) { ?>
		<tr>
			<td><?php echo $lessee['id']; ?></td>
			<td><img src="<?php echo $lessee['image']; ?>" alt=""></td>
			<td><?php echo $lessee['fullname']; ?></td>
			<td><?php echo $lessee['email']; ?></td>
			<td><?php echo $lessee['contact']; ?></td>
			<td><?php echo $lessee['total_reservation'] ?></td>
			<td><?php echo $lessee['total_penalty'] ?></td>
			<td>NA</td>
		</tr>
		<?php } ?>
	</tbody>
</table>