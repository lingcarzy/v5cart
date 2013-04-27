<?php foreach ($attributes as $attribute) {?>
<tbody id="attribute-row<?php echo $attribute_row; ?>">
  <tr>
	<td class="left"><input type="text" name="product_attribute[<?php echo $attribute_row; ?>][name]" value="<?php echo $attribute['name']; ?>" />
	  <input type="hidden" name="product_attribute[<?php echo $attribute_row; ?>][attribute_id]" value="<?php echo $attribute['attribute_id']; ?>" /></td>
	<td class="left">
	<?php echo $this->model_catalog_attribute->getAttributeFormField($attribute['attribute_id'], $attribute_row);?>
	  </td>
	<td class="left"><a onclick="$('#attribute-row<?php echo $attribute_row; ?>').remove();" class="button"><?php echo $_['button_remove']; ?></a></td>
  </tr>
</tbody>
<?php $attribute_row++; ?>
<?php } ?>