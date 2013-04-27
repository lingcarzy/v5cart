<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php echo bread_crumbs(); ?>
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
	  <a href="<?php echo UA('sale/return/insert'); ?>" class="button"><?php echo $_['button_insert']; ?></a>
	  <a onclick="$('form').submit();" class="button"><?php echo $_['button_delete']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('sale/return/delete'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="right"><?php if ($sort == 'r.return_id') { ?>
                <a href="<?php echo $sort_return_id; ?>" class="<?php echo $order; ?>"><?php echo $_['column_return_id']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_return_id; ?>"><?php echo $_['column_return_id']; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'r.order_id') { ?>
                <a href="<?php echo $sort_order_id; ?>" class="<?php echo $order; ?>"><?php echo $_['column_order_id']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_order_id; ?>"><?php echo $_['column_order_id']; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'customer') { ?>
                <a href="<?php echo $sort_customer; ?>" class="<?php echo $order; ?>"><?php echo $_['column_customer']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_customer; ?>"><?php echo $_['column_customer']; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'r.product') { ?>
                <a href="<?php echo $sort_product; ?>" class="<?php echo $order; ?>"><?php echo $_['column_product']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_product; ?>"><?php echo $_['column_product']; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'r.model') { ?>
                <a href="<?php echo $sort_model; ?>" class="<?php echo $order; ?>"><?php echo $_['column_model']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_model; ?>"><?php echo $_['column_model']; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo $order; ?>"><?php echo $_['column_status']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $_['column_status']; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'r.date_added') { ?>
                <a href="<?php echo $sort_date_added; ?>" class="<?php echo $order; ?>"><?php echo $_['column_date_added']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_added; ?>"><?php echo $_['column_date_added']; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'r.date_modified') { ?>
                <a href="<?php echo $sort_date_modified; ?>" class="<?php echo $order; ?>"><?php echo $_['column_date_modified']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_modified; ?>"><?php echo $_['column_date_modified']; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $_['column_action']; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
              <td></td>
              <td align="right"><input type="text" name="filter_return_id" value="<?php echo $filter_return_id; ?>" size="4" style="text-align: right;" /></td>
              <td align="right"><input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" size="4" style="text-align: right;" /></td>
              <td><input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" /></td>
              <td><input type="text" name="filter_product" value="<?php echo $filter_product; ?>" /></td>
              <td><input type="text" name="filter_model" value="<?php echo $filter_model; ?>" /></td>
              <td><select name="filter_return_status_id">
                  <option value="*"></option>
                  <?php foreach ($return_statuses as $return_status) { ?>
                  <?php if ($return_status['return_status_id'] == $filter_return_status_id) { ?>
                  <option value="<?php echo $return_status['return_status_id']; ?>" selected="selected"><?php echo $return_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $return_status['return_status_id']; ?>"><?php echo $return_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td><input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" size="12" class="date" /></td>
              <td><input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" size="12" class="date" /></td>
              <td align="right"><a onclick="filter();" class="button"><?php echo $_['button_filter']; ?></a></td>
            </tr>
            <?php if ($returns) { ?>
            <?php foreach ($returns as $return) { ?>
            <tr onmouseover="this.className='on';" onmouseout="this.className='';">
              <td style="text-align: center;"><?php if ($return['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $return['return_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $return['return_id']; ?>" />
                <?php } ?></td>
              <td class="right"><?php echo $return['return_id']; ?></td>
              <td class="right"><?php echo $return['order_id']; ?></td>
              <td class="left"><?php echo $return['customer']; ?></td>
              <td class="left"><?php echo $return['product']; ?></td>
              <td class="left"><?php echo $return['model']; ?></td>
              <td class="left"><?php echo $return['status']; ?></td>
              <td class="left"><?php echo $return['date_added']; ?></td>
              <td class="left"><?php echo $return['date_modified']; ?></td>
              <td class="right"><?php foreach ($return['action'] as $action) { ?>
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
	url = '<?php echo UA('sale/return'); ?>&filter_reset=1';

	var filter_return_id = $('input[name=\'filter_return_id\']').attr('value');

	if (filter_return_id) {
		url += '&filter_return_id=' + encodeURIComponent(filter_return_id);
	}

	var filter_order_id = $('input[name=\'filter_order_id\']').attr('value');

	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}

	var filter_customer = $('input[name=\'filter_customer\']').attr('value');

	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}

	var filter_product = $('input[name=\'filter_product\']').attr('value');

	if (filter_product) {
		url += '&filter_product=' + encodeURIComponent(filter_product);
	}

	var filter_model = $('input[name=\'filter_model\']').attr('value');

	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}

	var filter_return_status_id = $('select[name=\'filter_return_status_id\']').attr('value');

	if (filter_return_status_id != '*') {
		url += '&filter_return_status_id=' + encodeURIComponent(filter_return_status_id);
	}

	var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');

	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}

	var filter_date_modified = $('input[name=\'filter_date_modified\']').attr('value');

	if (filter_date_modified) {
		url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
	}

	location = url;
}
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
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<?php echo $footer; ?>