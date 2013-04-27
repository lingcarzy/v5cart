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
      <h1><img src="view/image/product.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a><a href="<?php echo UA('catalog/product'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs"><a href="#tab-general"><?php echo $_['tab_general']; ?></a><a href="#tab-data"><?php echo $_['tab_data']; ?></a><a href="#tab-links"><?php echo $_['tab_links']; ?></a><a href="#tab-attributes"><?php echo $_['tab_attribute']; ?></a><a href="#tab-option"><?php echo $_['tab_option']; ?></a><a href="#tab-discount"><?php echo $_['tab_discount']; ?></a><a href="#tab-special"><?php echo $_['tab_special']; ?></a><a href="#tab-image"><?php echo $_['tab_image']; ?></a><a href="#tab-reward"><?php echo $_['tab_reward']; ?></a><a href="#tab-design"><?php echo $_['tab_design']; ?></a></div>
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
                <td><input type="text" name="product_description[<?php echo $language['language_id']; ?>][name]" size="100" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?>"  data-rule-required="true" data-rule-rangelength="3,255" />
				<?php echo form_error("product_description[{$language['language_id']}][name]");?>
				</td>
              </tr>
				<tr>
					<td><?php echo $_['entry_seo_title']; ?></td>
					<td><input type="text" name="product_description[<?php echo $language['language_id']; ?>][seo_title]" size="100" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['seo_title'] : ''; ?>" /></td>
				</tr>
              <tr>
                <td><?php echo $_['entry_meta_description']; ?></td>
                <td><textarea name="product_description[<?php echo $language['language_id']; ?>][meta_description]" cols="80" rows="3"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
              </tr>
              <tr>
                <td><?php echo $_['entry_meta_keyword']; ?></td>
                <td><textarea name="product_description[<?php echo $language['language_id']; ?>][meta_keyword]" cols="80" rows="3"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea></td>
              </tr>
			<tr>
				<td><?php echo $_['entry_summary']; ?></td>
				<td><textarea name="product_description[<?php echo $language['language_id']; ?>][summary]" cols="80" rows="3"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['summary'] : ''; ?></textarea></td>
			</tr>
              <tr>
                <td><?php echo $_['entry_description']; ?></td>
                <td><textarea name="product_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['description'] : ''; ?></textarea>
				<br><a id="javascript:void(0)" onclick="selectTemplate(<?php echo $language['language_id']; ?>)">Product Template</a>&nbsp;<input type="checkbox" id="template_replace" checked="checked">Replace actual contents
				</td>
              </tr>
              <tr>
                <td><?php echo $_['entry_tag']; ?></td>
                <td><input type="text" name="product_description[<?php echo $language['language_id']; ?>][tag]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['tag'] : ''; ?>" size="80" /></td>
              </tr>
            </table>
          </div>
          <?php } ?>
        </div>
        <div id="tab-data">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_model']; ?></td>
              <td><input type="text" name="model" value="<?php echo $model; ?>" data-rule-required="true" data-rule-rangelength="3,64" />
                <?php echo form_error('model'); ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_sku']; ?></td>
              <td><input type="text" name="sku" value="<?php echo $sku; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_upc']; ?></td>
              <td><input type="text" name="upc" value="<?php echo $upc; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_ean']; ?></td>
              <td><input type="text" name="ean" value="<?php echo $ean; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_jan']; ?></td>
              <td><input type="text" name="jan" value="<?php echo $jan; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_isbn']; ?></td>
              <td><input type="text" name="isbn" value="<?php echo $isbn; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_mpn']; ?></td>
              <td><input type="text" name="mpn" value="<?php echo $mpn; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_location']; ?></td>
              <td><input type="text" name="location" value="<?php echo $location; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_price']; ?></td>
              <td><input type="text" name="price" value="<?php echo $price; ?>" /></td>
            </tr>
			  <tr>
				  <td><?php echo $_['entry_cost']; ?></td>
				  <td><input type="text" name="cost" value="<?php echo $cost; ?>" /></td>
			  </tr>
			  <tr>
				  <td><?php echo $_['entry_msrp']; ?></td>
				  <td><input type="text" name="msrp" value="<?php echo $msrp; ?>" /></td>
			  </tr>
            <tr>
              <td><?php echo $_['entry_tax_class']; ?></td>
              <td><select name="tax_class_id">
                  <option value="0"><?php echo $_['text_none']; ?></option>

				  <?php echo form_select_option($tax_classes, $tax_class_id, null, 'tax_class_id', 'title');?>

                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_quantity']; ?></td>
              <td><input type="text" name="quantity" value="<?php echo $quantity; ?>" size="2" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_minimum']; ?></td>
              <td><input type="text" name="minimum" value="<?php echo $minimum; ?>" size="2" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_subtract']; ?></td>
              <td><select name="subtract">

				<?php echo form_select_option($_['option_yesno'],$subtract, true);?>

                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_stock_status']; ?></td>
              <td><select name="stock_status_id">

			  <?php echo form_select_option($stock_statuses,$stock_status_id, true);?>

                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_shipping']; ?></td>
              <td>
				<select name="shipping">
				<?php echo form_select_option($_['option_yesno'],$shipping, true);?>
				</select>
				</td>
            </tr>
            <tr>
              <td><?php echo $_['entry_keyword']; ?></td>
              <td><input type="text" name="seo_url" value="<?php echo $seo_url; ?>" id="seo_url" size="45"/>
				&nbsp;&nbsp;<a href="javascript:formatUrl();"><?php echo $_['text_format'];?></a>
				</td>
            </tr>
            <tr>
              <td><?php echo $_['entry_image']; ?></td>
              <td><div class="image"><img src="<?php echo $thumb; ?>" alt="" id="thumb" />
                  <br>
                  <a onclick="image_upload('image', 'thumb', '<?php echo $token;?>');"><?php echo $_['text_browse']; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $_['text_clear']; ?></a><br><input type="text" name="image" value="<?php echo $image; ?>" id="image" size="45"/></div></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_date_available']; ?></td>
              <td><input type="text" name="date_available" value="<?php echo $date_available; ?>" size="12" class="date" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_dimension']; ?></td>
              <td><input type="text" name="length" value="<?php echo $length; ?>" size="4" />
                x <input type="text" name="width" value="<?php echo $width; ?>" size="4" />
                x <input type="text" name="height" value="<?php echo $height; ?>" size="4" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_length']; ?></td>
              <td><select name="length_class_id">

			   <?php echo form_select_option($length_classes, $length_class_id, null, 'length_class_id', 'title');?>

                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_weight']; ?></td>
              <td><input type="text" name="weight" value="<?php echo $weight; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_weight_class']; ?></td>
              <td><select name="weight_class_id">

			   <?php echo form_select_option($weight_classes, $weight_class_id, null, 'weight_class_id', 'title');?>

                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_status']; ?></td>
              <td><select name="status">

				<?php echo form_select_option($_['option_statuses'], $status, true);?>

                </select></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_sort_order']; ?></td>
              <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="2" /></td>
            </tr>
          </table>
        </div>
        <div id="tab-links">
          <table class="form">
            <tr>
              <td><?php echo $_['entry_manufacturer']; ?></td>
              <td><select name="manufacturer_id">
                  <option value="0" selected="selected"><?php echo $_['text_none']; ?></option>

				   <?php echo form_select_option($manufacturers, $manufacturer_id, null, 'manufacturer_id', 'name');?>

                </select></td>
            </tr>
			  <tr>
				  <td><?php echo $_['entry_supplier']; ?></td>
				  <td><select name="supplier_id">
					  <option value="0" selected="selected"><?php echo $_['text_none']; ?></option>

					  <?php echo form_select_option($suppliers, $supplier_id, true);?>

				  </select></td>
			  </tr>
			  <tr>
				<td><?php echo $_['entry_source_link']; ?></td>
				<td>
					<input type="text" name="source_link" size="100" value="<?php echo $source_link;?>"/>
				</td>
			  </tr>
			  <tr>
				  <td><span class="required">*</span> <?php echo $_['entry_category']; ?></td>
				  <td>
					<select name="cate_id">
					<?php foreach ($category_tree as $category) { ?>
						<?php if ($category['category_id'] == $cate_id) { ?>
						<option value="<?php echo $category['category_id'];?>" selected="selected">
						<?php if ($category['_level'] > 0) { ?>
						<?php echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $category['_level']);?>|_
						<?php } ?>
						<?php echo $category['name']; ?>
						</option>
						<?php } else { ?>
						<option value="<?php echo $category['category_id'];?>">
						<?php if ($category['_level'] > 0) { ?>
						<?php echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $category['_level']);?>|_
						<?php } ?>
						<?php echo $category['name']; ?>
						</option>
						<?php } ?>
					<?php } ?>
					</select>
				  </td>
			  </tr>
            <tr>
              <td><?php echo $_['entry_addtional_category']; ?></td>
              <td><div>
				<ul id="add_category_tree">
				  <?php $level = 0; ?>
                  <?php foreach ($category_tree as $category) { ?>
					<?php if ($category['_level'] > $level) { ?>
					<ul>
					<?php } elseif ($category['_level'] < $level) { ?>
					</ul>
					<?php } ?>
					<?php if (in_array($category['category_id'], $product_category)) { ?>
					<li><input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" /><label><?php echo $category['name']; ?></label>
					<?php } else { ?>
					<li><input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" /><label><?php echo $category['name']; ?></label>
					<?php } ?>
					<?php if ($category['_leaf']) { ?>
					</li>
					<?php } ?>
					<?php $level = $category['_level']; ?>
				  <?php }?>
				</ul>
                </div>
                <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $_['text_select_all']; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $_['text_unselect_all']; ?></a></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_store']; ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'even'; ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array(0, $product_store)) { ?>
                    <input type="checkbox" name="product_store[]" value="0" checked="checked" />
                    <?php echo $_['text_default']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="product_store[]" value="0" />
                    <?php echo $_['text_default']; ?>
                    <?php } ?>
                  </div>
                  <?php foreach ($stores as $store) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($store['store_id'], $product_store)) { ?>
                    <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                    <?php echo $store['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" />
                    <?php echo $store['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_download']; ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($downloads as $download) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($download['download_id'], $product_download)) { ?>
                    <input type="checkbox" name="product_download[]" value="<?php echo $download['download_id']; ?>" checked="checked" />
                    <?php echo $download['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="product_download[]" value="<?php echo $download['download_id']; ?>" />
                    <?php echo $download['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_related']; ?></td>
              <td><input type="text" name="related" value="" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><div id="product-related" class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($product_related as $product_related) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="product-related<?php echo $product_related['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_related['name']; ?><img src="view/image/delete.png" />
                    <input type="hidden" name="product_related[]" value="<?php echo $product_related['product_id']; ?>" />
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
          </table>
        </div>
		<div id="tab-attributes">
		  <div id="vtab-attributes" class="vtabs">
			<?php $attributes_row = 0; ?>
			  <?php foreach($attribute_groups as $attribute_group) { ?>
			  <a href="#tab-attributes-<?php echo $attributes_row; ?>" id="attributes-<?php echo $attributes_row; ?>"><?php echo $attribute_group; ?>&nbsp;<img src="view/image/delete.png" alt="" onclick="$('#vtabs a:first').trigger('click'); $('#attributes-<?php echo $attributes_row; ?>').remove(); $('#tab-attributes-<?php echo $attributes_row; ?>').remove(); return false;" /></a>
              <?php $attributes_row++; ?>
			<?php } ?>
		  <span id="attributes-add">
            <input name="attributes" value="" style="width: 130px;" />
            &nbsp;<img src="view/image/add.png" alt="<?php echo $_['button_add_attribute']; ?>" title="<?php echo $_['button_add_attribute']; ?>" /></span>
		</div>
		  <?php $attributes_row = 0; ?>
          <?php $attribute_row = 0; ?>
          <?php foreach ($attribute_groups as $attribute_group_id => $attribute_group) { ?>
          <div id="tab-attributes-<?php echo $attributes_row; ?>" class="vtabs-content">
			<table id="attribute-<?php echo $attribute_group_id; ?>" class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $_['entry_attribute']; ?></td>
                <td class="left"><?php echo $_['entry_value']; ?></td>
                <td></td>
              </tr>
            </thead>
            <?php foreach ($product_attributes as $product_attribute) { ?>
			<?php if ($product_attribute['attribute_group_id'] != $attribute_group_id) continue; ?>
            <tbody id="attribute-row<?php echo $attribute_row; ?>">
              <tr>
                <td class="left"><input type="text" name="product_attribute[<?php echo $attribute_row; ?>][name]" value="<?php echo $product_attribute['name']; ?>" />
                  <input type="hidden" name="product_attribute[<?php echo $attribute_row; ?>][attribute_id]" value="<?php echo $product_attribute['attribute_id']; ?>" /></td>
                <td class="left">
				<?php echo $this->model_catalog_attribute->getAttributeFormField($product_attribute['attribute_id'], $attribute_row, $product_attribute['product_attribute_description']);?>
				  </td>
                <td class="left"><a onclick="$('#attribute-row<?php echo $attribute_row; ?>').remove();" class="button"><?php echo $_['button_remove']; ?></a></td>
              </tr>
            </tbody>
            <?php $attribute_row++; ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td colspan="2"></td>
                <td class="left"><a onclick="addAttribute(<?php echo $attribute_group_id; ?>);" class="button"><?php echo $_['button_add_attribute']; ?></a></td>
              </tr>
            </tfoot>
          </table>
		  </div>
		  <?php $attributes_row++; ?>
		  <?php } ?>
		</div>
        <div id="tab-option">
          <div id="vtab-option" class="vtabs">
            <?php $option_row = 0; ?>
            <?php foreach ($product_options as $product_option) { ?>
            <a href="#tab-option-<?php echo $option_row; ?>" id="option-<?php echo $option_row; ?>"><?php echo $product_option['name']; ?>&nbsp;<img src="view/image/delete.png" alt="" onclick="$('#vtabs a:first').trigger('click'); $('#option-<?php echo $option_row; ?>').remove(); $('#tab-option-<?php echo $option_row; ?>').remove(); return false;" /></a>
            <?php $option_row++; ?>
            <?php } ?>
            <span id="option-add">
            <input name="option" value="" style="width: 130px;" />
            &nbsp;<img src="view/image/add.png" alt="<?php echo $_['button_add_option']; ?>" title="<?php echo $_['button_add_option']; ?>" /></span></div>
          <?php $option_row = 0; ?>
          <?php $option_value_row = 0; ?>
          <?php foreach ($product_options as $product_option) { ?>
          <div id="tab-option-<?php echo $option_row; ?>" class="vtabs-content">
            <input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_id]" value="<?php echo $product_option['product_option_id']; ?>" />
            <input type="hidden" name="product_option[<?php echo $option_row; ?>][name]" value="<?php echo $product_option['name']; ?>" />
            <input type="hidden" name="product_option[<?php echo $option_row; ?>][option_id]" value="<?php echo $product_option['option_id']; ?>" />
            <input type="hidden" name="product_option[<?php echo $option_row; ?>][type]" value="<?php echo $product_option['type']; ?>" />
            <table class="form">
              <tr>
                <td><?php echo $_['entry_required']; ?></td>
                <td><select name="product_option[<?php echo $option_row; ?>][required]">

				<?php echo form_select_option($_['option_yesno'],$product_option['required'], true);?>

                  </select></td>
              </tr>
              <?php if ($product_option['type'] == 'text') { ?>
              <tr>
                <td><?php echo $_['entry_option_value']; ?></td>
                <td><input type="text" name="product_option[<?php echo $option_row; ?>][option_value]" value="<?php echo $product_option['option_value']; ?>" /></td>
              </tr>
              <?php } ?>
              <?php if ($product_option['type'] == 'textarea') { ?>
              <tr>
                <td><?php echo $_['entry_option_value']; ?></td>
                <td><textarea name="product_option[<?php echo $option_row; ?>][option_value]" cols="40" rows="5"><?php echo $product_option['option_value']; ?></textarea></td>
              </tr>
              <?php } ?>
              <?php if ($product_option['type'] == 'file') { ?>
              <tr style="display: none;">
                <td><?php echo $_['entry_option_value']; ?></td>
                <td><input type="text" name="product_option[<?php echo $option_row; ?>][option_value]" value="<?php echo $product_option['option_value']; ?>" /></td>
              </tr>
              <?php } ?>
              <?php if ($product_option['type'] == 'date') { ?>
              <tr>
                <td><?php echo $_['entry_option_value']; ?></td>
                <td><input type="text" name="product_option[<?php echo $option_row; ?>][option_value]" value="<?php echo $product_option['option_value']; ?>" class="date" /></td>
              </tr>
              <?php } ?>
              <?php if ($product_option['type'] == 'datetime') { ?>
              <tr>
                <td><?php echo $_['entry_option_value']; ?></td>
                <td><input type="text" name="product_option[<?php echo $option_row; ?>][option_value]" value="<?php echo $product_option['option_value']; ?>" class="datetime" /></td>
              </tr>
              <?php } ?>
              <?php if ($product_option['type'] == 'time') { ?>
              <tr>
                <td><?php echo $_['entry_option_value']; ?></td>
                <td><input type="text" name="product_option[<?php echo $option_row; ?>][option_value]" value="<?php echo $product_option['option_value']; ?>" class="time" /></td>
              </tr>
              <?php } ?>
            </table>
            <?php if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') { ?>
            <table id="option-value<?php echo $option_row; ?>" class="list">
              <thead>
                <tr>
                  <td class="left"><?php echo $_['entry_option_value']; ?></td>
                  <td class="right"><?php echo $_['entry_quantity']; ?></td>
                  <td class="left"><?php echo $_['entry_subtract']; ?></td>
                  <td class="right"><?php echo $_['entry_price']; ?></td>
                  <td class="right"><?php echo $_['entry_option_points']; ?></td>
                  <td class="right"><?php echo $_['entry_weight']; ?></td>
                  <td></td>
                </tr>
              </thead>
              <?php foreach ($product_option['product_option_value'] as $product_option_value) { ?>
              <tbody id="option-value-row<?php echo $option_value_row; ?>">
                <tr>
                  <td class="left">
				  <select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][option_value_id]">
                      <?php if (isset($option_values[$product_option['option_id']])) { ?>
					  <?php echo form_select_option($option_values[$product_option['option_id']], $product_option_value['option_value_id'], null, 'option_value_id', 'name');?>
                      <?php } ?>

                    </select>
                    <input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][product_option_value_id]" value="<?php echo $product_option_value['product_option_value_id']; ?>" /></td>
                  <td class="right"><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][quantity]" value="<?php echo $product_option_value['quantity']; ?>" size="3" /></td>
                  <td class="left"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][subtract]">

					<?php echo form_select_option($_['option_yesno'],$product_option_value['subtract'], true);?>

                    </select></td>
                  <td class="right"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price_prefix]">
                      <?php if ($product_option_value['price_prefix'] == '+') { ?>
                      <option value="+" selected="selected">+</option>
                      <?php } else { ?>
                      <option value="+">+</option>
                      <?php } ?>
                      <?php if ($product_option_value['price_prefix'] == '-') { ?>
                      <option value="-" selected="selected">-</option>
                      <?php } else { ?>
                      <option value="-">-</option>
                      <?php } ?>
                    </select>
                    <input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price]" value="<?php echo $product_option_value['price']; ?>" size="5" /></td>
                  <td class="right"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points_prefix]">
                      <?php if ($product_option_value['points_prefix'] == '+') { ?>
                      <option value="+" selected="selected">+</option>
                      <?php } else { ?>
                      <option value="+">+</option>
                      <?php } ?>
                      <?php if ($product_option_value['points_prefix'] == '-') { ?>
                      <option value="-" selected="selected">-</option>
                      <?php } else { ?>
                      <option value="-">-</option>
                      <?php } ?>
                    </select>
                    <input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points]" value="<?php echo $product_option_value['points']; ?>" size="5" /></td>
                  <td class="right"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight_prefix]">
                      <?php if ($product_option_value['weight_prefix'] == '+') { ?>
                      <option value="+" selected="selected">+</option>
                      <?php } else { ?>
                      <option value="+">+</option>
                      <?php } ?>
                      <?php if ($product_option_value['weight_prefix'] == '-') { ?>
                      <option value="-" selected="selected">-</option>
                      <?php } else { ?>
                      <option value="-">-</option>
                      <?php } ?>
                    </select>
                    <input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight]" value="<?php echo $product_option_value['weight']; ?>" size="5" /></td>
                  <td class="left"><a onclick="$('#option-value-row<?php echo $option_value_row; ?>').remove();" class="button"><?php echo $_['button_remove']; ?></a></td>
                </tr>
              </tbody>
              <?php $option_value_row++; ?>
              <?php } ?>
              <tfoot>
                <tr>
                  <td colspan="6"></td>
                  <td class="left"><a onclick="addOptionValue('<?php echo $option_row; ?>');" class="button"><?php echo $_['button_add_option_value']; ?></a></td>
                </tr>
              </tfoot>
            </table>
            <select id="option-values<?php echo $option_row; ?>" style="display: none;">
              <?php if (isset($option_values[$product_option['option_id']])) { ?>
              <?php foreach ($option_values[$product_option['option_id']] as $option_value) { ?>
              <option value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
            <?php } ?>
          </div>
          <?php $option_row++; ?>
          <?php } ?>
        </div>
        <div id="tab-discount">
          <table id="discount" class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $_['entry_customer_group']; ?></td>
                <td class="right"><?php echo $_['entry_quantity']; ?></td>
                <td class="right"><?php echo $_['entry_priority']; ?></td>
                <td class="right"><?php echo $_['entry_price']; ?></td>
                <td class="left"><?php echo $_['entry_date_start']; ?></td>
                <td class="left"><?php echo $_['entry_date_end']; ?></td>
                <td></td>
              </tr>
            </thead>
            <?php $discount_row = 0; ?>
            <?php foreach ($product_discounts as $product_discount) { ?>
            <tbody id="discount-row<?php echo $discount_row; ?>">
              <tr>
                <td class="left"><select name="product_discount[<?php echo $discount_row; ?>][customer_group_id]">
				<?php echo form_select_option($customer_groups,$product_discount['customer_group_id'], null, 'customer_group_id', 'name');?>
                  </select></td>
                <td class="right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][quantity]" value="<?php echo $product_discount['quantity']; ?>" size="2" /></td>
                <td class="right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][priority]" value="<?php echo $product_discount['priority']; ?>" size="2" /></td>
                <td class="right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][price]" value="<?php echo $product_discount['price']; ?>" /></td>
                <td class="left"><input type="text" name="product_discount[<?php echo $discount_row; ?>][date_start]" value="<?php echo $product_discount['date_start']; ?>" class="date" /></td>
                <td class="left"><input type="text" name="product_discount[<?php echo $discount_row; ?>][date_end]" value="<?php echo $product_discount['date_end']; ?>" class="date" /></td>
                <td class="left"><a onclick="$('#discount-row<?php echo $discount_row; ?>').remove();" class="button"><?php echo $_['button_remove']; ?></a></td>
              </tr>
            </tbody>
            <?php $discount_row++; ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td colspan="6"></td>
                <td class="left"><a onclick="addDiscount();" class="button"><?php echo $_['button_add_discount']; ?></a></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <div id="tab-special">
          <table id="special" class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $_['entry_customer_group']; ?></td>
                <td class="right"><?php echo $_['entry_priority']; ?></td>
                <td class="right"><?php echo $_['entry_price']; ?></td>
                <td class="left"><?php echo $_['entry_date_start']; ?></td>
                <td class="left"><?php echo $_['entry_date_end']; ?></td>
                <td></td>
              </tr>
            </thead>
            <?php $special_row = 0; ?>
            <?php foreach ($product_specials as $product_special) { ?>
            <tbody id="special-row<?php echo $special_row; ?>">
              <tr>
                <td class="left"><select name="product_special[<?php echo $special_row; ?>][customer_group_id]">

				<?php echo form_select_option($customer_groups, $product_special['customer_group_id'], null, 'customer_group_id', 'name');?>

                  </select></td>
                <td class="right"><input type="text" name="product_special[<?php echo $special_row; ?>][priority]" value="<?php echo $product_special['priority']; ?>" size="2" /></td>
                <td class="right"><input type="text" name="product_special[<?php echo $special_row; ?>][price]" value="<?php echo $product_special['price']; ?>" /></td>
                <td class="left"><input type="text" name="product_special[<?php echo $special_row; ?>][date_start]" value="<?php echo $product_special['date_start']; ?>" class="date" /></td>
                <td class="left"><input type="text" name="product_special[<?php echo $special_row; ?>][date_end]" value="<?php echo $product_special['date_end']; ?>" class="date" /></td>
                <td class="left"><a onclick="$('#special-row<?php echo $special_row; ?>').remove();" class="button"><?php echo $_['button_remove']; ?></a></td>
              </tr>
            </tbody>
            <?php $special_row++; ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td colspan="5"></td>
                <td class="left"><a onclick="addSpecial();" class="button"><?php echo $_['button_add_special']; ?></a></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <div id="tab-image">
          <table id="images" class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $_['entry_image']; ?></td>
                <td class="right"><?php echo $_['entry_sort_order']; ?></td>
                <td></td>
              </tr>
            </thead>
            <?php $image_row = 0; ?>
            <?php foreach ($product_images as $product_image) { ?>
            <tbody id="image-row<?php echo $image_row; ?>">
              <tr>
                <td class="left"><div class="image"><img src="<?php echo $product_image['thumb']; ?>" alt="" id="thumb<?php echo $image_row; ?>" />
                    <br />
                    <a onclick="image_upload('image<?php echo $image_row; ?>', 'thumb<?php echo $image_row; ?>', '<?php echo $token;?>');"><?php echo $_['text_browse']; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $image_row; ?>').attr('value', '');"><?php echo $_['text_clear']; ?></a></div><input type="text" name="product_image[<?php echo $image_row; ?>][image]" value="<?php echo $product_image['image']; ?>" id="image<?php echo $image_row; ?>" size="45"/></td>
                <td class="right"><input type="text" name="product_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $product_image['sort_order']; ?>" size="2" /></td>
                <td class="left"><a onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="button"><?php echo $_['button_remove']; ?></a></td>
              </tr>
            </tbody>
            <?php $image_row++; ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td colspan="2"></td>
                <td class="left"><a onclick="addImage();" class="button"><?php echo $_['button_add_image']; ?></a></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <div id="tab-reward">
          <table class="form">
            <tr>
              <td><?php echo $_['entry_points']; ?></td>
              <td><input type="text" name="points" value="<?php echo $points; ?>" /></td>
            </tr>
          </table>
          <table class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $_['entry_customer_group']; ?></td>
                <td class="right"><?php echo $_['entry_reward']; ?></td>
              </tr>
            </thead>
            <?php foreach ($customer_groups as $customer_group) { ?>
            <tbody>
              <tr>
                <td class="left"><?php echo $customer_group['name']; ?></td>
                <td class="right"><input type="text" name="product_reward[<?php echo $customer_group['customer_group_id']; ?>][points]" value="<?php echo isset($product_reward[$customer_group['customer_group_id']]) ? $product_reward[$customer_group['customer_group_id']]['points'] : ''; ?>" /></td>
              </tr>
            </tbody>
            <?php } ?>
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
                <td class="left"><select name="product_layout[0][layout_id]">
                    <option value=""></option>

					<?php echo form_select_option($layouts,isset($product_layout[0])? $product_layout[0] : -1, null, 'layout_id', 'name');?>

                  </select></td>
              </tr>
            </tbody>
            <?php foreach ($stores as $store) { ?>
            <tbody>
              <tr>
                <td class="left"><?php echo $store['name']; ?></td>
                <td class="left"><select name="product_layout[<?php echo $store['store_id']; ?>][layout_id]">
                    <option value=""></option>
					<?php echo form_select_option($layouts,isset($product_layout[$store['store_id']])? $product_layout[$store['store_id']] : -1, null, 'layout_id', 'name');?>
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
$('input[name=\'related\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: '<?php echo UA('catalog/product/autocomplete'); ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});

	},
	select: function(event, ui) {
		$('#product-related' + ui.item.value).remove();

		$('#product-related').append('<div id="product-related' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" name="product_related[]" value="' + ui.item.value + '" /></div>');

		$('#product-related div:odd').attr('class', 'odd');
		$('#product-related div:even').attr('class', 'even');

		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

$('#product-related div img').live('click', function() {
	$(this).parent().remove();

	$('#product-related div:odd').attr('class', 'odd');
	$('#product-related div:even').attr('class', 'even');
});
//--></script>

<script type="text/javascript"><!--
var attributes_row = <?php echo $attributes_row; ?>;

$('input[name=\'attributes\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: '<?php echo UA('catalog/attribute_group/autocomplete'); ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.attribute_group_id,
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		html  = '<div id="tab-attributes-' + attributes_row + '" class="vtabs-content">';
		html  += '<table id="attribute-'+ ui.item.value +'" class="list">';
        html  += '<thead>';
        html  += '<tr>';
        html  += '<td class="left"><?php echo $_['entry_attribute']; ?></td>';
        html  += '<td class="left"><?php echo $_['entry_value']; ?></td>';
        html  += '<td></td>';
        html  += '</tr>';
        html  += '</thead>';
		html  += '<tfoot>';
        html  += '<tr>';
        html  += '<td colspan="2"></td>';
        html  += '<td class="left"><a onclick="addAttribute(' + ui.item.value + ');" class="button"><?php echo $_['button_add_attribute']; ?></a></td>';
        html  += '</tr>';
        html  += '</tfoot>';
        html  += '</table>';		  
		html  += '</div>';

		$('#tab-attributes').append(html);
		
		$('#attributes-add').before('<a href="#tab-attributes-' + attributes_row + '" id="attributes-' + attributes_row + '">' + ui.item.label + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'#vtab-attributes a:first\').trigger(\'click\'); $(\'#attributes-' + attributes_row + '\').remove(); $(\'#tab-attributes-' + attributes_row + '\').remove(); return false;" /></a>');

		$('#vtab-attributes a').tabs();
		$('#attributes-' + attributes_row).trigger('click');

		$.ajax({
			async: false,
			url: '<?php echo UA('catalog/attribute/attribute_form'); ?>&attribute_row=' + attribute_row + '&attribute_group_id=' +  ui.item.value,
			dataType: 'html',
			success: function(html) {
				$('#attribute-' + ui.item.value + ' tfoot').before(html);
			}
		});
		
		attributes_row++;
		attribute_row += $('#attribute-' + ui.item.value + ' tbody').length;
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});
//--></script>

<script type="text/javascript"><!--
var attribute_row = <?php echo $attribute_row; ?>;

function addAttribute(attribute_group_id) {
	html  = '<tbody id="attribute-row' + attribute_row + '">';
    html += '  <tr>';
	html += '    <td class="left"><input type="text" name="product_attribute[' + attribute_row + '][name]" value="" /><input type="hidden" name="product_attribute[' + attribute_row + '][attribute_id]" value="" /></td>';
	html += '    <td class="left" id="attribute-field'+attribute_row+'">';
	<?php foreach ($languages as $language) { ?>
	html += '<textarea name="product_attribute[' + attribute_row + '][product_attribute_description][<?php echo $language['language_id']; ?>][text]"></textarea><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" align="top" /><br />';
    <?php } ?>
	html += '    </td>';
	html += '    <td class="left"><a onclick="$(\'#attribute-row' + attribute_row + '\').remove();" class="button"><?php echo $_['button_remove']; ?></a></td>';
    html += '  </tr>';
    html += '</tbody>';

	$('#attribute-' + attribute_group_id +' tfoot').before(html);

	attributeautocomplete(attribute_row, attribute_group_id);

	attribute_row++;
}

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

function attributeautocomplete(attribute_row, attribute_group_id) {
	$('input[name=\'product_attribute[' + attribute_row + '][name]\']').catcomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: '<?php echo UA('catalog/attribute/autocomplete'); ?>&attribute_row=' + attribute_row + '&attribute_group_id=' + attribute_group_id + '&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							category: item.attribute_group,
							label: item.name,
							value: item.attribute_id,
							html: item.html
						}
					}));
				}
			});
		},
		select: function(event, ui) {
			$('input[name=\'product_attribute[' + attribute_row + '][name]\']').attr('value', ui.item.label);
			$('input[name=\'product_attribute[' + attribute_row + '][attribute_id]\']').attr('value', ui.item.value);
			$('#attribute-field' + attribute_row).html(ui.item.html);
			return false;
		},
		focus: function(event, ui) {
      		return false;
   		}
	});
}

