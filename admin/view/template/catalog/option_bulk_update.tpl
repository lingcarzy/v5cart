<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
		 <?php echo bread_crumbs();?>
		  ::
		  <?php echo $_['text_bulk_update'];?>
  </div>
	<?php if ($warning) { ?>
		<div class="warning"><?php echo $warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/information.png" alt="" /> <?php echo $_['text_bulk_update'];?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_update']; ?></a>
	  <a onclick="$('#form').attr('action','<?php echo UA('catalog/option/bulk_delete')?>');$('#form').submit();" class="button"><?php echo $_['button_delete']; ?></a>
	  <a href="<?php echo UA('catalog/option'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
	<div id="tab-option">
		<div id="vtab-option" class="vtabs">
		 <a><?php echo $option['name'];?></a>
		</div>
		<div id="tab-option" class="vtabs-content">
			<form action="<?php echo UA('catalog/option/bulk_update'); ?>" method="post" enctype="multipart/form-data" id="form">
			<input type="hidden" name="option_id" value="<?php echo $option_id; ?>" />
				<table class="form">
					<tr>
						<td>Required:</td>
						<td><select name="required">
						<?php echo form_select_option($_['option_yesno'], 0, true);?>
						</select></td>
					</tr>
					<tr>
						<td>Category:</td>
						<td>
				<ul id="category_tree">
				  <?php $level = 0; ?>
                  <?php foreach ($categories as $category) { ?>
					<?php if ($category['_level'] > $level) { ?>
					<ul>
					<?php } elseif ($category['_level'] < $level) { ?>
					</ul>
					<?php } ?>
					<li><input type="checkbox" name="category[]" value="<?php echo $category['category_id']; ?>" /><label><?php echo $category['name']; ?></label>
					<?php if ($category['_leaf']) { ?>
					</li>
					<?php } ?>
					<?php $level = $category['_level']; ?>
				  <?php }?>
				</ul>
						</td>
					</tr>
					<?php if ($option['type'] == 'text') { ?>
						<tr>
							<td>Value:</td>
							<td><input type="text" name="option_value" value="" /></td>
						</tr>
					<?php } ?>
					<?php if ($option['type'] == 'textarea') { ?>
						<tr>
							<td>Value:</td>
							<td><textarea name="option_value" cols="40" rows="5"></textarea></td>
						</tr>
					<?php } ?>
					<?php if ($option['type'] == 'file') { ?>
						<tr style="display: none;">
							<td>Value:</td>
							<td><input type="text" name="option_value" value="" /></td>
						</tr>
					<?php } ?>
					<?php if ($option['type'] == 'date') { ?>
						<tr>
							<td>Value:</td>
							<td><input type="text" name="option_value" value="" class="date" /></td>
						</tr>
					<?php } ?>
					<?php if ($option['type'] == 'datetime') { ?>
						<tr>
							<td>Value:</td>
							<td><input type="text" name="option_value" value="" class="datetime" /></td>
						</tr>
					<?php } ?>
					<?php if ($option['type'] == 'time') { ?>
						<tr>
							<td>Value:</td>
							<td><input type="text" name="option_value" value="" class="time" /></td>
						</tr>
					<?php } ?>
				</table>
				<?php if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') { ?>
				<table id="option-value" class="list">
					<thead>
						<tr>
							<td class="left">Value</td>
							<td class="right">Quantity</td>
							<td class="left">Subtract</td>
							<td class="right">Price</td>
							<td class="right">Points</td>
							<td class="right">Weight</td>
							<td></td>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td colspan="6"></td>
							<td class="left"><a onclick="addOptionValue();" class="button"><?php echo $_['button_add_option_value']; ?></a></td>
						</tr>
					</tfoot>
				</table>
				<select id="option-values" style="display: none;">
						<?php foreach ($option_values as $option_value) { ?>
							<option value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?></option>
						<?php } ?>
				</select>
				<?php } ?>
			</form>
		</div>
      
    </div>
  </div>
</div>
<script type="text/javascript"><!--		
  var option_value_row = 0;
  
  function addOptionValue() {	
	  html  = '<tbody id="option-value-row' + option_value_row + '">';
	  html += '  <tr>';
	  html += '    <td class="left"><select name="option_value[' + option_value_row + '][option_value_id]">';
	  html += $('#option-values').html();
	  html += '    </select><input type="hidden" name="option_value[option_value][' + option_value_row + '][option_value_id]" value="" /></td>';
	  html += '    <td class="right"><input type="text" name="option_value[' + option_value_row + '][quantity]" value="" size="3" /></td>'; 
	  html += '    <td class="left"><select name="option_value[' + option_value_row + '][subtract]">';	
	  html += '      <option value="0">No</option>';
	  html += '      <option value="1">Yes</option>';
	  html += '    </select></td>';
	  html += '    <td class="right"><select name="option_value[' + option_value_row + '][price_prefix]">';
	  html += '      <option value="+">+</option>';
	  html += '      <option value="-">-</option>';
	  html += '    </select>';
	  html += '    <input type="text" name="option_value[' + option_value_row + '][price]" value="" size="5" /></td>';
	  html += '    <td class="right"><select name="option_value[' + option_value_row + '][points_prefix]">';
	  html += '      <option value="+">+</option>';
	  html += '      <option value="-">-</option>';
	  html += '    </select>';
	  html += '    <input type="text" name="option_value[' + option_value_row + '][points]" value="" size="5" /></td>';	
	  html += '    <td class="right"><select name="option_value[' + option_value_row + '][weight_prefix]">';
	  html += '      <option value="+">+</option>';
	  html += '      <option value="-">-</option>';
	  html += '    </select>';
	  html += '    <input type="text" name="option_value[' + option_value_row + '][weight]" value="" size="5" /></td>';
	  html += '    <td class="left"><a onclick="$(\'#option-value-row' + option_value_row + '\').remove();" class="button"><?php echo $_['button_remove']; ?></a></td>';
	  html += '  </tr>';
	  html += '</tbody>';
	  
	  $('#option-value tfoot').before(html);
	  
	  option_value_row++;
  }
//--></script> 
<script>
$('#category_tree').checkboxTree({ onCheck: { ancestors: null, descendants: null}, onUncheck: {descendants: null},initializeUnchecked:'collapsed'});
</script>
<?php echo $footer; ?>