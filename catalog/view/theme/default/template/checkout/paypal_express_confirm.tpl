<?php echo $header; ?>
<div id="content-area">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
<div class="checkout-product">
  <table>
    <thead>
      <tr>
		<td class="name"><?php echo $column_image; ?></td>
        <td class="name"><?php echo $column_name; ?></td>
        <td class="model"><?php echo $column_model; ?></td>
        <td class="quantity"><?php echo $column_quantity; ?></td>
        <td class="price"><?php echo $column_price; ?></td>
        <td class="total"><?php echo $column_total; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product) { ?>
      <tr>
		<td><img src="<?php echo $product['image']; ?>"></td>
        <td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
          <?php foreach ($product['option'] as $option) { ?>
          <br />
          &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
          <?php } ?></td>
        <td class="model"><?php echo $product['model']; ?></td>
        <td class="quantity"><?php echo $product['quantity']; ?></td>
        <td class="price"><?php echo $product['price']; ?></td>
        <td class="total"><?php echo $product['total']; ?></td>
      </tr>
      <?php } ?>
      <?php foreach ($vouchers as $voucher) { ?>
      <tr>
        <td class="name"><?php echo $voucher['description']; ?></td>
        <td class="model"></td>
        <td class="quantity">1</td>
        <td class="price"><?php echo $voucher['amount']; ?></td>
        <td class="total"><?php echo $voucher['amount']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php foreach ($totals as $total) { ?>
      <tr>
        <td colspan="5" class="price"><b><?php echo $total['title']; ?>:</b></td>
        <td class="total"><?php echo $total['text']; ?></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>

<div id="shipping-info">
<table width="100%" cellpadding="5" bgcolor="#ECEBE8" cellspacing="1">
  <tr bgcolor="#F7F7F7"><td colspan="2"><b>Shipping Address</b></td></tr>
  <tr bgcolor="#ffffff">
	<th width="100" align="right">To</th>
	<td><?php echo $order['shipping_firstname'];?> <?php echo $order['shipping_lastname'];?></td>
  </tr>
  <tr bgcolor="#ffffff">
	<th align="right">Address</th>
	<td><p><?php echo $order['shipping_address_1'];?> <?php echo $order['shipping_address_2'];?></p>
    <p><?php echo $order['shipping_city'];?>, <?php echo $order['shipping_zone'];?> <?php echo $order['shipping_postcode'];?></p>
    <p><?php echo $order['shipping_country'];?></p></td>
  </tr>
  <tr bgcolor="#ffffff">
	<th align="right">Telephone</th>
	<td><?php echo $order['telephone'];?></td>
  </tr>
</table>

<p><br><?php echo $text_shipping_method; ?></p>
<table class="form">
  <?php foreach ($shipping_methods as $shipping_method) { ?>
  <tr>
    <td colspan="3"><b><?php echo $shipping_method['title']; ?></b></td>
  </tr>
  <?php foreach ($shipping_method['quote'] as $quote) { ?>
  <tr>
    <td style="width: 1px;"><?php if ($quote['code'] == $code || !$code) { ?>
      <?php $code = $quote['code']; ?>
      <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" checked="checked" />
      <?php } else { ?>
      <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" />
      <?php } ?></td>
    <td><label for="<?php echo $quote['code']; ?>"><?php echo $quote['title']; ?></label></td>
    <td style="text-align: right;"><label for="<?php echo $quote['code']; ?>"><?php echo $quote['text']; ?></label></td>
  </tr>
  <?php } } ?>
</table>
<p>
<b><?php echo $text_comments; ?></b>
<textarea name="comment" rows="4" style="width: 98%;"><?php echo $order["comment"]; ?></textarea>
</p>
<div class="buttons">
  <div class="right"><input type="button" id="button-confirm" class="button" value="<?php echo $button_confirm; ?>"></div>
</div>
<div id="pe_error"></div>
</div>

</div>
<script type="text/javascript"><!--
function paypal_express() {
	$.ajax({ 
		type: 'POST',
		url: '<? echo $continue; ?>',
		data: $('#shipping-info input[type=\'radio\']:checked, #shipping-info textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-confirm').unbind('click');
			$('#button-confirm').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		error: function(a, b, c) {
			alert(a.responseText);
		},
		success: function(json) {
			if (json['redirect']) {
				location = json['redirect'];
			}
			else if(json['error']) {
				$('#pe_error').html(json['error']);
				$('#button-confirm').bind('click', paypal_express);
				$('.wait').remove();
			}
		}
	});
}

$('#button-confirm').bind('click', paypal_express);
//--></script> 
<?php echo $footer; ?>