$('#attribute tbody').each(function(index, element) {
	attributeautocomplete(index);
});
//--></script>
<script type="text/javascript"><!--
var option_row = <?php echo $option_row; ?>;

$('input[name=\'option\']').catcomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: '<?php echo UA('catalog/option/autocomplete'); ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						category: item.category,
						label: item.name,
						value: item.option_id,
						type: item.type,
						option_value: item.option_value
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		html  = '<div id="tab-option-' + option_row + '" class="vtabs-content">';
		html += '	<input type="hidden" name="product_option[' + option_row + '][product_option_id]" value="" />';
		html += '	<input type="hidden" name="product_option[' + option_row + '][name]" value="' + ui.item.label + '" />';
		html += '	<input type="hidden" name="product_option[' + option_row + '][option_id]" value="' + ui.item.value + '" />';
		html += '	<input type="hidden" name="product_option[' + option_row + '][type]" value="' + ui.item.type + '" />';
		html += '	<table class="form">';
		html += '	  <tr>';
		html += '		<td><?php echo $_['entry_required']; ?></td>';
		html += '       <td><select name="product_option[' + option_row + '][required]">';
		html += '	      <option value="1"><?php echo $_['text_yes']; ?></option>';
		html += '	      <option value="0"><?php echo $_['text_no']; ?></option>';
		html += '	    </select></td>';
		html += '     </tr>';

		if (ui.item.type == 'text') {
			html += '     <tr>';
			html += '       <td><?php echo $_['entry_option_value']; ?></td>';
			html += '       <td><input type="text" name="product_option[' + option_row + '][option_value]" value="" /></td>';
			html += '     </tr>';
		}

		if (ui.item.type == 'textarea') {
			html += '     <tr>';
			html += '       <td><?php echo $_['entry_option_value']; ?></td>';
			html += '       <td><textarea name="product_option[' + option_row + '][option_value]" cols="40" rows="5"></textarea></td>';
			html += '     </tr>';
		}

		if (ui.item.type == 'file') {
			html += '     <tr style="display: none;">';
			html += '       <td><?php echo $_['entry_option_value']; ?></td>';
			html += '       <td><input type="text" name="product_option[' + option_row + '][option_value]" value="" /></td>';
			html += '     </tr>';
		}

		if (ui.item.type == 'date') {
			html += '     <tr>';
			html += '       <td><?php echo $_['entry_option_value']; ?></td>';
			html += '       <td><input type="text" name="product_option[' + option_row + '][option_value]" value="" class="date" /></td>';
			html += '     </tr>';
		}

		if (ui.item.type == 'datetime') {
			html += '     <tr>';
			html += '       <td><?php echo $_['entry_option_value']; ?></td>';
			html += '       <td><input type="text" name="product_option[' + option_row + '][option_value]" value="" class="datetime" /></td>';
			html += '     </tr>';
		}

		if (ui.item.type == 'time') {
			html += '     <tr>';
			html += '       <td><?php echo $_['entry_option_value']; ?></td>';
			html += '       <td><input type="text" name="product_option[' + option_row + '][option_value]" value="" class="time" /></td>';
			html += '     </tr>';
		}

		html += '  </table>';

		if (ui.item.type == 'select' || ui.item.type == 'radio' || ui.item.type == 'checkbox' || ui.item.type == 'image') {
			html += '  <table id="option-value' + option_row + '" class="list">';
			html += '  	 <thead>';
			html += '      <tr>';
			html += '        <td class="left"><?php echo $_['entry_option_value']; ?></td>';
			html += '        <td class="right"><?php echo $_['entry_quantity']; ?></td>';
			html += '        <td class="left"><?php echo $_['entry_subtract']; ?></td>';
			html += '        <td class="right"><?php echo $_['entry_price']; ?></td>';
			html += '        <td class="right"><?php echo $_['entry_option_points']; ?></td>';
			html += '        <td class="right"><?php echo $_['entry_weight']; ?></td>';
			html += '        <td></td>';
			html += '      </tr>';
			html += '  	 </thead>';
			html += '    <tfoot>';
			html += '      <tr>';
			html += '        <td colspan="6"></td>';
			html += '        <td class="left"><a onclick="addOptionValue(' + option_row + ');" class="button"><?php echo $_['button_add_option_value']; ?></a></td>';
			html += '      </tr>';
			html += '    </tfoot>';
			html += '  </table>';
            html += '  <select id="option-values' + option_row + '" style="display: none;">';

            for (i = 0; i < ui.item.option_value.length; i++) {
				html += '  <option value="' + ui.item.option_value[i]['option_value_id'] + '">' + ui.item.option_value[i]['name'] + '</option>';
            }

            html += '  </select>';
			html += '</div>';
		}

		$('#tab-option').append(html);

		$('#option-add').before('<a href="#tab-option-' + option_row + '" id="option-' + option_row + '">' + ui.item.label + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'#vtab-option a:first\').trigger(\'click\'); $(\'#option-' + option_row + '\').remove(); $(\'#tab-option-' + option_row + '\').remove(); return false;" /></a>');

		$('#vtab-option a').tabs();

		$('#option-' + option_row).trigger('click');

		$('.date').datepicker({dateFormat: 'yy-mm-dd'});
		$('.datetime').datetimepicker({
			dateFormat: 'yy-mm-dd',
			timeFormat: 'h:m'
		});

		$('.time').timepicker({timeFormat: 'h:m'});

		option_row++;

		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});
