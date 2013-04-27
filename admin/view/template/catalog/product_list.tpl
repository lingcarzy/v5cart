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
      <h1><img src="view/image/product.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
		<span style="margin-right:5px;">
		<select onchange="filter()" name="filter_category_id">
			<option value="*"> -- Category -- </option>
			<?php echo form_select_option($categories, $filter_category_id, null, 'category_id', 'name');?>
		</select>
		<select onchange="filter()" name="filter_supplier_id">
			<option value="*"> -- Supplier -- </option>
			<?php echo form_select_option($suppliers, $filter_supplier_id, true);?>
		</select>
		</span>
		 |
		<?php echo $_['column_status']; ?>:
		<select name="switch" onchange="$('#status').val(this.value);">
			<option></option>
			<option value="1"><?php echo $_['text_enabled'];?></option>
			<option value="0"><?php echo $_['text_disabled'];?></option>
		</select>
		<a onclick="$('#form').attr('action', '<?php echo UA('catalog/product/update_status'); ?>'); $('#form').submit();" class="button"><?php echo $_['button_update']; ?></a>
		 |
		<a href="<?php echo UA('catalog/product/insert'); ?>" class="button"><?php echo $_['button_insert']; ?></a>
		<a onclick="$('#form').attr('action', '<?php echo UA('catalog/product/copy'); ?>'); $('#form').submit();" class="button"><?php echo $_['button_copy']; ?></a>
		<a onclick="$('form').submit();" class="button"><?php echo $_['button_delete']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('catalog/product/delete'); ?>" method="post" id="form">
		<input type="hidden" name="status" id="status" value="1">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="center"><?php echo $_['column_image']; ?></td>
              <td class="left"><?php if ($sort == 'pd.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo $order; ?>"><?php echo $_['column_name']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $_['column_name']; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.model') { ?>
                <a href="<?php echo $sort_model; ?>" class="<?php echo $order; ?>"><?php echo $_['column_model']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_model; ?>"><?php echo $_['column_model']; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.price') { ?>
                <a href="<?php echo $sort_price; ?>" class="<?php echo $order; ?>"><?php echo $_['column_price']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_price; ?>"><?php echo $_['column_price']; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'p.quantity') { ?>
                <a href="<?php echo $sort_quantity; ?>" class="<?php echo $order; ?>"><?php echo $_['column_quantity']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_quantity; ?>"><?php echo $_['column_quantity']; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo $order; ?>"><?php echo $_['column_status']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $_['column_status']; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $_['column_action']; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
              <td></td>
              <td></td>
              <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
              <td><input type="text" name="filter_model" value="<?php echo $filter_model; ?>" /></td>
              <td align="left"><input type="text" name="filter_price" value="<?php echo $filter_price; ?>" size="8"/></td>
              <td align="right"><input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" style="text-align: right;" /></td>
              <td><select name="filter_status">
                  <option value="*"></option>
				  <?php echo form_select_option($_['option_statuses'], $filter_status, true);?>
                </select></td>
              <td align="right"><a onclick="filter();" class="button"><?php echo $_['button_filter']; ?></a></td>
            </tr>
            <?php if ($products) { ?>
            <?php foreach ($products as $product) { ?>
			<tr onmouseover="this.className='on';" onmouseout="this.className='';">
              <td style="text-align: center;"><?php if ($product['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                <?php } ?></td>
              <td class="center">
			  <a class="powerfloat" href="<?php echo $product['image']; ?>">
			  <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="padding: 1px; border: 1px solid #DDDDDD; width:40px" />
			  </a>
			  </td>
              <td class="left">
				<a href="<?php echo $product['href'];?>" target="_blank"><?php echo $product['name']; ?></a>
				  <?php if (!empty($product['source_link'])) { ?>
					  <a href="<?php echo $product['source_link'];?>" target="_blank"><img src="view/image/link_go.png"></a>
				  <?php } ?>
				<br>
				<small>
				viewed:<?php echo $product['viewed'];?>,
				<img src="../catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" height="10"/> (<?php echo $product['reviews']; ?>)</small>

			  </td>
              <td class="left"><?php echo $product['model']; ?></td>
              <td class="left"><?php if ($product['special']) { ?>
                <span style="text-decoration: line-through;"><?php echo $product['price']; ?></span><br/>
                <span style="color: #b00;"><?php echo $product['special']; ?></span>
                <?php } else { ?>
                <?php echo $product['price']; ?>
                <?php } ?>
				<br>
				<small>msrp:<?php echo $product['msrp'];?>, cost:<?php echo $product['cost'];?></small>
				</td>
              <td class="right"><?php if ($product['quantity'] <= 0) { ?>
                <span style="color: #FF0000;"><?php echo $product['quantity']; ?></span>
                <?php } elseif ($product['quantity'] <= 5) { ?>
                <span style="color: #FFA500;"><?php echo $product['quantity']; ?></span>
                <?php } else { ?>
                <span style="color: #008000;"><?php echo $product['quantity']; ?></span>
                <?php } ?></td>
              <td class="left"><?php echo $product['status']; ?></td>
              <td class="right"><?php foreach ($product['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="8"><?php echo $_['text_no_results']; ?></td>
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
	url = '<?php echo UA('catalog/product', 'filter_reset=1'); ?>';

	var v = $('input[name=\'filter_name\']').attr('value');
	if (v) {
		url += '&filter_name=' + encodeURIComponent(v);
	}

	v = $('input[name=\'filter_model\']').attr('value');
	if (v) {
		url += '&filter_model=' + encodeURIComponent(v);
	}

	v = $('input[name=\'filter_price\']').attr('value');
	if (v) {
		url += '&filter_price=' + encodeURIComponent(v);
	}

	v = $('input[name=\'filter_quantity\']').attr('value');
	if (v) {
		url += '&filter_quantity=' + encodeURIComponent(v);
	}

	v = $('select[name=\'filter_status\']').attr('value');
	if (v != '*') {
		url += '&filter_status=' + encodeURIComponent(v);
	}

	v = $('select[name=\'filter_category_id\']').attr('value');
	if (v != '*') {
		url += '&filter_category_id=' + encodeURIComponent(v);
	}

	v = $('select[name=\'filter_supplier_id\']').attr('value');
	if (v != '*') {
		url += '&filter_supplier_id=' + encodeURIComponent(v);
	}
	location = url;
}
//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: '<?php echo UA('catalog/product/autocomplete'); ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'filter_name\']').val(ui.item.label);

		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});

$('input[name=\'filter_model\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: '<?php echo UA('catalog/product/autocomplete'); ?>&filter_model=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.model,
						value: item.product_id
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'filter_model\']').val(ui.item.label);
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});

$(".powerfloat").powerFloat({targetMode: "ajax", targetAttr: "href", position: "3-4" });
//--></script>
<?php echo $footer; ?>