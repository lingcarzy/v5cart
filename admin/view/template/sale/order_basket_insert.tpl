<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php echo bread_crumbs(); ?> :: Insert
  </div>
  
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/order.png" alt="" /><?php echo $_['heading_title'];?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $_['button_insert']; ?></span></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('sale/order_basket/insert');?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
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
              <td></td>
              <td>
				  <select name="filter_order_status_id">
					  <option value="*"></option>
					  <?php echo form_select_option($order_statuses, $filter_order_status_id, true);?>
				  </select>
			  </td>
              <td></td>
              <td colspan="3"><input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" size="12" class="date" /> - <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" size="12" class="date" /></td>
              <td align="right"><a onclick="filter();" class="button"><span>Filter</span></a></td>
            </tr>
            <?php if ($orders) { ?>
            <?php foreach ($orders as $order) { ?>
            <tr onmouseover="this.className='on';" onmouseout="this.className='';">
              <td class="right"><?php echo $order['order_id']; ?></td>
              <td class="left"><?php echo $order['customer']; ?></td>
              <td class="left"><?php echo $order['status']; ?></td>
              <td class="right"><?php echo $order['total']; ?></td>
              <td class="left" title="<?php echo $order['date_modified']; ?>"><?php echo $order['date_added']; ?></td>
			  <td><?php echo $order['payment_method']; ?></td>
			  <td><?php echo $order['shipping_method']; ?></td>
              <td class="right">[<a href="<?php echo UA('sale/order/info')?>&order_id=<?=$order['order_id']?>" target="_blank">View</a>]</td>
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
	url = '<?php echo UA('sale/order_basket/insert'); ?>';
	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');
	
	if (filter_order_status_id != '*') {
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

//--></script>  
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script> 
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script> 
<?php echo $footer; ?>