//--></script>
<script type="text/javascript"><!--
var option_value_row = <?php echo $option_value_row; ?>;

function addOptionValue(option_row) {
	html  = '<tbody id="option-value-row' + option_value_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value_id]">';
	html += $('#option-values' + option_row).html();
	html += '    </select><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][product_option_value_id]" value="" /></td>';
	html += '    <td class="right"><input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][quantity]" value="" size="3" /></td>';
	html += '    <td class="left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][subtract]">';
	html += '      <option value="1"><?php echo $_['text_yes']; ?></option>';
	html += '      <option value="0"><?php echo $_['text_no']; ?></option>';
	html += '    </select></td>';
	html += '    <td class="right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price_prefix]">';
	html += '      <option value="+">+</option>';
	html += '      <option value="-">-</option>';
	html += '    </select>';
	html += '    <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price]" value="" size="5" /></td>';
	html += '    <td class="right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points_prefix]">';
	html += '      <option value="+">+</option>';
	html += '      <option value="-">-</option>';
	html += '    </select>';
	html += '    <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points]" value="" size="5" /></td>';
	html += '    <td class="right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight_prefix]">';
	html += '      <option value="+">+</option>';
	html += '      <option value="-">-</option>';
	html += '    </select>';
	html += '    <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight]" value="" size="5" /></td>';
	html += '    <td class="left"><a onclick="$(\'#option-value-row' + option_value_row + '\').remove();" class="button"><?php echo $_['button_remove']; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#option-value' + option_row + ' tfoot').before(html);

	option_value_row++;
}
//--></script>
<script type="text/javascript"><!--
var discount_row = <?php echo $discount_row; ?>;

