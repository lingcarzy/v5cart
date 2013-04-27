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
      <h1><img src="view/image/information.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a><a href="<?php echo UA('catalog/option'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_name']; ?></td>
            <td><?php foreach ($languages as $language) { ?>
              <input type="text" name="option_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($option_description[$language['language_id']]) ? $option_description[$language['language_id']]['name'] : ''; ?>" />
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
              <?php echo form_error('option_description['.$language['language_id'].'][name]'); ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_type']; ?></td>
            <td><select name="type">
                <optgroup label="<?php echo $_['text_choose']; ?>">
                <?php if ($type == 'select') { ?>
                <option value="select" selected><?php echo $_['text_select']; ?></option>
                <?php } else { ?>
                <option value="select"><?php echo $_['text_select']; ?></option>
                <?php } ?>
                <?php if ($type == 'radio') { ?>
                <option value="radio" selected><?php echo $_['text_radio']; ?></option>
                <?php } else { ?>
                <option value="radio"><?php echo $_['text_radio']; ?></option>
                <?php } ?>
                <?php if ($type == 'checkbox') { ?>
                <option value="checkbox" selected><?php echo $_['text_checkbox']; ?></option>
                <?php } else { ?>
                <option value="checkbox"><?php echo $_['text_checkbox']; ?></option>
                <?php } ?>
                <?php if ($type == 'image') { ?>
                <option value="image" selected><?php echo $_['text_image']; ?></option>
                <?php } else { ?>
                <option value="image"><?php echo $_['text_image']; ?></option>
                <?php } ?>
                </optgroup>
                <optgroup label="<?php echo $_['text_input']; ?>">
                <?php if ($type == 'text') { ?>
                <option value="text" selected><?php echo $_['text_text']; ?></option>
                <?php } else { ?>
                <option value="text"><?php echo $_['text_text']; ?></option>
                <?php } ?>
                <?php if ($type == 'textarea') { ?>
                <option value="textarea" selected><?php echo $_['text_textarea']; ?></option>
                <?php } else { ?>
                <option value="textarea"><?php echo $_['text_textarea']; ?></option>
                <?php } ?>
                </optgroup>
                <optgroup label="<?php echo $_['text_file']; ?>">
                <?php if ($type == 'file') { ?>
                <option value="file" selected><?php echo $_['text_file']; ?></option>
                <?php } else { ?>
                <option value="file"><?php echo $_['text_file']; ?></option>
                <?php } ?>
                </optgroup>
                <optgroup label="<?php echo $_['text_date']; ?>">
                <?php if ($type == 'date') { ?>
                <option value="date" selected><?php echo $_['text_date']; ?></option>
                <?php } else { ?>
                <option value="date"><?php echo $_['text_date']; ?></option>
                <?php } ?>
                <?php if ($type == 'time') { ?>
                <option value="time" selected><?php echo $_['text_time']; ?></option>
                <?php } else { ?>
                <option value="time"><?php echo $_['text_time']; ?></option>
                <?php } ?>
                <?php if ($type == 'datetime') { ?>
                <option value="datetime" selected><?php echo $_['text_datetime']; ?></option>
                <?php } else { ?>
                <option value="datetime"><?php echo $_['text_datetime']; ?></option>
                <?php } ?>
                </optgroup>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
          </tr>
        </table>
        <table id="option-value" class="list">
          <thead>
            <tr>
              <td class="left"><span class="required">*</span> <?php echo $_['entry_option_value']; ?></td>
              <td class="left"><?php echo $_['entry_image']; ?></td>
              <td class="right"><?php echo $_['entry_sort_order']; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $option_value_row = 0; ?>
          <?php foreach ($option_values as $option_value) { ?>
          <tbody id="option-value-row<?php echo $option_value_row; ?>">
            <tr>
              <td class="left"><input type="hidden" name="option_value[<?php echo $option_value_row; ?>][option_value_id]" value="<?php echo $option_value['option_value_id']; ?>" />
                <?php foreach ($languages as $language) { ?>
                <input type="text" name="option_value[<?php echo $option_value_row; ?>][option_value_description][<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($option_value['option_value_description'][$language['language_id']]) ? $option_value['option_value_description'][$language['language_id']]['name'] : ''; ?>" />
                <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                <?php echo form_error("option_value[{$option_value_row}][option_value_description][{$language['language_id']}][name]"); ?>
                <?php } ?></td>
              <td class="left"><div class="image"><img src="<?php echo $option_value['thumb']; ?>" alt="" id="thumb<?php echo $option_value_row; ?>" />
                  <input type="hidden" name="option_value[<?php echo $option_value_row; ?>][image]" value="<?php echo $option_value['image']; ?>" id="image<?php echo $option_value_row; ?>"  />
                  <br />
                  <a onclick="image_upload('image<?php echo $option_value_row; ?>', 'thumb<?php echo $option_value_row; ?>', '<?php echo $token;?>');"><?php echo $_['text_browse']; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $option_value_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $option_value_row; ?>').attr('value', '');"><?php echo $_['text_clear']; ?></a></div></td>
              <td class="right"><input type="text" name="option_value[<?php echo $option_value_row; ?>][sort_order]" value="<?php echo $option_value['sort_order']; ?>" size="1" /></td>
              <td class="left"><a onclick="$('#option-value-row<?php echo $option_value_row; ?>').remove();" class="button"><?php echo $_['button_remove']; ?></a></td>
            </tr>
          </tbody>
          <?php $option_value_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="3"></td>
              <td class="left"><a onclick="addOptionValue();" class="button"><?php echo $_['button_add_option_value']; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'type\']').bind('change', function() {
	if (this.value == 'select' || this.value == 'radio' || this.value == 'checkbox' || this.value == 'image') {
		$('#option-value').show();
	} else {
		$('#option-value').hide();
	}
});

var option_value_row = <?php echo $option_value_row; ?>;

function addOptionValue() {
	html  = '<tbody id="option-value-row' + option_value_row + '">';
	html += '<tr>';	
    html += '<td class="left"><input type="hidden" name="option_value[' + option_value_row + '][option_value_id]" value="" />';
	<?php foreach ($languages as $language) { ?>
	html += '<input type="text" name="option_value[' + option_value_row + '][option_value_description][<?php echo $language['language_id']; ?>][name]" value="" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
    <?php } ?>
	html += '</td>';
    html += '<td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + option_value_row + '" /><input type="hidden" name="option_value[' + option_value_row + '][image]" value="" id="image' + option_value_row + '" /><br /><a onclick="image_upload(\'image' + option_value_row + '\', \'thumb' + option_value_row + '\', , \'<?php echo $token;?>\');"><?php echo $_['text_browse']; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + option_value_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + option_value_row + '\').attr(\'value\', \'\');"><?php echo $_['text_clear']; ?></a></div></td>';
	html += '<td class="right"><input type="text" name="option_value[' + option_value_row + '][sort_order]" value="" size="1" /></td>';
	html += '<td class="left"><a onclick="$(\'#option-value-row' + option_value_row + '\').remove();" class="button"><?php echo $_['button_remove']; ?></a></td>';
	html += '</tr>';	
    html += '</tbody>';
	
	$('#option-value tfoot').before(html);
	
	option_value_row++;
}
//--></script>
<?php echo $footer; ?>