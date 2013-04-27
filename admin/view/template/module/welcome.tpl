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
      <h1><img src="view/image/module.png" alt="" /> <?php echo $_['module_name']; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a><a href="<?php echo UA('extension/module'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('module/welcome'); ?>" method="post" enctype="multipart/form-data" id="form">
        <div class="vtabs">
          <?php $module_row = 1; ?>
          <?php foreach ($modules as $module) { ?>
          <a href="#tab-module-<?php echo $module_row; ?>" id="module-<?php echo $module_row; ?>"><?php echo $tab_module . ' ' . $module_row; ?>&nbsp;<img src="view/image/delete.png" alt="" onclick="$('.vtabs a:first').trigger('click'); $('#module-<?php echo $module_row; ?>').remove(); $('#tab-module-<?php echo $module_row; ?>').remove(); return false;" /></a>
          <?php $module_row++; ?>
          <?php } ?>
          <span id="module-add"><?php echo $_['button_add_module']; ?>&nbsp;<img src="view/image/add.png" alt="" onclick="addModule();" /></span> </div>
        <?php $module_row = 1; ?>
        <?php foreach ($modules as $module) { ?>
        <div id="tab-module-<?php echo $module_row; ?>" class="vtabs-content">
          <div id="language-<?php echo $module_row; ?>" class="htabs">
            <?php foreach ($languages as $language) { ?>
            <a href="#tab-language-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
            <?php } ?>
          </div>
          <?php foreach ($languages as $language) { ?>
          <div id="tab-language-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>">
            <table class="form">
              <tr>
                <td><?php echo $_['entry_description']; ?></td>
                <td><textarea name="welcome_module[<?php echo $module_row; ?>][description][<?php echo $language['language_id']; ?>]" id="description-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>"><?php echo isset($module['description'][$language['language_id']]) ? $module['description'][$language['language_id']] : ''; ?></textarea></td>
              </tr>
            </table>
          </div>
          <?php } ?>
          <table class="form">
            <tr>
              <td><?php echo $_['entry_layout']; ?></td>
              <td><select name="welcome_module[<?php echo $module_row; ?>][layout_id]">
                  <?php echo form_select_option($layouts, $module['layout_id'], null, 'layout_id', 'name'); ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_position']; ?></td>
              <td><select name="welcome_module[<?php echo $module_row; ?>][position]">
                  <?php echo form_select_option($_['option_position'], $module['position'], true); ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_status']; ?></td>
              <td><select name="welcome_module[<?php echo $module_row; ?>][status]">
                 <?php echo form_select_option($_['option_statuses'], $module['status'], true);?>
                </select></td>
            </tr>
			  <tr>
				  <td>Cacheable</td>
				  <td><select name="welcome_module[<?php echo $module_row; ?>][cache]">
					 <?php echo form_select_option($_['option_statuses'], $module['cache'], true);?>
				  </select>&nbsp;<input type="text" name="welcome_module[<?php echo $module_row; ?>][expire]" value="<?php echo isset($module['expire']) ? $module['expire'] : 3600; ?>" size="3" /> /s</td>
			  </tr>
			  <tr>
				<td>Template</td>
				<td>
					<select name="welcome_module[<?php echo $module_row; ?>][template]">
						<option value="">Default</option>
						<?php echo form_select_option($templates, $module['template']); ?>
					</select>
				</td>
			  </tr>
			  <tr><td>ID</td>
				  <td><input type="text" name="welcome_module[<?php echo $module_row; ?>][id]" value="<?php echo $module['id']; ?>" size="3" /></td>
			  </tr>
            <tr>
              <td><?php echo $_['entry_sort_order']; ?></td>
              <td><input type="text" name="welcome_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
            </tr>
          </table>
        </div>
        <?php $module_row++; ?>
        <?php } ?>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
