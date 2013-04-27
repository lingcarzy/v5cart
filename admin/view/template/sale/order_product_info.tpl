<table class="list">
  <thead>
	<tr>
	  <td><?php echo $_['column_image']; ?></td>
	  <td class="left"><?php echo $_['column_product']; ?></td>
	  <td class="left"><?php echo $_['column_model']; ?></td>
	  <td class="right"><?php echo $_['column_quantity']; ?></td>
	  <td class="right">Shipped</td>
	  <td class="right"><?php echo $_['column_price']; ?></td>
	  <td class="right"><?php echo $_['column_total']; ?></td>
	</tr>
  </thead>
  <tbody>
	<?php foreach ($products as $product) { ?>
	<tr>
	  <td><img src="<?php echo $product['image']; ?>" /></td>
	  <td class="left"><a href="<?php echo $product['href']; ?>" target="_blank"><?php echo $product['name']; ?></a>
		<?php foreach ($product['option'] as $option) { ?>
		<br />
		<?php if ($option['type'] != 'file') { ?>
		&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
		<?php } else { ?>
		&nbsp;<small> - <?php echo $option['name']; ?>: <a href="<?php echo $option['href']; ?>"><?php echo $option['value']; ?></a></small>
		<?php } ?>
		<?php } ?></td>
	  <td class="left"><?php echo $product['model']; ?></td>
	  <td class="right"><?php echo $product['quantity']; ?></td>
	   <td class="right"><?php echo $product['shipped_qty']; ?></td>
	  <td class="right"><?php echo $product['price']; ?></td>
	  <td class="right"><?php echo $product['total']; ?></td>
	</tr>
	<?php } ?>
	<?php foreach ($vouchers as $voucher) { ?>
		<tr>
		  <td class="left"><a href="<?php echo $voucher['href']; ?>"><?php echo $voucher['description']; ?></a></td>
		  <td class="left"></td>
		  <td class="right">1</td>
		  <td class="right"><?php echo $voucher['amount']; ?></td>
		  <td class="right"><?php echo $voucher['amount']; ?></td>
		</tr>
		<?php } ?>
	  </tbody>
	  <?php foreach ($totals as $totals) { ?>
	  <tbody id="totals">
		<tr>
		  <td colspan="6" class="right"><?php echo $totals['title']; ?>:</td>
		  <td class="right"><?php echo $totals['text']; ?></td>
		</tr>
	  </tbody>
	  <?php } ?>
  </tbody>
</table>