function addDiscount() {
	html  = '<tbody id="discount-row' + discount_row + '">';
	html += '  <tr>';
    html += '    <td class="left"><select name="product_discount[' + discount_row + '][customer_group_id]">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
    <?php } ?>
    html += '    </select></td>';
    html += '    <td class="right"><input type="text" name="product_discount[' + discount_row + '][quantity]" value="" size="2" /></td>';
    html += '    <td class="right"><input type="text" name="product_discount[' + discount_row + '][priority]" value="" size="2" /></td>';
	html += '    <td class="right"><input type="text" name="product_discount[' + discount_row + '][price]" value="" /></td>';
    html += '    <td class="left"><input type="text" name="product_discount[' + discount_row + '][date_start]" value="" class="date" /></td>';
	html += '    <td class="left"><input type="text" name="product_discount[' + discount_row + '][date_end]" value="" class="date" /></td>';
	html += '    <td class="left"><a onclick="$(\'#discount-row' + discount_row + '\').remove();" class="button"><?php echo $_['button_remove']; ?></a></td>';
	html += '  </tr>';
    html += '</tbody>';

	$('#discount tfoot').before(html);

	$('#discount-row' + discount_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});

	discount_row++;
}
//--></script>
<script type="text/javascript"><!--
var special_row = <?php echo $special_row; ?>;

