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
      <form action="<?php echo UA('module/google_talk'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_code']; ?></td>
            <td><textarea name="google_talk_code" cols="40" rows="5"><?php echo $google_talk_code; ?></textarea>
              <?php if (isset($error_code)) { ?>
              <span class="error"><?php echo $error_code; ?></span>
              <?php } ?></td>
          </tr>
        </table>
        <table id="module" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $_['entry_layout']; ?></td>
              <td class="left"><?php echo $_['entry_position']; ?></td>
              <td class="left"><?php echo $_['entry_status']; ?></td>
			  <td class="left">Cacheable</td>
              <td class="right"><?php echo $_['entry_sort_order']; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $module_row = 0; ?>
          <?php foreach ($modules as $module) { ?>
          <tbody id="module-row<?php echo $module_row; ?>">
            <tr>
              <td class="left"><select name="google_talk_module[<?php echo $module_row; ?>][layout_id]">
                 <?php echo form_select_option($layouts, $module['layout_id'], null, 'layout_id', 'name'); ?>
                </select></td>
              <td class="left"><select name="google_talk_module[<?php echo $module_row; ?>][position]">
                  <?php echo form_select_option($_['option_position'], $module['position'], true); ?>
                </select></td>
              <td class="left"><select name="google_talk_module[<?php echo $module_row; ?>][status]">
                 <?php echo form_select_option($_['option_statuses'], $module['status'], true);?>
                </select></td>
				<td class="left"><select name="google_talk_module[<?php echo $module_row; ?>][cache]">
					<?php echo form_select_option($_['option_statuses'], $module['cache'], true);?>
                </select>&nbsp;<input type="text" name="google_talk_module[<?php echo $module_row; ?>][expire]" value="<?php echo isset($module['expire']) ? $module['expire'] : 3600; ?>" size="3" /> /s</td>
              <td class="right"><input type="text" name="google_talk_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
              <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $_['button_remove']; ?></a></td>
            </tr>
          </tbody>
          <?php $module_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="5"></td>
              <td class="left"><a onclick="addModule();" class="button"><?php echo $_['button_add_module']; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="google_talk_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="google_talk_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $_['text_content_top']; ?></option>';
	html += '      <option value="content_bottom"><?php echo $_['text_content_bottom']; ?></option>';
	html += '      <option value="column_left"><?php echo $_['text_column_left']; ?></option>';
	html += '      <option value="column_right"><?php echo $_['text_column_right']; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="google_talk_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $_['text_enabled']; ?></option>';
    html += '      <option value="0"><?php echo $_['text_disabled']; ?></option>';
    html += '    </select></td>';
	html += '    <td class="left"><select name="google_talk_module[' + module_row + '][cache]">';
    html += '      <option value="1" selected="selected"><?php echo $_['text_enabled']; ?></option>';
    html += '      <option value="0"><?php echo $_['text_disabled']; ?></option>';
    html += '    </select> <input type="text" name="google_talk_module['+ module_row +'][expire]" value="3600" size="3" /> /s</td>';
	html += '    <td class="right"><input type="text" name="google_talk_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $_['button_remove']; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}
//--></script> 
<?php echo $footer; ?>