<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
   <?php echo bread_crumbs(); ?> :: Orders
  </div>
	<?php if (isset($success)) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/order.png" alt="" /><?php echo $_['heading_title']; ?></h1>
      <div class="buttons">Order ID: <input type="text" name="input_order_id" size="15">&nbsp;&nbsp;<a onclick="addToPurchase()" class="button"><span>Add</span></a>
	  <a onclick="$('#form').submit();" class="button"><span>Remove</span></a>
	  |
	  <a onclick="printInvoice()" class="button"><span>Print Invoice</span></a>
	  <a href="<?php echo UA('sale/order_basket/skus', "basket_id=$basket_id");?>" target="_blank" class="button"><span>SKUs</span></a>
	  </div>
    </div>
    <div class="content">
	  <form action="<?=UA('sale/order_basket/orders', "basket_id=$basket_id");?>" method="post" enctype="multipart/form-data" id="form2">
		<input type="hidden" name="order_id" id="order_id" value="0">
	  </form>
      <form action="<?=UA('sale/order_basket/orders', "basket_id=$basket_id");?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
			  <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="right">Order ID</td>
              <td class="left">Customer</td>
              <td class="left">Status</td>
              <td class="right">Total</td>
              <td class="left">Date Added</td>
			  <td>Payment</td>
			  <td>Shipping</td>
              <td class="right">Action</td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
			  <td></td>
              <td align="right"><input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" size="4" style="text-align: right;" /></td>
              <td><input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" size="10"/></td>
              <td><select name="filter_order_status_id">
                <option value=""></option>
				<?php echo form_select_option($order_statuses, $filter_order_status_id, true);?>
                </select></td>			  
              <td></td>
              <td colspan="3"><input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" size="12" class="date" /> - <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" size="12" class="date" />
				  &nbsp;&nbsp;
			  Model: <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" title="Product Model"></td>
              <td align="right"><a onclick="filter();" class="button"><span>Filter</span></a></td>
            </tr>
            <?php if ($orders) { ?>
            <?php foreach ($orders as $order) { ?>
            <tr onmouseover="this.className='on';" onmouseout="this.className='';">
			  <td style="text-align: center;">
                <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
              </td>
              <td class="right"><?php echo $order['order_id']; ?></td>
              <td class="left"><?php echo $order['customer']; ?></td>
              <td class="left"><?php echo $order['status']; ?></td>
              <td class="right"><?php echo $order['total']; ?></td>
              <td class="left" title="<?php echo $order['date_modified']; ?>"><?php echo $order['date_added']; ?></td>
			  <td><?php echo $order['payment_method']; ?></td>
			  <td><?php echo $order['shipping_method']; ?></td>
              <td class="right">[<a href="<?php echo UA('sale/order/info')?>&order_id=<?=$order['order_id']?>" target="_blank">View</a>] [<a href="<?php echo UA('sale/order/invoice')?>&order_id=<?=$order['order_id']?>" target="_blank">Invoice</a>]</td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="18"></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = '<?php echo UA('sale/order_basket/orders'); ?>&basket_id=<?=$basket_id?>&filter_reset=1';
	
	var filter_model = $('input[name=\'filter_model\']').attr('value');
	
	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}
	
	var filter_order_id = $('input[name=\'filter_order_id\']').attr('value');
	
	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}
	
	var filter_customer = $('input[name=\'filter_customer\']').attr('value');
	
	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}
	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');
	
	if (filter_order_status_id) {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}
	
	var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	
	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

	location = url;
}

function addToPurchase() {
	var order_id = $('input[name=\'input_order_id\']').attr('value');
	$('#order_id').val(order_id);
	$('#form2').submit();
}

function printInvoice() {
	if ($('input[name*=\'selected\']:checked').length > 0) {
		$('#form').attr('action', '<?php echo UA('sale/order/invoice'); ?>');
	}
	else {
		$('#form').attr('action', '<?php echo UA('sale/order/invoice', "basket_id=$basket_id"); ?>');
	}
	$('#form').attr('target', '_blank');
	$('#form').submit();
}
//--></script>  
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script> 
<?php echo $footer; ?>