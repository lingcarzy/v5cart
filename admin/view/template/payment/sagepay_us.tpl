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
      <form action="<?php echo UA('payment/sagepay_us'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_merchant_id']; ?></td>
            <td><input type="text" name="sagepay_us_merchant_id" value="<?php echo $sagepay_us_merchant_id; ?>" />
              <?php if (isset($error_merchant_id)) { ?>
              <span class="error"><?php echo $error_merchant_id; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_merchant_key']; ?></td>
            <td><input type="text" name="sagepay_us_merchant_key" value="<?php echo $sagepay_us_merchant_key; ?>" />
              <?php if (isset($error_merchant_key)) { ?>
              <span class="error"><?php echo $error_merchant_key; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_total']; ?></td>
            <td><input type="text" name="sagepay_us_total" value="<?php echo $sagepay_us_total; ?>" /></td>
          </tr>          
          <tr>
            <td><?php echo $_['entry_order_status']; ?></td>
            <td><select name="sagepay_us_order_status_id">
			<?php echo form_select_option($order_statuses, $sagepay_us_order_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_geo_zone']; ?></td>
            <td><select name="sagepay_us_geo_zone_id">
                <option value="0"><?php echo $_['text_all_zones']; ?></option>
				<?php echo form_select_option($geo_zones, $sagepay_us_geo_zone_id, null, 'geo_zone_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="sagepay_us_status">
				<?php echo form_select_option($_['option_statuses'], $sagepay_us_status, true);?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="sagepay_us_sort_order" value="<?php echo $sagepay_us_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 