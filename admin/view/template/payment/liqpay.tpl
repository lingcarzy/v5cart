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
      <form action="<?php echo UA('payment/liqpay'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_merchant']; ?></td>
            <td><input type="text" name="liqpay_merchant" value="<?php echo $liqpay_merchant; ?>" />
              <?php if (isset($error_merchant)) { ?>
              <span class="error"><?php echo $error_merchant; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_signature']; ?></td>
            <td><input type="text" name="liqpay_signature" value="<?php echo $liqpay_signature; ?>" />
              <?php if (isset($error_signature)) { ?>
              <span class="error"><?php echo $error_signature; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_type']; ?></td>
            <td><select name="liqpay_type">
                <?php if ($liqpay_type == 'liqpay') { ?>
                <option value="liqpay" selected="selected"><?php echo $_['text_pay']; ?></option>
                <?php } else { ?>
                <option value="liqpay"><?php echo $_['text_pay']; ?></option>
                <?php } ?>
                <?php if ($liqpay_type == 'card') { ?>
                <option value="card" selected="selected"><?php echo $_['text_card']; ?></option>
                <?php } else { ?>
                <option value="card"><?php echo $_['text_card']; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_total']; ?></td>
            <td><input type="text" name="liqpay_total" value="<?php echo $liqpay_total; ?>" /></td>
          </tr>          
          <tr>
            <td><?php echo $_['entry_order_status']; ?></td>
            <td><select name="liqpay_order_status_id">
			   <?php echo form_select_option($order_statuses, $liqpay_order_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_geo_zone']; ?></td>
            <td><select name="liqpay_geo_zone_id">
                <option value="0"><?php echo $_['text_all_zones']; ?></option>
				<?php echo form_select_option($geo_zones, $liqpay_geo_zone_id, null, 'geo_zone_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="liqpay_status">
			   <?php echo form_select_option($_['option_statuses'], $liqpay_status, true);?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="liqpay_sort_order" value="<?php echo $liqpay_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>