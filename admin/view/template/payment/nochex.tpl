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
      <form action="<?php echo UA('payment/nochex'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_email']; ?></td>
            <td><input type="text" name="nochex_email" value="<?php echo $nochex_email; ?>" />
              <?php if (isset($error_email)) { ?>
              <span class="error"><?php echo $error_email; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_account']; ?></td>
            <td><select name="nochex_account">
                <?php if ($nochex_account == 'seller') { ?>
                <option value="seller" selected="selected"><?php echo $_['text_seller']; ?></option>
                <?php } else { ?>
                <option value="seller"><?php echo $_['text_seller']; ?></option>
                <?php } ?>
                <?php if ($nochex_account == 'merchant') { ?>
                <option value="merchant" selected="selected"><?php echo $_['text_merchant']; ?></option>
                <?php } else { ?>
                <option value="merchant"><?php echo $_['text_merchant']; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_merchant']; ?></td>
            <td><input type="text" name="nochex_merchant" value="<?php echo $nochex_merchant; ?>" />
              <?php if (isset($error_merchant)) { ?>
              <span class="error"><?php echo $error_merchant; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_template']; ?></td>
            <td>
			<?php echo form_radio($_['option_yesno'], 'nochex_template', $nochex_template); ?>
			</td>
          </tr>
          <tr>
            <td><?php echo $_['entry_test']; ?></td>
            <td>
			<?php echo form_radio($_['option_yesno'], 'nochex_test', $nochex_test); ?>
			</td>
          </tr>
          <tr>
            <td><?php echo $_['entry_total']; ?></td>
            <td><input type="text" name="nochex_total" value="<?php echo $nochex_total; ?>" /></td>
          </tr>          
          <tr>
            <td><?php echo $_['entry_order_status']; ?></td>
            <td><select name="nochex_order_status_id">
			<?php echo form_select_option($order_statuses, $nochex_order_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_geo_zone']; ?></td>
            <td><select name="nochex_geo_zone_id">
                <option value="0"><?php echo $_['text_all_zones']; ?></option>
				<?php echo form_select_option($geo_zones, $nochex_geo_zone_id, null, 'geo_zone_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="nochex_status">
				<?php echo form_select_option($_['option_statuses'], $nochex_status, true);?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="nochex_sort_order" value="<?php echo $nochex_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 