function addSpecial() {
	html  = '<tbody id="special-row' + special_row + '">';
	html += '  <tr>';
    html += '    <td class="left"><select name="product_special[' + special_row + '][customer_group_id]">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
    <?php } ?>
    html += '    </select></td>';
    html += '    <td class="right"><input type="text" name="product_special[' + special_row + '][priority]" value="" size="2" /></td>';
	html += '    <td class="right"><input type="text" name="product_special[' + special_row + '][price]" value="" /></td>';
    html += '    <td class="left"><input type="text" name="product_special[' + special_row + '][date_start]" value="" class="date" /></td>';
	html += '    <td class="left"><input type="text" name="product_special[' + special_row + '][date_end]" value="" class="date" /></td>';
	html += '    <td class="left"><a onclick="$(\'#special-row' + special_row + '\').remove();" class="button"><?php echo $_['button_remove']; ?></a></td>';
	html += '  </tr>';
    html += '</tbody>';

	$('#special tfoot').before(html);

	$('#special-row' + special_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});

	special_row++;
}
//--></script>
<script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
    html  = '<tbody id="image-row' + image_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + image_row + '" /><br /><a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\', \'<?php echo $token;?>\');"><?php echo $_['text_browse']; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $_['text_clear']; ?></a></div><input type="text" name="product_image[' + image_row + '][image]" value="" id="image' + image_row + '" size="45"/></td>';
	html += '    <td class="right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="" size="2" /></td>';
	html += '    <td class="left"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="button"><?php echo $_['button_remove']; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#images tfoot').before(html);

	image_row++;
}

