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
      <div class="buttons">
	  <a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a>
	  <a href="<?php echo UA('extension/payment'); ?>" class="button"><?php echo $_['button_cancel']; ?></a>
	  </div>
    </div>
    <div class="content">
      <form action="<?php echo UA('payment/authorizenet_aim'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_login']; ?></td>
            <td><input type="text" name="authorizenet_aim_login" value="<?php echo $authorizenet_aim_login; ?>" />
              <?php if (isset($error_login)) { ?>
              <span class="error"><?php echo $error_login; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_key']; ?></td>
            <td><input type="text" name="authorizenet_aim_key" value="<?php echo $authorizenet_aim_key; ?>" />
              <?php if (isset($error_key)) { ?>
              <span class="error"><?php echo $error_key; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_hash']; ?></td>
            <td><input type="text" name="authorizenet_aim_hash" value="<?php echo $authorizenet_aim_hash; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_server']; ?></td>
            <td><select name="authorizenet_aim_server">
                <?php if ($authorizenet_aim_server == 'live') { ?>
                <option value="live" selected="selected"><?php echo $_['text_live']; ?></option>
                <?php } else { ?>
                <option value="live"><?php echo $_['text_live']; ?></option>
                <?php } ?>
                <?php if ($authorizenet_aim_server == 'test') { ?>
                <option value="test" selected="selected"><?php echo $_['text_test']; ?></option>
                <?php } else { ?>
                <option value="test"><?php echo $_['text_test']; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_mode']; ?></td>
            <td><select name="authorizenet_aim_mode">
                <?php if ($authorizenet_aim_mode == 'live') { ?>
                <option value="live" selected="selected"><?php echo $_['text_live']; ?></option>
                <?php } else { ?>
                <option value="live"><?php echo $_['text_live']; ?></option>
                <?php } ?>
                <?php if ($authorizenet_aim_mode == 'test') { ?>
                <option value="test" selected="selected"><?php echo $_['text_test']; ?></option>
                <?php } else { ?>
                <option value="test"><?php echo $_['text_test']; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_method']; ?></td>
            <td><select name="authorizenet_aim_method">
                <?php if ($authorizenet_aim_method == 'authorization') { ?>
                <option value="authorization" selected="selected"><?php echo $_['text_authorization']; ?></option>
                <?php } else { ?>
                <option value="authorization"><?php echo $_['text_authorization']; ?></option>
                <?php } ?>
                <?php if ($authorizenet_aim_method == 'capture') { ?>
                <option value="capture" selected="selected"><?php echo $_['text_capture']; ?></option>
                <?php } else { ?>
                <option value="capture"><?php echo $_['text_capture']; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_total']; ?></td>
            <td><input type="text" name="authorizenet_aim_total" value="<?php echo $authorizenet_aim_total; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_order_status']; ?></td>
            <td><select name="authorizenet_aim_order_status_id">
				<?php echo form_select_option($order_statuses, $authorizenet_aim_order_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_geo_zone']; ?></td>
            <td><select name="authorizenet_aim_geo_zone_id">
                <option value="0"><?php echo $_['text_all_zones']; ?></option>
				<?php echo form_select_option($geo_zones, $authorizenet_aim_geo_zone_id, null, 'geo_zone_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="authorizenet_aim_status">
				<?php echo form_select_option($_['option_statuses'], $authorizenet_aim_status, true);?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="authorizenet_aim_sort_order" value="<?php echo $authorizenet_aim_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 