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
      <form action="<?php echo UA('payment/twocheckout'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><?php echo $_['entry_account']; ?></td>
            <td><input type="text" name="twocheckout_account" value="<?php echo $twocheckout_account; ?>" />
              <?php if (isset($error_account)) { ?>
              <span class="error"><?php echo $error_account; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_secret']; ?></td>
            <td><input type="text" name="twocheckout_secret" value="<?php echo $twocheckout_secret; ?>" />
              <?php if (isset($error_secret)) { ?>
              <span class="error"><?php echo $error_secret; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_test']; ?></td>
            <td>
			<?php echo form_radio($_['option_yesno'], 'twocheckout_test', $twocheckout_test); ?>
			</td>
          </tr>
          <tr>
            <td><?php echo $_['entry_total']; ?></td>
            <td><input type="text" name="twocheckout_total" value="<?php echo $twocheckout_total; ?>" /></td>
          </tr>          
          <tr>
            <td><?php echo $_['entry_order_status']; ?></td>
            <td><select name="twocheckout_order_status_id">
				<?php echo form_select_option($order_statuses, $twocheckout_order_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_geo_zone']; ?></td>
            <td><select name="twocheckout_geo_zone_id">
                <option value="0"><?php echo $_['text_all_zones']; ?></option>
				<?php echo form_select_option($geo_zones, $twocheckout_geo_zone_id, null, 'geo_zone_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="twocheckout_status">
				<?php echo form_select_option($_['option_statuses'], $twocheckout_status, true);?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="twocheckout_sort_order" value="<?php echo $twocheckout_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>