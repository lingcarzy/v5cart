<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php echo bread_crumbs(); ?>
  </div>
  <?php if (isset($error_warning)) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/tax.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a>
	  <a href="<?php echo UA('localisation/tax_rate'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_name']; ?></td>
            <td><input type="text" name="name" value="<?php echo $name; ?>" />
              <?php if (isset($error_name)) { ?>
              <span class="error"><?php echo $error_name; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_rate']; ?></td>
            <td><input type="text" name="rate" value="<?php echo $rate; ?>" />
              <?php if (isset($error_rate)) { ?>
              <span class="error"><?php echo $error_rate; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_type']; ?></td>
            <td><select name="type">
                <?php if ($type == 'P') { ?>
                <option value="P" selected="selected"><?php echo $_['text_percent']; ?></option>
                <?php } else { ?>
                <option value="P"><?php echo $_['text_percent']; ?></option>
                <?php } ?>
                <?php if ($type == 'F') { ?>
                <option value="F" selected="selected"><?php echo $_['text_amount']; ?></option>
                <?php } else { ?>
                <option value="F"><?php echo $_['text_amount']; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_customer_group']; ?></td>
            <td><div class="scrollbox">
                <?php $class = 'even'; ?>
                <?php foreach ($customer_groups as $customer_group) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
                  <?php if (in_array($customer_group['customer_group_id'], $tax_rate_customer_group)) { ?>
                  <input type="checkbox" name="tax_rate_customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                  <?php echo $customer_group['name']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="tax_rate_customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
                  <?php echo $customer_group['name']; ?>
                  <?php } ?>
                </div>
                <?php } ?>
              </div></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_geo_zone']; ?></td>
            <td><select name="geo_zone_id">
				<?php echo form_select_option($geo_zones, $geo_zone_id, null, 'geo_zone_id', 'name'); ?>
              </select></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>