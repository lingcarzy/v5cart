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
      <h1><img src="view/image/category.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a><a href="<?php echo UA('catalog/category'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
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
                <td><span class="required">*</span> <?php echo $_['entry_name']; ?></td>
                <td><input type="text" name="category_description[<?php echo $language['language_id']; ?>][name]" size="100" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['name'] : ''; ?>"  data-rule-required="true" data-rule-rangelength="3,255"/>
                  <?php echo form_error("category_description[{$language['language_id']}][name]") ?></td>
              </tr>
				<tr>
					<td><?php echo $_['entry_seo_title']; ?></td>
					<td><input type="text" name="category_description[<?php echo $language['language_id']; ?>][seo_title]" size="100" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['seo_title'] : ''; ?>" /></td>
				</tr>
              <tr>
                <td><?php echo $_['entry_meta_description']; ?></td>
                <td><textarea name="category_description[<?php echo $language['language_id']; ?>][meta_description]" cols="80" rows="3"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
              </tr>
              <tr>
                <td><?php echo $_['entry_meta_keyword']; ?></td>
                <td><textarea name="category_description[<?php echo $language['language_id']; ?>][meta_keyword]" cols="80" rows="3"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea></td>
              </tr>
              <tr>
                <td><?php echo $_['entry_description']; ?></td>
                <td><textarea name="category_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['description'] : ''; ?></textarea></td>
              </tr>
            </table>
          </div>
          <?php } ?>
        </div>
        <div id="tab-data">
          <table class="form">
            <tr>
              <td><?php echo $_['entry_parent']; ?></td>
              <td><select name="parent_id">
                  <option value="0"><?php echo $_['text_none']; ?></option>
				  <?php echo form_select_option($categories, $parent_id, null, 'category_id', 'name');?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_store']; ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'even'; ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array(0, $category_store)) { ?>
                    <input type="checkbox" name="category_store[]" value="0" checked="checked" />
                    <?php echo $_['text_default']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="category_store[]" value="0" />
                    <?php echo $_['text_default']; ?>
                    <?php } ?>
                  </div>
                  <?php foreach ($stores as $store) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($store['store_id'], $category_store)) { ?>
                    <input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                    <?php echo $store['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" />
                    <?php echo $store['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
			<tr>
			  <td><?php echo $_['entry_attribute']; ?></td>
			  <td><input type="text" name="attribute" value="" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<div id="category-attribute" class="scrollbox">
					<?php $class = 'odd'; ?>
					<?php foreach ($category_attribute as $attribute) { ?>
					<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
					<div id="category-attribute<?php echo $attribute['attribute_id']; ?>" class="<?php echo $class; ?>"><?php echo $attribute['name']; ?> <img src="view/image/delete.png" />
					  <input type="hidden" value="<?php echo $attribute['attribute_id']; ?>" />
					</div>
					<?php } ?>
				  </div>
				  <input type="hidden" name="attribute_ids" value="<?php echo $attribute_ids; ?>" /></td>
				</td>
			</tr>
            <tr>
              <td><?php echo $_['entry_keyword']; ?></td>
                <td><input type="text" name="seo_url" value="<?php echo $seo_url; ?>" size="45" id="seo_url"/>&nbsp;&nbsp;<a href="javascript:formatUrl();"><?php echo $_['text_format'];?></a></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_image']; ?></td>
              <td valign="top"><div class="image"><img src="<?php echo $thumb; ?>" alt="" id="thumb" />
                <input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
                <br /><a onclick="image_upload('image', 'thumb', '<?php echo $token;?>');"><?php echo $_['text_browse']; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $_['text_clear']; ?></a></div></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_top']; ?></td>
              <td><?php if ($top) { ?>
                <input type="checkbox" name="top" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="top" value="1" />
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_column']; ?></td>
              <td><input type="text" name="column" value="<?php echo $column; ?>" size="1" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_sort_order']; ?></td>
              <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_status']; ?></td>
              <td><select name="status">
                  <?php if ($status) { ?>
                  <option value="1" selected="selected"><?php echo $_['text_enabled']; ?></option>
                  <option value="0"><?php echo $_['text_disabled']; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $_['text_enabled']; ?></option>
                  <option value="0" selected="selected"><?php echo $_['text_disabled']; ?></option>
                  <?php } ?>
                </select></td>
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
                <td class="left"><select name="category_layout[0][layout_id]">
                    <option value=""></option>
					<?php echo form_select_option($layouts, isset($category_layout[0])? $category_layout[0] : 0 , null, 'layout_id', 'name');?>
                  </select></td>
              </tr>
            </tbody>
            <?php foreach ($stores as $store) { ?>
            <tbody>
              <tr>
                <td class="left"><?php echo $store['name']; ?></td>
                <td class="left"><select name="category_layout[<?php echo $store['store_id']; ?>][layout_id]">
                    <option value=""></option>
					<?php echo form_select_option($layouts, isset($category_layout[$store['store_id']])? $category_layout[$store['store_id']] : 0 , null, 'layout_id', 'name');?>
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
CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
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
$.widget('custom.catcomplete', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this, currentCategory = '';
		
		$.each(items, function(index, item) {
			if (item.category != currentCategory) {
				ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
				
				currentCategory = item.category;
			}
			
			self._renderItem(ul, item);
		});
	}
});

$('input[name=\'attribute\']').catcomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: '<?php echo UA('catalog/attribute/autocomplete'); ?>&filter_type=select&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						category: item.attribute_group,
						label: item.name,
						value: item.attribute_id,
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('#category-attribute' + ui.item.value).remove();
		$('#category-attribute').append('<div id="category-attribute' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" value="' + ui.item.value + '" /></div>');

		$('#category-attribute div:odd').attr('class', 'odd');
		$('#category-attribute div:even').attr('class', 'even');

		data = $.map($('#category-attribute input'), function(element){
			return $(element).attr('value');
		});

		$('input[name=\'attribute_ids\']').attr('value', data.join());

		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});

$('#category-attribute div img').live('click', function() {
	$(this).parent().remove();

	$('#category-attribute div:odd').attr('class', 'odd');
	$('#category-attribute div:even').attr('class', 'even');

	data = $.map($('#category-attribute input'), function(element){
		return $(element).attr('value');
	});

	$('input[name=\'category-attribute\']').attr('value', data.join());
});
//--></script>

<script type="text/javascript"><!--
$('#tabs a').tabs();
$('#languages a').tabs();
//--></script>
<?php echo $footer; ?>