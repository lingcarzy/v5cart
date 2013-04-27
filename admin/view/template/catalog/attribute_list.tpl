<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php echo bread_crumbs(); ?>
  </div>
  
  <?php if (isset($error_warning)) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/order.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <select onchange="location.href='<?php echo UA('catalog/attribute');?>&filter_reset=1&filter_attribute_group_id=' + this.value">
		<option value="0"><?php echo $_['text_select']; ?></option>
		<?php echo form_select_option($attribute_groups, $filter_attribute_group_id, null, 'attribute_group_id', 'name'); ?>
	  </select>
	  |
	  <a href="<?php echo UA('catalog/attribute/insert'); ?>" class="button"><?php echo $_['button_insert']; ?></a><a onclick="$('form').submit();" class="button"><?php echo $_['button_delete']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo  UA('catalog/attribute/delete'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'ad.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo $order; ?>"><?php echo $_['column_name']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $_['column_name']; ?></a>
                <?php } ?></td>
			  <td class="left"><?php echo $_['column_type']; ?></td>
			  <td class="left"><?php echo $_['column_value']; ?></td>
              <td class="left"><?php if ($sort == 'attribute_group') { ?>
                <a href="<?php echo $sort_attribute_group; ?>" class="<?php echo $order; ?>"><?php echo $_['column_attribute_group']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_attribute_group; ?>"><?php echo $_['column_attribute_group']; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'a.sort_order') { ?>
                <a href="<?php echo $sort_sort_order; ?>" class="<?php echo $order; ?>"><?php echo $_['column_sort_order']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sort_order; ?>"><?php echo $_['column_sort_order']; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $_['column_action']; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($attributes) { ?>
            <?php foreach ($attributes as $attribute) { ?>
            <tr onmouseover="this.className='on';" onmouseout="this.className='';">
              <td style="text-align: center;"><?php if ($attribute['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $attribute['attribute_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $attribute['attribute_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $attribute['name']; ?></td>
			  <td class="left"><?php echo $attribute['type']; ?></td>
			   <td class="left"><?php echo $attribute['value']; ?></td>
              <td class="left"><?php echo $attribute['attribute_group']; ?></td>
              <td class="right"><?php echo $attribute['sort_order']; ?></td>
              <td class="right"><?php foreach ($attribute['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="7"><?php echo $_['text_no_results']; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>