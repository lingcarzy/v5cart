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
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a>
	  <a href="<?php echo UA('extension/payment'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('payment/paypoint'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_merchant']; ?></td>
            <td><input type="text" name="paypoint_merchant" value="<?php echo $paypoint_merchant; ?>" />
              <?php if (isset($error_merchant)) { ?>
              <span class="error"><?php echo $error_merchant; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_password']; ?></td>
            <td><input type="text" name="paypoint_password" value="<?php echo $paypoint_password; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_test']; ?></td>
            <td><select name="paypoint_test">
                <?php if ($paypoint_test == 'live') { ?>
                <option value="live" selected="selected"><?php echo $_['text_live']; ?></option>
                <?php } else { ?>
                <option value="live"><?php echo $_['text_live']; ?></option>
                <?php } ?>
                <?php if ($paypoint_test == 'successful') { ?>
                <option value="successful" selected="selected"><?php echo $_['text_successful']; ?></option>
                <?php } else { ?>
                <option value="successful"><?php echo $_['text_successful']; ?></option>
                <?php } ?>
                <?php if ($paypoint_test == 'fail') { ?>
                <option value="fail" selected="selected"><?php echo $_['text_fail']; ?></option>
                <?php } else { ?>
                <option value="fail"><?php echo $_['text_fail']; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_total']; ?></td>
            <td><input type="text" name="paypoint_total" value="<?php echo $paypoint_total; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_order_status']; ?></td>
            <td><select name="paypoint_order_status_id">
				<?php echo form_select_option($order_statuses, $paypoint_order_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_geo_zone']; ?></td>
            <td><select name="paypoint_geo_zone_id">
                <option value="0"><?php echo $_['text_all_zones']; ?></option>
				<?php echo form_select_option($geo_zones, $paypoint_geo_zone_id, null, 'geo_zone_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="paypoint_status">
				<?php echo form_select_option($_['option_statuses'], $paypoint_status, true);?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="paypoint_sort_order" value="<?php echo $paypoint_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>