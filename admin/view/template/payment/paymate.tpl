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
      <form action="<?php echo UA('payment/paymate'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_username']; ?><br /></td>
            <td><input type="text" name="paymate_username" value="<?php echo $paymate_username; ?>" />
              <?php if (isset($error_username)) { ?>
              <span class="error"><?php echo $error_username; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_password']; ?><br /></td>
            <td><input type="text" name="paymate_password" value="<?php echo $paymate_password; ?>" />
              <?php if (isset($error_password)) { ?>
              <span class="error"><?php echo $error_password; ?></span>
              <?php } ?></td>
          </tr>          
          <tr>
            <td><?php echo $_['entry_test']; ?></td>
            <td><?php if ($paymate_test) { ?>
              <input type="radio" name="paymate_test" value="1" checked="checked" />
              <?php echo $_['text_yes']; ?>
              <?php } else { ?>
              <input type="radio" name="paymate_test" value="1" />
              <?php echo $_['text_yes']; ?>
              <?php } ?>
              <?php if (!$paymate_test) { ?>
              <input type="radio" name="paymate_test" value="0" checked="checked" />
              <?php echo $_['text_no']; ?>
              <?php } else { ?>
              <input type="radio" name="paymate_test" value="0" />
              <?php echo $_['text_no']; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_total']; ?></td>
            <td><input type="text" name="paymate_total" value="<?php echo $paymate_total; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_order_status']; ?></td>
            <td><select name="paymate_order_status_id">
				<?php echo form_select_option($order_statuses, $paymate_order_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_geo_zone']; ?></td>
            <td><select name="paymate_geo_zone_id">
                <option value="0"><?php echo $_['text_all_zones']; ?></option>
				<?php echo form_select_option($geo_zones, $paymate_geo_zone_id, null, 'geo_zone_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="paymate_status">
				<?php echo form_select_option($_['option_statuses'], $paymate_status, true);?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="paymate_sort_order" value="<?php echo $paymate_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 