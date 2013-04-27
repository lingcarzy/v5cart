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
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a><a href="<?php echo UA('catalog/page'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs"><a href="#tab-general"><?php echo $_['tab_general']; ?></a><a href="#tab-data"><?php echo $_['tab_data']; ?></a><a href="#tab-design"><?php echo $_['tab_design']; ?></a></div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
          <div id="languages" class="htabs">
            <?php foreach ($languages as $language) { ?>
            <a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
            <?php } ?>
          </div>
          <?php foreach ($languages as $language) { ?>
          <div id="language<?php echo $language['language_id']; ?>">
            <table class="form">
              <tr>
                <td><span class="required">*</span> <?php echo $_['entry_title']; ?></td>
                <td><input type="text" name="page_content[<?php echo $language['language_id']; ?>][title]" size="100" value="<?php echo isset($page_content[$language['language_id']]) ? $page_content[$language['language_id']]['title'] : ''; ?>" />
                  <?php echo form_error("page_content[{$language['language_id']}][title]"); ?></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $_['entry_content']; ?></td>
                <td><textarea name="page_content[<?php echo $language['language_id']; ?>][content]" id="content<?php echo $language['language_id']; ?>"><?php echo isset($page_content[$language['language_id']]) ? $page_content[$language['language_id']]['content'] : ''; ?></textarea>
                  <?php echo form_error("page_content[{$language['language_id']}][content]"); ?></td>
              </tr>
            </table>
          </div>
          <?php } ?>
        </div>
        <div id="tab-data">
          <table class="form">
            <tr>
              <td><?php echo $_['entry_store']; ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'even'; ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array(0, $page_store)) { ?>
                    <input type="checkbox" name="page_store[]" value="0" checked="checked" />
                    <?php echo $_['text_default']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="page_store[]" value="0" />
                    <?php echo $_['text_default']; ?>
                    <?php } ?>
                  </div>
                  <?php foreach ($stores as $store) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($store['store_id'], $page_store)) { ?>
                    <input type="checkbox" name="page_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                    <?php echo $store['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="page_store[]" value="<?php echo $store['store_id']; ?>" />
                    <?php echo $store['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_keyword']; ?></td>
              <td><input type="text" name="seo_url" value="<?php echo $seo_url; ?>" size="45" id="seo_url"/>&nbsp;&nbsp;<a href="javascript:formatUrl();"><?php echo $_['text_format'];?></a></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_bottom']; ?></td>
              <td><?php if ($bottom) { ?>
                <input type="checkbox" name="bottom" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="bottom" value="1" />
                <?php } ?></td>
            </tr>            
            <tr>
              <td><?php echo $_['entry_status']; ?></td>
              <td><select name="status">
				<?php echo form_select_option($_['option_statuses'], $status, true);?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_sort_order']; ?></td>
              <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
            </tr>
          </table>
        </div>
        <div id="tab-design">
          <table class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $_['entry_store']; ?></td>
                <td class="left"><?php echo $_['entry_layout']; ?></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="left"><?php echo $_['text_default']; ?></td>
                <td class="left"><select name="page_layout[0][layout_id]">
                    <option value=""></option>
					<?php echo form_select_option($layouts, isset($page_layout[0])?$page_layout[0]:-1, null, 'layout_id', 'name');?>
                  </select></td>
              </tr>
            </tbody>
            <?php foreach ($stores as $store) { ?>
            <tbody>
              <tr>
                <td class="left"><?php echo $store['name']; ?></td>
                <td class="left"><select name="page_layout[<?php echo $store['store_id']; ?>][layout_id]">
                    <option value=""></option>
                    <?php foreach ($layouts as $layout) { ?>
                    <?php if (isset($page_layout[$store['store_id']]) && $page_layout[$store['store_id']] == $layout['layout_id']) { ?>
                    <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
            </tbody>
            <?php } ?>
          </table>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('content<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: '<?php echo UA('common/filemanager'); ?>',
	filebrowserImageBrowseUrl: '<?php echo UA('common/filemanager'); ?>',
	filebrowserFlashBrowseUrl: '<?php echo UA('common/filemanager'); ?>',
	filebrowserUploadUrl: '<?php echo UA('common/filemanager'); ?>',
	filebrowserImageUploadUrl: '<?php echo UA('common/filemanager'); ?>',
	filebrowserFlashUploadUrl: '<?php echo UA('common/filemanager'); ?>'
});
<?php } ?>
//--></script> 
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
$('#languages a').tabs(); 
//--></script> 
<?php echo $footer; ?>