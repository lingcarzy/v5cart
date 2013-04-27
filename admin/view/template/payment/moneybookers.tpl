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
      <form action="<?php echo UA('payment/moneybookers'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><?php echo $_['entry_email']; ?></td>
            <td><input type="text" name="moneybookers_email" value="<?php echo $moneybookers_email; ?>" />
              <?php if (isset($error_email)) { ?>
              <span class="error"><?php echo $error_email; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_secret']; ?></td>
            <td><input type="text" name="moneybookers_secret" value="<?php echo $moneybookers_secret; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_total']; ?></td>
            <td><input type="text" name="moneybookers_total" value="<?php echo $moneybookers_total; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_order_status']; ?></td>
            <td><select name="moneybookers_order_status_id">
				<?php echo form_select_option($order_statuses, $moneybookers_order_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_pending_status']; ?></td>
            <td><select name="moneybookers_pending_status_id">
				<?php echo form_select_option($order_statuses, $moneybookers_pending_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_canceled_status']; ?></td>
            <td><select name="moneybookers_canceled_status_id">
				<?php echo form_select_option($order_statuses, $moneybookers_canceled_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_failed_status']; ?></td>
            <td><select name="moneybookers_failed_status_id">
			<?php echo form_select_option($order_statuses, $moneybookers_failed_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_chargeback_status']; ?></td>
            <td><select name="moneybookers_chargeback_status_id">
			<?php echo form_select_option($order_statuses, $moneybookers_chargeback_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_geo_zone']; ?></td>
            <td><select name="moneybookers_geo_zone_id">
                <option value="0"><?php echo $_['text_all_zones']; ?></option>
				<?php echo form_select_option($geo_zones, $moneybookers_geo_zone_id, null, 'geo_zone_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="moneybookers_status">
				<?php echo form_select_option($_['option_statuses'], $moneybookers_status, true);?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="moneybookers_sort_order" value="<?php echo $moneybookers_sort_order; ?>" size="3" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 