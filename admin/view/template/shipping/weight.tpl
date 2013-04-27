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
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a><a href="<?php echo UA('extension/shipping') ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <div class="vtabs"><a href="#tab-general"><?php echo $_['tab_general']; ?></a>
        <?php foreach ($geo_zones as $geo_zone) { ?>
        <a href="#tab-geo-zone<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></a>
        <?php } ?>
      </div>
      <form action="<?php echo UA('shipping/weight'); ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general" class="vtabs-content">
          <table class="form">
            <tr>
              <td><?php echo $_['entry_tax_class']; ?></td>
              <td><select name="weight_tax_class_id">				
                  <option value="0"><?php echo $_['text_none']; ?></option>
                  <?php echo form_select_option($tax_classes, $weight_tax_class_id, null, 'tax_class_id', 'title');?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_status']; ?></td>
              <td><select name="weight_status">
				<?php echo form_select_option($_['option_statuses'], $weight_status, true);?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_sort_order']; ?></td>
              <td><input type="text" name="weight_sort_order" value="<?php echo $weight_sort_order; ?>" size="1" /></td>
            </tr>
          </table>
        </div>
        <?php foreach ($geo_zones as $geo_zone) { ?>
        <div id="tab-geo-zone<?php echo $geo_zone['geo_zone_id']; ?>" class="vtabs-content">
          <table class="form">
            <tr>
              <td><?php echo $_['entry_rate']; ?></td>
              <td><textarea name="weight_<?php echo $geo_zone['geo_zone_id']; ?>_rate" cols="40" rows="5"><?php echo ${'weight_' . $geo_zone['geo_zone_id'] . '_rate'}; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_status']; ?></td>
              <td><select name="weight_<?php echo $geo_zone['geo_zone_id']; ?>_status">
				<?php echo form_select_option($_['option_statuses'], ${'weight_' . $geo_zone['geo_zone_id'] . '_status'}, true);?>
                </select></td>
            </tr>
          </table>
        </div>
        <?php } ?>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('.vtabs a').tabs(); 
//--></script> 
<?php echo $footer; ?> 