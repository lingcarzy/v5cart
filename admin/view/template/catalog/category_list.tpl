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
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/category.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a onclick="location = '<?php echo UA('catalog/category/insert'); ?>'" class="button"><?php echo $_['button_insert']; ?></a><a onclick="$('#form').submit();" class="button"><?php echo $_['button_delete']; ?></a> | <a onclick="location = '<?php echo UA('catalog/category/update_cache'); ?>'" class="button"><?php echo $_['button_update_cache']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('catalog/category/delete'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php echo $_['column_name']; ?></td>
			  <td class="left"><?php echo $_['column_sub_category']; ?></td>
			  <td class="left"><?php echo $_['column_product_total']; ?></td>
			  <td class="left"><?php echo $_['text_product']; ?></td>
              <td class="right"><?php echo $_['column_sort_order']; ?></td>
              <td class="right"><?php echo $_['column_action']; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($categories) { ?>
            <?php foreach ($categories as $category) { ?>
            <tr onmouseover="this.className='on';" onmouseout="this.className='';">
              <td style="text-align: center;"><?php if ($category['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $category['category_id']; ?>" />
                <?php } ?></td>
              <td class="left"><a href="<?php echo $category['href'];?>" target="_blank"><?php echo $category['name']; ?></a></td>
			  <td>[ <a href="<?php echo UA('catalog/category', 'category_id=' . $category['category_id']);?>"><?php echo $_['text_sub_category']; ?> ]</a></td>
			  <td class="right"><?php echo $category['total']; ?></td>
			  <td>
				[ <a href="<?php echo UA('catalog/product', 'filter_category_id=' . $category['category_id']); ?>"><?php echo $_['text_product']; ?></a> ]
              </td>
              <td class="right"><?php echo $category['sort_order']; ?></td>
              <td class="right"><?php foreach ($category['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="4"><?php echo $_['text_no_results']; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>