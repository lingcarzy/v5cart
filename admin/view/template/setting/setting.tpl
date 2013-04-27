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
      <h1><img src="view/image/setting.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a>
	  <a href="<?php echo UA('setting/store'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs"><a href="#tab-general"><?php echo $_['tab_general']; ?></a><a href="#tab-store"><?php echo $_['tab_store']; ?></a><a href="#tab-local"><?php echo $_['tab_local']; ?></a><a href="#tab-option"><?php echo $_['tab_option']; ?></a><a href="#tab-image"><?php echo $_['tab_image']; ?></a><a href="#tab-mail"><?php echo $_['tab_mail']; ?></a><a href="#tab-fraud"><?php echo $_['tab_fraud']; ?></a><a href="#tab-server"><?php echo $_['tab_server']; ?></a></div>
      <form action="<?php echo UA('setting/setting'); ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_name']; ?></td>
              <td><input type="text" name="config_name" value="<?php echo $config_name; ?>" size="40" />
                <?php echo form_error('config_name'); ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_owner']; ?></td>
              <td><input type="text" name="config_owner" value="<?php echo $config_owner; ?>" size="40" />
                <?php echo form_error('config_owner'); ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_address']; ?></td>
              <td><textarea name="config_address" cols="40" rows="5"><?php echo $config_address; ?></textarea>
                <?php echo form_error('config_address'); ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_email']; ?></td>
              <td><input type="text" name="config_email" value="<?php echo $config_email; ?>" size="40" />
                <?php echo form_error('config_email'); ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_telephone']; ?></td>
              <td><input type="text" name="config_telephone" value="<?php echo $config_telephone; ?>" />
                <?php echo form_error('config_telephone'); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_fax']; ?></td>
              <td><input type="text" name="config_fax" value="<?php echo $config_fax; ?>" /></td>
            </tr>
          </table>
        </div>
        <div id="tab-store">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_title']; ?></td>
              <td><input type="text" name="config_title" value="<?php echo $config_title; ?>" />
                <?php echo form_error('config_title'); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_meta_description']; ?></td>
              <td><textarea name="config_meta_description" cols="40" rows="5"><?php echo $config_meta_description; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_template']; ?></td>
              <td><select name="config_template" onchange="$('#template').load('<?php echo UA('setting/setting/template'); ?>&template=' + encodeURIComponent(this.value));">
                 <?php echo form_select_option($templates, $config_template); ?>
                </select></td>
            </tr>
            <tr>
              <td></td>
              <td id="template"></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_layout']; ?></td>
              <td><select name="config_layout_id">
                 <?php echo form_select_option($layouts, $config_layout_id, null, 'layout_id', 'name'); ?>
                </select></td>
            </tr>
          </table>
        </div>
        <div id="tab-local">
          <table class="form">
            <tr>
              <td><?php echo $_['entry_country']; ?></td>
              <td><select name="config_country_id">
                  <?php echo form_select_option($countries, $config_country_id, true);?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_zone']; ?></td>
              <td><select name="config_zone_id">
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_language']; ?></td>
              <td><select name="config_language">
                  <?php echo form_select_option($languages, $config_language, null, 'code', 'name'); ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_admin_language']; ?></td>
              <td><select name="config_admin_language">
			  <?php echo form_select_option($languages, $config_admin_language, null, 'code', 'name'); ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_currency']; ?></td>
              <td><select name="config_currency">
				<?php echo form_select_option($currencies, $config_currency, null, 'code', 'title'); ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_currency_auto']; ?></td>
              <td>
			   <?php echo form_radio($_['option_yesno'], 'config_currency_auto', $config_currency_auto); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_length_class']; ?></td>
              <td><select name="config_length_class_id">
			  <?php echo form_select_option($length_classes, $config_length_class_id, null, 'length_class_id', 'title'); ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_weight_class']; ?></td>
              <td><select name="config_weight_class_id">
			   <?php echo form_select_option($weight_classes, $config_weight_class_id, null, 'weight_class_id', 'title'); ?>
                </select></td>
            </tr>
          </table>
        </div>
        <div id="tab-option">
          <h2><?php echo $_['text_items']; ?></h2>
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_catalog_limit']; ?></td>
              <td><input type="text" name="config_catalog_limit" value="<?php echo $config_catalog_limit; ?>" size="3" />
                <?php echo form_error('config_catalog_limit'); ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_admin_limit']; ?></td>
              <td><input type="text" name="config_admin_limit" value="<?php echo $config_admin_limit; ?>" size="3" />
                <?php echo form_error('config_admin_limit'); ?></td>
            </tr>
          </table>
          <h2><?php echo $_['text_product']; ?></h2>
          <table class="form">
            <tr>
              <td><?php echo $_['entry_product_count']; ?></td>
              <td><?php echo form_radio($_['option_yesno'], 'config_product_count', $config_product_count); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_review']; ?></td>
              <td><?php echo form_radio($_['option_yesno'], 'config_review_status', $config_review_status); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_download']; ?></td>
              <td><?php echo form_radio($_['option_yesno'], 'config_download', $config_download); ?></td>
            </tr>            
			  <tr>
				  <td>Use Global Discount</td>
				  <td><?php echo form_radio($_['option_yesno'], 'config_use_global_discount', $config_use_global_discount); ?></td>
			  </tr>
			  <tr>
				  <td>Global Discount Rate
					  <br>
					  <span class="help">
						Example: customer group id/qty 1:discount rate 1,qty 2:discount rate 2,qty 3:discount rate 3...
					  </span>
				  </td>
				  <td><textarea name="config_global_discount_rate" cols="40" rows="5"><?php echo $config_global_discount_rate; ?></textarea></td>
			  </tr>
          </table>
          <h2><?php echo $_['text_voucher']; ?></h2>
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_voucher_min']; ?></td>
              <td><input type="text" name="config_voucher_min" value="<?php echo $config_voucher_min; ?>" />
                <?php echo form_error('config_voucher_min'); ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_voucher_max']; ?></td>
              <td><input type="text" name="config_voucher_max" value="<?php echo $config_voucher_max; ?>" />
                <?php echo form_error('config_voucher_max'); ?></td>
            </tr>
          </table>
          <h2><?php echo $_['text_tax']; ?></h2>
          <table class="form">
            <tr>
              <td><?php echo $_['entry_tax']; ?></td>
              <td><?php echo form_radio($_['option_yesno'], 'config_tax', $config_tax); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_vat']; ?></td>
              <td><?php echo form_radio($_['option_yesno'], 'config_vat', $config_vat); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_tax_default']; ?></td>
              <td><select name="config_tax_default">
                  <option value=""><?php echo $_['text_none']; ?></option>
                  <?php  if ($config_tax_default == 'shipping') { ?>
                  <option value="shipping" selected="selected"><?php echo $_['text_shipping']; ?></option>
                  <?php } else { ?>
                  <option value="shipping"><?php echo $_['text_shipping']; ?></option>
                  <?php } ?>
                  <?php  if ($config_tax_default == 'payment') { ?>
                  <option value="payment" selected="selected"><?php echo $_['text_payment']; ?></option>
                  <?php } else { ?>
                  <option value="payment"><?php echo $_['text_payment']; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_tax_customer']; ?></td>
              <td><select name="config_tax_customer">
                  <option value=""><?php echo $_['text_none']; ?></option>
                  <?php  if ($config_tax_customer == 'shipping') { ?>
                  <option value="shipping" selected="selected"><?php echo $_['text_shipping']; ?></option>
                  <?php } else { ?>
                  <option value="shipping"><?php echo $_['text_shipping']; ?></option>
                  <?php } ?>
                  <?php  if ($config_tax_customer == 'payment') { ?>
                  <option value="payment" selected="selected"><?php echo $_['text_payment']; ?></option>
                  <?php } else { ?>
                  <option value="payment"><?php echo $_['text_payment']; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
          <h2><?php echo $_['text_account']; ?></h2>
          <table class="form">
			<tr>
              <td><?php echo $_['entry_register_address']; ?></td>
              <td><?php echo form_radio($_['option_yesno'], 'config_register_address', $config_register_address); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_customer_online']; ?></td>
              <td><?php echo form_radio($_['option_yesno'], 'config_customer_online', $config_customer_online); ?></td>
            </tr>          
            <tr>
              <td><?php echo $_['entry_customer_group']; ?></td>
              <td><select name="config_customer_group_id">
			  <?php echo form_select_option($customer_groups, $config_customer_group_id, null, 'customer_group_id', 'name'); ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_customer_group_display']; ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($customer_group['customer_group_id'], $config_customer_group_display)) { ?>
                    <input type="checkbox" name="config_customer_group_display[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                    <?php echo $customer_group['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="config_customer_group_display[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
                    <?php echo $customer_group['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_customer_price']; ?></td>
              <td><?php echo form_radio($_['option_yesno'], 'config_customer_price', $config_customer_price); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_account']; ?></td>
              <td><select name="config_account_id">
                  <option value="0"><?php echo $_['text_none']; ?></option>
				  <?php echo form_select_option($pages, $config_account_id, null, 'page_id', 'title'); ?>
                </select></td>
            </tr>
          </table>
          <h2><?php echo $_['text_checkout']; ?></h2>
          <table class="form">
            <tr>
              <td><?php echo $_['entry_cart_weight']; ?></td>
              <td><?php echo form_radio($_['option_yesno'], 'config_cart_weight', $config_cart_weight); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_guest_checkout']; ?></td>
              <td><?php echo form_radio($_['option_yesno'], 'config_guest_checkout', $config_guest_checkout); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_checkout']; ?></td>
              <td><select name="config_checkout_id">
                  <option value="0"><?php echo $_['text_none']; ?></option>
				  <?php echo form_select_option($pages, $config_checkout_id, null, 'page_id', 'title'); ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_order_edit']; ?></td>
              <td><input type="text" name="config_order_edit" value="<?php echo $config_order_edit; ?>" size="3" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_invoice_prefix']; ?></td>
              <td><input type="text" name="config_invoice_prefix" value="<?php echo $config_invoice_prefix; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_order_status']; ?></td>
              <td><select name="config_order_status_id">
			  <?php echo form_select_option($order_statuses, $config_order_status_id, true);?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_complete_status']; ?></td>
              <td><select name="config_complete_status_id">
			  <?php echo form_select_option($order_statuses, $config_complete_status_id, true);?>
                </select></td>
            </tr>
          </table>
          <h2><?php echo $_['text_stock']; ?></h2>
          <table class="form">
            <tr>
              <td><?php echo $_['entry_stock_display']; ?></td>
              <td><?php echo form_radio($_['option_yesno'], 'config_stock_display', $config_stock_display); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_stock_warning']; ?></td>
              <td><?php echo form_radio($_['option_yesno'], 'config_stock_warning', $config_stock_warning); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_stock_checkout']; ?></td>
              <td><?php echo form_radio($_['option_yesno'], 'config_stock_checkout', $config_stock_checkout); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_stock_status']; ?></td>
              <td><select name="config_stock_status_id">
			     <?php echo form_select_option($stock_statuses, $config_stock_status_id, null, 'stock_status_id', 'name'); ?>
                </select></td>
            </tr>
          </table>
          <h2><?php echo $_['text_affiliate']; ?></h2>
          <table class="form">
            <tr>
              <td><?php echo $_['entry_affiliate']; ?></td>
              <td><select name="config_affiliate_id">			  
                  <option value="0"><?php echo $_['text_none']; ?></option>
				   <?php echo form_select_option($pages, $config_affiliate_id, null, 'page_id', 'title'); ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_commission']; ?></td>
              <td><input type="text" name="config_commission" value="<?php echo $config_commission; ?>" size="3" /></td>
            </tr>
          </table>
          <h2><?php echo $_['text_return']; ?></h2>
          <table class="form">
			<tr>
              <td><?php echo $_['entry_return']; ?></td>
              <td><select name="config_return_id">
                  <option value="0"><?php echo $_['text_none']; ?></option>
				   <?php echo form_select_option($pages, $config_return_id, null, 'page_id', 'title'); ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_return_status']; ?></td>
              <td><select name="config_return_status_id">
			     <?php echo form_select_option($return_statuses, $config_return_status_id, null, 'return_status_id', 'name'); ?>
                </select></td>
            </tr>
          </table>
        </div>
        <div id="tab-image">
          <table class="form">
            <tr>
              <td><?php echo $_['entry_logo']; ?></td>
              <td><div class="image"><img src="<?php echo $logo; ?>" alt="" id="thumb-logo" />
                  <input type="hidden" name="config_logo" value="<?php echo $config_logo; ?>" id="logo" />
                  <br />
                  <a onclick="image_upload('logo', 'thumb-logo', '<?php echo $token;?>');"><?php echo $_['text_browse']; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-logo').attr('src', '<?php echo $no_image; ?>'); $('#logo').attr('value', '');"><?php echo $_['text_clear']; ?></a></div></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_icon']; ?></td>
              <td><div class="image"><img src="<?php echo $icon; ?>" alt="" id="thumb-icon" />
                  <input type="hidden" name="config_icon" value="<?php echo $config_icon; ?>" id="icon" />
                  <br />
                  <a onclick="image_upload('icon', 'thumb-icon', '<?php echo $token;?>');"><?php echo $_['text_browse']; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-icon').attr('src', '<?php echo $no_image; ?>'); $('#icon').attr('value', '');"><?php echo $_['text_clear']; ?></a></div></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_image_category']; ?></td>
              <td><input type="text" name="config_image_category_width" value="<?php echo $config_image_category_width; ?>" size="3" />
                x
                <input type="text" name="config_image_category_height" value="<?php echo $config_image_category_height; ?>" size="3" />
                <?php echo form_error('config_image_category_width'); ?><br>
				<?php echo form_error('config_image_category_height'); ?>
				</td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_image_thumb']; ?></td>
              <td><input type="text" name="config_image_thumb_width" value="<?php echo $config_image_thumb_width; ?>" size="3" />
                x
                <input type="text" name="config_image_thumb_height" value="<?php echo $config_image_thumb_height; ?>" size="3" />
                  <?php echo form_error('config_image_thumb_width'); ?><br>
				<?php echo form_error('config_image_thumb_height'); ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_image_popup']; ?></td>
              <td><input type="text" name="config_image_popup_width" value="<?php echo $config_image_popup_width; ?>" size="3" />
                x
                <input type="text" name="config_image_popup_height" value="<?php echo $config_image_popup_height; ?>" size="3" />
                  <?php echo form_error('config_image_popup_width'); ?><br>
				<?php echo form_error('config_image_popup_height'); ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_image_product']; ?></td>
              <td><input type="text" name="config_image_product_width" value="<?php echo $config_image_product_width; ?>" size="3" />
                x
                <input type="text" name="config_image_product_height" value="<?php echo $config_image_product_height; ?>" size="3" />
				<?php echo form_error('config_image_product_width'); ?><br>
				<?php echo form_error('config_image_product_height'); ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_image_additional']; ?></td>
              <td><input type="text" name="config_image_additional_width" value="<?php echo $config_image_additional_width; ?>" size="3" />
                x
                <input type="text" name="config_image_additional_height" value="<?php echo $config_image_additional_height; ?>" size="3" />
				<?php echo form_error('config_image_additional_width'); ?><br>
				<?php echo form_error('config_image_additional_height'); ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_image_related']; ?></td>
              <td><input type="text" name="config_image_related_width" value="<?php echo $config_image_related_width; ?>" size="3" />
                x
                <input type="text" name="config_image_related_height" value="<?php echo $config_image_related_height; ?>" size="3" />
                  <?php echo form_error('config_image_related_width'); ?><br>
				<?php echo form_error('config_image_related_height'); ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_image_compare']; ?></td>
              <td><input type="text" name="config_image_compare_width" value="<?php echo $config_image_compare_width; ?>" size="3" />
                x
                <input type="text" name="config_image_compare_height" value="<?php echo $config_image_compare_height; ?>" size="3" />
                  <?php echo form_error('config_image_compare_width'); ?><br>
				<?php echo form_error('config_image_compare_height'); ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_image_wishlist']; ?></td>
              <td><input type="text" name="config_image_wishlist_width" value="<?php echo $config_image_wishlist_width; ?>" size="3" />
                x
                <input type="text" name="config_image_wishlist_height" value="<?php echo $config_image_wishlist_height; ?>" size="3" />
                  <?php echo form_error('config_image_wishlist_width'); ?><br>
				<?php echo form_error('config_image_wishlist_height'); ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_image_cart']; ?></td>
              <td><input type="text" name="config_image_cart_width" value="<?php echo $config_image_cart_width; ?>" size="3" />
                x
                <input type="text" name="config_image_cart_height" value="<?php echo $config_image_cart_height; ?>" size="3" />
                  <?php echo form_error('config_image_cart_width'); ?><br>
				<?php echo form_error('config_image_cart_height'); ?></td>
            </tr>
          </table>
        </div>
        <div id="tab-mail">
          <table class="form">
            <tr>
              <td><?php echo $_['entry_mail_protocol']; ?></td>
              <td><select name="config_mail_protocol">
                  <?php if ($config_mail_protocol == 'mail') { ?>
                  <option value="mail" selected="selected"><?php echo $_['text_mail']; ?></option>
                  <?php } else { ?>
                  <option value="mail"><?php echo $_['text_mail']; ?></option>
                  <?php } ?>
                  <?php if ($config_mail_protocol == 'smtp') { ?>
                  <option value="smtp" selected="selected"><?php echo $_['text_smtp']; ?></option>
                  <?php } else { ?>
                  <option value="smtp"><?php echo $_['text_smtp']; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_mail_parameter']; ?></td>
              <td><input type="text" name="config_mail_parameter" value="<?php echo $config_mail_parameter; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_smtp_host']; ?></td>
              <td><input type="text" name="config_smtp_host" value="<?php echo $config_smtp_host; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_smtp_username']; ?></td>
              <td><input type="text" name="config_smtp_username" value="<?php echo $config_smtp_username; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_smtp_password']; ?></td>
              <td><input type="text" name="config_smtp_password" value="<?php echo $config_smtp_password; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_smtp_port']; ?></td>
              <td><input type="text" name="config_smtp_port" value="<?php echo $config_smtp_port; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_smtp_timeout']; ?></td>
              <td><input type="text" name="config_smtp_timeout" value="<?php echo $config_smtp_timeout; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_alert_mail']; ?></td>
              <td><?php echo form_radio($_['option_yesno'], 'config_alert_mail', $config_alert_mail); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_account_mail']; ?></td>
              <td><?php echo form_radio($_['option_yesno'], 'config_account_mail', $config_account_mail); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_alert_emails']; ?></td>
              <td><textarea name="config_alert_emails" cols="40" rows="5"><?php echo $config_alert_emails; ?></textarea></td>
            </tr>
          </table>
        </div>
        <div id="tab-fraud">
          <table class="form">
            <tr>
              <td><?php echo $_['entry_fraud_detection']; ?></td>
              <td><?php echo form_radio($_['option_yesno'], 'config_fraud_detection', $config_fraud_detection); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_fraud_key']; ?></td>
              <td><input type="text" name="config_fraud_key" value="<?php echo $config_fraud_key; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_fraud_score']; ?></td>
              <td><input type="text" name="config_fraud_score" value="<?php echo $config_fraud_score; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_fraud_status']; ?></td>
              <td><select name="config_fraud_status_id">
			  <?php echo form_select_option($order_statuses, $config_fraud_status_id, true);?>
                </select></td>
            </tr>
          </table>
        </div>
        <div id="tab-server">
          <table class="form">
			<tr>
              <td><?php echo $_['entry_file_extension_allowed']; ?></td>
              <td><input name="config_file_extension_allowed" value="<?php echo $config_file_extension_allowed; ?>" size="65" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_file_mime_allowed']; ?></td>
              <td><textarea name="config_file_mime_allowed" cols="60" rows="5"><?php echo $config_file_mime_allowed; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_use_ssl']; ?></td>
              <td>
			  <?php echo form_radio($_['option_yesno'], 'config_use_ssl', $config_use_ssl); ?>
			  </td>
            </tr>
            <tr>
              <td><?php echo $_['entry_seo_url']; ?></td>
			 
              <td><?php echo form_radio($_['option_yesno'], 'config_seo_url', $config_seo_url); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_maintenance']; ?></td>
              <td>
			    <?php echo form_radio($_['option_yesno'], 'config_maintenance', $config_maintenance); ?></td>
            </tr>
			 <tr>
              <td><?php echo $_['entry_password']; ?></td>
              <td><?php echo form_radio($_['option_yesno'], 'config_password', $config_password); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_encryption']; ?></td>
              <td><input type="text" name="config_encryption" value="<?php echo $config_encryption; ?>" size="40"/></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_compression']; ?></td>
              <td><input type="text" name="config_compression" value="<?php echo $config_compression; ?>" size="3" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_error_display']; ?></td>
              <td>
			  <?php echo form_radio($_['option_yesno'], 'config_error_display', $config_error_display); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_error_log']; ?></td>
              <td>
			   <?php echo form_radio($_['option_yesno'], 'config_error_log', $config_error_log); ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_error_filename']; ?></td>
              <td><input type="text" name="config_error_filename" value="<?php echo $config_error_filename; ?>" size="40" />
                <?php echo form_error('config_error_filename'); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_google_analytics']; ?></td>
              <td><textarea name="config_google_analytics" cols="40" rows="5"><?php echo $config_google_analytics; ?></textarea></td>
            </tr>
          </table>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#template').load('<?php echo UA('setting/setting/template'); ?>&template=' + encodeURIComponent($('select[name=\'config_template\']').attr('value')));
//--></script> 
<script type="text/javascript"><!--
$('select[name=\'config_country_id\']').bind('change', function() {
	$.ajax({
		url: '<?php echo UA('common/ajax/country'); ?>&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
		},		
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#postcode-required').show();
			} else {
				$('#postcode-required').hide();
			}
			
			html = '<option value=""><?php echo $_['text_select']; ?></option>';
			
			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
	    			
					if (json['zone'][i]['zone_id'] == '<?php echo $config_zone_id; ?>') {
	      				html += ' selected="selected"';
	    			}
	
	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $_['text_none']; ?></option>';
			}
			
			$('select[name=\'config_zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'config_country_id\']').trigger('change');
//--></script> 
<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script> 
<?php echo $footer; ?>