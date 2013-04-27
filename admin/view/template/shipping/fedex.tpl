<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if (isset($error_warning)) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/shipping.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a><a href="<?php echo UA('extension/shipping'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('shipping/fedex'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_key']; ?></td>
            <td><input type="text" name="fedex_key" value="<?php echo $fedex_key; ?>" data-rule-required="true" />
              <?php echo form_error('fedex_key'); ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_password']; ?></td>
            <td><input type="text" name="fedex_password" value="<?php echo $fedex_password; ?>" data-rule-required="true" />
              <?php echo form_error('fedex_password'); ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_account']; ?></td>
            <td><input type="text" name="fedex_account" value="<?php echo $fedex_account; ?>" data-rule-required="true" />
              <?php echo form_error('fedex_account'); ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_meter']; ?></td>
            <td><input type="text" name="fedex_meter" value="<?php echo $fedex_meter; ?>" data-rule-required="true" />
             <?php echo form_error('fedex_meter'); ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_postcode']; ?></td>
            <td><input type="text" name="fedex_postcode" value="<?php echo $fedex_postcode; ?>" data-rule-required="true" />
              <?php echo form_error('fedex_postcode'); ?></td>
          </tr>          
          <tr>
            <td><?php echo $_['entry_test']; ?></td>
            <td>
			<?php echo form_radio($_['option_yesno'], 'fedex_test', $fedex_test);?>
			</td>
          </tr>
          <tr>
            <td><?php echo $_['entry_service']; ?></td>
            <td><div class="scrollbox">
                <?php $class = 'odd'; ?>
                <?php foreach ($services as $service) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
                  <?php if (in_array($service['value'], $fedex_service)) { ?>
                  <input type="checkbox" name="fedex_service[]" value="<?php echo $service['value']; ?>" checked="checked" />
                  <?php echo $service['text']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_service[]" value="<?php echo $service['value']; ?>" />
                  <?php echo $service['text']; ?>
                  <?php } ?>
                </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $_['text_select_all']; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $_['text_unselect_all']; ?></a></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_dropoff_type']; ?></td>
            <td><select name="fedex_dropoff_type">
                <?php if ($fedex_dropoff_type == 'REGULAR_PICKUP') { ?>
                <option value="REGULAR_PICKUP" selected="selected"><?php echo $_['text_regular_pickup']; ?></option>
                <?php } else { ?>
                <option value="REGULAR_PICKUP"><?php echo $_['text_regular_pickup']; ?></option>
                <?php } ?>
                <?php if ($fedex_dropoff_type == 'REQUEST_COURIER') { ?>
                <option value="REQUEST_COURIER" selected="selected"><?php echo $_['text_request_courier']; ?></option>
                <?php } else { ?>
                <option value="REQUEST_COURIER"><?php echo $_['text_request_courier']; ?></option>
                <?php } ?>
                <?php if ($fedex_dropoff_type == 'DROP_BOX') { ?>
                <option value="DROP_BOX" selected="selected"><?php echo $_['text_drop_box']; ?></option>
                <?php } else { ?>
                <option value="DROP_BOX"><?php echo $_['text_drop_box']; ?></option>
                <?php } ?>
                <?php if ($fedex_dropoff_type == 'BUSINESS_SERVICE_CENTER') { ?>
                <option value="BUSINESS_SERVICE_CENTER" selected="selected"><?php echo $_['text_business_service_center']; ?></option>
                <?php } else { ?>
                <option value="BUSINESS_SERVICE_CENTER"><?php echo $_['text_business_service_center']; ?></option>
                <?php } ?>
                <?php if ($fedex_dropoff_type == 'STATION') { ?>
                <option value="STATION" selected="selected"><?php echo $_['text_station']; ?></option>
                <?php } else { ?>
                <option value="STATION"><?php echo $_['text_station']; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_packaging_type']; ?></td>
            <td><select name="fedex_packaging_type">
                <?php if ($fedex_packaging_type == 'FEDEX_ENVELOPE') { ?>
                <option value="FEDEX_ENVELOPE" selected="selected"><?php echo $_['text_fedex_envelope']; ?></option>
                <?php } else { ?>
                <option value="FEDEX_ENVELOPE"><?php echo $_['text_fedex_envelope']; ?></option>
                <?php } ?>
                <?php if ($fedex_packaging_type == 'FEDEX_PAK') { ?>
                <option value="FEDEX_PAK" selected="selected"><?php echo $_['text_fedex_pak']; ?></option>
                <?php } else { ?>
                <option value="FEDEX_PAK"><?php echo $_['text_fedex_pak']; ?></option>
                <?php } ?>
                <?php if ($fedex_packaging_type == 'FEDEX_BOX') { ?>
                <option value="FEDEX_BOX" selected="selected"><?php echo $_['text_fedex_box']; ?></option>
                <?php } else { ?>
                <option value="FEDEX_BOX"><?php echo $_['text_fedex_box']; ?></option>
                <?php } ?>
                <?php if ($fedex_packaging_type == 'FEDEX_TUBE') { ?>
                <option value="FEDEX_TUBE" selected="selected"><?php echo $_['text_fedex_tube']; ?></option>
                <?php } else { ?>
                <option value="FEDEX_TUBE"><?php echo $_['text_fedex_tube']; ?></option>
                <?php } ?>
                <?php if ($fedex_packaging_type == 'FEDEX_10KG_BOX') { ?>
                <option value="FEDEX_10KG_BOX" selected="selected"><?php echo $_['text_fedex_10kg_box']; ?></option>
                <?php } else { ?>
                <option value="FEDEX_10KG_BOX"><?php echo $_['text_fedex_10kg_box']; ?></option>
                <?php } ?>
                <?php if ($fedex_packaging_type == 'FEDEX_25KG_BOX') { ?>
                <option value="FEDEX_25KG_BOX" selected="selected"><?php echo $_['text_fedex_25kg_box']; ?></option>
                <?php } else { ?>
                <option value="FEDEX_25KG_BOX"><?php echo $_['text_fedex_25kg_box']; ?></option>
                <?php } ?>
                <?php if ($fedex_packaging_type == 'YOUR_PACKAGING') { ?>
                <option value="YOUR_PACKAGING" selected="selected"><?php echo $_['text_your_packaging']; ?></option>
                <?php } else { ?>
                <option value="YOUR_PACKAGING"><?php echo $_['text_your_packaging']; ?></option>
                <?php } ?>                                
              </select></td>
          </tr> 
          <tr>
            <td><?php echo $_['entry_rate_type']; ?></td>
            <td><select name="fedex_rate_type">
                <?php if ($fedex_rate_type == 'LIST') { ?>
                <option value="LIST" selected="selected"><?php echo $_['text_list_rate']; ?></option>
                <?php } else { ?>
                <option value="LIST"><?php echo $_['text_list_rate']; ?></option>
                <?php } ?>
                <?php if ($fedex_rate_type == 'ACCOUNT') { ?>
                <option value="ACCOUNT" selected="selected"><?php echo $_['text_account_rate']; ?></option>
                <?php } else { ?>
                <option value="ACCOUNT"><?php echo $_['text_account_rate']; ?></option>
                <?php } ?>
              </select></td>
          </tr>    
		  <tr>
            <td><?php echo $_['entry_display_time']; ?></td>
            <td>
			<?php echo form_radio($_['option_yesno'], 'fedex_display_time', $fedex_display_time);?>
			</td>
          </tr>
          <tr>
            <td><?php echo $_['entry_display_weight']; ?></td>
            <td>
			<?php echo form_radio($_['option_yesno'], 'fedex_display_weight', $fedex_display_weight);?>
			</td>
          </tr>              
          <tr>
            <td><?php echo $_['entry_weight_class']; ?></td>
            <td><select name="fedex_weight_class_id">
				<?php echo form_select_option($weight_classes, $fedex_weight_class_id, null, 'weight_class_id', 'title'); ?>
              </select></td>
          </tr>                                
          <tr>
            <td><?php echo $_['entry_tax_class']; ?></td>
            <td><select name="fedex_tax_class_id">
                <option value="0"><?php echo $_['text_none']; ?></option>
				<?php echo form_select_option($tax_classes, $fedex_tax_class_id, null, 'tax_class_id', 'title'); ?>
              </select></td>
          </tr>          
          <tr>
            <td><?php echo $_['entry_geo_zone']; ?></td>
            <td><select name="fedex_geo_zone_id">
                <option value="0"><?php echo $_['text_all_zones']; ?></option>
				<?php echo form_select_option($geo_zones, $fedex_geo_zone_id, null, 'geo_zone_id', 'name'); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="fedex_status">
				<?php echo form_select_option($_['option_statuses'], $fedex_status, true); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="fedex_sort_order" value="<?php echo $fedex_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>