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
      <h1><img src="view/image/shipping.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a><a href="<?php echo UA('extension/shipping'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('shipping/auspost'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_postcode']; ?></td>
            <td><input type="text" name="auspost_postcode" size="4" maxlength="4" value="<?php echo $auspost_postcode; ?>" />
              <?php if (isset($error_postcode)) { ?>
              <span class="error"><?php echo $error_postcode; ?></span>
              <?php } ?></td>
          </tr>        
          <tr>
            <td><?php echo $_['entry_standard'] ?></td>
            <td><select name="auspost_standard">
				<?php echo form_select_option($_['option_statuses'], $auspost_standard, true); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_express'] ?></td>
            <td><select name="auspost_express">
				<?php echo form_select_option($_['option_statuses'], $auspost_express, true); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_display_time'] ?></td>
            <td><select name="auspost_display_time">
				<?php echo form_select_option($_['option_statuses'], $auspost_display_time, true); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_weight_class']; ?></td>
            <td><select name="auspost_weight_class_id">
				<?php echo form_select_option($weight_classes, $auspost_weight_class_id, null, 'weight_class_id', 'title'); ?>
              </select></td>
          </tr>          
          <tr>
            <td><?php echo $_['entry_tax_class']; ?></td>
            <td><select name="auspost_tax_class_id">
                <option value="0"><?php echo $_['text_none']; ?></option>
				<?php echo form_select_option($tax_classes, $auspost_tax_class_id, null, 'tax_class_id', 'title'); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_geo_zone']; ?></td>
            <td><select name="auspost_geo_zone_id">
                <option value="0"><?php echo $_['text_all_zones']; ?></option>
				<?php echo form_select_option($geo_zones, $auspost_geo_zone_id, null, 'geo_zone_id', 'name'); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status'] ?></td>
            <td><select name="auspost_status">
				<?php echo form_select_option($_['option_statuses'], $auspost_status, true); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="auspost_sort_order" value="<?php echo $auspost_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>