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
      <form action="<?php echo UA('shipping/usps'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_user_id']; ?></td>
            <td><input type="text" name="usps_user_id" value="<?php echo $usps_user_id; ?>" />
              <?php echo form_error('usps_user_id'); ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_postcode']; ?></td>
            <td><input type="text" name="usps_postcode" value="<?php echo $usps_postcode; ?>" />
              <?php echo form_error('usps_postcode'); ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_domestic']; ?></td>
            <td><div class="scrollbox">
                <div class="even">
                  <?php if ($usps_domestic_00) { ?>
                  <input type="checkbox" name="usps_domestic_00" value="1" checked="checked" />
                  <?php echo $_['text_domestic_00']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_domestic_00" value="1" />
                  <?php echo $_['text_domestic_00']; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($usps_domestic_01) { ?>
                  <input type="checkbox" name="usps_domestic_01" value="1" checked="checked" />
                  <?php echo $_['text_domestic_01']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_domestic_01" value="1" />
                  <?php echo $_['text_domestic_01']; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($usps_domestic_02) { ?>
                  <input type="checkbox" name="usps_domestic_02" value="1" checked="checked" />
                  <?php echo $_['text_domestic_02']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_domestic_02" value="1" />
                  <?php echo $_['text_domestic_02']; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($usps_domestic_03) { ?>
                  <input type="checkbox" name="usps_domestic_03" value="1" checked="checked" />
                  <?php echo $_['text_domestic_03']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_domestic_03" value="1" />
                  <?php echo $_['text_domestic_03']; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($usps_domestic_1) { ?>
                  <input type="checkbox" name="usps_domestic_1" value="1" checked="checked" />
                  <?php echo $_['text_domestic_1']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_domestic_1" value="1" />
                  <?php echo $_['text_domestic_1']; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($usps_domestic_2) { ?>
                  <input type="checkbox" name="usps_domestic_2" value="1" checked="checked" />
                  <?php echo $_['text_domestic_2']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_domestic_2" value="1" />
                  <?php echo $_['text_domestic_2']; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($usps_domestic_3) { ?>
                  <input type="checkbox" name="usps_domestic_3" value="1" checked="checked" />
                  <?php echo $_['text_domestic_3']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_domestic_3" value="1" />
                  <?php echo $_['text_domestic_3']; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($usps_domestic_4) { ?>
                  <input type="checkbox" name="usps_domestic_4" value="1" checked="checked" />
                  <?php echo $_['text_domestic_4']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_domestic_4" value="1" />
                  <?php echo $_['text_domestic_4']; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($usps_domestic_5) { ?>
                  <input type="checkbox" name="usps_domestic_5" value="1" checked="checked" />
                  <?php echo $_['text_domestic_5']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_domestic_5" value="1" />
                  <?php echo $_['text_domestic_5']; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($usps_domestic_6) { ?>
                  <input type="checkbox" name="usps_domestic_6" value="1" checked="checked" />
                  <?php echo $_['text_domestic_6']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_domestic_6" value="1" />
                  <?php echo $_['text_domestic_6']; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($usps_domestic_7) { ?>
                  <input type="checkbox" name="usps_domestic_7" value="1" checked="checked" />
                  <?php echo $_['text_domestic_7']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_domestic_7" value="1" />
                  <?php echo $_['text_domestic_7']; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($usps_domestic_12) { ?>
                  <input type="checkbox" name="usps_domestic_12" value="1" checked="checked" />
                  <?php echo $_['text_domestic_12']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_domestic_12" value="1" />
                  <?php echo $_['text_domestic_12']; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($usps_domestic_13) { ?>
                  <input type="checkbox" name="usps_domestic_13" value="1" checked="checked" />
                  <?php echo $_['text_domestic_13']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_domestic_13" value="1" />
                  <?php echo $_['text_domestic_13']; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($usps_domestic_16) { ?>
                  <input type="checkbox" name="usps_domestic_16" value="1" checked="checked" />
                  <?php echo $_['text_domestic_16']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_domestic_16" value="1" />
                  <?php echo $_['text_domestic_16']; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($usps_domestic_17) { ?>
                  <input type="checkbox" name="usps_domestic_17" value="1" checked="checked" />
                  <?php echo $_['text_domestic_17']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_domestic_17" value="1" />
                  <?php echo $_['text_domestic_17']; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($usps_domestic_18) { ?>
                  <input type="checkbox" name="usps_domestic_18" value="1" checked="checked" />
                  <?php echo $_['text_domestic_18']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_domestic_18" value="1" />
                  <?php echo $_['text_domestic_18']; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($usps_domestic_19) { ?>
                  <input type="checkbox" name="usps_domestic_19" value="1" checked="checked" />
                  <?php echo $_['text_domestic_19']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_domestic_19" value="1" />
                  <?php echo $_['text_domestic_19']; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($usps_domestic_22) { ?>
                  <input type="checkbox" name="usps_domestic_22" value="1" checked="checked" />
                  <?php echo $_['text_domestic_22']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_domestic_22" value="1" />
                  <?php echo $_['text_domestic_22']; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($usps_domestic_23) { ?>
                  <input type="checkbox" name="usps_domestic_23" value="1" checked="checked" />
                  <?php echo $_['text_domestic_23']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_domestic_23" value="1" />
                  <?php echo $_['text_domestic_23']; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($usps_domestic_25) { ?>
                  <input type="checkbox" name="usps_domestic_25" value="1" checked="checked" />
                  <?php echo $_['text_domestic_25']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_domestic_25" value="1" />
                  <?php echo $_['text_domestic_25']; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($usps_domestic_27) { ?>
                  <input type="checkbox" name="usps_domestic_27" value="1" checked="checked" />
                  <?php echo $_['text_domestic_27']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_domestic_27" value="1" />
                  <?php echo $_['text_domestic_27']; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($usps_domestic_28) { ?>
                  <input type="checkbox" name="usps_domestic_28" value="1" checked="checked" />
                  <?php echo $_['text_domestic_28']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_domestic_28" value="1" />
                  <?php echo $_['text_domestic_28']; ?>
                  <?php } ?>
                </div>
              </div>
              <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $_['text_select_all']; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $_['text_unselect_all']; ?></a></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_international']; ?></td>
            <td><div class="scrollbox">
                <?php $class = 'odd'; ?>
                <div class="even">
                  <?php if ($usps_international_1) { ?>
                  <input type="checkbox" name="usps_international_1" value="1" checked="checked" />
                  <?php echo $_['text_international_1']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_international_1" value="1" />
                  <?php echo $_['text_international_1']; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($usps_international_2) { ?>
                  <input type="checkbox" name="usps_international_2" value="1" checked="checked" />
                  <?php echo $_['text_international_2']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_international_2" value="1" />
                  <?php echo $_['text_international_2']; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($usps_international_4) { ?>
                  <input type="checkbox" name="usps_international_4" value="1" checked="checked" />
                  <?php echo $_['text_international_4']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_international_4" value="1" />
                  <?php echo $_['text_international_4']; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($usps_international_5) { ?>
                  <input type="checkbox" name="usps_international_5" value="1" checked="checked" />
                  <?php echo $_['text_international_5']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_international_5" value="1" />
                  <?php echo $_['text_international_5']; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($usps_international_6) { ?>
                  <input type="checkbox" name="usps_international_6" value="1" checked="checked" />
                  <?php echo $_['text_international_6']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_international_6" value="1" />
                  <?php echo $_['text_international_6']; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($usps_international_7) { ?>
                  <input type="checkbox" name="usps_international_7" value="1" checked="checked" />
                  <?php echo $_['text_international_7']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_international_7" value="1" />
                  <?php echo $_['text_international_7']; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($usps_international_8) { ?>
                  <input type="checkbox" name="usps_international_8" value="1" checked="checked" />
                  <?php echo $_['text_international_8']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_international_8" value="1" />
                  <?php echo $_['text_international_8']; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($usps_international_9) { ?>
                  <input type="checkbox" name="usps_international_9" value="1" checked="checked" />
                  <?php echo $_['text_international_9']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_international_9" value="1" />
                  <?php echo $_['text_international_9']; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($usps_international_10) { ?>
                  <input type="checkbox" name="usps_international_10" value="1" checked="checked" />
                  <?php echo $_['text_international_10']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_international_10" value="1" />
                  <?php echo $_['text_international_10']; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($usps_international_11) { ?>
                  <input type="checkbox" name="usps_international_11" value="1" checked="checked" />
                  <?php echo $_['text_international_11']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_international_11" value="1" />
                  <?php echo $_['text_international_11']; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($usps_international_12) { ?>
                  <input type="checkbox" name="usps_international_12" value="1" checked="checked" />
                  <?php echo $_['text_international_12']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_international_12" value="1" />
                  <?php echo $_['text_international_12']; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($usps_international_13) { ?>
                  <input type="checkbox" name="usps_international_13" value="1" checked="checked" />
                  <?php echo $_['text_international_13']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_international_13" value="1" />
                  <?php echo $_['text_international_13']; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($usps_international_14) { ?>
                  <input type="checkbox" name="usps_international_14" value="1" checked="checked" />
                  <?php echo $_['text_international_14']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_international_14" value="1" />
                  <?php echo $_['text_international_14']; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($usps_international_15) { ?>
                  <input type="checkbox" name="usps_international_15" value="1" checked="checked" />
                  <?php echo $_['text_international_15']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_international_15" value="1" />
                  <?php echo $_['text_international_15']; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($usps_international_16) { ?>
                  <input type="checkbox" name="usps_international_16" value="1" checked="checked" />
                  <?php echo $_['text_international_16']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_international_16" value="1" />
                  <?php echo $_['text_international_16']; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($usps_international_21) { ?>
                  <input type="checkbox" name="usps_international_21" value="1" checked="checked" />
                  <?php echo $_['text_international_21']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="usps_international_21" value="1" />
                  <?php echo $_['text_international_21']; ?>
                  <?php } ?>
                </div>
              </div>
              <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $_['text_select_all']; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $_['text_unselect_all']; ?></a></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_size']; ?></td>
            <td><select name="usps_size">
				<?php echo form_select_option($sizes, $usps_size, null, 'value', 'text'); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_container']; ?></td>
            <td><select name="usps_container">
				<?php echo form_select_option($containers, $usps_container, null, 'value', 'text'); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_machinable']; ?></td>
            <td><select name="usps_machinable">
				<?php echo form_select_option($_['option_yesno'], $usps_machinable, true); ?>
              </select></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_dimension']; ?></td>
            <td>
		      <input type="text" name="usps_length" value="<?php echo $usps_length; ?>" size="4" />
              <input type="text" name="usps_width" value="<?php echo $usps_width; ?>" size="4" />
              <input type="text" name="usps_height" value="<?php echo $usps_height; ?>" size="4" />
			 <?php echo form_error('usps_length'); ?>
			 <?php echo form_error('usps_width'); ?>
			 <?php echo form_error('usps_height'); ?>
		    </td>
          </tr>
		  <tr>
            <td><?php echo $_['entry_display_time']; ?></td>
            <td>
			<?php echo form_radio($_['option_yesno'], 'usps_display_time', $usps_display_time); ?>
			</td>
          </tr>
          <tr>
            <td><?php echo $_['entry_display_weight']; ?></td>
            <td>
			<?php echo form_radio($_['option_yesno'], 'usps_display_weight', $usps_display_weight); ?>
			</td>
          </tr>
          <tr>
            <td><?php echo $_['entry_weight_class']; ?></td>
            <td><select name="usps_weight_class_id">
				<?php echo form_select_option($weight_classes, $usps_weight_class_id, null, 'weight_class_id', 'title'); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_tax']; ?></td>
            <td><select name="usps_tax_class_id">
                <option value="0"><?php echo $_['text_none']; ?></option>
				<?php echo form_select_option($tax_classes, $usps_tax_class_id, null, 'tax_class_id', 'title'); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_geo_zone']; ?></td>
            <td><select name="usps_geo_zone_id">
                <option value="0"><?php echo $_['text_all_zones']; ?></option>
				<?php echo form_select_option($geo_zones, $usps_geo_zone_id, null, 'geo_zone_id', 'name'); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="usps_status">
			<?php echo form_select_option($_['option_statuses'], $usps_status, true); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="usps_sort_order" value="<?php echo $usps_sort_order; ?>" size="1" /></td>
          </tr>
		  <tr>
            <td><?php echo $_['entry_debug']; ?></td>
            <td><select name="usps_debug">
			<?php echo form_select_option($_['option_statuses'], $usps_debug, true); ?>
            </select></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>