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
      <form action="<?php echo UA('payment/pp_pro_uk'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_vendor']; ?></td>
            <td><input type="text" name="pp_pro_uk_vendor" value="<?php echo $pp_pro_uk_vendor; ?>" />
              <?php echo form_error('pp_pro_uk_vendor'); ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_user']; ?></td>
            <td><input type="text" name="pp_pro_uk_user" value="<?php echo $pp_pro_uk_user; ?>" />
             <?php echo form_error('pp_pro_uk_user'); ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_password']; ?></td>
            <td><input type="text" name="pp_pro_uk_password" value="<?php echo $pp_pro_uk_password; ?>" />
              <?php echo form_error('pp_pro_uk_password'); ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_partner']; ?></td>
            <td><input type="text" name="pp_pro_uk_partner" value="<?php echo $pp_pro_uk_partner; ?>" />
              <?php echo form_error('pp_pro_uk_partner'); ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_test']; ?></td>
            <td><?php if ($pp_pro_uk_test) { ?>
              <input type="radio" name="pp_pro_uk_test" value="1" checked="checked" />
              <?php echo $_['text_yes']; ?>
              <input type="radio" name="pp_pro_uk_test" value="0" />
              <?php echo $_['text_no']; ?>
              <?php } else { ?>
              <input type="radio" name="pp_pro_uk_test" value="1" />
              <?php echo $_['text_yes']; ?>
              <input type="radio" name="pp_pro_uk_test" value="0" checked="checked" />
              <?php echo $_['text_no']; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_transaction']; ?></td>
            <td><select name="pp_pro_uk_transaction">
                <?php if (!$pp_pro_uk_transaction) { ?>
                <option value="0" selected="selected"><?php echo $_['text_authorization']; ?></option>
                <?php } else { ?>
                <option value="0"><?php echo $_['text_authorization']; ?></option>
                <?php } ?>
                <?php if ($pp_pro_uk_transaction) { ?>
                <option value="1" selected="selected"><?php echo $_['text_sale']; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $_['text_sale']; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_total']; ?></td>
            <td><input type="text" name="pp_pro_uk_total" value="<?php echo $pp_pro_uk_total; ?>" /></td>
          </tr>          
          <tr>
            <td><?php echo $_['entry_order_status']; ?></td>
            <td><select name="pp_pro_uk_order_status_id">
				<?php echo form_select_option($order_statuses, $pp_pro_uk_order_status_id, null, 'order_status_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_geo_zone']; ?></td>
            <td><select name="pp_pro_uk_geo_zone_id">
                <option value="0"><?php echo $_['text_all_zones']; ?></option>
				<?php echo form_select_option($geo_zones, $pp_pro_uk_geo_zone_id, null, 'geo_zone_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="pp_pro_uk_status">
				<?php echo form_select_option($_['option_statuses'], $pp_pro_uk_status, true);?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="pp_pro_uk_sort_order" value="<?php echo $pp_pro_uk_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>