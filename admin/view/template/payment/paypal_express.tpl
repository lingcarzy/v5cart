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
    <div class="left"></div>
    <div class="right"></div>
    <div class="heading">
      <h1><?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $_['button_save']; ?></span></a>
	  <a href="<?php echo UA('extension/payment'); ?>" class="button"><span><?php echo $_['button_cancel']; ?></span></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('payment/paypal_express'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_username']; ?></td>
            <td><input type="text" name="paypal_express_username" value="<?php echo $paypal_express_username; ?>" size="45"/>
              <?php if (isset($error_username)) { ?>
              <span class="error"><?php echo $error_username; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_password']; ?></td>
            <td><input type="text" name="paypal_express_password" value="<?php echo $paypal_express_password; ?>"  size="45"/>
              <?php if (isset($error_password)) { ?>
              <span class="error"><?php echo $error_password; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_signature']; ?></td>
            <td><input type="text" name="paypal_express_signature" value="<?php echo $paypal_express_signature; ?>"  size="45"/>
              <?php if (isset($error_signature)) { ?>
              <span class="error"><?php echo $error_signature; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_add_to_cart']; ?></td>
            <td>
			<?php echo form_radio($_['option_yesno'], 'paypal_express_add_to_cart', $paypal_express_add_to_cart); ?>
			</td>
          </tr>
          <tr>
            <td><?php echo $_['entry_test']; ?></td>
            <td>
			<?php echo form_radio($_['option_yesno'], 'paypal_express_test', $paypal_express_test); ?>
			</td>
          </tr>
          <tr>
            <td><?php echo $_['entry_method']; ?></td>
            <td><select name="paypal_express_method">
                <?php if (!$paypal_express_method) { ?>
                <option value="0" selected="selected"><?php echo $_['text_authorization']; ?></option>
                <?php } else { ?>
                <option value="0"><?php echo $_['text_authorization']; ?></option>
                <?php } ?>
                <?php if ($paypal_express_method) { ?>
                <option value="1" selected="selected"><?php echo $_['text_sale']; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $_['text_sale']; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_order_status']; ?></td>
            <td><select name="paypal_express_order_status_id">
				<?php echo form_select_option($order_statuses, $paypal_express_order_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_geo_zone']; ?></td>
            <td><select name="paypal_express_geo_zone_id">
                <option value="0"><?php echo $_['text_all_zones']; ?></option>
				<?php echo form_select_option($geo_zones, $paypal_express_geo_zone_id, null, 'geo_zone_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="paypal_express_status">
				<?php echo form_select_option($_['option_statuses'], $paypal_express_status, true);?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="paypal_express_sort_order" value="<?php echo $paypal_express_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>