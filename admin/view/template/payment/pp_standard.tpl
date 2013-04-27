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
      <form action="<?php echo UA('payment/pp_standard'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_email']; ?></td>
            <td><input type="text" name="pp_standard_email" value="<?php echo $pp_standard_email; ?>" />
              <?php if (isset($error_email)) { ?>
              <span class="error"><?php echo $error_email; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_test']; ?></td>
            <td><?php if ($pp_standard_test) { ?>
              <input type="radio" name="pp_standard_test" value="1" checked="checked" />
              <?php echo $_['text_yes']; ?>
              <input type="radio" name="pp_standard_test" value="0" />
              <?php echo $_['text_no']; ?>
              <?php } else { ?>
              <input type="radio" name="pp_standard_test" value="1" />
              <?php echo $_['text_yes']; ?>
              <input type="radio" name="pp_standard_test" value="0" checked="checked" />
              <?php echo $_['text_no']; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_transaction']; ?></td>
            <td><select name="pp_standard_transaction">
                <?php if (!$pp_standard_transaction) { ?>
                <option value="0" selected="selected"><?php echo $_['text_authorization']; ?></option>
                <?php } else { ?>
                <option value="0"><?php echo $_['text_authorization']; ?></option>
                <?php } ?>
                <?php if ($pp_standard_transaction) { ?>
                <option value="1" selected="selected"><?php echo $_['text_sale']; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $_['text_sale']; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_debug']; ?></td>
            <td><select name="pp_standard_debug">
				<?php echo form_select_option($_['option_statuses'], $pp_standard_debug, true);?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_total']; ?></td>
            <td><input type="text" name="pp_standard_total" value="<?php echo $pp_standard_total; ?>" /></td>
          </tr>          
          <tr>
            <td><?php echo $_['entry_canceled_reversal_status']; ?></td>
            <td><select name="pp_standard_canceled_reversal_status_id">
				<?php echo form_select_option($order_statuses, $pp_standard_canceled_reversal_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_completed_status']; ?></td>
            <td><select name="pp_standard_completed_status_id">
				<?php echo form_select_option($order_statuses, $pp_standard_completed_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_denied_status']; ?></td>
            <td><select name="pp_standard_denied_status_id">
			<?php echo form_select_option($order_statuses, $pp_standard_denied_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_expired_status']; ?></td>
            <td><select name="pp_standard_expired_status_id">
			<?php echo form_select_option($order_statuses, $pp_standard_expired_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_failed_status']; ?></td>
            <td><select name="pp_standard_failed_status_id">
			<?php echo form_select_option($order_statuses, $pp_standard_failed_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_pending_status']; ?></td>
            <td><select name="pp_standard_pending_status_id">
			<?php echo form_select_option($order_statuses, $pp_standard_pending_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_processed_status']; ?></td>
            <td><select name="pp_standard_processed_status_id">
			<?php echo form_select_option($order_statuses, $pp_standard_processed_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_refunded_status']; ?></td>
            <td><select name="pp_standard_refunded_status_id">
			<?php echo form_select_option($order_statuses, $pp_standard_refunded_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_reversed_status']; ?></td>
            <td><select name="pp_standard_reversed_status_id">
			<?php echo form_select_option($order_statuses, $pp_standard_reversed_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_voided_status']; ?></td>
            <td><select name="pp_standard_voided_status_id">
			<?php echo form_select_option($order_statuses, $pp_standard_voided_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_geo_zone']; ?></td>
            <td><select name="pp_standard_geo_zone_id">
                <option value="0"><?php echo $_['text_all_zones']; ?></option>
				<?php echo form_select_option($geo_zones, $pp_standard_geo_zone_id, null, 'geo_zone_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="pp_standard_status">
				<?php echo form_select_option($_['option_statuses'], $pp_standard_status, true);?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="pp_standard_sort_order" value="<?php echo $pp_standard_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 