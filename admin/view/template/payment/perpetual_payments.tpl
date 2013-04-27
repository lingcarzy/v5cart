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
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a><a href="<?php echo UA('extension/payment'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('payment/perpetual_payments'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_auth_id']; ?></td>
            <td><input type="text" name="perpetual_payments_auth_id" value="<?php echo $perpetual_payments_auth_id; ?>" />
              <?php if (isset($error_auth_id)) { ?>
              <span class="error"><?php echo $error_auth_id; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_auth_pass']; ?></td>
            <td><input type="text" name="perpetual_payments_auth_pass" value="<?php echo $perpetual_payments_auth_pass; ?>" />
              <?php if (isset($error_auth_pass)) { ?>
              <span class="error"><?php echo $error_auth_pass; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_test']; ?></td>
            <td><?php if ($perpetual_payments_test) { ?>
              <input type="radio" name="perpetual_payments_test" value="1" checked="checked" />
              <?php echo $_['text_yes']; ?>
              <input type="radio" name="perpetual_payments_test" value="0" />
              <?php echo $_['text_no']; ?>
              <?php } else { ?>
              <input type="radio" name="perpetual_payments_test" value="1" />
              <?php echo $_['text_yes']; ?>
              <input type="radio" name="perpetual_payments_test" value="0" checked="checked" />
              <?php echo $_['text_no']; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_total']; ?></td>
            <td><input type="text" name="perpetual_payments_total" value="<?php echo $perpetual_payments_total; ?>" /></td>
          </tr>          
          <tr>
            <td><?php echo $_['entry_order_status']; ?></td>
            <td><select name="perpetual_payments_order_status_id">
				<?php echo form_select_option($order_statuses, $perpetual_payments_order_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_geo_zone']; ?></td>
            <td><select name="perpetual_payments_geo_zone_id">
                <option value="0"><?php echo $_['text_all_zones']; ?></option>
				<?php echo form_select_option($geo_zones, $perpetual_payments_geo_zone_id, null, 'geo_zone_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="perpetual_payments_status">
				<?php echo form_select_option($_['option_statuses'], $perpetual_payments_status, true);?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="perpetual_payments_sort_order" value="<?php echo $perpetual_payments_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 