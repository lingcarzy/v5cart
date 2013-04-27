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
      <form action="<?php echo UA('payment/worldpay'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_merchant']; ?></td>
            <td><input type="text" name="worldpay_merchant" value="<?php echo $worldpay_merchant; ?>" />
              <?php if (isset($error_merchant)) { ?>
              <span class="error"><?php echo $error_merchant; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_password']; ?></td>
            <td><input type="text" name="worldpay_password" value="<?php echo $worldpay_password; ?>" />
              <?php if (isset($error_password)) { ?>
              <span class="error"><?php echo $error_password; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_callback']; ?></td>
            <td><textarea cols="40" rows="5"><?php echo $callback; ?></textarea></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_test']; ?></td>
            <td><select name="worldpay_test">
                <?php if ($worldpay_test == '0') { ?>
                <option value="0" selected="selected"><?php echo $_['text_off']; ?></option>
                <?php } else { ?>
                <option value="0"><?php echo $_['text_off']; ?></option>
                <?php } ?>
                <?php if ($worldpay_test == '100') { ?>
                <option value="100" selected="selected"><?php echo $_['text_successful']; ?></option>
                <?php } else { ?>
                <option value="100"><?php echo $_['text_successful']; ?></option>
                <?php } ?>
                <?php if ($worldpay_test == '101') { ?>
                <option value="101" selected="selected"><?php echo $_['text_declined']; ?></option>
                <?php } else { ?>
                <option value="101"><?php echo $_['text_declined']; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_total']; ?></td>
            <td><input type="text" name="worldpay_total" value="<?php echo $worldpay_total; ?>" /></td>
          </tr>          
          <tr>
            <td><?php echo $_['entry_order_status']; ?></td>
            <td><select name="worldpay_order_status_id">
				<?php echo form_select_option($order_statuses, $worldpay_order_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_geo_zone']; ?></td>
            <td><select name="worldpay_geo_zone_id">
                <option value="0"><?php echo $_['text_all_zones']; ?></option>
				<?php echo form_select_option($geo_zones, $worldpay_geo_zone_id, null, 'geo_zone_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="worldpay_status">
				<?php echo form_select_option($_['option_statuses'], $worldpay_status, true);?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="worldpay_sort_order" value="<?php echo $worldpay_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 