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
      <h1><img src="view/image/country.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a>
	  <a href="<?php echo $cancel; ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
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
            <td><span class="required">*</span> <?php echo $_['entry_description']; ?></td>
            <td><input type="text" name="description" value="<?php echo $description; ?>" />
              <?php if (isset($error_description)) { ?>
              <span class="error"><?php echo $error_description; ?></span>
              <?php } ?></td>
          </tr>
        </table>
        <br />
        <table id="zone-to-geo-zone" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $_['entry_country']; ?></td>
              <td class="left"><?php echo $_['entry_zone']; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $zone_to_geo_zone_row = 0; ?>
          <?php foreach ($zone_to_geo_zones as $zone_to_geo_zone) { ?>
          <tbody id="zone-to-geo-zone-row<?php echo $zone_to_geo_zone_row; ?>">
            <tr>
              <td class="left"><select name="zone_to_geo_zone[<?php echo $zone_to_geo_zone_row; ?>][country_id]" id="country<?php echo $zone_to_geo_zone_row; ?>" onchange="$('#zone<?php echo $zone_to_geo_zone_row; ?>').load('<?php echo UA('common/ajax/zone'); ?>&country_id=' + this.value + '&zone_id=0');">
			  <?php echo form_select_option($countries, $zone_to_geo_zone['country_id'], true);?>
                </select></td>
              <td class="left"><select name="zone_to_geo_zone[<?php echo $zone_to_geo_zone_row; ?>][zone_id]" id="zone<?php echo $zone_to_geo_zone_row; ?>">
                </select></td>
              <td class="left"><a onclick="$('#zone-to-geo-zone-row<?php echo $zone_to_geo_zone_row; ?>').remove();" class="button"><?php echo $_['button_remove']; ?></a></td>
            </tr>
          </tbody>
          <?php $zone_to_geo_zone_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="2"></td>
              <td class="left"><a onclick="addGeoZone();" class="button"><?php echo $_['button_add_geo_zone']; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#zone-id').load('<?php echo UA('common/ajax/zone'); ?>&country_id=' + $('#country-id').attr('value') + '&zone_id=0');
//--></script>
<?php $zone_to_geo_zone_row = 0; ?>
<?php foreach ($zone_to_geo_zones as $zone_to_geo_zone) { ?>
<script type="text/javascript"><!--
$('#zone<?php echo $zone_to_geo_zone_row; ?>').load('<?php echo UA('common/ajax/zone'); ?>&country_id=<?php echo $zone_to_geo_zone['country_id']; ?>&zone_id=<?php echo $zone_to_geo_zone['zone_id']; ?>');
//--></script>
<?php $zone_to_geo_zone_row++; ?>
<?php } ?>
<script type="text/javascript"><!--
var zone_to_geo_zone_row = <?php echo $zone_to_geo_zone_row; ?>;

function addGeoZone() {
	html  = '<tbody id="zone-to-geo-zone-row' + zone_to_geo_zone_row + '">';
	html += '<tr>';
	html += '<td class="left"><select name="zone_to_geo_zone[' + zone_to_geo_zone_row + '][country_id]" id="country' + zone_to_geo_zone_row + '" onchange="$(\'#zone' + zone_to_geo_zone_row + '\').load(\'<?php echo UA('common/ajax/zone'); ?>&country_id=\' + this.value + \'&zone_id=0\');">';
	<?php foreach ($countries as $id => $name) { ?>
	html += '<option value="<?php echo $id; ?>"><?php echo addslashes($name); ?></option>';
	<?php } ?>   
	html += '</select></td>';
	html += '<td class="left"><select name="zone_to_geo_zone[' + zone_to_geo_zone_row + '][zone_id]" id="zone' + zone_to_geo_zone_row + '"></select></td>';
	html += '<td class="left"><a onclick="$(\'#zone-to-geo-zone-row' + zone_to_geo_zone_row + '\').remove();" class="button"><?php echo $_['button_remove']; ?></a></td>';
	html += '</tr>';
	html += '</tbody>';
	
	$('#zone-to-geo-zone > tfoot').before(html);
		
	$('#zone' + zone_to_geo_zone_row).load('<?php echo UA('common/ajax/zone'); ?>&country_id=' + $('#country' + zone_to_geo_zone_row).attr('value') + '&zone_id=0');
	
	zone_to_geo_zone_row++;
}
//--></script> 
<?php echo $footer; ?>