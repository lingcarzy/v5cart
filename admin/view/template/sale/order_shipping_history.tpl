<?php if (isset($error)) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<?php if (isset($success)) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<table class="list">
	<thead>
		<td class="left" width="100"><?php echo $_['text_ship_date']; ?></td>
		<td><?php echo $_['text_ship_package']; ?></td>
		<td><?php echo $_['text_ship_carrier']; ?></td>
		<td><?php echo $_['text_track_number']; ?></td>
		<td><?php echo $_['text_ship_remark']; ?></td>
	</thead>
	<tbody>
		<?php foreach ($histories as $his) { ?>
		<tr>
			<td><?php echo date($_['date_format_short'], $his['ship_date']); ?></td>
			<td>
				<?php foreach($his['products'] as $p) { ?>
				<p><small><?php echo $p['name']; ?></small><br>
					<?php echo $p['model']; ?> - <span class="green"><?php echo $p['shipped_qty']; ?></span> / <span class="red"><?php echo $p['quantity']; ?></span></p>
				<?php } ?>
			</td>
			<td><?php echo $his['ship_carrier']; ?></td>
			<td>
				<?php if ($his['tracking_link']) { ?>
					<a href="<?php echo $his['tracking_link']?>" target="_blank"><?php echo $his['track_number']; ?></a>
				<?php } else { ?>
					<?php echo $his['track_number']; ?>
				<?php } ?>
			</td>
			<td><?php echo $his['remark']; ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>