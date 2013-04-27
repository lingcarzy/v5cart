<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php echo bread_crumbs();?>
  </div>
  <?php if (isset($error_warning)) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/order.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a onclick="$('#form').attr('action', '<?php echo UA('sale/order/invoice'); ?>'); $('#form').attr('target', '_blank'); $('#form').submit();" class="button"><?php echo $_['button_invoice']; ?></a>
	  <a href="<?php echo UA('sale/order/insert'); ?>" class="button"><?php echo $_['button_insert']; ?></a>
	  <a onclick="$('#form').attr('action', '<?php echo UA('sale/order/delete'); ?>'); $('#form').attr('target', '_self'); $('#form').submit();" class="button"><?php echo $_['button_delete']; ?></a></div>
    </div>
    <div class="content">
      <form action="" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="right"><?php if ($sort == 'o.order_id') { ?>
                <a href="<?php echo $sort_order; ?>" class="<?php echo $order; ?>"><?php echo $_['column_order_id']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_order; ?>"><?php echo $_['column_order_id']; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'customer') { ?>
                <a href="<?php echo $sort_customer; ?>" class="<?php echo $order; ?>"><?php echo $_['column_customer']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_customer; ?>"><?php echo $_['column_customer']; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo $order; ?>"><?php echo $_['column_status']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $_['column_status']; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'o.total') { ?>
                <a href="<?php echo $sort_total; ?>" class="<?php echo $order; ?>"><?php echo $_['column_total']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_total; ?>"><?php echo $_['column_total']; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'o.date_added') { ?>
                <a href="<?php echo $sort_date_added; ?>" class="<?php echo $order; ?>"><?php echo $_['column_date_added']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_added; ?>"><?php echo $_['column_date_added']; ?></a>
                <?php } ?></td>
			  <td>Payment Method</td>
			  <td>Shipping Method</td>
              <td class="right"><?php echo $_['column_action']; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
              <td></td>
              <td align="right"><input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" size="4" style="text-align: right;" /></td>
              <td><input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" size="10"/></td>
              <td><select name="filter_order_status_id">
                  <option value="*"></option>
                  <?php if ($filter_order_status_id == '0') { ?>
                  <option value="0" selected="selected"><?php echo $_['text_missing']; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $_['text_missing']; ?></option>
                  <?php } ?>
				  <?php echo form_select_option($order_statuses, $filter_order_status_id, true);?>
                </select></td>
              <td align="right"><input type="text" name="filter_total" value="<?php echo $filter_total; ?>" size="4" style="text-align: right;" /></td>
              <td colspan="3">
			  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" size="12" class="date" />
			  -
			  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" size="12" class="date" />
			  &nbsp;&nbsp;
			  Model: <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" title="Product Model">
			  </td>
              <td align="right"><a onclick="filter();" class="button"><?php echo $_['button_filter']; ?></a></td>
            </tr>
            <?php if ($orders) { ?>
            <?php foreach ($orders as $order) { ?>
            <tr onmouseover="this.className='on';" onmouseout="this.className='';">
              <td style="text-align: center;"><?php if ($order['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
                <?php } ?></td>
              <td class="right">
			  <a class="tipTrigger" href="javascript:;" rel="<?php echo UA('sale/order/product_info'); ?>&order_id=<?php echo $order['order_id']; ?>"><?php echo $order['order_id']; ?></a>
			  <?php if ($order['invoice_no'] > 0) {?>
			  <br /> <small><?php echo $order['invoice_prefix'].$order['invoice_no']; ?></small>
			  <?php } ?>
			  </td>
              <td class="left">
			  <a href="mailto:<?php echo $order['email'];?>" title="<?php echo $order['email'];?>"><?php echo $order['customer']; ?></a>
			  <br /><small><?php echo $order['city']; ?>, <?php echo $order['country']; ?></small>
			  </td>
              <td class="left">
			  <a class="tipTrigger" href="javascript:;" rel="<?php echo UA('sale/order/history'); ?>&order_id=<?php echo $order['order_id']; ?>">
			  <?php echo $order['status']; ?>
			  </a>
			  </td>
              <td class="right"><?php echo $order['total']; ?></td>
              <td class="left" title="<?php echo $_['column_date_modified']; ?>: <?php echo $order['date_modified']; ?>"><?php echo $order['date_added']; ?></td>
			  <td class="left"><?php echo $order['payment_method']; ?></td>
			  <td class="left">
			  <a class="tipTrigger" href="javascript:;" rel="<?php echo UA('sale/order/shipping_history'); ?>&order_id=<?php echo $order['order_id']; ?>">
			  <?php echo $order['shipping_method']; ?>
			  </a>
			  </td>
              <td class="right"><?php foreach ($order['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="10"><?php echo $_['text_no_results']; ?></td>
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
	url = '<?php echo UA('sale/order'); ?>&filter_reset=1';

	var filter_order_id = $('input[name=\'filter_order_id\']').attr('value');

	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}

	var filter_customer = $('input[name=\'filter_customer\']').attr('value');

	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}

	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');

	if (filter_order_status_id != '*') {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}

	var filter_total = $('input[name=\'filter_total\']').attr('value');

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}

	var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

	var filter_model = $('input[name=\'filter_model\']').attr('value');

	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}

	location = url;
}
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
});

$(".tipTrigger").powerFloat({
	eventType: "click",
	targetMode: "ajax"
});
//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<script type="text/javascript"><!--
$.widget('custom.catcomplete', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this, currentCategory = '';

		$.each(items, function(index, item) {
			if (item.category != currentCategory) {
				ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');

				currentCategory = item.category;
			}

			self._renderItem(ul, item);
		});
	}
});

$('input[name=\'filter_customer\']').catcomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: '<?php echo UA('sale/customer/autocomplete'); ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						category: item.customer_group,
						label: item.name,
						value: item.customer_id
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'filter_customer\']').val(ui.item.label);

		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});
//--></script>
<?php echo $footer; ?>