var template_langid = 0;
function insertTemplate(template_id) {
	if (!template_id) return;
	$.ajax({
		url: '<?php echo UA('catalog/product_tpl/get'); ?>&template_id=' + template_id,
		dataType: 'html',
		success: function(html) {
			editor = CKEDITOR.instances['description' + template_langid];
			if (editor.mode == 'wysiwyg') {
				if ($$('template_replace').checked) {
					editor.fire( 'saveSnapshot' );
					editor.setData(html);
				}
				else {
					editor.insertHtml(html);
				}

			}
			else alert( 'You must be in WYSIWYG mode!' );
		}
	});
}

function selectTemplate(langid) {
	template_langid = langid;
	$.ajax({
		url: '<?php echo UA('catalog/product_tpl/ls'); ?>',
		dataType: 'json',
		success: function(json) {
			var code = "<div class='scrollbox2'><table class='list'>";
			for (i = 0; i < json.length; i++) {
				code = code + "<tr><td><a href='javascript:void(0)' onclick='closeDialog("+ json[i].template_id +")'>" + json[i].title +"</a></td></tr>"
			}
			code = code + '</table></div>';
			makeDialog('Content Template', code, '', 400, 10, 0, 0, insertTemplate);
		}
	});
}
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
$('#main_category').checkboxTree({ onCheck: { ancestors: 'uncheck', descendants: 'uncheck', others: 'uncheck'}, onUncheck: {descendants: null},initializeUnchecked:'collapsed'});
$('#add_category_tree').checkboxTree({ onCheck: { ancestors: null, descendants: null}, onUncheck: {descendants: null},initializeUnchecked:'collapsed'});
//--></script>
<script type="text/javascript"><!--
$('#tabs a').tabs();
$('#languages a').tabs();
$('#vtab-option a').tabs();
//--></script>
<?php echo $footer; ?>