<?php $module_row = 1; ?>
<?php foreach ($modules as $module) { ?>
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('description-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: '<?php echo UA('common/filemanager'); ?>',
	filebrowserImageBrowseUrl: '<?php echo UA('common/filemanager'); ?>',
	filebrowserFlashBrowseUrl: '<?php echo UA('common/filemanager'); ?>',
	filebrowserUploadUrl: '<?php echo UA('common/filemanager'); ?>',
	filebrowserImageUploadUrl: '<?php echo UA('common/filemanager'); ?>',
	filebrowserFlashUploadUrl: '<?php echo UA('common/filemanager'); ?>'
});
<?php } ?>
<?php $module_row++; ?>
<?php } ?>
//--></script> 
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<div id="tab-module-' + module_row + '" class="vtabs-content">';
	html += '  <div id="language-' + module_row + '" class="htabs">';
    <?php foreach ($languages as $language) { ?>
    html += '    <a href="#tab-language-'+ module_row + '-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>';
    <?php } ?>
	html += '  </div>';

	<?php foreach ($languages as $language) { ?>
	html += '    <div id="tab-language-'+ module_row + '-<?php echo $language['language_id']; ?>">';
	html += '      <table class="form">';
	html += '        <tr>';
	html += '          <td><?php echo $_['entry_description']; ?></td>';
	html += '          <td><textarea name="welcome_module[' + module_row + '][description][<?php echo $language['language_id']; ?>]" id="description-' + module_row + '-<?php echo $language['language_id']; ?>"></textarea></td>';
	html += '        </tr>';
	html += '      </table>';
	html += '    </div>';
	<?php } ?>

	html += '  <table class="form">';
	html += '    <tr>';
	html += '      <td><?php echo $_['entry_layout']; ?></td>';
	html += '      <td><select name="welcome_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '           <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '      </select></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $_['entry_position']; ?></td>';
	html += '      <td><select name="welcome_module[' + module_row + '][position]">';
	html += '        <option value="content_top"><?php echo $_['text_content_top']; ?></option>';
	html += '        <option value="content_bottom"><?php echo $_['text_content_bottom']; ?></option>';
	html += '        <option value="column_left"><?php echo $_['text_column_left']; ?></option>';
	html += '        <option value="column_right"><?php echo $_['text_column_right']; ?></option>';
	html += '      </select></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $_['entry_status']; ?></td>';
	html += '      <td><select name="welcome_module[' + module_row + '][status]">';
	html += '        <option value="1"><?php echo $_['text_enabled']; ?></option>';
	html += '        <option value="0"><?php echo $_['text_disabled']; ?></option>';
	html += '      </select></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td>Cacheable</td>';
	html += '      <td><select name="welcome_module[' + module_row + '][cache]">';
	html += '        <option value="1"><?php echo $_['text_enabled']; ?></option>';
	html += '        <option value="0"><?php echo $_['text_disabled']; ?></option>';
	html += '      </select> <input type="text" name="welcome_module['+ module_row +'][expire]" value="3600" size="3" /> /s</td>';
	html += '    </tr>';
	html += '<tr><td>Template</td>';
	html += '<td>';
	html += '	<select name="welcome_module[' + module_row +'][template]">';
	html += '			<option value="">Default</option>';
			<?php
				foreach($templates as $tpl) {
			?>
	html += '<option value="<?php echo $tpl; ?>"><?php echo $tpl; ?></option>';
			<?php } ?>
	html += '				</select>';
	html += '			</td>';
	html += '		  </tr>';
	html += '		  <tr><td>ID</td>';
	html += '			  <td><input type="text" name="welcome_module[' + module_row + '][id]" value="0" size="3" /></td>';
	html += '		  </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $_['entry_sort_order']; ?></td>';
	html += '      <td><input type="text" name="welcome_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    </tr>';
	html += '  </table>'; 
	html += '</div>';
	
	$('#form').append(html);
	
	<?php foreach ($languages as $language) { ?>
	CKEDITOR.replace('description-' + module_row + '-<?php echo $language['language_id']; ?>', {
		filebrowserBrowseUrl: '<?php echo UA('common/filemanager'); ?>',
		filebrowserImageBrowseUrl: '<?php echo UA('common/filemanager'); ?>',
		filebrowserFlashBrowseUrl: '<?php echo UA('common/filemanager'); ?>',
		filebrowserUploadUrl: '<?php echo UA('common/filemanager'); ?>',
		filebrowserImageUploadUrl: '<?php echo UA('common/filemanager'); ?>',
		filebrowserFlashUploadUrl: '<?php echo UA('common/filemanager'); ?>'
	});  
	<?php } ?>
	
	$('#language-' + module_row + ' a').tabs();
	
	$('#module-add').before('<a href="#tab-module-' + module_row + '" id="module-' + module_row + '"><?php echo $_['tab_module']; ?> ' + module_row + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'.vtabs a:first\').trigger(\'click\'); $(\'#module-' + module_row + '\').remove(); $(\'#tab-module-' + module_row + '\').remove(); return false;" /></a>');
	
	$('.vtabs a').tabs();
	
	$('#module-' + module_row).trigger('click');
	
	module_row++;
}
//--></script> 
<script type="text/javascript"><!--
$('.vtabs a').tabs();
//--></script> 
<script type="text/javascript"><!--
<?php $module_row = 1; ?>
<?php foreach ($modules as $module) { ?>
$('#language-<?php echo $module_row; ?> a').tabs();
<?php $module_row++; ?>
<?php } ?> 
//--></script> 
<?php echo $footer; ?>