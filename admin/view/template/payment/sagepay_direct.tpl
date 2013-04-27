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
      <form action="<?php echo UA('payment/sagepay_direct'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_vendor']; ?></td>
            <td><input type="text" name="sagepay_direct_vendor" value="<?php echo $sagepay_direct_vendor; ?>" />
              <?php if (isset($error_vendor)) { ?>
              <span class="error"><?php echo $error_vendor; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_test']; ?></td>
            <td><select name="sagepay_direct_test">
                <?php if ($sagepay_direct_test == 'sim') { ?>
                <option value="sim" selected="selected"><?php echo $_['text_sim']; ?></option>
                <?php } else { ?>
                <option value="sim"><?php echo $_['text_sim']; ?></option>
                <?php } ?>
                <?php if ($sagepay_direct_test == 'test') { ?>
                <option value="test" selected="selected"><?php echo $_['text_test']; ?></option>
                <?php } else { ?>
                <option value="test"><?php echo $_['text_test']; ?></option>
                <?php } ?>
                <?php if ($sagepay_direct_test == 'live') { ?>
                <option value="live" selected="selected"><?php echo $_['text_live']; ?></option>
                <?php } else { ?>
                <option value="live"><?php echo $_['text_live']; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_transaction']; ?></td>
            <td><select name="sagepay_direct_transaction">
                <?php if ($sagepay_direct_transaction == 'PAYMENT') { ?>
                <option value="PAYMENT" selected="selected"><?php echo $_['text_payment']; ?></option>
                <?php } else { ?>
                <option value="PAYMENT"><?php echo $_['text_payment']; ?></option>
                <?php } ?>
                <?php if ($sagepay_direct_transaction == 'DEFERRED') { ?>
                <option value="DEFERRED" selected="selected"><?php echo $_['text_defered']; ?></option>
                <?php } else { ?>
                <option value="DEFERRED"><?php echo $_['text_defered']; ?></option>
                <?php } ?>
                <?php if ($sagepay_direct_transaction == 'AUTHENTICATE') { ?>
                <option value="AUTHENTICATE" selected="selected"><?php echo $_['text_authenticate']; ?></option>
                <?php } else { ?>
                <option value="AUTHENTICATE"><?php echo $_['text_authenticate']; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_total']; ?></td>
            <td><input type="text" name="sagepay_direct_total" value="<?php echo $sagepay_direct_total; ?>" /></td>
          </tr>          
          <tr>
            <td><?php echo $_['entry_order_status']; ?></td>
            <td><select name="sagepay_direct_order_status_id">
				<?php echo form_select_option($order_statuses, $sagepay_direct_order_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_geo_zone']; ?></td>
            <td><select name="sagepay_direct_geo_zone_id">
                <option value="0"><?php echo $_['text_all_zones']; ?></option>
				<?php echo form_select_option($geo_zones, $sagepay_direct_geo_zone_id, null, 'geo_zone_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="sagepay_direct_status">
				<?php echo form_select_option($_['option_statuses'], $sagepay_direct_status, true);?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="sagepay_direct_sort_order" value="<?php echo $sagepay_direct_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 