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
      <form action="<?php echo UA('shipping/parcelforce_48'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><?php echo $_['entry_rate']; ?></td>
            <td><textarea name="parcelforce_48_rate" cols="40" rows="5"><?php echo $parcelforce_48_rate; ?></textarea></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_insurance']; ?></td>
            <td><textarea name="parcelforce_48_insurance" cols="40" rows="5"><?php echo $parcelforce_48_insurance; ?></textarea></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_display_weight']; ?></td>
            <td>
			<?php echo form_radio($_['option_yesno'], 'parcelforce_48_display_weight', $parcelforce_48_display_weight); ?>
			</td>
          </tr>
          <tr>
            <td><?php echo $_['entry_display_insurance']; ?></td>
            <td>
			<?php echo form_radio($_['option_yesno'], 'parcelforce_48_display_insurance', $parcelforce_48_display_insurance); ?>
			</td>
          </tr>
          <tr>
            <td><?php echo $_['entry_display_time']; ?></td>
            <td>
			<?php echo form_radio($_['option_yesno'], 'parcelforce_48_display_time', $parcelforce_48_display_time); ?>
			</td>
          </tr>
          <tr>
            <td><?php echo $_['entry_tax_class']; ?></td>
            <td><select name="parcelforce_48_tax_class_id">
                <option value="0"><?php echo $_['text_none']; ?></option>
				<?php echo form_select_option($tax_classes, $parcelforce_48_tax_class_id, null, 'tax_class_id', 'title'); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_geo_zone']; ?></td>
            <td><select name="parcelforce_48_geo_zone_id">
                <option value="0"><?php echo $_['text_all_zones']; ?></option>
				<?php echo form_select_option($geo_zones, $parcelforce_48_geo_zone_id, null, 'geo_zone_id', 'name'); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="parcelforce_48_status">
				<?php echo form_select_option($_['option_statuses'], $parcelforce_48_status, true); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="parcelforce_48_sort_order" value="<?php echo $parcelforce_48_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 