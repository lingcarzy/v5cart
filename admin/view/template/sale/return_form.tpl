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
      <div class="buttons">
	  <a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a>
	  <a href="<?php echo $cancel; ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <div class="htabs"><a href="#tab-return"><?php echo $_['tab_return']; ?></a><a href="#tab-product"><?php echo $_['tab_product']; ?></a></div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-return">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_order_id']; ?></td>
              <td><input type="text" name="order_id" value="<?php echo $order_id; ?>" />
                <?php if (isset($error_order_id)) { ?>
                <span class="error"><?php echo $error_order_id; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_date_ordered']; ?></td>
              <td><input type="text" name="date_ordered" value="<?php echo $date_ordered; ?>" class="date" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_customer']; ?></td>
              <td><input type="text" name="customer" value="<?php echo $customer; ?>" />
                <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" /></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_firstname']; ?></td>
              <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
                <?php if (isset($error_firstname)) { ?>
                <span class="error"><?php echo $error_firstname; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_lastname']; ?></td>
              <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
                <?php if (isset($error_lastname)) { ?>
                <span class="error"><?php echo $error_lastname; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_email']; ?></td>
              <td><input type="text" name="email" value="<?php echo $email; ?>" />
                <?php if (isset($error_email)) { ?>
                <span class="error"><?php echo $error_email; ?></span>
                <?php  } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_telephone']; ?></td>
              <td><input type="text" name="telephone" value="<?php echo $telephone; ?>" />
                <?php if (isset($error_telephone)) { ?>
                <span class="error"><?php echo $error_telephone; ?></span>
                <?php  } ?></td>
            </tr>
          </table>
        </div>
        <div id="tab-product">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_product']; ?></td>
              <td><input type="text" name="product" value="<?php echo $product; ?>" />
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
                <?php if (isset($error_product)) { ?>
                <span class="error"><?php echo $error_product; ?></span>
                <?php  } ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_model']; ?></td>
              <td><input type="text" name="model" value="<?php echo $model; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_quantity']; ?></td>
              <td><input type="text" name="quantity" value="<?php echo $quantity; ?>" size="3" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_reason']; ?></td>
              <td><select name="return_reason_id">
			  <?php echo form_select_option($return_reasons, $return_reason_id, null, 'return_reason_id', 'name'); ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_opened']; ?></td>
              <td><select name="opened">
                  <?php if ($opened) { ?>
                  <option value="1" selected="selected"><?php echo $_['text_opened']; ?></option>
                  <option value="0"><?php echo $_['text_unopened']; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $_['text_opened']; ?></option>
                  <option value="0" selected="selected"><?php echo $_['text_unopened']; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_comment']; ?></td>
              <td><textarea name="comment" cols="40" rows="5"><?php echo $comment; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_action']; ?></td>
              <td><select name="return_action_id">
                  <option value="0"></option>
				   <?php echo form_select_option($return_actions, $return_action_id, null, 'return_action_id', 'name'); ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_return_status']; ?></td>
              <td><select name="return_status_id">
				 <?php echo form_select_option($return_statuses, $return_status_id, null, 'return_status_id', 'name'); ?>
                </select></td>
            </tr>
          </table>
        </div>
      </form>
    </div>
  </div>
</div>
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

$('input[name=\'customer\']').catcomplete({
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
						value: item.customer_id,
						firstname: item.firstname,
						lastname: item.lastname,
						email: item.email,
						telephone: item.telephone
					}
				}));
			}
		});
		
	}, 
	select: function(event, ui) {
		$('input[name=\'customer\']').attr('value', ui.item.label);
		$('input[name=\'customer_id\']').attr('value', ui.item.value);
		$('input[name=\'firstname\']').attr('value', ui.item.firstname);
		$('input[name=\'lastname\']').attr('value', ui.item.lastname);
		$('input[name=\'email\']').attr('value', ui.item.email);
		$('input[name=\'telephone\']').attr('value', ui.item.telephone);

		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});
//--></script> 
<script type="text/javascript"><!--
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
						value: item.product_id,
						model: item.model
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'product_id\']').attr('value', ui.item.value);
		$('input[name=\'product\']').attr('value', ui.item.label);
		$('input[name=\'model\']').attr('value', ui.item.model);
		
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
<script type="text/javascript"><!--
$('.htabs a').tabs(); 
//--></script> 
<?php echo $footer; ?>