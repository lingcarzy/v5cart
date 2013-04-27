<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
   <?php echo bread_crumbs(); ?>
  </div>
  
  <?php if (isset($error_warning)) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  
  <div class="box">
  
    <div class="heading">
      <h1><img src="view/image/customer.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a><a href="<?php echo UA('sale/coupon'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
	
    <div class="content">
      <div id="tabs" class="htabs"><a href="#tab-general"><?php echo $_['tab_general']; ?></a>
        <?php if ($coupon_id) { ?>
        <a href="#tab-history"><?php echo $_['tab_coupon_history']; ?></a>
        <?php } ?>
      </div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_name']; ?></td>
              <td><input name="name" value="<?php echo $name; ?>" />
                <?php if (isset($error_name)) { ?>
                <span class="error"><?php echo $error_name; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_code']; ?></td>
              <td><input type="text" name="code" value="<?php echo $code; ?>" />
                <?php if (isset($error_code)) { ?>
                <span class="error"><?php echo $error_code; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_type']; ?></td>
              <td><select name="type">
                  <?php if ($type == 'P') { ?>
                  <option value="P" selected="selected"><?php echo $_['text_percent']; ?></option>
                  <?php } else { ?>
                  <option value="P"><?php echo $_['text_percent']; ?></option>
                  <?php } ?>
                  <?php if ($type == 'F') { ?>
                  <option value="F" selected="selected"><?php echo $_['text_amount']; ?></option>
                  <?php } else { ?>
                  <option value="F"><?php echo $_['text_amount']; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_discount']; ?></td>
              <td><input type="text" name="discount" value="<?php echo $discount; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_total']; ?></td>
              <td><input type="text" name="total" value="<?php echo $total; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_logged']; ?></td>
              <td><?php echo form_radio($_['option_yesno'], 'logged', $logged); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_shipping']; ?></td>
              <td><?php echo form_radio($_['option_yesno'], 'shipping', $shipping); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_category']; ?></td>
              <td>
			  <ul id="category_tree">
				  <?php $level = 0; ?>
                  <?php foreach ($categories as $category) { ?>
					<?php if ($category['_level'] > $level) { ?>
					<ul>
					<?php } elseif ($category['_level'] < $level) { ?>
					</ul>
					<?php } ?>
					<?php if (in_array($category['category_id'], $coupon_category)) { ?>
					<li><input type="checkbox" name="coupon_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" /><label><?php echo $category['name']; ?></label>
					<?php } else { ?>
					<li><input type="checkbox" name="coupon_category[]" value="<?php echo $category['category_id']; ?>" /><label><?php echo $category['name']; ?></label>
					<?php } ?>
					<?php if ($category['_leaf']) { ?>
					</li>
					<?php } ?>
					<?php $level = $category['_level']; ?>
				  <?php }?>
				</ul></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_product']; ?></td>
              <td><input type="text" name="product" value="" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><div id="coupon-product" class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($coupon_product as $coupon_product) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="coupon-product<?php echo $coupon_product['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $coupon_product['name']; ?><img src="view/image/delete.png" />
                    <input type="hidden" name="coupon_product[]" value="<?php echo $coupon_product['product_id']; ?>" />
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_date_start']; ?></td>
              <td><input type="text" name="date_start" value="<?php echo $date_start; ?>" size="12" id="date-start" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_date_end']; ?></td>
              <td><input type="text" name="date_end" value="<?php echo $date_end; ?>" size="12" id="date-end" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_uses_total']; ?></td>
              <td><input type="text" name="uses_total" value="<?php echo $uses_total; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_uses_customer']; ?></td>
              <td><input type="text" name="uses_customer" value="<?php echo $uses_customer; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_status']; ?></td>
              <td><select name="status">
				<?php echo form_select_option($_['option_statuses'], $status, true); ?>
                </select></td>
            </tr>
          </table>
        </div>
        <?php if ($coupon_id) { ?>
        <div id="tab-history">
          <div id="history"></div>
        </div>
        <?php } ?>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('input[name=\'category[]\']').bind('change', function() {
	var filter_category_id = this;
	
	$.ajax({
		url: '<?php echo UA('catalog/product/autocomplete'); ?>&filter_category_id=' +  filter_category_id.value + '&limit=10000',
		dataType: 'json',
		success: function(json) {
			for (i = 0; i < json.length; i++) {
				if ($(filter_category_id).attr('checked') == 'checked') {
					$('#coupon-product' + json[i]['product_id']).remove();
					
					$('#coupon-product').append('<div id="coupon-product' + json[i]['product_id'] + '">' + json[i]['name'] + '<img src="view/image/delete.png" /><input type="hidden" name="coupon_product[]" value="' + json[i]['product_id'] + '" /></div>');
				} else {
					$('#coupon-product' + json[i]['product_id']).remove();
				}			
			}
			
			$('#coupon-product div:odd').attr('class', 'odd');
			$('#coupon-product div:even').attr('class', 'even');			
		}
	});
});

$('input[name=\'product\']').autocomplete({
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
		$('#coupon-product' + ui.item.value).remove();
		
		$('#coupon-product').append('<div id="coupon-product' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" name="coupon_product[]" value="' + ui.item.value + '" /></div>');

		$('#coupon-product div:odd').attr('class', 'odd');
		$('#coupon-product div:even').attr('class', 'even');
		
		$('input[name=\'product\']').val('');
		
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});

$('#coupon-product div img').live('click', function() {
	$(this).parent().remove();	
	$('#coupon-product div:odd').attr('class', 'odd');
	$('#coupon-product div:even').attr('class', 'even');	
});
//--></script> 

<script type="text/javascript"><!--
$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});
$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
//--></script>

<?php if ($coupon_id) { ?>
<script type="text/javascript"><!--
$('#history .pagination a').live('click', function() {
	$('#history').load(this.href);	
	return false;
});

$('#history').load('<?php echo UA('sale/coupon/history'); ?>&coupon_id=<?php echo $coupon_id; ?>');
//--></script>
<?php } ?>

<script type="text/javascript"><!--
$('#tabs a').tabs(); 
$('#category_tree').checkboxTree({ onCheck: { ancestors: null, descendants: null}, onUncheck: {descendants: null},initializeUnchecked:'collapsed'});
//--></script> 
<?php echo $footer; ?>