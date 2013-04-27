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
      <form action="<?php echo UA('shipping/ups'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_key']; ?></td>
            <td><input type="text" name="ups_key" value="<?php echo $ups_key; ?>" />
              <?php echo form_error('ups_key'); ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_username']; ?></td>
            <td><input type="text" name="ups_username" value="<?php echo $ups_username; ?>" />
              <?php echo form_error('ups_username'); ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_password']; ?></td>
            <td><input type="text" name="ups_password" value="<?php echo $ups_password; ?>" />
             <?php echo form_error('ups_password'); ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_pickup']; ?></td>
            <td><select name="ups_pickup">
				<?php echo form_select_option($pickups, $ups_pickup, null, 'value', 'text'); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_packaging']; ?></td>
            <td><select name="ups_packaging">
				<?php echo form_select_option($packages, $ups_packaging, null, 'value', 'text'); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_classification']; ?></td>
            <td><select name="ups_classification">
				<?php echo form_select_option($classifications, $ups_classification, null, 'value', 'text'); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_origin']; ?></td>
            <td><select name="ups_origin">
				<?php echo form_select_option($origins, $ups_origin, null, 'value', 'text'); ?>
              </select></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_city']; ?></td>
            <td><input type="text" name="ups_city" value="<?php echo $ups_city; ?>" />
              <?php echo form_error('ups_city'); ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_state']; ?></td>
            <td><input type="text" name="ups_state" value="<?php echo $ups_state; ?>" maxlength="2" size="4" />
              <?php echo form_error('ups_state'); ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_country']; ?></td>
            <td><input type="text" name="ups_country" value="<?php echo $ups_country; ?>" maxlength="2" size="4" />
              <?php echo form_error('ups_country'); ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_postcode']; ?></td>
            <td><input type="text" name="ups_postcode" value="<?php echo $ups_postcode; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_test']; ?></td>
            <td>
			<?php echo form_radio($_['option_yesno'], 'ups_test', $ups_test); ?>
			</td>
          </tr>
          <tr>
            <td><?php echo $_['entry_quote_type']; ?></td>
            <td><select name="ups_quote_type">
				<?php echo form_select_option($quote_types, $ups_quote_type, null, 'value', 'text'); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_service']; ?></td>
            <td id="service"><div id="US">
                <div class="scrollbox">
                  <div class="even">
                    <?php if ($ups_us_01) { ?>
                    <input type="checkbox" name="ups_us_01" value="1" checked="checked" />
                    <?php echo $_['text_next_day_air']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_us_01" value="1" />
                    <?php echo $_['text_next_day_air']; ?>
                    <?php } ?>
                  </div>
                  <div class="odd">
                    <?php if ($ups_us_02) { ?>
                    <input type="checkbox" name="ups_us_02" value="1" checked="checked" />
                    <?php echo $_['text_2nd_day_air']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_us_02" value="1" />
                    <?php echo $_['text_2nd_day_air']; ?>
                    <?php } ?>
                  </div>
                  <div class="even">
                    <?php if ($ups_us_03) { ?>
                    <input type="checkbox" name="ups_us_03" value="1" checked="checked" />
                    <?php echo $_['text_ground']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_us_03" value="1" />
                    <?php echo $_['text_ground']; ?>
                    <?php } ?>
                  </div>
                  <div class="odd">
                    <?php if ($ups_us_07) { ?>
                    <input type="checkbox" name="ups_us_07" value="1" checked="checked" />
                    <?php echo $_['text_worldwide_express']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_us_07" value="1" />
                    <?php echo $_['text_worldwide_express']; ?>
                    <?php } ?>
                  </div>
                  <div class="even">
                    <?php if ($ups_us_08) { ?>
                    <input type="checkbox" name="ups_us_08" value="1" checked="checked" />
                    <?php echo $_['text_worldwide_expedited']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_us_08" value="1" />
                    <?php echo $_['text_worldwide_expedited']; ?>
                    <?php } ?>
                  </div>
                  <div class="odd">
                    <?php if ($ups_us_11) { ?>
                    <input type="checkbox" name="ups_us_11" value="1" checked="checked" />
                    <?php echo $_['text_standard']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_us_11" value="1" />
                    <?php echo $_['text_standard']; ?>
                    <?php } ?>
                  </div>
                  <div class="even">
                    <?php if ($ups_us_12) { ?>
                    <input type="checkbox" name="ups_us_12" value="1" checked="checked" />
                    <?php echo $_['text_3_day_select']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_us_12" value="1" />
                    <?php echo $_['text_3_day_select']; ?>
                    <?php } ?>
                  </div>
                  <div class="even">
                    <?php if ($ups_us_13) { ?>
                    <input type="checkbox" name="ups_us_13" value="1" checked="checked" />
                    <?php echo $_['text_next_day_air_saver']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_us_13" value="1" />
                    <?php echo $_['text_next_day_air_saver']; ?>
                    <?php } ?>
                  </div>
                  <div class="odd">
                    <?php if ($ups_us_14) { ?>
                    <input type="checkbox" name="ups_us_14" value="1" checked="checked" />
                    <?php echo $_['text_next_day_air_early_am']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_us_14" value="1" />
                    <?php echo $_['text_next_day_air_early_am']; ?>
                    <?php } ?>
                  </div>
                  <div class="even">
                    <?php if ($ups_us_54) { ?>
                    <input type="checkbox" name="ups_us_54" value="1" checked="checked" />
                    <?php echo $_['text_worldwide_express_plus']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_us_54" value="1" />
                    <?php echo $_['text_worldwide_express_plus']; ?>
                    <?php } ?>
                  </div>
                  <div class="odd">
                    <?php if ($ups_us_59) { ?>
                    <input type="checkbox" name="ups_us_59" value="1" checked="checked" />
                    <?php echo $_['text_2nd_day_air_am']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_us_59" value="1" />
                    <?php echo $_['text_2nd_day_air_am']; ?>
                    <?php } ?>
                  </div>
                  <div class="even">
                    <?php if ($ups_us_65) { ?>
                    <input type="checkbox" name="ups_us_65" value="1" checked="checked" />
                    <?php echo $_['text_saver']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_us_65" value="1" />
                    <?php echo $_['text_saver']; ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div id="PR">
                <div class="scrollbox">
                  <div class="even">
                    <?php if ($ups_pr_01) { ?>
                    <input type="checkbox" name="ups_pr_01" value="1" checked="checked" />
                    <?php echo $_['text_next_day_air']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_pr_01" value="1" />
                    <?php echo $_['text_next_day_air']; ?>
                    <?php } ?>
                  </div>
                  <div class="odd">
                    <?php if ($ups_pr_02) { ?>
                    <input type="checkbox" name="ups_pr_02" value="1" checked="checked" />
                    <?php echo $_['text_2nd_day_air']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_pr_02" value="1" />
                    <?php echo $_['text_2nd_day_air']; ?>
                    <?php } ?>
                  </div>
                  <div class="even">
                    <?php if ($ups_pr_03) { ?>
                    <input type="checkbox" name="ups_pr_03" value="1" checked="checked" />
                    <?php echo $_['text_ground']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_pr_03" value="1" />
                    <?php echo $_['text_ground']; ?>
                    <?php } ?>
                  </div>
                  <div class="odd">
                    <?php if ($ups_pr_07) { ?>
                    <input type="checkbox" name="ups_pr_07" value="1" checked="checked" />
                    <?php echo $_['text_worldwide_express']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_pr_07" value="1" />
                    <?php echo $_['text_worldwide_express']; ?>
                    <?php } ?>
                  </div>
                  <div class="even">
                    <?php if ($ups_pr_08) { ?>
                    <input type="checkbox" name="ups_pr_08" value="1" checked="checked" />
                    <?php echo $_['text_worldwide_expedited']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_pr_08" value="1" />
                    <?php echo $_['text_worldwide_expedited']; ?>
                    <?php } ?>
                  </div>
                  <div class="odd">
                    <?php if ($ups_pr_14) { ?>
                    <input type="checkbox" name="ups_pr_14" value="1" checked="checked" />
                    <?php echo $_['text_next_day_air_early_am']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_pr_14" value="1" />
                    <?php echo $_['text_next_day_air_early_am']; ?>
                    <?php } ?>
                  </div>
                  <div class="even">
                    <?php if ($ups_pr_54) { ?>
                    <input type="checkbox" name="ups_pr_54" value="1" checked="checked" />
                    <?php echo $_['text_worldwide_express_plus']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_pr_54" value="1" />
                    <?php echo $_['text_worldwide_express_plus']; ?>
                    <?php } ?>
                  </div>
                  <div class="even">
                    <?php if ($ups_pr_65) { ?>
                    <input type="checkbox" name="ups_pr_65" value="1" checked="checked" />
                    <?php echo $_['text_saver']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_pr_65" value="1" />
                    <?php echo $_['text_saver']; ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div id="CA">
                <div class="scrollbox">
                  <div class="even">
                    <?php if ($ups_ca_01) { ?>
                    <input type="checkbox" name="ups_ca_01" value="1" checked="checked" />
                    <?php echo $_['text_express']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_ca_01" value="1" />
                    <?php echo $_['text_express']; ?>
                    <?php } ?>
                  </div>
                  <div class="odd">
                    <?php if ($ups_ca_02) { ?>
                    <input type="checkbox" name="ups_ca_02" value="1" checked="checked" />
                    <?php echo $_['text_expedited']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_ca_02" value="1" />
                    <?php echo $_['text_expedited']; ?>
                    <?php } ?>
                  </div>
                  <div class="even">
                    <?php if ($ups_ca_07) { ?>
                    <input type="checkbox" name="ups_ca_07" value="1" checked="checked" />
                    <?php echo $_['text_worldwide_express']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_ca_07" value="1" />
                    <?php echo $_['text_worldwide_express']; ?>
                    <?php } ?>
                  </div>
                  <div class="odd">
                    <?php if ($ups_ca_08) { ?>
                    <input type="checkbox" name="ups_ca_08" value="1" checked="checked" />
                    <?php echo $_['text_worldwide_expedited']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_ca_08" value="1" />
                    <?php echo $_['text_worldwide_expedited']; ?>
                    <?php } ?>
                  </div>
                  <div class="even">
                    <?php if ($ups_ca_11) { ?>
                    <input type="checkbox" name="ups_ca_11" value="1" checked="checked" />
                    <?php echo $_['text_standard']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_ca_11" value="1" />
                    <?php echo $_['text_standard']; ?>
                    <?php } ?>
                  </div>
                  <div class="odd">
                    <?php if ($ups_ca_12) { ?>
                    <input type="checkbox" name="ups_ca_12" value="1" checked="checked" />
                    <?php echo $_['text_3_day_select']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_ca_12" value="1" />
                    <?php echo $_['text_3_day_select']; ?>
                    <?php } ?>
                  </div>
                  <div class="even">
                    <?php if ($ups_ca_13) { ?>
                    <input type="checkbox" name="ups_ca_13" value="1" checked="checked" />
                    <?php echo $_['text_saver']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_ca_13" value="1" />
                    <?php echo $_['text_saver']; ?>
                    <?php } ?>
                  </div>
                  <div class="odd">
                    <?php if ($ups_ca_14) { ?>
                    <input type="checkbox" name="ups_ca_14" value="1" checked="checked" />
                    <?php echo $_['text_express_early_am']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_ca_14" value="1" />
                    <?php echo $_['text_express_early_am']; ?>
                    <?php } ?>
                  </div>
                  <div class="even">
                    <?php if ($ups_ca_54) { ?>
                    <input type="checkbox" name="ups_ca_54" value="1" checked="checked" />
                    <?php echo $_['text_worldwide_express_plus']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_ca_54" value="1" />
                    <?php echo $_['text_worldwide_express_plus']; ?>
                    <?php } ?>
                  </div>
                  <div class="odd">
                    <?php if ($ups_ca_65) { ?>
                    <input type="checkbox" name="ups_ca_65" value="1" checked="checked" />
                    <?php echo $_['text_saver']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_ca_65" value="1" />
                    <?php echo $_['text_saver']; ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div id="MX">
                <div class="scrollbox">
                  <div class="even">
                    <?php if ($ups_mx_07) { ?>
                    <input type="checkbox" name="ups_mx_07" value="1" checked="checked" />
                    <?php echo $_['text_worldwide_express']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_mx_07" value="1" />
                    <?php echo $_['text_worldwide_express']; ?>
                    <?php } ?>
                  </div>
                  <div class="odd">
                    <?php if ($ups_mx_08) { ?>
                    <input type="checkbox" name="ups_mx_08" value="1" checked="checked" />
                    <?php echo $_['text_worldwide_expedited']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_mx_08" value="1" />
                    <?php echo $_['text_worldwide_expedited']; ?>
                    <?php } ?>
                  </div>
                  <div class="even">
                    <?php if ($ups_mx_54) { ?>
                    <input type="checkbox" name="ups_mx_54" value="1" checked="checked" />
                    <?php echo $_['text_worldwide_express_plus']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_mx_54" value="1" />
                    <?php echo $_['text_worldwide_express_plus']; ?>
                    <?php } ?>
                  </div>
                  <div class="odd">
                    <?php if ($ups_mx_65) { ?>
                    <input type="checkbox" name="ups_mx_65" value="1" checked="checked" />
                    <?php echo $_['text_saver']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_mx_65" value="1" />
                    <?php echo $_['text_saver']; ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div id="EU">
                <div class="scrollbox">
                  <div class="even">
                    <?php if ($ups_eu_07) { ?>
                    <input type="checkbox" name="ups_eu_07" value="1" checked="checked" />
                    <?php echo $_['text_express']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_eu_07" value="1" />
                    <?php echo $_['text_express']; ?>
                    <?php } ?>
                  </div>
                  <div class="odd">
                    <?php if ($ups_eu_08) { ?>
                    <input type="checkbox" name="ups_eu_08" value="1" checked="checked" />
                    <?php echo $_['text_expedited']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_eu_08" value="1" />
                    <?php echo $_['text_expedited']; ?>
                    <?php } ?>
                  </div>
                  <div class="even">
                    <?php if ($ups_eu_11) { ?>
                    <input type="checkbox" name="ups_eu_11" value="1" checked="checked" />
                    <?php echo $_['text_standard']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_eu_11" value="1" />
                    <?php echo $_['text_standard']; ?>
                    <?php } ?>
                  </div>
                  <div class="odd">
                    <?php if ($ups_eu_54) { ?>
                    <input type="checkbox" name="ups_eu_54" value="1" checked="checked" />
                    <?php echo $_['text_worldwide_express_plus']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_eu_54" value="1" />
                    <?php echo $_['text_worldwide_express_plus']; ?>
                    <?php } ?>
                  </div>
                  <div class="even">
                    <?php if ($ups_eu_65) { ?>
                    <input type="checkbox" name="ups_eu_65" value="1" checked="checked" />
                    <?php echo $_['text_saver']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_eu_65" value="1" />
                    <?php echo $_['text_saver']; ?>
                    <?php } ?>
                  </div>
                  <div class="odd">
                    <?php if ($ups_eu_82) { ?>
                    <input type="checkbox" name="ups_eu_82" value="1" checked="checked" />
                    <?php echo $_['text_today_standard']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_eu_82" value="1" />
                    <?php echo $_['text_today_standard']; ?>
                    <?php } ?>
                  </div>
                  <div class="even">
                    <?php if ($ups_eu_83) { ?>
                    <input type="checkbox" name="ups_eu_83" value="1" checked="checked" />
                    <?php echo $_['text_today_dedicated_courier']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_eu_83" value="1" />
                    <?php echo $_['text_today_dedicated_courier']; ?>
                    <?php } ?>
                  </div>
                  <div class="odd">
                    <?php if ($ups_eu_84) { ?>
                    <input type="checkbox" name="ups_eu_84" value="1" checked="checked" />
                    <?php echo $_['text_today_intercity']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_eu_84" value="1" />
                    <?php echo $_['text_today_intercity']; ?>
                    <?php } ?>
                  </div>
                  <div class="even">
                    <?php if ($ups_eu_85) { ?>
                    <input type="checkbox" name="ups_eu_85" value="1" checked="checked" />
                    <?php echo $_['text_today_express']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_eu_85" value="1" />
                    <?php echo $_['text_today_express']; ?>
                    <?php } ?>
                  </div>
                  <div class="odd">
                    <?php if ($ups_eu_86) { ?>
                    <input type="checkbox" name="ups_eu_86" value="1" checked="checked" />
                    <?php echo $_['text_today_express_saver']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_eu_86" value="1" />
                    <?php echo $_['text_today_express_saver']; ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div id="other">
                <div class="scrollbox">
                  <div class="even">
                    <?php if ($ups_other_07) { ?>
                    <input type="checkbox" name="ups_other_07" value="1" checked="checked" />
                    <?php echo $_['text_express']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_other_07" value="1" />
                    <?php echo $_['text_express']; ?>
                    <?php } ?>
                  </div>
                  <div class="odd">
                    <?php if ($ups_other_08) { ?>
                    <input type="checkbox" name="ups_other_08" value="1" checked="checked" />
                    <?php echo $_['text_expedited']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_other_08" value="1" />
                    <?php echo $_['text_expedited']; ?>
                    <?php } ?>
                  </div>
                  <div class="even">
                    <?php if ($ups_other_11) { ?>
                    <input type="checkbox" name="ups_other_11" value="1" checked="checked" />
                    <?php echo $_['text_standard']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_other_11" value="1" />
                    <?php echo $_['text_standard']; ?>
                    <?php } ?>
                  </div>
                  <div class="odd">
                    <?php if ($ups_other_54) { ?>
                    <input type="checkbox" name="ups_other_54" value="1" checked="checked" />
                    <?php echo $_['text_worldwide_express_plus']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_other_54" value="1" />
                    <?php echo $_['text_worldwide_express_plus']; ?>
                    <?php } ?>
                  </div>
                  <div class="even">
                    <?php if ($ups_other_65) { ?>
                    <input type="checkbox" name="ups_other_65" value="1" checked="checked" />
                    <?php echo $_['text_saver']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ups_other_65" value="1" />
                    <?php echo $_['text_saver']; ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $_['text_select_all']; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $_['text_unselect_all']; ?></a></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_insurance']; ?></td>
            <td>
			<?php echo form_radio($_['option_yesno'], 'ups_insurance', $ups_insurance); ?>
			</td>
          </tr>
          <tr>
            <td><?php echo $_['entry_display_weight']; ?></td>
            <td>
			<?php echo form_radio($_['option_yesno'], 'ups_display_weight', $ups_display_weight); ?>
			</td>
          </tr>
          <tr>
            <td><?php echo $_['entry_weight_class']; ?></td>
            <td><select name="ups_weight_class_id">
				<?php echo form_select_option($weight_classes, $ups_weight_class_id, null, 'weight_class_id', 'title'); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_length_class']; ?></td>
            <td><select name="ups_length_class_id">
				<?php echo form_select_option($length_classes, $ups_length_class_id, null, 'length_class_id', 'title'); ?>
              </select></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_dimension']; ?></td>
            <td><input type="text" name="ups_length" value="<?php echo $ups_length; ?>" size="4" />
              <input type="text" name="ups_width" value="<?php echo $ups_width; ?>" size="4" />
              <input type="text" name="ups_height" value="<?php echo $ups_height; ?>" size="4" />
			  <?php echo form_error('ups_length'); ?>
			  <?php echo form_error('ups_width'); ?>
			  <?php echo form_error('ups_height'); ?>
			  </td>
          </tr>
          <tr>
            <td><?php echo $_['entry_tax_class']; ?></td>
            <td><select name="ups_tax_class_id">
                <option value="0"><?php echo $_['text_none']; ?></option>
				<?php echo form_select_option($tax_classes, $ups_tax_class_id, null, 'tax_class_id', 'title'); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_geo_zone']; ?></td>
            <td><select name="ups_geo_zone_id">
                <option value="0"><?php echo $_['text_all_zones']; ?></option>
				<?php echo form_select_option($geo_zones, $ups_geo_zone_id, null, 'geo_zone_id', 'name'); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="ups_status">
				<?php echo form_select_option($_['option_statuses'], $ups_status, true); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="ups_sort_order" value="<?php echo $ups_sort_order; ?>" size="1" /></td>
          </tr>
		  <tr>
            <td><?php echo $_['entry_debug']; ?></td>
            <td><select name="ups_debug">
				<?php echo form_select_option($_['option_statuses'], $ups_debug, true); ?>
            </select></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'ups_origin\']').bind('change', function() {
	$('#service > div').hide();

	$('#' + this.value).show();
});

$('select[name=\'ups_origin\']').trigger('change');
//--></script>
<?php echo $footer; ?>